<?php

/**
 * WbMiner/config/wb-miner.services.php
 */

return array(
    'invokables' => array(
        'WbMiner\Job\Processor\ExtensionSwitcher' => 'WbMiner\Service\ExtensionSwitcherFactory',
    ),
    'factories' => array(

        'WbMiner\Config' => 'WbMiner\Service\ConfigFactory',
        'WbMiner\Job\Provider' => 'WbMiner\Service\JobProviderFactory',
        'WbMiner\Job\Processor' => 'WbMiner\Service\JobProcessorFactory',
        'WbMiner\Job\MainProcessor' => 'WbMiner\Service\MainProcessorFactory',

        'WbMiner\Doctrine\EntityManager' => 'WbMiner\Service\DoctrineEntityManagerFactory',

        'WbMiner\ImageRepository' => 'WbMiner\Service\ImageRepositoryFactory',
        'WbMiner\JobRepository' => 'WbMiner\Service\JobRepositoryFactory',

        'WbMiner\Job\Processor\SaveImage' => 'WbMiner\Service\SaveImageProcessorFactory',
        'WbMiner\Job\Provider\Doctrine' => 'WbMiner\Service\DoctrineProviderFactory',
        'WbMiner\ImageRepository\Doctrine' => 'WbMiner\Service\DoctrineImageRepositoryFactory',
        'WbMiner\JobRepository\Doctrine' => 'WbMiner\Service\DoctrineJobRepositoryFactory',
    ),
    'delegators' => array(
        'WbMiner\Job\Processor\SaveImage' => array(
            'WbMiner\Job\Processor\ExtensionSwitcher',
        ),
    )
);