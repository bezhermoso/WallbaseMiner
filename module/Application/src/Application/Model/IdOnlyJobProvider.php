<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Model;


use Application\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use WbMiner\Image\ProviderInterface;
use WbMiner\Entity\JobInterface;
use WbMiner\Job\Provider\ProviderInterface;

class IdOnlyImageProvider implements ProviderInterface
{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\Tools\Pagination\Paginator|Image[]
     */
    public function getImages()
    {
        $query = $this->em->createQuery('SELECT partial image.{originId} FROM Application\Entity\Image image');

        return new Paginator($query);
    }

    /**
     * @return \WbMiner\Entity\JobInterface[]
     */
    public function getJobs()
    {
        // TODO: Implement getJobs() method.
    }
}