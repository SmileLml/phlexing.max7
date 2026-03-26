<?php
class zentaobizUserZen extends userZen
{
    /**
     * 批量创建用户时检查授权用户数。
     * Check user limit for batch create users.
     *
     * @access public
     * @return void
     */
    public function checkUserLimitForBatch()
    {
        $properties = array();
        if(function_exists('ioncube_license_properties')) $properties = ioncube_license_properties();
        if($this->config->edition != 'open' && isset($properties['user']))
        {
            $userCount = $this->dao->select("COUNT('*') AS count")->from(TABLE_USER)->where('deleted')->eq('0')->fetch('count');
            $maxUser   = $properties['user']['value'] <= $userCount;

            if($maxUser) return $this->send(array('result' => 'fail', 'message' => $this->lang->user->noticeUserLimit, 'load' => $this->createLink('company', 'browse')));

            if($_POST)
            {
                foreach($this->post->account as $i => $account)
                {
                    if(empty($account)) continue;
                    if(join(',', $_POST['visions'][$i]) == 'ditto') $_POST['visions'][$i] = $_POST['visions'][($i - 1)];

                    if(!$maxUser)
                    {
                        $userCount ++;
                        if($properties['user']['value'] <= $userCount) $maxUser = true;
                    }
                    else
                    {
                        $_POST['account'][$i] = '';
                    }
                }
            }
        }

        $this->view->userAddWarning = $this->user->getAddUserWarning();
        $this->view->properties     = $properties;
    }
}
