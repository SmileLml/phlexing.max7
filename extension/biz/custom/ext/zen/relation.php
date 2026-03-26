<?php
/**
 * 处理关联关系数据。
 * Process relation data.
 *
 * @access public
 * @return bool|array
 */
public function processRelationData()
{
    $formData = array();
    foreach($_POST['relation'] as $key => $relationName)
    {
        $relativeRelationName = zget($_POST['relativeRelation'], $key, '');
        if($this->app->getMethodName() == 'createrelation' && empty($relationName) && empty($relativeRelationName)) continue;

        if(empty($relationName)) dao::$errors["relation[$key]"] = sprintf($this->lang->error->notempty, $this->lang->custom->relation);
        if(empty($relativeRelationName)) dao::$errors["relativeRelation[$key]"] = sprintf($this->lang->error->notempty, $this->lang->custom->relativeRelation);
        if(dao::isError()) return false;

        $relation = new stdClass();
        $relation->relation         = $relationName;
        $relation->relativeRelation = $relativeRelationName;
        $formData[$key] = $relation;
    }

    return $formData;
}

/**
 * 构建搜索表单。
 * Build search form.
 *
 * @param  int       $objectID
 * @param  string    $objectType
 * @param  string    $relatedObjectType
 * @access protected
 * @return void
 */
protected function buildSearchForm($objectID, $objectType, $relatedObjectType)
{
    $actionURL = $this->createLink('custom', 'relateObject', "objectID=$objectID&objectType=$objectType&relatedObjectType=$relatedObjectType&browseType=bySearch");
    if(in_array($relatedObjectType, array('epic', 'requirement', 'story'))) $this->loadModel('product')->buildSearchForm(0, array(), 0, $actionURL, $relatedObjectType);
    if($relatedObjectType == 'task')     $this->loadModel('execution')->buildTaskSearchForm(0, array(), 0, $actionURL);
    if($relatedObjectType == 'bug')      $this->loadModel('bug')->buildSearchForm(0, array(), 0, $actionURL);
    if($relatedObjectType == 'testcase') $this->loadModel('testcase')->buildSearchForm(0, array(), 0, $actionURL);
    if($relatedObjectType == 'issue')    $this->loadModel('issue')->buildSearchForm($actionURL, 0);
    if($relatedObjectType == 'risk')     $this->loadModel('risk')->buildSearchForm(0, $actionURL);
    if($relatedObjectType == 'feedback') $this->loadModel('feedback')->buildSearchForm($actionURL);
    if($relatedObjectType == 'ticket')   $this->loadModel('ticket')->buildSearchForm(0, $actionURL, 'all');
    if($relatedObjectType == 'design')
    {
        $this->loadModel('design');
        $this->config->design->search['actionURL'] = $actionURL;
        $this->config->design->search['queryID']   = 0;
        $this->loadModel('search')->setSearchParams($this->config->design->search);
    }
    if($relatedObjectType == 'doc')
    {
        $this->loadModel('doc');
        $products   = $this->loadModel('product')->getPairs();
        $projects   = $this->loadModel('project')->getPairs();
        $executions = $this->loadModel('execution')->getPairs();
        $this->config->doc->search['module']    = 'doc';
        $this->config->doc->search['actionURL'] = $actionURL;
        $this->config->doc->search['queryID']   = 0;
        $this->config->doc->search['params']['product']['values']   = $products;
        $this->config->doc->search['params']['execution']['values'] = $executions;
        $this->config->doc->search['params']['lib']['values']       = $this->doc->getLibPairs('all', 'withObject', 0, '', $products, $projects, $executions);
        $this->loadModel('search')->setSearchParams($this->config->doc->search);
    }
    if($relatedObjectType == 'repocommit')
    {
        $this->loadModel('repo');
        $this->config->repo->repocommitSearch = array();
        $this->config->repo->repocommitSearch['module']    = 'repocommit';
        $this->config->repo->repocommitSearch['actionURL'] = $actionURL;
        $this->config->repo->repocommitSearch['queryID']   = 0;
        $this->config->repo->repocommitSearch['fields']    = array();
        $this->config->repo->repocommitSearch['params']    = array();
        $this->config->repo->repocommitSearch['fields']['revision']  = $this->lang->repo->revisionA;
        $this->config->repo->repocommitSearch['fields']['time']      = $this->lang->repo->time;
        $this->config->repo->repocommitSearch['fields']['committer'] = $this->lang->repo->committer;
        $this->config->repo->repocommitSearch['params']['revision']  = array('operator' => 'include', 'control' => 'input', 'values' => '');
        $this->config->repo->repocommitSearch['params']['time']      = array('operator' => '=',       'control' => 'date',  'values' => '');
        $this->config->repo->repocommitSearch['params']['committer'] = array('operator' => 'include', 'control' => 'input', 'values' => '');
        $this->loadModel('search')->setSearchParams($this->config->repo->repocommitSearch);
    }
    if($relatedObjectType == 'demand')
    {
        $this->loadModel('demand');
        $this->config->demand->search['module']    = 'demand';
        $this->config->demand->search['actionURL'] = $actionURL;
        $this->config->demand->search['queryID']   = 0;
        $this->config->demand->search['params']['product']['values'] = arrayUnion(array('' => ''), $this->loadModel('product')->getPairs());
        $this->config->demand->search['params']['pool']['values']    = arrayUnion(array('' => ''), $this->dao->select('id,name')->from(TABLE_DEMANDPOOL)->where('deleted')->eq(0)->fetchPairs());
        $this->loadModel('search')->setSearchParams($this->config->demand->search);
    }
    if(strpos($relatedObjectType, 'workflow_') !== false)
    {
        $this->app->loadLang('workflowfield');
        $statusList = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('module')->eq(str_replace('workflow_', '', $relatedObjectType))->andWhere('field')->eq('status')->fetch('options');

        $workflowSearch = array();
        $workflowSearch['module']    = $relatedObjectType;
        $workflowSearch['actionURL'] = $actionURL;
        $workflowSearch['queryID']   = 0;
        $workflowSearch['fields']['id']         = 'ID';
        $workflowSearch['fields']['createdBy']  = $this->lang->workflowfield->default->fields['createdBy'];
        $workflowSearch['fields']['assignedTo'] = $this->lang->workflowfield->default->fields['assignedTo'];
        $workflowSearch['fields']['status']     = $this->lang->workflowfield->default->fields['status'];
        $workflowSearch['params']['id']         = array('operator' => '=', 'control' => 'input',  'values' => '');
        $workflowSearch['params']['createdBy']  = array('operator' => '=', 'control' => 'select', 'values' => 'users');
        $workflowSearch['params']['assignedTo'] = array('operator' => '=', 'control' => 'select', 'values' => 'users');
        $workflowSearch['params']['status']     = array('operator' => '=', 'control' => 'select', 'values' => json_decode($statusList));
        $this->loadModel('search')->setSearchParams($workflowSearch);
    }
}

