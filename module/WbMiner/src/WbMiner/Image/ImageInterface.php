<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Image;


interface ImageInterface
{
    const PURITY_LEVEL_SFW = 0;
    const PURITY_LEVEL_SKETCHY = 1;
    const PURITY_LEVEL_NSFW = 2;

    public function getOriginUrl();

    public function getPurityLevel();

    public function getTags();

    public function getOriginId();
}