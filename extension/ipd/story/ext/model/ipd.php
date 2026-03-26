<?php
/**
 * Build operate menu.
 *
 * @param  object $story
 * @param  string $type
 * @param  object $execution
 * @param  string $storyType story|requirement
 * @access public
 * @return array
 */
public function buildOperateMenu($story, $type = 'view', $execution = null, $storyType = 'story')
{
    $this->lang->story->changeTip = $storyType == 'story' ? $this->lang->story->changeTip : $this->lang->story->URChangeTip;

    if($story->type != 'requirement' and !empty($story->confirmeObject))
    {
        $mothed = $story->confirmeObject['type'] == 'confirmedretract' ? 'confirmDemandRetract' : 'confirmDemandUnlink';
        return $this->buildMenu('story', $mothed, "objectID=$story->id&object=story&extra={$story->confirmeObject['id']}", $story, 'view', 'search', '', 'iframe', true);
    }

    return parent::buildOperateMenu($story, $type, $execution, $storyType);
}

/**
 * Get affect objects.
 *
 * @param  mixed  $objects
 * @param  string $objectType
 * @param  string $object
 * @access public
 * @return void
 */
public function getAffectObject($objects = '', $objectType = '', $objectInfo = '')
{
    if(empty($objects) and empty($objectInfo)) return $objects;
    if(empty($objects) and $objectInfo) $objects = array($objectInfo->id => $objectInfo);

    $objectIDList = array();
    $storyIDList  = array();
    foreach($objects as $object)
    {
        $objectID = $object->id;
        if($this->app->rawModule == 'testtask') $objectID = $object->case;
        if(!empty($object->children)) $this->getAffectObject($object->children, $objectType);

        $objectIDList[] = $objectID;
        $storyIDList[]  = $objectType == 'story' ? $object->id : $object->story;
        $object->confirmeObject = array();
        $object->URs            = '';
    }

    /* 根据需求ID查找父需求。*/
    $parentStories = $this->dao->select('id,parent')->from(TABLE_STORY)
        ->where('deleted')->eq(0)
        ->andWhere('id')->in($storyIDList)
        ->andWhere('parent')->gt(0)
        ->fetchPairs('id', 'parent');

    /* 获取需要确认的用户需求id。 */
    $URs   = $this->getStoryRelationByIds($storyIDList, 'story');
    $URIds = implode(',', $URs);

    if($parentStories)
    {
        $parentURs = $this->getStoryRelationByIds($parentStories, 'story');
        $URIds .= ',' . implode(',', $parentURs);

        foreach($parentStories as $storyID => $parentStoryID)
        {
            $URs[$storyID]  = isset($URs[$storyID]) ? $URs[$storyID] : '';
            $URs[$storyID] .= isset($parentURs[$parentStoryID]) ? ',' . $parentURs[$parentStoryID] : '';
        }
    }

    if(!$URIds) return $objectInfo ? $objectInfo : $objects;

    /* 查询最近一次撤回/移除操作。 */
    $lastActions = $this->dao->select('*')->from(TABLE_ACTION)
        ->where('objectType')->eq('story')
        ->andWhere('objectID')->in($URIds)
        ->andWhere('action')->in('retractclosed,unlinkedfromroadmap')
        ->orderBy('id_asc')
        ->fetchAll('objectID');

    /* 获取已经确认过的对象。*/
    $confirmedActions = $this->dao->select('*')->from(TABLE_ACTION)
        ->where('objectType')->eq($objectType)
        ->andWhere('objectID')->in($objectIDList)
        ->andWhere('action')->in('confirmedretract,confirmedunlink')
        ->orderBy('id_asc')
        ->fetchAll('objectID');

    /* 将确认信息插入到objects中并且过滤掉已经确认的用户需求。*/
    foreach($objects as $objectID => $object)
    {
        $objectID = $object->id;
        if($this->app->rawModule == 'testtask') $objectID = $object->case;

        $storyID  = $objectType == 'story' ? $object->id : $object->story;
        $object->URs = isset($URs[$storyID]) ? $URs[$storyID] : '';
        if(!$object->URs) continue;

        $objectURs = explode(',', $object->URs);
        $URAction  = $objectAction = array();

        foreach($objectURs as $URID)
        {
            if(!isset($lastActions[$URID])) continue;
            if($object->openedDate > $lastActions[$URID]->date) continue;

            if(empty($URAction)) $URAction = $lastActions[$URID];
            if($URAction->date < $lastActions[$URID]->date) $URAction = $lastActions[$URID];

            if(!isset($confirmedActions[$objectID])) continue;

            $objectAction = $confirmedActions[$objectID];
            if($objectAction and $objectAction->date > $URAction->date) $URAction = array();
        }

        if($URAction)
        {
            $actionType = $URAction->action == 'retractclosed' ? 'confirmedretract' : 'confirmedunlink';
            $object->confirmeObjectID    = $URAction->objectID;
            $object->confirmeActionType  = $actionType;
        }
    }

    if($objectInfo) return $objects[$objectInfo->id];
    return $objects;
}

/**
 * Get story relation by Ids.
 *
 * @param  array  $storyIdList
 * @param  string $storyType
 * @access public
 * @return array
 */
public function getStoryRelationByIds($storyIdList, $storyType = 'story')
{
    $conditionField = $storyType == 'story' ? 'BID' : 'AID';
    $anotherField   = $storyType == 'story' ? 'AID' : 'BID';

    $relations = $this->dao->select('BID,AID')->from(TABLE_RELATION)
        ->where('AType')->eq('requirement')
        ->andWhere('BType')->eq('story')
        ->andWhere('relation')->eq('subdivideinto')
        ->andWhere($conditionField)->in($storyIdList)
        ->fetchGroup($conditionField, $anotherField);

    foreach($relations as $storyId => $relation)
    {
        if($relation) $relations[$storyId] = implode(',', array_keys($relation));
    }

    return $relations;
}
