<?php
/**
 * @param mixed[] $projectIdList
 * @param string $time
 */
public function getProjectsConsumed($projectIdList, $time = '')
{
    return $this->loadExtension('effort')->getProjectsConsumed($projectIdList, $time);
}
