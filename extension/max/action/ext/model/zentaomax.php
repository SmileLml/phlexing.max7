<?php
/**
 * 将交付物配置项渲染成可读的变更记录。
 * Process deliverable json.
 *
 * @param  string $objectType
 * @param  int    $objectID
 * @param  object $history
 * @access public
 * @return string
 */
public function processDeliverableJson($objectType, $objectID, $history)
{
    return $this->loadExtension('zentaomax')->processDeliverableJson($objectType, $objectID, $history);
}
