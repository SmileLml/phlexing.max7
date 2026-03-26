<?php
helper::importControl('convert');
class myConvert extends convert
{
    /**
     * 初始化confluence用户。
     * Init confluence user.
     *
     * @access public
     * @return void
     */
    public function initConfluenceUser()
    {
        if($_POST)
        {
            $errors = array();
            if(!$this->post->password1) $errors['password1'][] = sprintf($this->lang->error->notempty, $this->lang->user->password);
            if(!$this->post->password2) $errors['password2'][] = sprintf($this->lang->error->notempty, $this->lang->user->password2);
            if($this->post->password1 && strlen(trim($this->post->password1)) < 6) $errors['password1'][] = $this->lang->convert->jira->passwordLess;
            if($this->post->password1 && $this->post->password2 && $this->post->password1 != $this->post->password2) $errors['password2'][] = $this->lang->convert->jira->passwordDifferent;
            if($errors) return $this->send(array('result' => 'fail', 'message' => $errors));

            $confluenceUser['password'] = md5($this->post->password1);
            $confluenceUser['group']    = $this->post->group;
            $confluenceUser['mode']     = $this->post->mode;
            $this->session->set('confluenceUser', $confluenceUser);

            return $this->send(array('result' => 'success', 'load' => inlink('importConfluence')));
        }

        $this->view->title  = $this->lang->convert->confluence->importUser;
        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->display();
    }
}
