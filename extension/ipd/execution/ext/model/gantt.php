<?php
/**
 * The control file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     execution
 * @version     $Id$
 * @link        http://www.zentao.net
 * @param mixed[] $relations
 */
public function mergeRelations($relations = array())
{
    return $this->loadExtension('gantt')->mergeRelations($relations);
}

/**
 * @param mixed[] $taskList
 * @param int $projectID
 * @param int $executionID
 * @param mixed[] $appendTasks
 */
public function getRelationTasks($taskList, $projectID, $executionID, $appendTasks = array())
{
    return $this->loadExtension('gantt')->getRelationTasks($taskList, $projectID, $executionID, $appendTasks);
}

/**
 * @param mixed[] $taskRelations
 * @param int $taskID
 * @param string $taskType
 */
public function getDisabledTasks($taskRelations, $taskID, $taskType)
{
    return $this->loadExtension('gantt')->getDisabledTasks($taskRelations, $taskID, $taskType);
}

/**
 * @param mixed[] $relations
 * @param int $projectID
 * @param mixed[] $tasks
 */
public function checkRelation($relations = array(), $projectID = 0, $tasks = array())
{
    return $this->loadExtension('gantt')->checkRelation($relations, $projectID, $tasks);
}

/**
 * @param int $projectID
 * @param int $executionID
 */
public function createRelationOfTasks($projectID, $executionID)
{
    return $this->loadExtension('gantt')->createRelationOfTasks($projectID, $executionID);
}

/**
 * @param int $relationID
 * @param int $projectID
 */
public function updateRelationOfTask($relationID, $projectID)
{
    return $this->loadExtension('gantt')->updateRelationOfTask($relationID, $projectID);
}

/**
 * @param int $projectID
 */
public function editRelationOfTasks($projectID)
{
    return $this->loadExtension('gantt')->editRelationOfTasks($projectID);
}

/**
 * @param int $projectID
 * @param int $executionID
 * @param object|null $pager
 */
public function getRelationsOfTasks($projectID, $executionID, $pager = null)
{
    return $this->loadExtension('gantt')->getRelationsOfTasks($projectID, $executionID, $pager);
}

/**
 * @param int $executionID
 * @param string $type
 * @param string $orderBy
 */
public function getDataForGantt($executionID, $type, $orderBy)
{
    return $this->loadExtension('gantt')->getDataForGantt($executionID, $type, $orderBy);
}

/**
 * @param int $relationID
 */
public function deleteRelation($relationID)
{
    return $this->loadExtension('gantt')->deleteRelation($relationID);
}

/**
 * @param string $orderBy
 */
public function parseOrderBy($orderBy)
{
    return $this->loadExtension('gantt')->parseOrderBy($orderBy);
}

/**
 * @param string $field
 * @param string $currentOrder
 * @param string $currentDirect
 */
public function buildKanbanOrderBy($field, $currentOrder, $currentDirect)
{
    return $this->loadExtension('gantt')->buildKanbanOrderBy($field, $currentOrder, $currentDirect);
}

/**
 * @param mixed[] $relations
 * @param int $projectID
 */
public function checkTaskRelation($relations, $projectID)
{
    return $this->loadExtension('gantt')->checkTaskRelation($relations, $projectID);
}
