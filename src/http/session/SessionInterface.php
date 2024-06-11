<?php

declare(strict_types=1);

namespace Corephp\Http\Session;

/**
 * SessionInterface
 * -----------
 * SessionInterface
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Session
 */
interface SessionInterface
{
    /**
     * set
     *
     * @param  mixed $property
     * @param  mixed $value
     * @return void
     */
    public function set(string $property, mixed $value): void;
    /**
     * csrfToken
     *
     * @param  mixed $formName
     * @param  mixed $generate
     * @param  mixed $length
     * @return string
     */
    public function csrfToken(string $formName, bool $generate = true, int $length = 32): string;

    /**
     * validateCsrfToken
     *
     * @param  mixed $formName
     * @param  mixed $token
     * @return bool
     */
    public function validateCsrfToken(string $formName, ?string $token): bool;

    /**
     * setUserId
     *
     * @param  mixed $userId
     * @return void
     */
    public function setUserId(string $userId): void;
    /**
     * setUserHash
     *
     * @param  mixed $userHash
     * @return void
     */
    public function setUserHash(string $userHash): void;
    /**
     * captcha
     *
     * @param  mixed $formName
     * @param  mixed $length
     * @return string
     */
    public function captcha(string $formName, int $length = 6): string;
    /**
     * get
     *
     * @param  mixed $property
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function get(?string $property = null, mixed $defaultValue = null): mixed;
    /**
     * getUserId
     *
     * @return mixed
     */
    public function getUserId(): mixed;
    /**
     * getUserHash
     *
     * @return mixed
     */
    public function getUserHash(): mixed;
    /**
     * validateCaptcha
     *
     * @param  mixed $formName
     * @param  mixed $captcha
     * @return bool
     */
    public function validateCaptcha(string $formName, ?string $captcha): bool;
    /**
     * has
     *
     * @param  mixed $property
     * @return bool
     */
    public function has(?string $property = null): bool;

    /**
     * unset
     *
     * @param  mixed $property
     * @return mixed
     */
    public function unset(?string $property = null): mixed;
    /**
     * unsetUserId
     *
     * @return void
     */
    public function unsetUserId(): void;
    /**
     * unsetUserHash
     *
     * @return void
     */
    public function unsetUserHash(): void;

    /**
     * addFlashInfo
     *
     * @param  mixed $message
     * @return void
     */
    public function addFlashInfo(string $message): void;

    /**
     * flashInfo
     *
     * @return FlashMessage
     */
    public function flashInfo(): ?FlashMessage;
    /**
     * addFlashError
     *
     * @param  mixed $message
     * @return void
     */
    public function addFlashError(string $message): void;
    /**
     * flashError
     *
     * @return FlashMessage
     */
    public function flashError(): FlashMessage;
    /**
     * addFlashWarning
     *
     * @param  mixed $message
     * @return void
     */
    public function addFlashWarning(string $message): void;
    /**
     * flashWarning
     *
     * @return FlashMessage
     */
    public function flashWarning(): FlashMessage;
    /**
     * addFlashSuccess
     *
     * @param  mixed $message
     * @return void
     */
    public function addFlashSuccess(string $message): void;
    /**
     * flashSuccess
     *
     * @return FlashMessage
     */
    public function flashSuccess(): FlashMessage;

    /**
     * addFlash
     *
     * @param  mixed $type
     * @param  mixed $message
     * @return void
     */
    public function addFlash(string $type, string $message): void;

    /**
     * flash
     *
     * @param  mixed $type
     * @return FlashMessage|array
     */
    public function flash(?string $type = null): FlashMessage|array;

    /**
     * hasFlash
     *
     * @param  string $type
     * @return bool
     */
    public function hasFlash(?string $type = null): bool;

    /**
     * hasFlashSuccess
     *
     * @return bool
     */
    public function hasFlashSuccess(): bool;
    /**
     * hasFlashError
     *
     * @return bool
     */
    public function hasFlashError(): bool;
    /**
     * hasFlashWarning
     *
     * @return bool
     */
    public function hasFlashWarning(): bool;
    /**
     * hasFlashInfo
     *
     * @return bool
     */
    public function hasFlashInfo(): bool;
}
