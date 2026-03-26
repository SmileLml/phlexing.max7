<?php
/**
 * @param string $objectType
 * @param int $objectID
 * @param string $actionType
 * @param int $actionID
 * @param string $actor
 * @param string $extra
 */
public function send($objectType, $objectID, $actionType, $actionID, $actor = '', $extra = '')
{
    $this->loadExtension('sms')->send($objectType, $objectID, $actionType, $actionID, $actor, $extra);
}
