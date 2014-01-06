<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Job\Process;


use WbMiner\Entity\JobInterface;
use Zend\Config\Config;
use Zend\Config\Reader\ReaderInterface;
use Zend\Config\Writer\Json as JsonWriter;
use Zend\Config\Reader\Json as JsonReader;
use Zend\Config\Writer\WriterInterface;
use Zend\EventManager\EventManagerInterface;

class LockingMainProcessor extends MainProcessor
{
    protected $mainProcessor;

    protected $lockFile;

    protected $reader;

    protected $writer;

    public function __construct(MainProcessor $processor, $lockFile, ReaderInterface $reader = null, WriterInterface $writer = null)
    {
        if ($writer === null)
        $this->writer = new JsonWriter();

        if ($reader === null)
            $this->reader = new JsonReader();

        $this->mainProcessor = $processor;
        $this->lockFile = $lockFile;
    }

    public function process(JobInterface $job = NULL)
    {
        if (!file_exists($this->lockFile)) {
            $this->writeLockFile();
            $result = $this->mainProcessor->process($job);
            $this->deleteLockFile();
            return $result;
        } else {

            $lockData = $this->readLockFile();
            throw new \RuntimeException(
                sprintf('Locked -- another process is probably still running this script [Ran on: %s]. ' .
                        'Delete lock file ("%s") if you think this is in error.',
                isset($lockData['date']) ? $lockData['date'] : 'null',
                realpath($this->lockFile))
            );
        }
    }

    public function writeLockFile()
    {
        $date = new \DateTime();

        $config = new Config(array(
           'date' => $date->format('Y-m-d H:i:s')
        ));

        $this->writer->toFile($this->lockFile, $config);
    }

    public function readLockFile()
    {
        return $this->reader->fromFile($this->lockFile);
    }

    public function deleteLockFile()
    {
        unlink($this->lockFile);
    }

    public function getEventManager()
    {
        return $this->mainProcessor->getEventManager();
    }

    public function setLimit($limit)
    {
        $this->mainProcessor->setLimit($limit);
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->mainProcessor->setEventManager($eventManager);
    }


} 