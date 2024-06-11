<?php

declare(strict_types=1);

namespace Corephp\Http\Renderer;

/**
 * ViewRenderer
 * -----------
 * ViewRenderer
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Http\Renderer
 */
class ViewRenderer implements RendererInterface
{
    private array $params = [];
    private string $viewPath;
    private string $fileExtension;

    /**
     * __construct
     *
     * @param  mixed $viewPath
     * @param  mixed $fileExtension
     * @return void
     */
    public function __construct(string $viewPath, string $fileExtension = '.php')
    {
        $this->viewPath = $viewPath;
        $this->fileExtension = $fileExtension;
    }
    /**
     * render
     *
     * @param  mixed $view
     * @param  mixed $params
     * @return string
     */
    public function render(string $view, array $params = []): string
    {
        if (!empty($params)) {
            $this->params = array_merge($this->params, $params);
        }
        extract($this->params, EXTR_SKIP);
        $filename = $this->viewPath . DS . $view . $this->fileExtension;
        ob_start();
        if (file_exists($filename))
            require $filename;
        else
            echo "File '" . ROOT . DS . "$filename' doesn't exists.";
        return ob_get_clean();
    }

    /**
     * getParam
     *
     * @param  mixed $key
     * @param  mixed $defaultValue
     * @return mixed
     */
    public function getParam(?string $key = null, mixed $defaultValue = null): mixed
    {
        if ($key) {
            return $this->params[$key] ?? $defaultValue;
        }

        return $this->params;
    }
    /**
     * setParam
     *
     * @param  mixed $key
     * @param  mixed $defaultValue
     * @return void
     */
    public function setParam(string $key, mixed $value): void
    {
        $this->params[$key] = $value;
    }
}
