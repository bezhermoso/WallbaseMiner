<?php
/**
 * Copyright 2014 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace WbMiner;


class Wallbase
{
    protected static $url = 'http://wallbase.cc';

    public   static function setUrl($url)
    {
        self::$url;
    }

    public static function getUrl()
    {
        return self::$url;
    }
} 