<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Entity;


use WbMiner\Entity\ImageInterface;

interface JobInterface
{
    public function getJobId();

    /**
     * @return \WbMiner\Entity\ImageInterface
     */
    public function getImage();

}