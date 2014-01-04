<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use Doctrine\Common\EventManager;
use WbMiner\Job\JobInterface;
use WbMiner\Job\Provider\ProviderInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class MainProcessor implements ProcessorInterface, EventManagerAwareInterface
{
    /**
     * @var \WbMiner\Job\Provider\ProviderInterface
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
        foreach ($this->provider->getJobs() as $job) {
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
}