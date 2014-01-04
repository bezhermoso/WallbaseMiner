<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Provider;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use WbMiner\Entity\JobInterface;

class DoctrineProvider implements ProviderInterface, LimitAwareInterface
{

    protected $em;

    protected $jobClass;

    protected $limit;

    public function __construct(EntityManager $em, $jobClass)
    {
        $this->em = $em;
        $this->jobClass = $jobClass;
    }

    /**
     * @return JobInterface[]|Job[]|\Iterator
     */
    public function getJobs()
    {
        $qb = $this->em->getRepository($this->jobClass)
                          ->createQueryBuilder('job');

        if ($this->limit) {
            $qb->setMaxResults($this->limit);
        }

        return $qb->getQuery()->getResult();


    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }
}