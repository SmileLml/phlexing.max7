<?php
/**
 * 恢复一条记录。
 * Undelete a record.
 *
 * @param  int    $actionID
 * @access public
 * @return string|bool
 */
public function undelete($actionID)
{
    $action = $this->getById($actionID);
    if($action->action != 'deleted') return false;

    if($action->objectType == 'demand')
    {
        $demand = $this->loadModel('demand')->getByID($action->objectID);
        if(isset($demand->parent)) $this->dao->update(TABLE_DEMAND)->set('parent')->eq(-1)->where('id')->eq($demand->parent)->exec();
    }

    return parent::undelete($actionID);
}
