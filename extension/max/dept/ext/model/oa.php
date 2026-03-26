<?php
public function getDeptManagedByMe($account)
{
    return $this->loadExtension('oa')->getDeptManagedByMe($account);
}
