<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\JobInterface;

class DoctrineProcessor implements ProcessorInterface
{
    protected $processor;

    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @param JobInterface $job
     * @return ProcessResult
     */
    public function process(JobInterface $job)
    {

    }
}