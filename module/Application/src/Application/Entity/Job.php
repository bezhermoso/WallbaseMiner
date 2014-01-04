<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use WbMiner\Entity\Job as BaseJob;

/**
 * Class Job
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Application\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="job")
 */
class Job extends BaseJob
{

}
