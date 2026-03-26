<?php
/**
 * @param mixed[]|int $objectID
 * @param string $objectType
 */
public function getList($objectType, $objectID)
{
    return $this->loadExtension('feedback')->getList($objectType, $objectID);
}

/**
 * @param string $objectType
 * @param int $objectID
 * @param string $actionType
 * @param string $extra
 */
public function getRelatedFields($objectType, $objectID, $actionType = '', $extra = '')
{
    return $this->loadExtension('feedback')->getRelatedFields($objectType, $objectID, $actionType, $extra);
}

/**
 * @param string|mixed[] $desc
 * @param object $action
 */
public function printAction($action, $desc = '')
{
    return $this->loadExtension('feedback')->printAction($action, $desc);
}

/**
 * @param string|mixed[] $desc
 * @param object $action
 */
public function renderAction($action, $desc = '')
{
    return $this->loadExtension('feedback')->renderAction($action, $desc);
}
