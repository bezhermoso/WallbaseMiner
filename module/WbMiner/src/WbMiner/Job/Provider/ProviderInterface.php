<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Provider;


use WbMiner\Job\JobInterface;

interface ProviderInterface
{
    /**
     * @return JobInterface[]
     */
    public function getJobs();
}