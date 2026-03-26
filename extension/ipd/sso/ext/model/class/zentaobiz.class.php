<?php
class zentaobizSso extends ssoModel
{
    /**
     * @return object|false
     */
    public function bind()
    {
        if($this->post->bindType == 'add' and $this->loadModel('user')->checkBizUserLimit('user'))
        {
            dao::$errors['password1'][] = $this->lang->user->noticeUserLimit;
            return false;
        }
        return parent::bind();
    }

    /**
     * @param object $data
     */
    public function createUser($data)
    {
        if($this->loadModel('user')->checkBizUserLimit('user')) return array('status' => 'fail', 'data' => $this->lang->user->noticeUserLimit);
        return parent::createUser($data);
    }
}
