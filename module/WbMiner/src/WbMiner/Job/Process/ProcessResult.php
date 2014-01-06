<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\JobInterface;

class ProcessResult
{
    const SUCCESSFUL = 1;

    const FAILURE = -1;

    protected $status;

    /**
     * @var \WbMiner\Entity\JobInterface
     */
    protected $job;

    protected $params = array();

    protected $reason = '';

    public function __construct($status, JobInterface $job = null, $reason = '')
    {
        $this->status = $status;

        if (null !== $job) {
            $this->job = $job;
        }

        $this->reason = $reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
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

    public function getParam($param)
    {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        } else {
            return null;
        }

    }

    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }
} 