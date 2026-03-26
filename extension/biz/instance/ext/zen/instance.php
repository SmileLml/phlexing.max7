<?php
/**
 * @param int $id
 */
protected function storeView($id)
{
    return $this->loadExtension('instance')->storeView($id);
}

/**
 * @param int $appID
 */
protected function buildCustomConfig($appID)
{
    return $this->loadExtension('instance')->buildCustomConfig($appID);
}

/**
 * @param int $appID
 */
protected function checkCustomFields($appID)
{
    return $this->loadExtension('instance')->checkCustomFields($appID);
}
