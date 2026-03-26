<?php
public function getManager($deptID)
{
    return $this->loadExtension('zentaobiz')->getManager($deptID);
}
