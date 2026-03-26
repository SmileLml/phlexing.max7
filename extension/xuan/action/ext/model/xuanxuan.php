<?php
/**
 * Get user actions of password change and login since last two xxd poll.
 *
 * @param  string $action
 * @access public
 * @return array
 */
public function getListSinceLastPoll($action = '')
{
    $lastPoll = date(DT_DATETIME1, strtotime($this->config->xxd->lastPoll . ' - ' . 2 * $this->config->xuanxuan->pollingInterval . ' second'));
    $actions  = array();
    if($action === 'changepassword')
    {
        $actions = $this->dao->select('t1.id, t1.objectID, t1.date')->from(TABLE_ACTION)->alias('t1')
            ->leftJoin(TABLE_HISTORY)->alias('t2')->on('t1.id = t2.action')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.objectID = t3.id')
            ->where('t3.clientStatus')->ne('offline')
            ->andWhere('t1.objectType')->eq('user')
            ->andWhere('t2.field')->eq('password')
            ->andWhere('t1.action')->eq('edited')
            ->andWhere('t1.date')->gt($lastPoll)
            ->orderBy('t1.`date`_desc')
            ->fetchAll();
    }
    elseif($action === 'loginxuanxuan')
    {
        $actions = $this->dao->select('id, objectID, date')->from(TABLE_ACTION)
            ->where('date')->gt($lastPoll)
            ->andWhere('action')->eq('loginxuanxuan')
            ->orderBy('`date`_desc')
            ->fetchAll();
    }

    $uniqueActions = array();
    foreach($actions as $action)
    {
        $exist = false;
        foreach($uniqueActions as $uniqueAction)
        {
            if($uniqueAction->objectID === $action->objectID)
            {
                $exist = true;
                break;
            }
        }
        if(!$exist) $uniqueActions[] = $action;
    }

    return $uniqueActions;
}

/**
 * Check log level for action.
 *
 * @param  string    $module
 * @param  object    $action
 * @access public
 * @return bool
 */
public function checkLogLevel($module, $action)
{
    $logLevel    = (int)zget($this->config->xuanxuan, 'logLevel', 1);
    $actionLevel = zget($this->config->action->logLevel->{$module}, $action, 0);

    if($logLevel >= $actionLevel) return true;
    return false;
}
