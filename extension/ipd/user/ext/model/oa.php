<?php
public function getDeptPairs($params = '', $dept = '')
{
    return $this->loadExtension('oa')->getDeptPairs($params, $dept);
}

public function getUser($account)
{
    return $this->loadExtension('oa')->getUser($account);
}

public function getUserManagerPairs()
{
    return $this->loadExtension('oa')->getUserManagerPairs();
}
