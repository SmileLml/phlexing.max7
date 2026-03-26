<?php
public function getHasFeedbackPriv($pager = null, $vision = 'lite')
{
    return $this->loadExtension('feedback')->getHasFeedbackPriv($pager, $vision);
}

/**
 * @param string|mixed[] $usersToAppended
 * @param string|mixed[] $accounts
 * @param string $params
 * @param int $maxCount
 */
public function getPairs($params = '', $usersToAppended = '', $maxCount = 0, $accounts = '')
{
    return $this->loadExtension('feedback')->getPairs($params, $usersToAppended, $maxCount, $accounts);
}

public function getProductViewUsers($productID)
{
    return $this->loadExtension('feedback')->getProductViewUsers($productID);
}
