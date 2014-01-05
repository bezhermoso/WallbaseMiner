<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Image;


use Application\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use WbMiner\Entity\ImageInterface;

class DoctrineRepository extends EntityRepository implements RepositoryInterface
{
    /**
     * @return array|Image[]|\Iterator
     */
    public function getImages()
    {
        return $this->findAll();
    }

    /**
     * @param \WbMiner\Entity\ImageInterface $image
     * @param bool $flush
     * @return mixed
     */
    public function save(ImageInterface $image, $flush = false)
    {
        $this->_em->persist($image);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(ImageInterface $image, $flush = false)
    {
        $this->_em->remove($image);

        if ($flush) {
            $this->_em->flush();
        }
    }
}