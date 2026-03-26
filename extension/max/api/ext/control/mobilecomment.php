<?php
class api extends control
{
    /**
     * Comment.
     * 
     * @param  int    $id
     * @param  string $type  task / bug / todo / story / project / product
     * @param  string $content
     * @param  string $user
     * @access public
     * @return void
     */
    public function mobileComment($id, $type, $content = '', $user = '')
    {
        if($this->get->lang and $this->get->lang != $this->app->getClientLang())
        {
            $this->app->setClientLang($this->get->lang);
            $this->app->loadLang('api');
        }

        if(!$this->loadModel('user')->isLogon()) die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failLogin)));

        if(empty($content) and isset($_POST['comment'])) $content = $this->post->comment;
        $content = strip_tags($content, $this->config->allowedTags !== null && is_array($this->config->allowedTags) ? '<' . implode('><', $this->config->allowedTags) . '>' : $this->config->allowedTags);
        if(empty($content) or !strip_tags($content, '<img>'))  die(json_encode(array('status' => 'failed', 'reason' => $this->lang->api->failComment)));

        $user     = empty($user) ? $this->app->user->account : $user;
        $actionID = $this->loadModel('action')->create($type, $id, 'commented', $content, '', $user);

        die($this->api->compression(array('id' => (int) $actionID, 'type' => $type, 'objId' => (int) $id)));
    }
}
