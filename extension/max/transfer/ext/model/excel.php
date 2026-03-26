<?php
/**
 * @return object|bool
 * @param string $module
 * @param int $pagerID
 * @param string $insert
 * @param string $filter
 */
public function readExcel($module = '', $pagerID = 1, $insert = '', $filter = '')
{
    return $this->loadExtension('excel')->readExcel($module, $pagerID, $insert, $filter);
}
