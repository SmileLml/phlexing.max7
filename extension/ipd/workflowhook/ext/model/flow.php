<?php
public function getTableFields($table)
{
    return $this->loadExtension('flow')->getTableFields($table);
}
