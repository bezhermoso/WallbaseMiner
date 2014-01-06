<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Controller;


use WbMiner\Job\Process\MainProcessor;
use WbMiner\Job\Process\ProcessEvent;
use WbMiner\Job\Process\ProcessResult;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Request as ConsoleRequest;
use Zend\Log\Logger;
use Zend\Log\LoggerInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\ColorInterface;

class ConsoleController extends AbstractActionController
{
    /**
     * @var \WbMiner\Job\Process\MainProcessor
     */
    protected $mainProcessor;

    protected $consoleAdapter;

    protected $logger;

    public function __construct(MainProcessor $processor, AdapterInterface $adapter, Logger $logger = null)
    {
        $this->mainProcessor = $processor;
        $this->consoleAdapter = $adapter;
        $this->logger = $logger;
    }

    protected function log($level, $message, $extra = array())
    {
        if ($this->logger !== null) {
            $this->logger->log($level, $message, $extra);
        }
    }

    public function processJobsAction()
    {
        $request = $this->getRequest();

        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('Not available outside the console.');
        }

        $limit = $request->getParam('limit');

        if ($limit && is_numeric($limit))
            $this->mainProcessor->setLimit((int) $limit);

        try {

            $this->consoleAdapter->writeLine('');
            $this->consoleAdapter->writeLine('Processing jobs...');
            $this->consoleAdapter->writeLine('');

            $result = $this->mainProcessor->process();

            if ($result->getStatus() == ProcessResult::SUCCESSFUL) {

                $str = '';

                $jobs = $result->getParam('Jobs');

                $str = sprintf(
                    'Processed %d job(s). (%d) successful, (%d) failed [(%d) threw exceptions]',
                    count($jobs),
                    $result->getParam('SuccessCount'),
                    $result->getParam('FailureCount') + $result->getParam('ExceptionCount'),
                    $result->getParam('ExceptionCount'));

                $this->log(Logger::INFO, $str);
                $this->consoleAdapter->writeLine($str);

            } else {
                $str = 'An error was encountered in processing jobs: ' . $result->getParam('Exception')->getMessage();
                $this->log(Logger::ERR, $str);
                $this->consoleAdapter->writeLine($str);
            }

        } catch (\Exception $e) {
            $this->log(Logger::CRIT, $e->getMessage());
            $this->consoleAdapter->writeTextBlock($e->getMessage(), 300, null, 0, 0, ColorInterface::WHITE, ColorInterface::RED);
        }

    }

} 