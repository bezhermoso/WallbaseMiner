<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job;


use Doctrine\ORM\EntityRepository;
use WbMiner\Entity\JobInterface;

class DoctrineRepository extends EntityRepository implements RepositoryInterface
{

    public function save(JobInterface $job, $flush = false)
    {
        $this->_em->persist($job);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(JobInterface $job, $flush = false)
    {
        $this->_em->remove($job);
        if ($flush) {
            $this->_em->flush();
        }
    }
}