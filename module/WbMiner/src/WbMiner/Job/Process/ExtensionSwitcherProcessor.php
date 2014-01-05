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

    protected $initialExtension = '';

    protected $attemptedExtensions = array();

    /**
     * @var SaveImageProcessor
     */
    protected $processor;

    public  function __construct(SaveImageProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @param JobInterface $job
     * @param null $extension
     * @internal param int|\WbMiner\Job\Process\a $attempt
     * @internal param bool $firstCall
     * @return ProcessResult
     */
    public function process(JobInterface $job, $extension = null)
    {
        try {

            $result = $this->processor->process($job);
            return $result;

        } catch (BadRequestException $e) {

            $image = $job->getImage();

            if ($image instanceof Image)
            {
                $url = $image->getOriginUrl();
                $pos = strrpos($url, '.');
                $extension = substr($url, $pos);

                if ($extension === null) {

                    /**
                     * Loop through possible extensions until BadRequestException is not thrown.
                     */
                    $this->initialExtension = $extension;
                    $this->attemptedExtensions[] = $extension;

                    foreach ($this->extensions as $extension) {

                        if (in_array($extension, $this->attemptedExtensions)) {
                            continue;
                        }

                        try {

                            $result = $this->process($job, $extension);
                            return $result;

                        } catch (BadRequestException $newE) {
                            continue;
                        }
                    }

                    $this->attemptedExtensions = array();
                    return new ProcessResult(ProcessResult::FAILURE, $job);

                } else { //If extension is defined in process() call.

                    $url = substr($url, 0, $pos) . $extension;
                    $image->setOriginUrl($url);
                    $this->processor->process($job);
                }

            } else {
                return new ProcessResult(ProcessResult::FAILURE, $job);
            }

        }
    }
}