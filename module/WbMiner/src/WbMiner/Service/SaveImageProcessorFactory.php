<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use WbMiner\Config;
use WbMiner\Job\Process\SaveImageProcessor;
use Zend\ServiceManager\ServiceLocatorInterface;

class SaveImageProcessorFactory extends AbstractServiceFactory
{

    function create(ServiceLocatorInterface $locator, Config $config)
    {
        $saveImage = new SaveImageProcessor($config['save_image']['target_dir']);
        return $saveImage;
    }
}