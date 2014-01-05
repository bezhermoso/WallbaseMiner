<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Event;


use WbMiner\Job\Process\MainProcessor;
use WbMiner\Job\Process\ProcessEvent;
use WbMiner\Job\RepositoryInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class RemoveJobListener implements ListenerAggregateInterface
{

    protected $listeners = array();

    protected $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->getSharedManager()->attach(MainProcessor::EVENT_MANAGER_ID, ProcessEvent::PROCESS_POST, array($this, 'deleteJob'));
    }

    public function deleteJob(Event $event)
    {
        $job = $event->getParam('Job');
        $this->repository->remove($job);
    }

    /**
     * Detach all previously attached listeners
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $i => $listener) {
            if ($events->getSharedManager()->detach(MainProcessor::EVENT_MANAGER_ID, $listener)) {
                unset($this->listeners[$i]);
            }
        }
    }
}