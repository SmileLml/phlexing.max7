<?php
helper::importControl('user');
class myuser extends user
{
    /**
     * @param int $deptID
     */
    public function create($deptID = 0, $type = 'inside')
    {
        if($this->config->edition != 'open' and !defined('TUTORIAL'))
        {
            if(isset($_POST['visions'])) $type = join(',', $_POST['visions']) == 'lite' ? 'lite' : 'user';
            $maxUser = $this->user->checkBizUserLimit();
            if($maxUser)
            {
                if(!empty($_POST)) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->noticeUserLimit));

                return $this->send(array('result' => 'fail', 'message' => $this->lang->user->noticeUserLimit, 'load' => $this->createLink('company', 'browse')));
            }

            $this->view->userAddWarning = $this->user->getAddUserWarning();

            if($_POST)
            {
                if(!isset($_POST['visions'])) $this->post->visions = array();
                if($maxUser) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->noticeUserLimit));
            }
        }
        return parent::create($deptID, $type);
    }
}
