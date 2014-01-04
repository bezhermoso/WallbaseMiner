<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Image;


use Application\Entity\Image;

interface ImageProviderInterface
{
    /**
     * @var Image[]
     */
    public function getImages();
}