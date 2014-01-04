<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Controller;


use Application\Entity\Hydrator\ImageHydrator;
use Application\Entity\Image;
use Application\Entity\Job;
use Application\Model\IdOnlyProvider;
use Doctrine\ORM\EntityManager;
use WbMiner\Entity\JobInterface;
use WbMiner\Wallbase;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use WbMiner\Entity\ImageInterface;

class JobRequestController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function jobRequestAction()
    {
        /** @var $request Request */
        $request = $this->getRequest();

        if (!$request->isPost()) {
         //   return $this->getResponse();
        }


        $provider = new IdOnlyProvider($this->em);


        $images = $provider->getImages();
        $jobs = $provider->getJobs();

        $ids = array();

        foreach ($images as $image) {
            $ids[] = $image->getOriginId();
        }

        foreach ($jobs as $job) {
            $ids[] = $job->getImage()->getOriginId();
        }

        $purityMap = array(
            '0' => ImageInterface::PURITY_LEVEL_SFW,
            '1' => ImageInterface::PURITY_LEVEL_NSFW,
            '2' => ImageInterface::PURITY_LEVEL_SKETCHY,
        );

        $jobsData = Json::decode($request->getContent());

        if ($jobsData) {

            foreach ($jobsData as $jobData) {

                if (in_array($jobData->id, $ids)) {
                    continue;
                }

                $job = new Job();

                $job->setOriginId($jobData->id);
                $job->setPurityLevel(isset($purityMap[$jobData->purity]) ? $purityMap[$jobData->purity] : null);
                $job->setTags($jobData->tags);
                $job->setOriginUrl($jobData->imageUrl);

                $this->em->persist($job);
            }
        }

        $this->em->flush();

        return $this->getResponse();
    }
}