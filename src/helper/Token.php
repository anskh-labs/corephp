<?php

declare(strict_types=1);

namespace Corephp\Helper;

/**
 * Token
 * -----------
 * Class for working with token
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Helper
 */
class Token
{    
    /**
     * generateToken
     *
     * @param  mixed $length
     * @return string
     */
    public static function generateToken(int $length = 16): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * generateMD5Token
     *
     * @param  mixed $length
     * @return string
     */
    public static function generateMD5Token(int $length = 64): string
    {
        return md5(random_bytes($length));
    }
}
