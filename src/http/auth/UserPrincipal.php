<?php

declare(strict_types=1);

namespace Corephp\Http\Auth;

/**
 * UserPrincipal
 * -----------
 * UserPrincipal
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Auth
 */
class UserPrincipal implements UserPrincipalInterface
{
    private AuthProviderInterface $provider;
    private UserIdentityInterface $identity;
    
    /**
     * __construct
     *
     * @param  mixed $identity
     * @return void
     */
    public function __construct(?AuthProviderInterface $provider = null, ?UserIdentityInterface $identity = null)
    {
        $this->provider = $provider ?? make(AuthProvider::class);
        $this->identity = $identity ?? make(AnonymousIdentity::class);
    }
    /**
     * getProvider
     *
     * @return AuthProviderInterface
     */
    public function getProvider(): AuthProviderInterface
    {
        return $this->provider;
    }
    /**
     * getIdentity
     *
     * @return UserIdentityInterface
     */
    public function getIdentity(): UserIdentityInterface
    {
        return $this->identity;
    }    
    /**
     * hasRole
     *
     * @param  string|array $role
     * @return bool
     */
    public function hasRole(string|array $role): bool
    {
        if(is_string($role)){
            return in_array($role, $this->identity->getRoles());
        }elseif(is_array($role)){
            foreach($role as $r){
                if($this->hasRole($r)){
                    return true;
                }
            }
        }
        return false;
    }    
    /**
     * hasPermission
     *
     * @param  string|array $permission
     * @return bool
     */
    public function hasPermission(string|array $permission): bool
    {
        if(is_string($permission)){
            return in_array($permission, $this->identity->getPermissions());
        }elseif(is_array($permission)){
            foreach($permission as $p){
                if($this->hasPermission($p)){
                    return true;
                }
            }
        }
        return false;
    }
}
