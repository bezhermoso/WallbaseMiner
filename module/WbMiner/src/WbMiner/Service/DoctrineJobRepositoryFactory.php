<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Service;


use Doctrine\ORM\EntityManager;
use WbMiner\Config;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineJobRepositoryFactory extends AbstractServiceFactory
{

    function create(ServiceLocatorInterface $locator, Config $config)
    {
        /** @var $em EntityManager */
        $em = $locator->get('WbMiner\Doctrine\EntityManager');
        return $em->getRepository($config->doctrine->orm->job_class);
    }
}