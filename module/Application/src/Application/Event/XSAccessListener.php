<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Event;


use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Header\GenericHeader;
use Zend\Http\Headers;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;

class XSAccessListener implements ListenerAggregateInterface
{

    protected $listeners = array();

    protected $matched = false;
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
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onDispatch'));
        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'));
    }

    public function onFinish(MvcEvent $event)
    {

    }

    public function onDispatch(MvcEvent $event)
    {
        $match = $event->getRouteMatch();

        if ($match->getMatchedRouteName() == 'job-request') {

            $response = $event->getResponse();

            /** @var $request Request */
            $request = $event->getRequest();

            if ($request->getHeader('Origin')) {
                /** @var $headers Headers */
                $headers = $response->getHeaders();
                $headers->addHeader(new GenericHeader('Access-Control-Allow-Origin', $request->getHeader('Origin')->getFieldValue()));

                if ($request->getHeader('Access-Control-Request-Headers'))
                    $headers->addHeader(new GenericHeader('Access-Control-Allow-Headers', $request->getHeader('Access-Control-Request-Headers')->getFieldValue()));

                if ($request->getHeader('Access-Control-Request-Method'))
                $headers->addHeader(new GenericHeader('Access-Control-Allow-Methods', $request->getHeader('Access-Control-Request-Method')->getFieldValue()));
            }
        }

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
            if ($events->detach($listener)) {
                unset($this->listeners[$i]);
            }
        }
    }
}