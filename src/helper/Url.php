<?php

declare(strict_types=1);

namespace Corephp\Helper;

/**
 * Url
 * -----------
 * Class for working with url
 *
 * @author Khaerul Anas <anasikova@gmail.com>
 * @since v1.0.0
 * @package Corephp\Helper
 */
class Url
{
    static ?string $basePath = null;
    static ?string $hostUrl = null;
    static ?array $parseurl = null;
    
    /**
     * getBasePath
     *
     * @param  string $path
     * @return string
     */
    public static function getBasePath(string $path = ''): string
    {
        if (static::$basePath === null) {
            if (array_key_exists('PATH_INFO', $_SERVER) === true) {
                $basePath = $_SERVER['PATH_INFO'];
            } else {
                $toReplace = '/' . basename($_SERVER['SCRIPT_FILENAME']);
                $basePath = str_replace($toReplace, '', $_SERVER['PHP_SELF']);
            }
            static::$basePath = $basePath;
        }

        return sprintf("%s%s", static::$basePath , $path);
    }
    
    /**
     * getSiteUrl
     *
     * @param  string $url
     * @return string
     */
    public static function getSiteUrl(string $url = ''): string
    {
        return sprintf("%s%s", static::getHostUrl(), static::getBasePath($url));
    }
    
    /**
     * getCurrentUrl
     *
     * @return string
     */
    public static function getCurrentUrl(): string
    {
        return sprintf("%s%s", static::getHostUrl(), $_SERVER['REQUEST_URI']);
    }
            
    /**
     * getHostUrl
     *
     * @param  string $path
     * @return string
     */
    public static function getHostUrl(string $path = ''): string
    {
        if (static::$hostUrl === null) {
            if(CORE_ENVIRONMENT == 'production'){
                static::$hostUrl = sprintf(
                    "https://%s", $_SERVER['HTTP_HOST']);
            }else{
                static::$hostUrl = sprintf(
                    "%s%s",
                    isset($_SERVER['HTTPS']) ? 'https://' : 'http://',
                    $_SERVER['HTTP_HOST']);
            }
        }

        return static::$hostUrl . $path;
    }
    
    /**
     * getCurrentPath
     *
     * @param  mixed $query
     * @return string
     */
    public static function getCurrentPath(string $query = ''): string
    {
        if($query){
            $query = '?' . $query;
        }
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        return sprintf("%s%s", $path, $query);
    }
    
    /**
     * getParseUrl
     *
     * @return array
     */
    public static function getParseUrl(): array{
        if (static::$parseurl === null) {
            static::$parseurl = parse_url(static::getCurrentUrl());
        }
        
        return static::$parseurl;
    } 
}
