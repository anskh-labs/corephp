<?php

declare(strict_types=1);

namespace Corephp\Http\Auth;

/**
 * UserPrincipalInterface
 * -----------
 * UserPrincipalInterface
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Auth
 */
interface UserPrincipalInterface
{
    /**
     * getProvider
     *
     * @return AuthProviderInterface
     */
    public function getProvider(): AuthProviderInterface;
    /**
     * getIdentity
     *
     * @return UserIdentityInterface
     */
    public function getIdentity(): UserIdentityInterface;
    /**
     * hasRole
     *
     * @param  string|array $role
     * @return bool
     */
    public function hasRole(string|array $role): bool;
    /**
     * hasPermission
     *
     * @param  string|array $permission
     * @return bool
     */
    public function hasPermission(string|array $permission): bool;
}
