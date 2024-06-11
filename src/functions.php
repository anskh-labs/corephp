<?php

declare(strict_types=1);

use Corephp\Db\Database;
use Corephp\Db\DatabaseFactory;
use Corephp\Component\Escaper\Escaper;
use Corephp\Helper\Url;
use Corephp\Helper\Config;
use Corephp\Helper\Container;
use Corephp\Helper\Service;
use Corephp\Html\Form;
use Corephp\Http\Session\Session;
use Corephp\Http\Session\SessionInterface;
use Corephp\Model\FormModel;

/**
 * Set of function helper 
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 */

if (!function_exists('config')) {
    /**
     * config
     *
     * @param  mixed $offset
     * @param  mixed $defaultValue
     * @return mixed
     */
    function config(mixed $offset, mixed $defaultValue = null): mixed
    {
        return Config::get($offset, $defaultValue);
    }
}

if (!function_exists('make')) {
    /**
     * make
     *
     * @param  mixed $id
     * @param  mixed $options
     * @return mixed
     */
    function make(string $id, array $options = ['shared' => false, 'args' => []])
    {
        $shared =  $options['shared'] ?? false;
        $args =  $options['args'] ?? [];

        return Container::get($id, compact('shared', 'args'));
    }
}
if (!function_exists('site_url')) {
    /**
     * site_url
     *
     * @param  mixed $path
     * @return string
     */
    function site_url(string $path = ''): string
    {
        return Url::getSiteUrl($path);
    }
}
if (!function_exists('base_url')) {
    /**
     * base_url
     *
     * @param  mixed $path
     * @return string
     */
    function base_url(string $path = ''): string
    {
        return Url::getHostUrl($path);
    }
}
if (!function_exists('base_path')) {
    /**
     * base_path
     *
     * @param  mixed $path
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return Url::getBasePath($path);
    }
}
if (!function_exists('current_url')) {
    /**
     * current_url
     *
     * @return string
     */
    function current_url(): string
    {
        return Url::getCurrentUrl();
    }
}
if (!function_exists('current_path')) {
    /**
     * current_path
     *
     * @param  mixed $query
     * @return string
     */
    function current_path(string $query = ''): string
    {
        return Url::getCurrentPath($query);
    }
}
if (!function_exists('route')) {
    /**
     * route
     *
     * @param  mixed $name
     * @param  mixed $param
     * @return string
     */
    function route(string $name, string $param = ''): string
    {
        $route = Config::get('route.' . $name);
        $url = $route[1];
        if ($pos = strpos($url, '[')) {
            $url = substr($url, 0, $pos);
        }
        if ($pos = strpos($url, '{')) {
            $url = substr($url, 0, $pos);
        }

        return Url::getBasePath($url . $param);
    }
}

if (!function_exists('is_route')) {
    /**
     * is_route
     *
     * @param  mixed $name
     * @return bool
     */
    function is_route(array|string $name): bool
    {
        if (is_array($name)) {
            foreach ($name as $n) {
                if (is_route($n))
                    return true;
            }

            return false;
        } elseif (is_string($name)) {
            $route = Config::get('route.' . $name);
            $url = $route[1];
            if ($pos = strpos($url, '[')) {
                $url = substr($url, 0, $pos);
            }
            if ($pos = strpos($url, '{')) {
                $url = substr($url, 0, $pos);
            }
            $rpath = Url::getBasePath($url);
            $cpath = Url::getCurrentPath();
            if ($url === $route[1])
                return $rpath === $cpath;
            else
                return str_starts_with($cpath, $rpath);
        }
    }
}

if (!function_exists('attr_to_string')) {
    /**
     * attr_to_string
     *
     * @param  mixed $attributes
     * @return string
     */
    function attr_to_string(mixed $attributes): string
    {
        if (empty($attributes)) {
            return '';
        }
        if (is_object($attributes)) {
            $attributes = (array) $attributes;
        }
        if (is_array($attributes)) {
            $atts = '';
            foreach ($attributes as $key => $val) {

                if (is_object($val)) {
                    $val = (array) $val;
                }
                if (is_array($val)) {
                    $val = trim(attr_to_string($val));
                }
                if (is_numeric($key)) {
                    $key = '';
                } else {
                    $key .= '=';
                    $val = "\"$val\"";
                }
                $atts = empty($atts) ? ' ' . $key . $val : $atts . ' ' . $key  . $val;
            }

            return $atts;
        }

        if (is_string($attributes)) {
            return ' ' . $attributes;
        }

        return '';
    }
}
if (!function_exists('session')) {
    /**
     * session
     *
     * @param  mixed $request
     * @return Session
     */
    function session(): SessionInterface
    {
        return Service::session();
    }
}
if (!function_exists('db')) {
    /**
     * db
     *
     * @param  mixed $connection
     * @return Database
     */
    function db(?string $connection = null): Database
    {
        $connection = $connection ?? CORE_CONNECTION;
        return DatabaseFactory::create($connection);
    }
}
if (!function_exists('esc')) {

    /**
     * esc
     *
     * @param  mixed $data
     * @param  mixed $context
     * @param  mixed $encoding
     * @return array
     */
    function esc(array|string $data, string $context = 'html', ?string $encoding = null): array|string
    {
        $encoding = $encoding ?? 'utf-8';
        if (is_array($data)) {
            foreach ($data as &$value) {
                $value = esc($value, $context);
            }
        }

        if (is_string($data)) {
            $context = strtolower($context);

            // Provide a way to NOT escape data since
            // this could be called automatically by
            // the View library.
            if ($context === 'raw') {
                return $data;
            }

            if (!in_array($context, ['html', 'js', 'css', 'url', 'attr'], true)) {
                throw new InvalidArgumentException('Invalid escape context provided.');
            }

            $method = $context === 'attr' ? 'escapeHtmlAttr' : 'escape' . ucfirst($context);

            static $escaper;
            if (!$escaper) {
                $escaper = new Escaper($encoding);
            }

            if ($encoding && $escaper->getEncoding() !== $encoding) {
                $escaper = new Escaper($encoding);
            }

            $data = $escaper->{$method}($data);
        }

        return $data;
    }
}
if (!function_exists('create_form')) {

    /**
     * create_form
     *
     * @param  mixed $model
     * @return Form
     */
    function create_form(FormModel $model): Form
    {
        return new Form($model);
    }
}
