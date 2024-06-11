<?php

declare(strict_types=1);

namespace Corephp\Http\Auth;

/**
 * AuthProvider
 * -----------
 * AuthProvider 
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Auth
 */
class AuthProvider implements AuthProviderInterface
{   
    private string $loginUri;
    private string $logoutUri;
        
    /**
     * __construct
     *
     * @param  mixed $loginUri
     * @param  mixed $logoutUri
     * @return void
     */
    public function __construct(string $loginUri = '', string $logoutUri='')
    {
        $this->loginUri = $loginUri;
        $this->logoutUri = $logoutUri;
    }
    /**
     * getProvider
     *
     * @return string
     */
    public function getProvider(): string
    {
        return 'User Authentication';
    }    
    /**
     * getLoginUri
     *
     * @return string
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }
    /**
     * getLogoutUri
     *
     * @return string
     */
    public function getLogoutUri(): string
    {
        return $this->logoutUri;
    }
}