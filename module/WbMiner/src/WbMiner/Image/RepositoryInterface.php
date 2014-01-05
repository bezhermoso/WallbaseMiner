<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Image;


use WbMiner\Entity\Image;
use WbMiner\Entity\ImageInterface;

interface RepositoryInterface
{
    /**
     * @return Image[]
     */
    public function findAll();

    /**
     * @param \WbMiner\Entity\ImageInterface $image
     * @return mixed
     */
    public function save(ImageInterface $image);

    public function remove(ImageInterface $image);

} 