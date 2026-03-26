<?php
public function setUserList($users, $account)
{
    return $this->loadExtension('calendar')->setUserList($users, $account);
}
