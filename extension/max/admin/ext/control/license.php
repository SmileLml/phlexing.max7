<?php
class admin extends control
{
    public function license()
    {
        $ioncubeProperties = $this->admin->getIoncubeProperties();

        $extProperties = array();
        foreach(array_keys($this->lang->admin->extensionList) as $extCode)
        {
            $getLicMethod = "get{$extCode}License";
            if(method_exists($this->admin, $getLicMethod)) $extProperties[$extCode] = $this->admin->{$getLicMethod}();
        }

        $this->view->title             = $this->lang->admin->license;
        $this->view->ioncubeProperties = $ioncubeProperties;
        $this->view->extProperties     = $extProperties;
        $this->view->userCount         = $this->dao->select('count(*) AS count')->from(TABLE_USER)->where('deleted')->eq('0')->fetch('count');
        $this->display();
    }
}
