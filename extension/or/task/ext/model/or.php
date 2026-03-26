<?php
/**
 * Get research tasks.
 *
 * @param  int         $researchID
 * @param  string      $type
 * @param  string      $orderBy
 * @param  object|null $pager
 * @access public
 * @return array
 */
public function getResearchTasks($researchID = 0, $type = 'all', $orderBy = 'id_desc', $pager = null)
{
    if(is_string($type)) $type = strtolower($type);
    $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
    $fields  = "DISTINCT t1.*, t2.realname AS assignedToRealName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder";

    $actionIDList = array();
    if($type == 'assignedbyme') $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)->where('objectType')->eq('task')->andWhere('action')->eq('assigned')->andWhere('actor')->eq($this->app->user->account)->fetchPairs('objectID', 'objectID');

    $tasks  = $this->dao->select($fields)
        ->from(TABLE_TASK)->alias('t1')
        ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
        ->leftJoin(TABLE_TASKTEAM)->alias('t3')->on('t3.task = t1.id')
        ->where('t1.project')->eq((int)$researchID)
        ->beginIF($type == 'myinvolved')
        ->andWhere("((t3.`account` = '{$this->app->user->account}') OR t1.`assignedTo` = '{$this->app->user->account}' OR t1.`finishedby` = '{$this->app->user->account}')")
        ->fi()
        ->beginIF($type == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
        ->beginIF($type == 'assignedtome')->andWhere("(t1.assignedTo = '{$this->app->user->account}' or (t1.mode = 'multi' and t3.`account` = '{$this->app->user->account}' and t1.status != 'closed' and t3.status != 'done') )")->fi()
        ->beginIF($type == 'finishedbyme')
        ->andWhere('t1.finishedby', 1)->eq($this->app->user->account)
        ->orWhere('t3.status')->eq("done")
        ->markRight(1)
        ->fi()
        ->beginIF($type == 'delayed')->andWhere('t1.deadline')->gt('1970-1-1')->andWhere('t1.deadline')->lt(date(DT_DATE1))->andWhere('t1.status')->in('wait,doing')->fi()
        ->beginIF(is_array($type) or strpos(',all,undone,needconfirm,assignedtome,delayed,finishedbyme,myinvolved,assignedbyme,review,', ",$type,") === false)->andWhere('t1.status')->in($type)->fi()
        ->beginIF($type == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
        ->beginIF($type == 'review')
        ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
        ->andWhere('t1.reviewStatus')->eq('doing')
        ->fi()
        ->andWhere('t1.deleted')->eq(0)
        ->orderBy($orderBy)
        ->page($pager, 't1.id')
        ->fetchAll('id');

    $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', (in_array($type, array('myinvolved', 'needconfirm', 'assignedtome'))) ? false : true);

    $this->session->set('researchTaskTotal', count($tasks));
    if(empty($tasks)) return array();

    return $this->processTasks($tasks);
}

/**
 * Get tasks of a story.
 *
 * @param  int    $storyID
 * @access public
 * @return array
 */
public function getStoryTasks($storyID)
{
    return $this->loadExtension('or')->getStoryTasks($storyID);
}
