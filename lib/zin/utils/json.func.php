<?php
namespace zin\utils;

/**
 * @return string|false
 */
function jsonEncode($data, $flags = 0, $depth = 512)
{
    return json_encode($data, $flags, $depth);
}
