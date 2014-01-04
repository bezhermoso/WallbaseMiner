<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use WbMiner\Job\Process\MainProcessor;
use WbMiner\Job\Process\ProcessorInterface;
use WbMiner\Job\Provider\ProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MainProcessorFactory extends AbstractServiceFactory
{

    /**
     * @param ServiceLocatorInterface $locator
     * @param Config $config
     * @return MainProcessor
     */
    function create(ServiceLocatorInterface $locator, Config $config)
    {
        /** @var $processor ProcessorInterface */
        $processor = $locator->get('WbMiner\Job\Processor');

        /** @var $provider ProviderInterface */
        $provider = $locator->get('WbMiner\Job\Provider');

        $mainProcessor = new MainProcessor($provider, $processor);

        return $mainProcessor;
    }
}