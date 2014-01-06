<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\WbMiner;


use WbMiner\Entity\Image;
use WbMiner\Entity\JobInterface;
use WbMiner\Exception\BadRequestException;
use WbMiner\Job\Process\ExtensionSwitcherProcessor;
use WbMiner\Job\Process\ProcessResult;
use Zend\Log\LoggerInterface;

class LoggingExtensionSwitcher extends ExtensionSwitcherProcessor
{
    protected $switcher;

    protected $logger;

    /**
     * @param ExtensionSwitcherProcessor $switcher
     * @param \Zend\Log\LoggerInterface $logger
     */
    public function __construct(ExtensionSwitcherProcessor $switcher, LoggerInterface $logger)
    {
        $this->processor = $switcher->processor;
        $this->logger = $logger;
    }

    public function process(JobInterface $job, $extension = NULL)
    {
        if ($extension !== null) {
            $this->logger->info(
                sprintf('Switching extension of %s to "%s"',
                    $job->getImage()->getOriginUrl(),
                    $extension
            ));

            try {

                $result = parent::process($job, $extension);

                if ($result->getStatus() == ProcessResult::SUCCESSFUL) {
                    $this->logger->info(
                        sprintf(
                            'Successfully resolved to proper extension: %s',
                            $job->getImage()->getOriginUrl()
                    ));
                } else {
                    $this->logger->err(
                        sprintf(
                            'Failed to resolve: %s',
                            $job->getImage()->getOriginUrl()
                    ));
                }

                return $result;

            } catch (BadRequestException $e) {
                $this->logger->err(get_class($this->switcher) . ' failed: ' . $e->getMessage());
                throw $e;
            }
        } else {
            return parent::process($job, $extension);
        }
    }


} 