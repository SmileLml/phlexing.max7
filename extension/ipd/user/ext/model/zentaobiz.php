<?php
public function checkBizUserLimit($type = 'user')
{
    return $this->loadExtension('zentaobiz')->checkBizUserLimit($type);
}

public function getBizUserLimit($type = 'user')
{
    return $this->loadExtension('zentaobiz')->getBizUserLimit($type);
}

/**
 * @param string $browseType
 * @param string $query
 * @param object|null $pager
 * @param string $orderBy
 */
public function getByQuery($browseType = 'inside', $query = '', $pager = null, $orderBy = 'id')
{
    return $this->loadExtension('zentaobiz')->getByQuery($browseType, $query, $pager, $orderBy);
}

public function getLeftUsers()
{
    return $this->loadExtension('zentaobiz')->getLeftUsers();
}

public function getAddUserWarning()
{
    return $this->loadExtension('zentaobiz')->getAddUserWarning();
}
