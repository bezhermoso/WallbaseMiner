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

            try {

                $result = $this->processor->process($job);

                if ($result->getStatus() == ProcessResult::SUCCESSFUL) {
                    $this->getEventManager()->trigger('process.post', $this, array(
                        'job' => $job,
                        'result' => $result
                    ));
                } else {
                    $this->getEventManager()->trigger('process.failure', $this, array(
                        'job' => $job,
                        'result' => $result
                    ));
                }

            } catch (\Exception $e) {
                $this->getEventManager()->trigger('process.error', $this, array(
                    'job' => $job,
                    'exception' => $e
                ));
            }
        }

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