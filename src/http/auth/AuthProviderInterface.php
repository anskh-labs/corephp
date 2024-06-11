<?php

declare(strict_types=1);

namespace Corephp\Http\Auth;

/**
 * AuthProviderInterface
 * -----------
 * AuthProvider contract
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Auth
 */
interface AuthProviderInterface
{    
    /**
     * getProvider
     *
     * @return string
     */
    public function getProvider(): string;    
    /**
     * getLoginUri
     *
     * @return string
     */
    public function getLoginUri(): string;    
    /**
     * getLogoutUri
     *
     * @return string
     */
    public function getLogoutUri(): string;
}