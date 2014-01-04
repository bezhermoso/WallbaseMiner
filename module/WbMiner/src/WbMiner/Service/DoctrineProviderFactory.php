<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use WbMiner\Job\Provider\DoctrineProvider;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineProviderFactory extends AbstractServiceFactory
{

    function create(ServiceLocatorInterface $locator, Config $config)
    {
        if (!$config['doctrine']['orm']['job_class'])
            throw new \RuntimeException('wb-miner.doctrine.orm.job_class must be set.');

        $provider = new DoctrineProvider(
                            $locator->get($config['doctrine']['orm']['entity_manager']),
                            $config['doctrine']['orm']['job_class']);

        return $provider;
    }
}