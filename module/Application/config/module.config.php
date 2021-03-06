<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'wb-miner' => array(
        'doctrine' => array(
            'orm' => array(
                'job_class' => 'Application\Entity\Job',
                'image_class' => 'Application\Entity\Image',
            )
        ),
        'logger' => 'Application\Log'
    ),
    'doctrine' => array(
        'driver' => array(
            'application_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Application/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_annotation_driver',
                )
            )
        ),
    ),
    'log' => array(
        'Application\Log' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'options' => array(
                        'stream' => 'data/logs/app.log'
                    ),
                ),
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'job-request' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/api/job-request',
                    'defaults' => array(
                        'controller' => 'Application\Controller\JobRequest',
                        'action' => 'job-request'
                    ),
                )
            )
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'invokables' => array(
            'Application\LoggingExtensionSwitcher' => 'Application\Service\LoggingExtensionSwitcherFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        'delegators' => array(
            'WbMiner\Job\Processor\SaveImage' => array(
                'Application\LoggingExtensionSwitcher'
            )
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
        'factories' => array(
            'Application\Controller\JobRequest' => 'Application\Service\JobRequestControllerFactory'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