/**
 * 获取图谱数据。
 * Get graph data.
 *
 * @param  array     $relatedObjects
 * @param  int       $objectID
 * @param  string    $objectType
 * @access protected
 * @return array
 */
protected function getGraphData($relatedObjects, $objectID, $objectType)
{
    $module = in_array($objectType, array('epic', 'requirement', 'story')) ? 'story' : ($objectType == 'repocommit' ? 'repo' : $objectType);
    $this->loadModel($module);

    $mainObjectInfo     = $this->custom->getObjectInfoByType(array($objectType => $objectID));
    $mainObjectInfo     = $mainObjectInfo[$objectType][$objectID];
    $relateObjectFields = $this->config->custom->relateObjectFields;

    $relateObjectList = $this->config->custom->relateObjectList;
    $relateObjectList['commit']  = $this->config->custom->relateObjectList['repocommit'];
    if(!isset($relateObjectList[$objectType])) $relateObjectList[$objectType] = $this->lang->$objectType->common;

    $graphData = array();
    $graphData['id'] = 'main';
    $graphData['objectType']     = $objectType;
    $graphData['objectTypeName'] = $relateObjectList[$objectType];
    $graphData['objectID']       = $objectID;
    $graphData['objectTitle']    = zget($mainObjectInfo, 'title', '');
    $graphData['nodeType']       = 'card';
    $graphData['children']       = array();

    if(strpos($objectType, 'workflow_') === false && $objectType != 'doc')
    {
        $graphData['objectStatus'] = zget($mainObjectInfo, 'status', '');
        $graphData['statusName']   = zget($mainObjectInfo, 'status', '') && isset($relateObjectFields[$objectType]) && in_array('status', $relateObjectFields[$objectType == 'commit' ? 'repocommit' : $objectType]) ? zget($this->lang->$module->statusList, $mainObjectInfo->status, '') : '';
        $graphData['objectAssign'] = zget($mainObjectInfo, 'assignedTo', '');
    }

    foreach($relatedObjects as $relationKey => $objectList)
    {
        $explodeName  = explode('_', $relationKey, 2);
        $mainChildren = array();
        $mainChildren['id']       = $relationKey;
        $mainChildren['text']     = $explodeName[1];
        $mainChildren['nodeType'] = 'relation';
        $mainChildren['children'] = array();
        foreach($objectList as $objectType => $objectInfo)
        {
            $module = in_array($objectType, array('epic', 'requirement', 'story')) ? 'story' : ($objectType == 'repocommit' ? 'repo' : $objectType);
            $this->loadModel($module);
            if(!isset($relateObjectList[$objectType])) $relateObjectList[$objectType] = $this->lang->$objectType->common;

            foreach($objectInfo as $objectID => $object)
            {
                $relationChildren = array();
                $relationChildren['id'] = "$relationKey-$objectType-$objectID";
                $relationChildren['objectType']     = $objectType;
                $relationChildren['objectTypeName'] = $relateObjectList[$objectType];
                $relationChildren['objectID']       = $objectID;
                $relationChildren['objectTitle']    = zget($object, 'title', '');
                $relationChildren['objectURL']      = zget($object, 'url', '');
                $relationChildren['nodeType']       = 'card';

                if(strpos($objectType, 'workflow_') === false && !in_array($objectType, array('release', 'build', 'mr', 'doc')))
                {
                    $relationChildren['objectStatus'] = zget($object, 'status', '');
                    $relationChildren['statusName']   = isset($relateObjectFields[$objectType]) && in_array('status', $relateObjectFields[$objectType]) ? zget($this->lang->$module->statusList, $object['status'], '') : '';
                    $relationChildren['objectAssign'] = zget($object, 'assignedTo', '');
                }

                $mainChildren['children'][] = $relationChildren;
            }
        }

        $graphData['children'][] = $mainChildren;
    }
    return $graphData;
}
