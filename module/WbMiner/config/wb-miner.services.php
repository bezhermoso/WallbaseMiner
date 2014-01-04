<?php

/**
 * WbMiner/config/wb-miner.services.php
 */

return array(
    'factories' => array(
        'WbMiner\Config' => 'WbMiner\Service\ConfigFactory',
        'WbMiner\Job\Provider' => 'WbMiner\Service\JobProviderFactory',
        'WbMiner\Job\Processor' => 'WbMiner\Service\JobProcessorFactory',
        'WbMiner\Job\MainProcessor' => 'WbMiner\Service\MainProcessorFactory',
        'WbMiner\Job\Processor\ExtensionSwitcher' => 'WbMiner\Service\ExtensionSwitcherProcessorFactory',
        'WbMiner\Job\Processor\SaveImage' => 'WbMiner\Service\SaveImageProcessorFactory',
        'WbMiner\Job\Provider\Doctrine' => 'WbMiner\Service\DoctrineProviderFactory'
    )
);