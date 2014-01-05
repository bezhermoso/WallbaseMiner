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
        if (in_array($param, MainProcessor::$extraParamBlacklist)) {
            throw new \DomainException(
                sprintf('"%s" is a black-listed parameter. Black-listed parameters are: ',
                    $param,
                    json_encode(MainProcessor::$extraParamBlacklist)));
        }

        $this->params[$param] = $value;
        return $this;
    }

    public function getParams()
    {
        return $this->params;
    }
} 