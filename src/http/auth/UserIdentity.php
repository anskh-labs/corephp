<?php

declare(strict_types=1);

namespace Corephp\Http\Auth;

/**
 * AnonymousIdentity
 * -----------
 * Identitiy for guest or not athenticated user
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Auth
 */
class UserIdentity implements UserIdentityInterface
{
    protected string|int|null $id;
    protected array $roles;
    protected array $permissions;
    protected array $data;

    /**
     * __construct
     *
     * @param  mixed $id
     * @param  mixed $name
     * @param  mixed $roles
     * @param  mixed $permissions
     * @param  mixed $data
     * @return void
     */
    public function __construct(string|int|null $id = null, array $roles = [], array $permissions = [], array $data = [])
    {
        $this->id = $id;
        $this->roles = $roles;
        $this->permissions = $permissions;
        $this->data = $data;
    }

    /**
     * getId
     *
     * @return string
     */
    public function getId(): string|int|null
    {
        return $this->id;
    }
    /**
     * getRoles
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
    /**
     * getPermissions
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
    /**
     * isAuthenticated
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return empty($this->id) === false;
    }
    /**
     * getData
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
