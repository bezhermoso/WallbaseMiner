<?php

/**
 * WbMiner/config/wb-miner.config.php
 */

return array(
    'wb-miner' => array(
        'job_provider' => 'WbMiner\Job\Provider\DoctrineProvider',
        'job_processor' => 'WbMiner\Job\Processor\DoctrineProcessor',
        'main_processor' => 'WbMiner\Job\MainProcessor',
        'doctrine' => array(
            'entities' => array(
                'image' => null,
                'job' => null
            )
        )
    )
);