<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Entity\Hydrator;


use WbMiner\Entity\Image;
use WbMiner\Entity\JobInterface;
use Zend\Stdlib\Exception;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;
use Zend\Stdlib\Hydrator\Reflection;

class ImageHydrator extends ClassMethods
{
    protected $extractor;

    public function __construct()
    {
        parent::__construct();
        $this->setUnderscoreSeparatedKeys(false);

        $this->extractor = new Reflection();
        $this->extractor->addFilter('image', function ($property) {
            return !in_array($property, array('image', 'imageClass', 'imageHydrator'));
        }, FilterComposite::CONDITION_AND);

        $this->addFilter('hydrator', new MethodMatchFilter('getHydrator'), FilterComposite::CONDITION_AND);
        $this->addFilter('image', new MethodMatchFilter('getImage'), FilterComposite::CONDITION_AND);
    }

    public function extract($object) {

        if (!$object instanceof JobInterface) {
            throw new \InvalidArgumentException(sprintf('Expected %s. %s given', 'WbMiner\Entity\JobInterface', get_class($object)));
        }

        return $this->extractor->extract($object);

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
            throw new \InvalidArgumentException(sprintf('Expected %s. %s given', 'WbMiner\Entity\Image', get_class($object)));

        return parent::hydrate($data, $object);
    }
}