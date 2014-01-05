<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Entity\Hydrator\ImageHydrator;
use Application\Entity\Job;
use Application\Event\XSAccessListener;
use Doctrine\ORM\EntityRepository;
use WbMiner\Event\PersistImageListener;
use WbMiner\Event\RemoveJobListener;
use WbMiner\Job\Process\MainProcessor;
use WbMiner\Job\Process\ProcessEvent;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $xsAccessListener = new XSAccessListener();
        $xsAccessListener->attach($eventManager);

        $serviceLocator = $e->getApplication()->getServiceManager();

        if (class_exists('WbMiner\Config')) {

            $imageRepo = $serviceLocator->get('WbMiner\ImageRepository');
            $saveImage = new PersistImageListener($imageRepo);
            $saveImage->attach($eventManager);

            $jobRepo = $serviceLocator->get('WbMiner\JobRepository');
            $removeJob = new RemoveJobListener($jobRepo);
            $removeJob->attach($eventManager);

            if ($jobRepo instanceof EntityRepository || $imageRepo instanceof EntityRepository) {
                $eventManager->getSharedManager()
                    ->attach(
                        MainProcessor::EVENT_MANAGER_ID,
                        array(
                            ProcessEvent::PROCESS_FINISHED,
                            ProcessEvent::PROCESS_EXCEPTION
                        ),
                        function ($e) use ($serviceLocator) {
                            $em = $serviceLocator->get('WbMiner\Doctrine\EntityManager');
                            try {
                                $em->flush();
                            } catch (\Exception $e) {

                            }
                    });
            }

        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
