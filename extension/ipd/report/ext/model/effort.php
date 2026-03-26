<?php
public function getEffort4Month($account, $year)
{
    return $this->loadExtension('effort')->getEffort4Month($account, $year);
}
