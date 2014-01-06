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
     * @throws \WbMiner\Exception\BadRequestException
     * @throws \Exception
     * @throws \WbMiner\Exception\BadRequestException
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

            if ($image instanceof Image) {
                $url = $image->getOriginUrl();
                $pos = strrpos($url, '.');
                $attemptExtension = substr($url, $pos + 1);

                if ($extension === null) {

                    /**
                     * Loop through possible extensions until BadRequestException is not thrown.
                     */
                    $this->initialExtension = $attemptExtension;
                    $this->attemptedExtensions[] = $attemptExtension;

                    foreach ($this->extensions as $attemptExtension) {

                        if (in_array($attemptExtension, $this->attemptedExtensions)) {
                            continue;
                        }

                        try {

                            $result = $this->process($job, $attemptExtension);
                            return $result;

                        } catch (BadRequestException $newE) {
                            $this->attemptedExtensions[] = $attemptExtension;
                            continue;
                        }
                    }

                    $this->attemptedExtensions = array();

                    if ($newE) {
                        throw $newE;
                    } else {
                        throw new BadRequestException(__CLASS__ . ' failed to resolve ' . $image->getOriginUrl() . ' with the proper extension.');
                    }

                } else { //If extension is defined in process() call.

                    $url = substr($url, 0, $pos + 1) . $extension;
                    $image->setOriginUrl($url);
                    $result = $this->processor->process($job, $extension);
                    return $result;
                }

            } else {
                return new ProcessResult(ProcessResult::FAILURE, $job);
            }
        }
    }
}