<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Controller;


use WbMiner\Job\Process\MainProcessor;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
    /**
     * @var \WbMiner\Job\Process\MainProcessor
     */
    protected $mainProcessor;

    function __construct(MainProcessor $processor)
    {
        $this->mainProcessor = $processor;
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

        $this->mainProcessor->process();

    }

} 