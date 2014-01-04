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

    protected $tags;

    protected $originUrl;

    public static $imageClass = 'Application\Entity\Image';

    /**
     * @var HydratorInterface
     */
    public static $imageHydrator = 'Application\Entity\Hydrator\ImageHydrator';

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
            $hydrator->hydrate($hydrator->extract($this), $image);
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

    /**
     * Set originId
     *
     * @param integer $originId
     * @return Job
     */
    public function setOriginId($originId)
    {
        $this->originId = $originId;

        return $this;
    }

    /**
     * Get originId
     *
     * @return integer 
     */
    public function getOriginId()
    {
        return $this->originId;
    }

    /**
     * Set purityLevel
     *
     * @param integer $purityLevel
     * @return Job
     */
    public function setPurityLevel($purityLevel)
    {
        $this->purityLevel = $purityLevel;

        return $this;
    }

    /**
     * Get purityLevel
     *
     * @return integer 
     */
    public function getPurityLevel()
    {
        return $this->purityLevel;
    }

    /**
     * Set tags
     *
     * @param array $tags
     * @return Job
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return array 
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function setOriginUrl($url)
    {
        $this->originUrl = $url;

        return $this;
    }

    public function getOriginUrl()
    {
        return $this->originUrl;
    }
}
