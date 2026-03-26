<?php
class zentaobizUser extends userModel
{
    public function checkBizUserLimit()
    {
        if(!function_exists('ioncube_license_properties')) return false;

        $properties = ioncube_license_properties();
        if(empty($properties['user']['value'])) return false;

        $user = $this->dao->select("COUNT('*') AS count")->from(TABLE_USER)->where('deleted')->eq('0')->fetch();
        return $user->count >= $properties['user']['value'];
    }

    public function getBizUserLimit()
    {
        if(!function_exists('ioncube_license_properties')) return false;

        $properties = ioncube_license_properties();
        if(empty($properties['user']['value'])) return false;

        return $properties['user']['value'];
    }

    /**
     * Get left users.
     *
     * @access public
     * @return bool|int
     */
    public function getLeftUsers()
    {
        if(!function_exists('ioncube_license_properties')) return false;

        $properties = ioncube_license_properties();
        if(empty($properties['user']['value'])) return false;

        $userCount = $this->dao->select("COUNT('*') AS count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch('count');

        return $properties['user']['value'] - $userCount;
    }

    /**
     * Get add user waring.
     *
     * @access public
     * @return bool|string
     */
    public function getAddUserWarning()
    {
        $leftUser = $this->getLeftUsers();
        if($leftUser === false) return false;

        return $leftUser < $this->config->user->userAddLimit ? sprintf($this->lang->user->userAddWarning, $leftUser) : '';
    }
}
