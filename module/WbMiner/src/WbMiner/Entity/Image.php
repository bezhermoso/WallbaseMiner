<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner\Entity;


use WbMiner\Wallbase;

class Image implements ImageInterface
{
    protected $originId;

    protected $tags = array();

    protected $purityLevel = 0;

    protected $originFormat;

    protected $fileName;

    protected $originUrl;

    /**
     * Set originId
     *
     * @param integer $originId
     * @return Image
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
     * Set tags
     *
     * @param array $tags
     * @return Image
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

    /**
     * Set purityLevel
     *
     * @param integer $purityLevel
     * @return Image
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

    public function getOriginUrl2()
    {
        return sprintf('%s/wallpaper/%s.%s', Wallbase::getUrl(), $this->getOriginId(), $this->getOriginFormat());
    }

    /**
     * Set originFormat
     *
     * @param string $originFormat
     * @return Image
     */
    public function setOriginFormat($originFormat)
    {
        $this->originFormat = $originFormat;

        return $this;
    }

    /**
     * Get originFormat
     *
     * @return string
     */
    public function getOriginFormat()
    {
        return $this->originFormat;
    }

    public $hydrator;

    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Image
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }



    public function setOriginUrl($url)
    {
        $this->originUrl = $url;

        return $this;
    }

    public function getOriginUrl()
    {
        $url = $this->originUrl;

        if (!$this->originFormat) {
            return $url;
        } else {
            $url = substr($url, strrpos($url, '.'));
            return $url.$this->originFormat;
        }
    }
}