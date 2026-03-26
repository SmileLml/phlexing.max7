<?php
class effort extends control
{
    public function ajaxGetEfforts($userID = '', $year = '')
    {
        $account = '';
        if($userID)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;
        }

        die($this->effort->getEfforts4Calendar($account, $year));
    }
}
