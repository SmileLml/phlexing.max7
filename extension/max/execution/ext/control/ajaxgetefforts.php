<?php
class execution extends control
{
    public function ajaxGetEfforts($executionID, $userID = '', $year = '')
    {
        $account = $userID;
        if($userID)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;
        }

        die($this->execution->getEfforts4Calendar($executionID, $account, $year));
    }
}
