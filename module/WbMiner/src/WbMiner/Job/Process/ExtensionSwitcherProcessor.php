<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\Image;
use WbMiner\Entity\JobInterface;
use WbMiner\Exception\BadRequestException;

class ExtensionSwitcherProcessor implements ProcessorInterface
{
    protected $extensions = array(
        'jpg', 'jpeg', 'png'
    );


    /**
     * @var ProcessorInterface
     */
    protected $processor;

    public  function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @param JobInterface $job
     * @return ProcessResult
     */
    public function process(JobInterface $job)
    {
        try {

            $result = $this->processor->process($job);

        } catch (BadRequestException $e) {

            $image = $job->getImage();

            if ($image instanceof Image
                && $image->getOriginFormat() != end($this->extensions))
            {
                if ($image->getOriginFormat() == null) {

                    $image->setOriginFormat($this->extensions[0]);
                    $result = $this->process($job);

                } elseif (false !== ($i = array_search($image->getOriginFormat(), $this->extensions))) {

                    $image->setOriginFormat($this->extensions[$i + 1]);
                    $result = $this->process($job);

                }
            } else {
                return new ProcessResult(ProcessResult::FAILURE, $job);
            }

        }

        return $result;
    }
}