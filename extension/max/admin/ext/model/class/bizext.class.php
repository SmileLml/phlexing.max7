<?php
class bizextAdmin extends adminModel
{
    public function getUserCount()
    {
        return $this->dao->select('count(1) as userCount')->from(TABLE_USER)->where('deleted')->eq('0')->fetch('userCount');
    }

    public function getIoncubeProperties()
    {
        if(!function_exists('ioncube_license_properties')) return array();

        $properties = ioncube_license_properties();
        if(empty($properties)) return array();

        foreach($properties as $key => $property) $properties[$key] = $property['value'];
        return $properties;
    }
}
