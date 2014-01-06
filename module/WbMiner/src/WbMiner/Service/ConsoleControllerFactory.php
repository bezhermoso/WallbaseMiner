<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use WbMiner\Controller\ConsoleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleControllerFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $config = $serviceLocator->get('WbMiner\Config');

        $processor = $serviceLocator->get('WbMiner\Job\MainProcessor');

        $logger = null;

        if ($serviceLocator->has($config->logger)) {
            $logger = $serviceLocator->get($config->logger);
        }

        $console = $serviceLocator->get('console');
        $controller = new ConsoleController($processor, $console, $logger);

        return $controller;

    }
}