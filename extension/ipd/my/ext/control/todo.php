<?php
helper::importControl('my');
class mymy extends my
{
    public function todo($type = 'today', $userID = '', $status = 'all', $orderBy = "date_desc,status,begin", $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!empty($_SESSION['user']->feedback) or !empty($_COOKIE['feedbackView']))
        {
        }
        parent::todo($type, $userID, $status, $orderBy, $recTotal, $recPerPage, $pageID);
    }
}
