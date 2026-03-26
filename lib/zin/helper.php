<?php
/**
 * The helper methods file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

if(!function_exists('str_contains'))
{
    /**
     * Determine if a string contains a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}
else
{
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

if(!function_exists('str_starts_with'))
{
    /**
     * Checks if a string starts with a given substring
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_starts_with($haystack, $needle)
    {
        return strpos($haystack, $needle) === 0;
    }
}
else
{
    function str_starts_with($haystack, $needle)
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if(!function_exists('str_ends_with'))
{
    /**
     * Checks if a string starts with a given substring.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length === 0) return true;

        $position = strpos($haystack, $needle);
        return $position !== false && $position === strlen($haystack) - $length;
    }
}
else
{
    function str_ends_with($haystack, $needle)
    {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }
}

if(!function_exists('array_is_list'))
{
    /**
     * Checks whether a given array is a list.
     *
     * @param array $array
     * @return bool
     */
    function array_is_list($array)
    {
        if ($array === []) {
            return true;
        }
        return array_keys($array) === range(0, count($array) - 1);
    }
}
else
{
    function array_is_list($array)
    {
        $arrayIsList = function (array $array) : bool {
            if (function_exists('array_is_list')) {
                return array_is_list($array);
            }
            if ($array === []) {
                return true;
            }
            $current_key = 0;
            foreach ($array as $key => $noop) {
                if ($key !== $current_key) {
                    return false;
                }
                ++$current_key;
            }
            return true;
        };
        return $arrayIsList($array);
    }
}

function uncamelize($camelCaps, $separator = '-')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

function isHTML($string)
{
    return $string !== strip_tags($string) ? true : false;
}

/**
 * Check if any element in an array passes a test.
 *
 * @param array $array
 * @param callable $fn
 * @return bool
 */
function array_some($array, $fn)
{
    foreach ($array as $value)
    {
        if($fn($value)) return true;
    }
    return false;
}

/**
 * Check if all elements in an array pass a test.
 *
 * @param array $array
 * @param callable $fn
 * @return bool
 */
function array_every($array, $fn)
{
    foreach ($array as $value)
    {
        if(!$fn($value)) return false;
    }
    return true;
}

/**
 * Filter null values from an array.
 *
 * @param array $array
 * @return array
 */
function array_filter_null($array)
{
    return array_filter($array, function($value)
    {
        return $value !== null;
    });
}
