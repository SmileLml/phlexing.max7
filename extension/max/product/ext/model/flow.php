<?php
/**
 * @param string $module
 * @param string $method
 * @param string $extra
 * @param bool $branch
 */
public function getProductLink($module, $method, $extra = '', $branch = false)
{
    return $this->loadExtension('flow')->getProductLink($module, $method, $extra, $branch);
}
