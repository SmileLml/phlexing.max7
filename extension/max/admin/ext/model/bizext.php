<?php
public function getUserCount()
{
    return $this->loadExtension('bizext')->getUserCount();
}

public function getIoncubeProperties()
{
    return $this->loadExtension('bizext')->getIoncubeProperties();
}

public function createManageExtMemberLink($dept, $extCode)
{
    return helper::createLink('admin', 'manageExtMember', "extCode=$extCode&deptID={$dept->id}");
}
