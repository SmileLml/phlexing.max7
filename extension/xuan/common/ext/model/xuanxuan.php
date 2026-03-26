<?php
public static function getLicensePropertyValue($propertyName)
{
    return (new self)->loadExtension('xuanxuan')->getLicensePropertyValue($propertyName);
}
public static function ilMethod($module, $method, $extra = '')
{
    return (new self)->loadExtension('xuanxuan')->ilMethod($module, $method, $extra);
}
