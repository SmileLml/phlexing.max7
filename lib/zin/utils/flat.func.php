<?php
namespace zin\utils;

function flat($array, $prefix = '')
{
    $result = array();
    foreach($array as $key => $value)
    {
        if(is_array($value))
        {
            $result = array_merge($result, flat($value, "{$prefix}{$key}."));
        }
        else
        {
            $result[$prefix . $key] = $value;
        }
    }
    return $result;
}
