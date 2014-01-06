<?php

/**
 * WbMiner/config/wb-miner.config.php
 */

return array(
    'wb-miner' => array(
        'job_provider' => 'WbMiner\Job\Provider\Doctrine',
        'job_processor' => 'WbMiner\Job\Processor\SaveImage',
        'main_processor' => 'WbMiner\Job\MainProcessor',
        'save_image' => array(
            'target_dir' => 'public/wb'
        ),
        'image_repository' => 'WbMiner\ImageRepository\Doctrine',
        'job_repository' => 'WbMiner\JobRepository\Doctrine',
        'doctrine' => array(
            'orm' => array(
                'entity_manager' => 'doctrine.entitymanager.orm_default',
                'image_class' => null,
                'job_class' => null,
            )
        ),
        'script_lock_file' => '.wbminer.job-process.lock'
    ),
    'doctrine' => array(
        'driver' => array(
            'wbm_yml_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/doctrine/mapping'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'WbMiner\Entity' => 'wbm_yml_driver',
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'wbminer-process-jobs' => array(
                    'options' => array(
                        'route' => 'wbminer jobs process [--limit=]',
                        'defaults' => array(
                            'controller' => 'WbMiner\Controller\Console',
                            'action' => 'process-jobs'
                        )
                    )
                ),
            ),
        ),
    ),
);