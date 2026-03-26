<?php
public function getFields($module, $action, $getRealOptions = true, $datas = array(), $ui = 0, $groupID = null)
{
    return $this->loadExtension('flow')->getFields($module, $action, $getRealOptions, $datas, $ui, $groupID);
}

public function saveNotice($id)
{
    return $this->loadExtension('flow')->saveNotice($id);
}

public function update($id)
{
    return $this->loadExtension('flow')->update($id);
}

public function isClickable($action, $methodName)
{
    return $this->loadExtension('flow')->isClickable($action, $methodName);
}
