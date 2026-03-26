<?php
if($this->config->edition != 'open' and !empty($_POST))
{
    $userLimit = $this->getBizUserLimit('user');
    if($userLimit)
    {
        $userCount = $this->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch('count');
        $maxLimit  = $userCount >= $userLimit;
    }

    foreach($this->post->add as $i => $add)
    {
        if(empty($add)) continue;

        if(isset($_POST['visions'][$i]))
        {
            if(!$userLimit) continue;
            if(!$maxLimit)
            {
                $userCount ++;
                if($userCount >= $userLimit) $maxLimit = true;
            }
            else
            {
                $_POST['add'][$i] = '';
            }
        }
    }
}
