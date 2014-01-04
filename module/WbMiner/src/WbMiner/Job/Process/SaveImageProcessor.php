<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\ImageInterface;
use WbMiner\Entity\JobInterface;
use WbMiner\Exception\BadRequestException;
use WbMiner\Exception\IOException;
use Zend\Http\Client;

class SaveImageProcessor implements ProcessorInterface
{
    protected $targetDir;

    protected $dirExists = null;

    public function __construct($targetDir)
    {
        $this->targetDir = realpath($targetDir);
    }

    /**
     * @param JobInterface $job
     * @throws \WbMiner\Exception\BadRequestException
     * @return ProcessResult
     */
    public function process(JobInterface $job)
    {
        $image = $job->getImage();

        $client = new Client($image->getOriginUrl());
        $response = $client->send();

        if ($response->getStatusCode() == 200) {
            $this->writeToDisk($image, $response->getBody());
            return new ProcessResult(ProcessResult::SUCCESSFUL, $job);

        } else {

            throw new BadRequestException('Bad request: ' . $client->getUri(), $response->getStatusCode());
            
        }
    }

    protected function writeToDisk(ImageInterface $image, $body)
    {
        if (null === $this->dirExists && !is_writable($this->targetDir)) {
            throw new \RuntimeException("{$this->dirExists} must be writable.");
        }

        $this->dirExists = true;

        $outputFile = $this->targetDir . '/' . basename($image->getOriginUrl());

        if (file_exists($outputFile))
            return;

        if (false === @file_put_contents($outputFile, $body)) {
            throw new IOException('Cannot write to ' . $outputFile);
        }
    }
}