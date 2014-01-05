<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

class JobRepositoryFactory extends AbstractServiceFactory
{

    function create(ServiceLocatorInterface $locator, Config $config)
    {
        return $locator->get($config->job_repository);
    }
}