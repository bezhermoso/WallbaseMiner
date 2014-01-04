<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use WbMiner\Image\ImageInterface;
use WbMiner\Job\JobInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class Job
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Application\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="job")
 */
class Job implements JobInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $jobId;

    /**
     * @var ImageInterface
     */
    protected $image;

    /**
     * @ORM\Column(name="origin_id", type="integer")
     */
    protected $originId;

    /**
     * @ORM\Column(name="purity_level", type="integer")
     */
    protected $purityLevel;

    /**
     * @ORM\Column(name="tags", type="json_array")
     */
    protected $tags;

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
     * @return ImageInterface
     */
    public function getImage()
    {
        $image = new self::$imageClass;

        $hydrator = static::getHydrator();

        $hydrator->hydrate($hydrator->extract($this), $image);

        $this->image = $image;

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
}
