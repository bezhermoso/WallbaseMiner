<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Entity;

use WbMiner\Entity\ImageInterface;
use WbMiner\Entity\JobInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class Job
 *
 *
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package WbMiner\Entity
 */
class Job implements JobInterface
{
    protected $jobId;

    /**
     * @var ImageInterface
     */
    protected $image;

    protected $originId;

    protected $purityLevel;

    protected $tags = array();

    protected $originUrl;

    public static $imageClass = 'Application\Entity\Image';

    /**
     * @var HydratorInterface
     */
    public static $imageHydrator = 'WbMiner\Entity\Hydrator\ImageHydrator';

    public function getJobId()
    {
        return $this->jobId;
    }

    public function setImage(ImageInterface $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \WbMiner\Entity\ImageInterface
     */
    public function getImage()
    {
        if (null === $this->image) {
            $image = new static::$imageClass;
            $hydrator = static::getHydrator();
            $data = $hydrator->extract($this);
            $hydrator->hydrate($data, $image);
            $this->image = $image;
        }

        return $this->image;
    }

    /**
     * @return HydratorInterface
     */
    public static function getHydrator()
    {

        if (is_string(static::$imageHydrator)) {
            static::setHydrator(static::$imageHydrator);
        }

        return static::$imageHydrator;
    }

    /**
     * @param HydratorInterface|string $hydrator
     * @throws \RuntimeException
     */
    public static function setHydrator($hydrator)
    {
        if ($hydrator instanceof HydratorInterface) {
            static::$imageHydrator = $hydrator;
        } elseif (is_string($hydrator)) {
            static::$imageHydrator = new $hydrator;
        } else {
            throw new \RuntimeException('Cannot construct a valid image hydrator.');
        }
    }

    public function updateFields()
    {
        $image = $this->getImage();

        if ($image) {
            $this->originId = $image->getOriginId();
            $this->originUrl = $image->getOriginUrl();
            $this->purityLevel = $image->getPurityLevel();
            $this->tags = $image->getTags();
        }
    }
}
