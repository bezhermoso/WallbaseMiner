<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Model;


use WbMiner\Entity\JobInterface;
use WbMiner\Job\Process\ProcessorInterface;
use WbMiner\Job\Process\ProcessResult;

class SaveImageProcessor implements ProcessorInterface
{

    /**
     * @param \WbMiner\Entity\JobInterface $job
     * @return ProcessResult
     */
    public function process(JobInterface $job)
    {

    }
}