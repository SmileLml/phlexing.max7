<?php
if($actionID <= 0) return false;
$action = $this->getById($actionID);
if(!$action || $action->action != 'deleted') return false;
if($action->objectType == 'user')
{
    $user = $this->dao->select('*')->from(TABLE_USER)->where('id')->eq($action->objectID)->fetch();
    if($this->loadModel('user')->checkBizUserLimit()) return $this->app->control->sendError($this->lang->user->noticeUserLimit);
}
