<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Job\JobInterface;

class ProcessResult
{
    const SUCCESSFUL = 1;

    const FAILURE = -1;

    protected $status;

    /**
     * @var \WbMiner\Job\JobInterface
     */
    protected $job;

    public function __construct($status, JobInterface $job = null)
    {
        $this->status = $status;

        if (null !== $job) {
            $this->job = $job;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return JobInterface
     */
    public function getJob()
    {
        return $this->job;
    }
} 