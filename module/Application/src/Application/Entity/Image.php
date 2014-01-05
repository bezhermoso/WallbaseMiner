<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Entity;


use WbMiner\Entity\Image as BaseImage;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Image
 *
 * @ORM\Entity
 * @ORM\Table(name="images")
 * @ORM\HasLifecycleCallbacks
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Application\Entity
 */
class Image extends BaseImage
{
    /**
     * @ORM\PrePersist
     */
    public function updateFilename()
    {
        $filename = $this->getFilename();

        $savePath = static::getSavePath();

        if ($savePath) {
            $savePath = preg_replace('#^' . preg_quote($savePath) . '/#', '', $filename);
            $this->setFilename($savePath);
        }

    }
}
