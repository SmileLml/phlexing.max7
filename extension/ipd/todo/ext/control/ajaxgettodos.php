<?php
class todo extends control
{
    public function ajaxGetTodos($userID = '', $year = '')
    {
        $account = '';
        if($userID)
        {
            $user    = $this->loadModel('user')->getById($userID, 'id');
            $account = $user->account;
        }

        die($this->todo->getTodos4Calendar($account, $year));
    }
}
