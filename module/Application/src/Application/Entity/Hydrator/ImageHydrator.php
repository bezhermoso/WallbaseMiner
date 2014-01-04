<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Entity\Hydrator;


use Application\Entity\Image;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ImageHydrator extends ClassMethods
{

    public function __construct()
    {
        parent::__construct();
        $this->setUnderscoreSeparatedKeys(false);
        $this->addFilter('hydrator', new MethodMatchFilter('getHydrator'), FilterComposite::CONDITION_AND);
        $this->addFilter('image', new MethodMatchFilter('getImage'), FilterComposite::CONDITION_AND);
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @throws \InvalidArgumentException
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Image)
            throw new \InvalidArgumentException(sprintf('Expected %s. %s given', 'Application\Entity\Image', get_class($object)));

        return parent::hydrate($data, $object);
    }
}