<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Event;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use WbMiner\Entity\Image;
use WbMiner\Image\RepositoryInterface;
use WbMiner\Job\Process\MainProcessor;
use WbMiner\Job\Process\ProcessEvent;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class PersistImageListener implements ListenerAggregateInterface
{
    protected $em;

    protected $repository;

    protected $listeners = array();

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
        $this->listeners[] = $events->getSharedManager()->attach(MainProcessor::EVENT_MANAGER_ID, ProcessEvent::PROCESS_POST, array($this, 'persistImage'));
    }

    public function persistImage(Event $event)
    {
        $job = $event->getParam('Job');
        $outputFile = $event->getParam('OutputFile');

        $image = $job->getImage();

        if ($image instanceof Image || method_exists($image, 'setFilename')) {
            $image->setFilename($outputFile);
        }
        $this->repository->save($image);
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
        foreach($this->listeners as $i => $listener) {
            if ($events->getSharedManager()->detach(MainProcessor::EVENT_MANAGER_ID, $listener)) {
                unset($this->listeners[$i]);
            }
        }
    }
}