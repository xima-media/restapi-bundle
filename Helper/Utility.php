<?php

namespace Xima\RestApiBundle\Helper;

/**
 * Class Utility
 *
 * @author Steve Lenz <steve.lenz@xima.de>, XIMA Media GmbH
 * @package Xima\RestApiBundle\Helper
 */
class Utility
{

    /**
     * Generates and returns a unique hash
     *
     * @return string
     */
    public static function generateUniqueHash()
    {
        return md5(uniqid(null, true));
    }

}