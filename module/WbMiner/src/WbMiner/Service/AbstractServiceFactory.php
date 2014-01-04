<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractServiceFactory implements FactoryInterface
{

    abstract function create(ServiceLocatorInterface $locator, Config $config);

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->create($serviceLocator, $serviceLocator->get('WbMiner\Config'));
    }
}