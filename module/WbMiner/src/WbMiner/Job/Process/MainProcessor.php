<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\JobInterface;
use WbMiner\Job\Provider\LimitAwareInterface;
use WbMiner\Job\Provider\ProviderInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Stdlib\ResponseInterface;

class MainProcessor implements ProcessorInterface, EventManagerAwareInterface, LimitAwareInterface
{
    /**
     * @var \WbMiner\Job\Provider\ProviderInterface|LimitAwareInterface
     */
    protected $provider;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    protected $limit;

    public static $extraParamBlacklist = array(
        'ProcessResult', 'Exception', 'Job', 'LastResult'
    );

    const EVENT_MANAGER_ID = 'WbMiner\MainProcessor';

    public function __construct(ProviderInterface $provider, ProcessorInterface $processor)
    {
        $this->provider = $provider;
        $this->processor = $processor;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    public function process(JobInterface $job = null)
    {
        try {

            if ($this->limit !== null && $this->provider instanceof LimitAwareInterface) {
                $this->provider->setLimit($this->limit);
            }

            $jobs = $this->provider->getJobs();

            $errCount = 0;
            $exeptionCount = 0;
            $successCount = 0;

            $this->getEventManager()->attach(ProcessEvent::PROCESS, function (Event $event) {
                $job = $event->getParam('Job');
                $processor = $event->getParam('Processor');
                return $processor->process($job);
            }, 0);

            foreach ($jobs as $job) {

                $exception = null;

                $eventName = '';

                $params = array(
                    'Job' => $job,
                    'Processor' => $this->processor
                );

                try {

                    $results = $this->getEventManager()->triggerUntil(ProcessEvent::PROCESS, $this, $params, function ($v) {
                        return (!$v instanceof ProcessResult);
                    });

                    if ($results->stopped()) {

                        $params['LastResult'] = $results->last();
                        $this->getEventManager()->triggerUntil(ProcessEvent::PROCESS_STOPPED, $params, function ($v) {
                            return ($v instanceof ResponseInterface);
                        });

                    } else {

                        $result = $results->last();

                        if ($result->getStatus() == ProcessResult::SUCCESSFUL) {
                            $eventName = ProcessEvent::PROCESS_POST;
                            $successCount++;
                        } else {
                            $eventName = ProcessEvent::PROCESS_FAILED;
                            $errCount++;
                        }

                        $extraParams = $result->getParams();
                        $params = array_merge($params, $extraParams);

                        $params['ProcessResult'] = $result;
                    }

                } catch (\Exception $e) {
                    $eventName = ProcessEvent::PROCESS_EXCEPTION;
                    $params['Exception'] = $e;
                    $exeptionCount++;
                }

                $this->getEventManager()->triggerUntil($eventName, $this, $params, function ($res) {
                    return ($res instanceof ResponseInterface);
                });
            }

            $this->getEventManager()->trigger(ProcessEvent::PROCESS_FINISHED, $this);

            $result = new ProcessResult(ProcessResult::SUCCESSFUL);

            $result->setParam('Jobs', $jobs);
            $result->setParam('FailureCount', $errCount);
            $result->setParam('ExceptionCount', $exeptionCount);
            $result->setParam('SuccessCount', $successCount);

            return $result;

        } catch (\Exception $e) {

            $result = new ProcessResult(ProcessResult::FAILURE, null, $e->getMessage());
            $result->setParam('Exception', $e);

            return $result;
        }
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
            self::EVENT_MANAGER_ID,
        ));
        $this->events = $eventManager;
        return $this;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}