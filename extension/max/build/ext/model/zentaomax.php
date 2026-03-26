<?php
/**
 * @param mixed[]|int $productIdList
 * @param string|int $branch
 * @param string $params
 * @param int $objectID
 * @param string $objectType
 * @param string $buildIdList
 * @param bool $replace
 * @param int $system
 */
public function getBuildPairs($productIdList, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true, $system = 0)
{
    return $this->loadExtension('zentaomax')->getBuildPairs($productIdList, $branch, $params, $objectID, $objectType, $buildIdList, $replace, $system);
}
