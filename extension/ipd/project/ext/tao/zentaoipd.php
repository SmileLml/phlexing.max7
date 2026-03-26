<?php
/**
 * @param string $projectModel
 */
protected function setMenuByModel($projectModel)
{
    return $this->loadExtension('zentaoipd')->setMenuByModel($projectModel);
}
