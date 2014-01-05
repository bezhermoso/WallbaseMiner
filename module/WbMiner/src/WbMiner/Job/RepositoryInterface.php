<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job;


use WbMiner\Entity\JobInterface;

interface RepositoryInterface
{
    /**
     * @return array|JobInterface[]
     */
    public function findAll();

    /**
     * @param JobInterface $job
     * @return mixed
     */
    public function save(JobInterface $job);

    /**
     * @param JobInterface $job
     * @return mixed
     */
    public function remove(JobInterface $job);
} 