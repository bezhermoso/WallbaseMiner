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
        if ($this->limit !== null && $this->provider instanceof LimitAwareInterface) {
            $this->provider->setLimit($this->limit);
        }

        $jobs = $this->provider->getJobs();

        foreach ($jobs as $job) {

            $exception = null;

            $eventName = '';

            $params = array(
                'Job' => $job
            );

            try {

                $result = $this->processor->process($job);

                if ($result->getStatus() == ProcessResult::SUCCESSFUL) {
                    $eventName = ProcessEvent::PROCESS_POST;
                } else {
                    $eventName = ProcessEvent::PROCESS_FAILED;
                }

                $extraParams = $result->getParams();
                $params = array_merge($params, $extraParams);

                $params['ProcessResult'] = $result;

            } catch (\Exception $e) {
                $eventName = ProcessEvent::PROCESS_EXCEPTION;
                $params['Exception'] = $e;
            }

            $results = $this->getEventManager()->triggerUntil($eventName, $this, $params, function ($res) {
                return ($res instanceof ResponseInterface);
            });

            if ($results->stopped()) {
                $params['LastResult'] = $results->last();
                $this->getEventManager()->trigger(ProcessEvent::PROCESS_STOPPED, $this, $params);
                break;
            }
        }

        $this->getEventManager()->trigger(ProcessEvent::PROCESS_FINISHED, $this);

        return new ProcessResult(ProcessResult::SUCCESSFUL);

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