<?php
class admin extends control
{
    public function manageExtMember($extCode, $deptID = 0)
    {
        $getLicMethod  = "get{$extCode}License";
        $extProperties = $this->admin->{$getLicMethod}();

        $users      = $this->loadModel('dept')->getDeptUserPairs($deptID);
        $extAuthors = $this->dao->select('*')->from(TABLE_EXTUSER)->where('code')->eq($extCode)->fetchPairs('account', 'account');

        $authorUsers   = array();
        $noAuthorUsers = array();
        foreach($users as $account => $realname)
        {
            if(isset($extAuthors[$account]))  $authorUsers[$account]   = $realname;
            if(!isset($extAuthors[$account])) $noAuthorUsers[$account] = $realname;
        }

        $deletedUsers = $this->dao->select('account,realname')->from(TABLE_USER)->where('deleted')->eq('1')->andWhere('account')->in($extAuthors)->fetchPairs('account', 'realname');
        if($deletedUsers)
        {
            $deptAllUsers = $this->dept->getDeptUserPairs($deptID, 'account', 'inside', 'queryAll');
            foreach($deletedUsers as $account => $realname)
            {
                if(isset($deptAllUsers[$account]))  $authorUsers[$account] = $deptAllUsers[$account] . " ({$this->lang->deleted})";
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $members = array();
            if(!empty($_POST['members'])) $members = fixer::input('post')->get('members');

            $count = count($members);
            if(!empty($extProperties['user']) && $count > $extProperties['user']) return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->admin->extGrantCountError, $count, $count - $extProperties['user'])));

            $this->dao->delete()->from(TABLE_EXTUSER)->where('code')->eq($extCode)->andWhere('account')->in(array_keys($authorUsers))->exec();

            $member = new stdclass();
            $member->code = $extCode;
            foreach($members as $account)
            {
                $member->account = $account;
                $this->dao->insert(TABLE_EXTUSER)->data($member)->exec();
            }

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->extProperties = $extProperties;
        $this->view->extAuthors    = $extAuthors;
        $this->view->extCode       = $extCode;
        $this->view->authorUsers   = $authorUsers;
        $this->view->noAuthorUsers = $noAuthorUsers;
        $this->view->deptID        = $deptID;
        $this->view->deptTree      = $this->loadModel('dept')->getTreeMenu($rooteDeptID = 0, array($this->admin, 'createManageExtMemberLink'), $extCode);
        $this->view->userCount     = $this->dao->select('count(*) AS count')->from(TABLE_USER)->where('deleted')->eq('0')->fetch('count');
        $this->display();
    }
}
