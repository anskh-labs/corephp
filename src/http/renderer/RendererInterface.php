<?php declare(strict_types=1);

namespace Corephp\Http\Renderer;

/**
 * ViewRendererInterface
 * -----------
 * ViewRendererInterface
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Renderer
 */
Interface RendererInterface
{    
    /**
     * render
     *
     * @param  mixed $view
     * @param  mixed $params
     * @return string
     */
    public function render(string $view, array $params = []): string;
    /**
     * getParam
     *
     * @param  mixed $key
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function getParam(?string $key = null, mixed $defaultValue = null): mixed;
    /**
     * setParam
     *
     * @param  mixed $key
     * @param  mixed $defaultValue
     * @return void
     */
    public function setParam(string $key, mixed $value): void;
}