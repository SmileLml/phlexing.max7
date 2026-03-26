<?php
/**
 * @param string $module
 * @param mixed[] $data
 */
public function setStoryGrade($module, $data)
{
    return $this->loadExtension('zentaoipd')->setStoryGrade($module, $data);
}
