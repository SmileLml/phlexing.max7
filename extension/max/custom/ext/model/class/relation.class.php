<?php
class relationCustom extends customModel
{
    /**
     * 通过ID获取关联关系详情。
     * Get relation by ID.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getRelationByID($id)
    {
        $lang = $this->app->getClientLang();
        $relation = $this->dao->select('`value`')->from(TABLE_LANG)
            ->where('lang')->in("all,$lang")
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('relationList')
            ->andWhere('`key`')->eq($id)
            ->fetch('value');
        return json_decode($relation);
    }

    /**
     * 获取所有的关联关系名称。
     * Get all relation name.
     *
     * @param  excludedID
     * @access public
     * @return array
     */
    public function getAllRelationName($excludedID = 0)
    {
        $nameList = array();
        $nameList['relation']         = array();
        $nameList['relativeRelation'] = array();
        foreach($this->getRelationList() as $id => $relation)
        {
            if($excludedID && $excludedID == $id) continue;
            if(!in_array($relation->relation, $nameList['relation'])) $nameList['relation'][] = $relation->relation;
            if(!in_array($relation->relativeRelation, $nameList['relativeRelation'])) $nameList['relativeRelation'][] = $relation->relativeRelation;
        }
        return $nameList;
    }

    /**
     * 新增关联关系。
     * Create relation.
     *
     * @param  array  formData
     * @access public
     * @return void
     */
    public function createRelation($formData)
    {
        $maxKey = $this->dao->select('MAX(CAST(`key` AS SIGNED)) AS maxKey')->from(TABLE_LANG)
            ->where('section')->eq('relationList')
            ->andWhere('module')->eq('custom')
            ->fetch('maxKey');
        $maxKey = $maxKey ? $maxKey : 1;

        foreach($formData as $relation)
        {
            $maxKey += 1;
            $this->setItem("all.custom.relationList.{$maxKey}.0", json_encode($relation));
        }
    }

    /**
     * 编辑关联关系。
     * Edit relation.
     *
     * @param  int    id
     * @param  array  formData
     * @access public
     * @return void
     */
    public function editRelation($id, $formData)
    {
        $data = current($formData);
        $lang = $this->app->getClientLang();

        $this->dao->update(TABLE_LANG)->set('value')->eq(json_encode($data))
            ->where('`key`')->eq($id)
            ->andWhere('section')->eq('relationList')
            ->andWhere('lang')->in("all,$lang")
            ->andWhere('module')->eq('custom')
            ->exec();
    }

    /**
     * 获取使用了此关联关系的对象个数。
     * Get relation object count.
     *
     * @param  int    key
     * @access public
     * @return int
     */
    public function getRelationObjectCount($key = 0)
    {
        $relationObjectCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_RELATION)->where('relation')->eq($key)->fetch('count');
        return $relationObjectCount;
    }

    /**
     * 获取关联对象列表。
     * Get objects.
     *
     * @param  string $objectType
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $excludedID
     * @access public
     * @return array
     */
    public function getObjects($objectType, $browseType = '', $orderBy = 'id_desc', $pager = null, $excludedID = 0)
    {
        $objectTable = zget($this->config->objectTables, $objectType);

        $hasVisionField = $hasDeletedField = false;
        if($this->config->db->driver == 'mysql')
        {
            $hasVisionField = $this->dao->select('COUNT(1) AS count')->from('INFORMATION_SCHEMA.COLUMNS')
                ->where('TABLE_SCHEMA')->eq($this->config->db->name)
                ->andWhere('TABLE_NAME')->eq(trim($objectTable, '`'))
                ->andWhere('COLUMN_NAME')->eq('vision')
                ->fetch('count');

            $hasDeletedField = $this->dao->select('COUNT(1) AS count')->from('INFORMATION_SCHEMA.COLUMNS')
                ->where('TABLE_SCHEMA')->eq($this->config->db->name)
                ->andWhere('TABLE_NAME')->eq(trim($objectTable, '`'))
                ->andWhere('COLUMN_NAME')->eq('deleted')
                ->fetch('count');
        }

        if($this->config->db->driver == 'dm')
        {
            $hasVisionField = $this->dao->select('COUNT(1) AS count')->from('DBA_TAB_COLUMNS')
                ->where('TABLE_NAME')->eq(trim($objectTable, '`'))
                ->andWhere('COLUMN_NAME')->eq('vision')
                ->fetch('count');

            $hasDeletedField = $this->dao->select('COUNT(1) AS count')->from('DBA_TAB_COLUMNS')
                ->where('TABLE_NAME')->eq(trim($objectTable, '`'))
                ->andWhere('COLUMN_NAME')->eq('deleted')
                ->fetch('count');
        }

        /* Get products, projects, and executions that the user has permission to not delete. */
        $userViewProducts   = $this->app->user->view->products;
        $userViewProjects   = $this->app->user->view->projects;
        $userViewExecutions = $this->app->user->view->sprints;
        $deletedProducts    = $this->dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq(1)->fetchPairs();
        $deletedProjects    = $this->dao->select('id')->from(TABLE_PROJECT)->where('deleted')->eq(1)->fetchPairs();
        foreach($deletedProducts as $deletedProductID) $userViewProducts = str_replace(",{$deletedProductID},", ',', ",{$userViewProducts},");
        foreach($deletedProjects as $deletedProjectID)
        {
            $userViewProjects   = str_replace(",{$deletedProjectID},", ',', ",{$userViewProjects},");
            $userViewExecutions = str_replace(",{$deletedProjectID},", ',', ",{$userViewExecutions},");
        }

        /* Get user demand pools. */
        $userDemandPools = $this->getUserDemandpools();

        $module      = in_array($objectType, array('epic', 'requirement')) ? 'story' : $objectType;
        $objectQuery = $module . 'Query';
        if($browseType == 'bySearch' && $this->session->$objectQuery === false) $this->session->set($objectQuery, ' 1 = 1');
        $query       = $this->session->$objectQuery;

        if(strpos($query, "`product` = 'all'") !== false)
        {
            $query = str_replace("`product` = 'all'", '1=1', $query);
        }
        elseif(strpos($query, "`project` = 'all'") !== false)
        {
            $query = str_replace("`project` = 'all'", '1=1', $query);
        }
        elseif(strpos($query, "`execution` = 'all'") !== false)
        {
            $query = str_replace("`execution` = 'all'", '1=1', $query);
        }

        $caseID   = $objectType == 'testcase' ? ',id AS caseID' : '';
        $query    = $this->dao->select('*, 1 AS relation' . $caseID)->from($objectTable)
            ->where('1=1')
            ->beginIF(!empty($excludedID))->andWhere('id')->ne($excludedID)->fi()
            ->beginIF($hasDeletedField)->andWhere('deleted')->eq(0)->fi()
            ->beginIF(in_array($objectType, array('epic', 'requirement', 'story')))->andWhere('type')->eq($objectType)->fi()
            ->beginIF($objectType == 'doc')->andWhere('type')->eq('text')->andWhere('acl')->eq('open')->andWhere('status')->eq('normal')->andWhere('templateType')->eq('')->fi()
            ->beginIF($objectType == 'task')->andWhere('vision')->eq('rnd')->fi()
            ->beginIF($objectType == 'demand')->andWhere('pool')->in($userDemandPools)->fi()
            ->beginIF($browseType == 'bySearch')->andWhere($query)->fi();

        $userView = '';
        if(in_array($objectType, $this->config->custom->objectOwner['product']) && !empty($userViewProducts)) $userView .= '`product` IN (' . trim($userViewProducts, ',') . ')';
        if(in_array($objectType, $this->config->custom->objectOwner['project']) && !empty($userViewProjects)) $userView .= (empty($userView) ? '' : ' OR ') . '`project` IN (' . trim($userViewProjects, ',') . ')';
        if(in_array($objectType, $this->config->custom->objectOwner['execution']) && !empty($userViewExecutions)) $userView .= (empty($userView) ? '' : ' OR ') . '`execution` IN (' . trim($userViewExecutions, ',') . ')';
        $query = $query->beginIF(!empty($userView))->andWhere("($userView)")->fi();

        if($hasVisionField)
        {
            $query = $query->andWhere("FIND_IN_SET('{$this->config->vision}', vision)", 1);
            foreach(explode(',', trim($this->app->user->visions, ',')) as $userVision)
            {
                if($userVision == $this->config->vision) continue;
                $query = $query->orWhere("FIND_IN_SET('{$userVision}', vision)");
            }
            $query = $query->markRight(1);
        }

        $objects = $query->orderBy($orderBy)->page($pager)->fetchAll('id');

        /* 工作流的名称根据显示值设置来展示。 */
        if(strpos($objectType, 'workflow_') !== false && !empty($objects))
        {
            $module    = str_replace('workflow_', '', $objectType);
            $flow      = $this->loadModel('workflow')->getByModule($module);
            $dataPairs = $this->loadModel('flow')->getDataPairs($flow, array_keys($objects));
            foreach($dataPairs as $dataID => $dataName)
            {
                if($dataID) $objects[$dataID]->flowName = $dataName;
            }
        }
        return $objects;
    }

    /**
     * 获取关联对象列。
     * Get object cols.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getObjectCols($objectType)
    {
        $this->loadModel('product');
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('tree');
        $this->loadModel('repo');

        $cols = array();
        if(isset($this->config->custom->relateObjectFields[$objectType]))
        {
            $module = in_array($objectType, array('epic', 'requirement', 'story')) ? 'story' : ($objectType == 'repocommit' ? 'repo' : $objectType);
            $this->loadModel($module);
            $fieldList = $this->config->$module->dtable->fieldList;
            if($module == 'repo') $fieldList = $this->config->repo->commentDtable->fieldList;
            foreach($this->config->custom->relateObjectFields[$objectType] as $fieldKey)
            {
                $fieldSetting = isset($fieldList[$fieldKey]) ? $fieldList[$fieldKey] : array();

                $fieldSetting['sortType'] = true;
                if(!isset($fieldSetting['title'])) $fieldSetting['title'] = zget($this->lang->$module, $fieldKey, $fieldKey);
                if(isset($fieldSetting['fixed']) && $fieldSetting['fixed']) $fieldSetting['fixed'] = false;
                if((isset($fieldSetting['type']) && in_array($fieldSetting['type'], array('assign'))) || $fieldKey == 'createdBy') $fieldSetting['type'] = 'user';
                if(isset($fieldSetting['hidden'])) unset($fieldSetting['hidden']);

                if($fieldKey == 'id')
                {
                    $fieldSetting['title'] = 'ID';
                    $fieldSetting['name']  = 'id';
                    $fieldSetting['type']  = 'checkID';
                }
                if(in_array($objectType, array('epic', 'requirement', 'story')) && $fieldKey == 'title') $fieldSetting['title'] = $objectType == 'requirement' ? str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title) : ($objectType == 'epic' ? str_replace($this->lang->SRCommon, $this->lang->ERCommon, $this->lang->story->title) : $this->lang->story->name);
                if($fieldKey == 'product')   $fieldSetting['map'] = arrayUnion(array(0 => ''), $this->dao->select('id,name')->from(TABLE_PRODUCT)->fetchPairs('id'));
                if($fieldKey == 'project')   $fieldSetting['map'] = arrayUnion(array(0 => ''), $this->project->getPairs(true));
                if($fieldKey == 'execution') $fieldSetting['map'] = arrayUnion(array(0 => ''), $this->execution->getPairs(0, 'all', 'all', true));
                if($fieldKey == 'pool')      $fieldSetting['map'] = arrayUnion(array(0 => ''), $this->dao->select('id,name')->from(TABLE_DEMANDPOOL)->fetchPairs());
                if($fieldKey == 'module')    $fieldSetting['map'] = $this->tree->getAllModulePairs($objectType, $objectType);
                if($fieldKey == 'repo')      $fieldSetting['map'] = $this->repo->getRepoPairs('');
                if($fieldKey == 'relation')  $fieldSetting = array('name' => 'relation', 'title' => $this->lang->custom->relation, 'type' => 'control', 'control' => 'picker', 'sortType' => false, 'width' => '90');
                if(in_array($fieldKey, array('product', 'module', 'project', 'execution')))
                {
                    $fieldSetting['type']  = 'category';
                    $fieldSetting['align'] = 'left';
                }
                if($fieldKey == 'title' || $fieldKey == 'name')
                {
                    $fieldSetting['data-toggle'] = 'modal';
                    $fieldSetting['data-size']   = 'lg';
                    $fieldSetting['flex']        = false;
                    $fieldSetting['fixed']       = false;
                    $fieldSetting['width']       = '200';
                }
                if($fieldKey == 'revision')
                {
                    $fieldSetting['width'] = '0.2';
                    $fieldSetting['flex']  = false;
                    $fieldSetting['link']  = helper::createLink('repo', 'revision', "repo={repo}&objectID=0&revision={revision}");
                };

                $cols[$fieldKey] = $fieldSetting;
            }
        }

        if(strpos($objectType, 'workflow_') !== false)
        {
            $this->loadModel('flow');
            $this->loadModel('workflow');
            $this->app->loadLang('workflowfield');
            $flowModule  = str_replace('workflow_', '', $objectType);
            $flow        = $this->workflow->getByModule($flowModule);
            $flowNameMap = $this->flow->getDataPairs($flow);
            $cols['id']         = array('name' => 'id', 'title' => 'ID', 'type' => 'checkID');
            $cols['relation']   = array('name' => 'relation', 'title' => $this->lang->custom->relation, 'type' => 'control', 'control' => 'picker', 'sortType' => false);
            $cols['flowName']   = array('name' => 'flowName', 'title' => $this->lang->workflowfield->name, 'type' => 'title', 'width' => '0.2', 'link' => array('module' => $flowModule, 'method' => 'view', 'params' => "id={id}"), 'fixed' => false, 'flex' => false, 'map' => $flowNameMap);
            $cols['createdBy']  = array('name' => 'createdBy', 'title' => $this->lang->workflowfield->default->fields['createdBy'], 'type' => 'user');
            $cols['assignedTo'] = array('name' => 'assignedTo', 'title' => $this->lang->workflowfield->default->fields['assignedTo'], 'type' => 'user');
            $cols['status']     = array('name' => 'status', 'title' => $this->lang->workflowfield->default->fields['status'], 'sortType' => true);

            $statusList = $this->dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('module')->eq($flowModule)->andWhere('field')->eq('status')->fetch('options');
            $cols['status']['map'] = json_decode($statusList);
        }

        return $cols;
    }

    /**
     * 获取关联关系列表。
     * Get relation list.
     *
     * @param  bool   $getParis
     * @param  bool   $addDefault
     * @access public
     * @return array
     */
    public function getRelationList($getParis = false, $addDefault = false)
    {
        $this->app->loadLang('custom');
        $lang = $this->app->getClientLang();

        $relations = $this->dao->select('`key`, `value`, `system`')->from(TABLE_LANG)
            ->where('lang')->in($lang . ',all')
            ->andWhere('module')->eq('custom')
            ->andWhere('section')->eq('relationList')
            ->fetchAll();

        $relationList = array();
        foreach($relations as $relation)
        {
            $value = json_decode($relation->value);
            $relationList[$relation->key] = new stdclass();
            $relationList[$relation->key]->key              = $relation->key;
            $relationList[$relation->key]->relation         = $value->relation;
            $relationList[$relation->key]->relativeRelation = $value->relativeRelation;
            $relationList[$relation->key]->system           = $relation->system;
        }
        ksort($relationList);

        if($addDefault)
        {
            foreach($this->config->custom->relationPairs as $relationKey => $relativeRelationKey)
            {
                $relationList[$relationKey] = new stdclass();
                $relationList[$relationKey]->key              = $relationKey;
                $relationList[$relationKey]->relation         = $this->lang->custom->relationList[$relationKey];
                $relationList[$relationKey]->relativeRelation = $this->lang->custom->relationList[$relativeRelationKey];
                $relationList[$relationKey]->system           = 1;
            }
        }

        if($getParis)
        {
            $relationPairs = array();
            foreach($relationList as $relation) $relationPairs[$relation->key] = $relation->relation;
            return $relationPairs;
        }

        return $relationList;
    }

    /**
     * 关联对象。
     * Relate object.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  array  $objectRelation
     * @param  string $relatedObjectType
     * @access public
     * @return void
     */
    public function relateObject($objectID, $objectType, $objectRelation, $relatedObjectType)
    {
        $relatedObjectList = $this->dao->select('BID,relation')->from(TABLE_RELATION)
            ->where('AID')->eq($objectID)
            ->andWhere('AType')->eq($objectType)
            ->andWhere('BType')->eq($relatedObjectType)
            ->fetchGroup('BID', 'relation');

        foreach($objectRelation as $relatedID => $relationID)
        {
            if(isset($relatedObjectList[$relatedID][$relationID])) continue;

            $relation = new stdclass();
            $relation->AID      = $objectID;
            $relation->AType    = $objectType;
            $relation->relation = $relationID;
            $relation->BID      = $relatedID;
            $relation->BType    = $relatedObjectType;
            $this->dao->insert(TABLE_RELATION)->data($relation)->exec();
        }
    }

    /**
     * 移除关联对象。
     * Remove objects.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $relationName
     * @param  int    $relatedObjectID
     * @param  string $relatedObjectType
     * @access public
     * @return bool
     */
    public function removeObjects($objectID, $objectType, $relationName, $relatedObjectID, $relatedObjectType)
    {
        $relationIdList = array();
        $relationList   = $this->getRelationList();
        foreach($relationList as $relationID => $relation)
        {
            if($relation->relation == $relationName || $relation->relativeRelation == $relationName) $relationIdList[$relationID] = $relationID;
        }

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('relation')->in($relationIdList)
            ->andWhere('AID')->in("$objectID,$relatedObjectID")
            ->andWhere('BID')->in("$objectID,$relatedObjectID")
            ->andWhere('AType')->in("$objectType,$relatedObjectType")
            ->andWhere('BType')->in("$objectType,$relatedObjectType")
            ->exec();
        return !dao::isError();
    }

    /**
     * 通过对象类型和ID获取对象信息。
     * Get object info by type.
     *
     * @param  array  $objectList
     * @access public
     * @return array
     */
    public function getObjectInfoByType($objectList)
    {
        /* Get user demand pools. */
        if($this->config->edition == 'ipd') $userDemandPools = $this->getUserDemandpools();

        $objectInfo = array();
        foreach($objectList as $type => $id)
        {
            if(!isset($objectInfo[$type])) $objectInfo[$type] = array();

            if($type == 'repocommit' || $type == 'commit')
            {
                $infoList = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->in($id)->fetchAll('id');
                if(empty($infoList)) continue;
                foreach($infoList as $objectID => $object)
                {
                    $object->title    = substr($object->revision, 0, 10);
                    $object->titleURL = common::hasPriv('repo', 'revision') ? helper::createLink('repo', 'revision', "repo={$object->repo}&objectID=0&revision={$object->revision}") : null;
                    $objectInfo[$type][$objectID] = $object;
                }
            }
            elseif(strpos($type, 'workflow_') !== false)
            {
                $table = zget($this->config->objectTables, str_replace('workflow_', '', $type), '');
                $table = empty($table) ? zget($this->config->objectTables, $type, '') : $table;
                if(empty($table)) continue;

                $infoList = $this->dao->select('*')->from($table)->where('id')->in($id)->fetchAll('id');
                if(empty($infoList)) continue;

                $hasPriv    = false;
                $flowModule = str_replace('workflow_', '', $type);
                if(common::hasPriv($flowModule, 'view'))
                {
                    $workflowVision = $this->dao->select('vision')->from(TABLE_WORKFLOW)->where('module')->eq($flowModule)->fetch('vision');
                    $hasPriv        = $workflowVision == $this->config->vision;
                }

                $this->loadModel('flow');
                $this->loadModel('workflow');
                $flow      = $this->workflow->getByModule($flowModule);
                $namePairs = $this->flow->getDataPairs($flow);
                foreach($infoList as $objectID => $object)
                {
                    $object->title    = isset($namePairs[$objectID]) ? $namePairs[$objectID] : $this->lang->workflow->common . $objectID;
                    $object->titleURL = $hasPriv ? helper::createLink($flowModule, 'view', "objectID={$objectID}") : null;
                    $objectInfo[$type][$objectID] = $object;
                }
            }
            else
            {
                $infoList = $this->dao->select('*')->from($this->config->objectTables[$type])->where('id')->in($id)->fetchAll('id');
                if(empty($infoList)) continue;

                foreach($infoList as $objectID => $object)
                {
                    $hasPriv = false;
                    $method  = $type == 'feedback' ? 'adminView' : 'view';
                    if(common::hasPriv($type, $method))
                    {
                        if($this->app->user->admin || $type == 'mr') $hasPriv = true;
                        if(in_array($type, $this->config->custom->objectOwner['product']) && !empty($object->product) && strpos(",{$this->app->user->view->products},", ",{$object->product},") !== false) $hasPriv = true;
                        if(in_array($type, $this->config->custom->objectOwner['project']) && !empty($object->project) && strpos(",{$this->app->user->view->projects},", ",{$object->project},") !== false) $hasPriv = true;
                        if(in_array($type, $this->config->custom->objectOwner['execution']) && !empty($object->execution) && strpos(",{$this->app->user->view->sprints},", ",{$object->execution},") !== false) $hasPriv = true;
                    }
                    if($type == 'demand' && common::hasPriv('demand', 'view') && isset($userDemandPools[$object->pool])) $hasPriv = true;
                    if($type == 'doc' && $object->vision == $this->config->vision) $hasPriv = true;

                    $object->title    = empty($object->title) ? (empty($object->name) ? '' : $object->name) : $object->title;
                    $object->titleURL = $hasPriv ? helper::createLink($type, 'view', "objectID={$objectID}") : null;
                    $objectInfo[$type][$objectID] = $object;
                }
            }
        }

        return $objectInfo;
    }

    /**
     * 获取已关联的对象列表。
     * Get related object list.
     *
     * @param  int|array $objectID
     * @param  string    $objectType
     * @param  string    $browseType byRelation|byObject
     * @param  bool      $getCount
     * @access public
     * @return array
     */
    public function getRelatedObjectList($objectID, $objectType, $browseType = 'byRelation', $getCount = false)
    {
        if(empty($objectID)) return array();

        $this->setConfig4Workflow();
        $tab = $this->app->tab;

        $isNumeric = is_numeric($objectID);
        if(is_numeric($objectID))  $objectID = array($objectID);

        $relationList   = $this->getRelationList(false, true);
        $objectTypeList = array_keys($this->config->custom->relateObjectList);
        $objectTypeList = array_merge($objectTypeList, array('commit', 'mr', 'release', 'build'));
        $relationObjects = $this->dao->select('*')->from(TABLE_RELATION)
            ->where('relation')->in(array_keys($relationList))
            ->andWhere("( `AID`", true)->in($objectID)
            ->andWhere('AType')->in($objectType)->markRight(1)
            ->orWhere("( `BID`")->in($objectID)
            ->andWhere('BType')->in($objectType)->markRight(2)
            ->andWhere('AType')->in(implode(',', $objectTypeList))
            ->andWhere('BType')->in(implode(',', $objectTypeList))
            ->orderBy('relation_asc,id_desc')
            ->fetchAll('id');

        $objectList = array();
        foreach($relationObjects as $object)
        {
            if(!isset($objectList[$object->AType])) $objectList[$object->AType] = array();
            if(!isset($objectList[$object->BType])) $objectList[$object->BType] = array();
            $objectList[$object->AType][$object->AID] = $object->AID;
            $objectList[$object->BType][$object->BID] = $object->BID;
        }
        $objectInfoList = $this->getObjectInfoByType($objectList);

        $relationObjectList  = array();
        $relationObjectCount = array();
        foreach($relationObjects as $object)
        {
            if(!isset($objectInfoList[$object->AType][$object->AID])) continue;
            $objectAinfo = $objectInfoList[$object->AType][$object->AID];
            if(empty($objectAinfo) || !empty($objectAinfo->deleted)) continue;
            $objectBinfo = $objectInfoList[$object->BType][$object->BID];
            if(empty($objectBinfo) || !empty($objectBinfo->deleted)) continue;

            $isDefault     = isset($this->config->custom->relationPairs[$object->relation]) ? true : false;
            $relationAname = $relationList[$object->relation]->relation;
            $relationAkey  = $isDefault ? "default_$relationAname" : "custom_$relationAname";
            $relationBname = $relationList[$object->relation]->relativeRelation;
            $relationBkey  = $isDefault ? "default_$relationBname" : "custom_$relationBname";

            if(in_array($object->AID, $objectID) && strpos(",$objectType,", ",$object->AType,") !== false)
            {
                $secondKey = $browseType == 'byRelation' ? $relationAkey : $object->BType;
                $thirdKey  = $browseType == 'byRelation' ? $object->BType : $relationAkey;
                if($secondKey == 'build' && (empty($objectBinfo->execution) || $tab == 'product')) $tab = 'project';

                if(!isset($relationObjectList[$object->AID])) $relationObjectList[$object->AID] = array();
                if(!isset($relationObjectList[$object->AID][$secondKey])) $relationObjectList[$object->AID][$secondKey] = array();
                if(!isset($relationObjectList[$object->AID][$secondKey][$thirdKey])) $relationObjectList[$object->AID][$secondKey][$thirdKey] = array();
                if(!isset($relationObjectList[$object->AID][$secondKey][$thirdKey][$object->BID]))
                {
                    $relationObjectList[$object->AID][$secondKey][$thirdKey][$object->BID] = array('title' => zget($objectBinfo, 'title', ''), 'url' => zget($objectBinfo, 'titleURL', null), 'status' => zget($objectBinfo, 'status', ''), 'assignedTo' => zget($objectBinfo, 'assignedTo', ''), 'tab' => $tab);

                    if(!isset($relationObjectCount[$object->AID])) $relationObjectCount[$object->AID] = 0;
                    $relationObjectCount[$object->AID] += 1;
                }
            }

            if(in_array($object->BID, $objectID) && strpos(",$objectType,", ",$object->BType,") !== false)
            {
                $secondKey = $browseType == 'byRelation' ? $relationBkey : $object->AType;
                $thirdKey  = $browseType == 'byRelation' ? $object->AType : $relationBkey;

                if(!isset($relationObjectList[$object->BID])) $relationObjectList[$object->BID] = array();
                if(!isset($relationObjectList[$object->BID][$secondKey])) $relationObjectList[$object->BID][$secondKey] = array();
                if(!isset($relationObjectList[$object->BID][$secondKey][$thirdKey])) $relationObjectList[$object->BID][$secondKey][$thirdKey] = array();
                if(!isset($relationObjectList[$object->BID][$secondKey][$thirdKey][$object->AID]))
                {
                    $relationObjectList[$object->BID][$secondKey][$thirdKey][$object->AID] = array('title' => zget($objectAinfo, 'title', ''), 'url' => zget($objectAinfo, 'titleURL', null), 'status' => zget($objectAinfo, 'status', ''), 'assignedTo' => zget($objectAinfo, 'assignedTo', ''));

                    if(!isset($relationObjectCount[$object->BID])) $relationObjectCount[$object->BID] = 0;
                    $relationObjectCount[$object->BID] += 1;
                }
            }
        }

        if($browseType == 'byObject')
        {
            $sortList = array();
            foreach($objectTypeList as $objectType)
            {
                foreach($relationObjectList as $id => $list)
                {
                    if(!isset($list[$objectType])) continue;
                    if(!isset($sortList[$id])) $sortList[$id] = array();
                    $sortList[$id][$objectType] = $list[$objectType];
                    if($objectType == 'repocommit' && isset($list['commit'])) $sortList[$id][$objectType] += $list['commit'];
                }
            }
            $relationObjectList = $sortList;
        }

        if($isNumeric) return $getCount ? zget($relationObjectCount, $objectID[0], 0) : zget($relationObjectList, $objectID[0], array());
        return $getCount ? $relationObjectCount : $relationObjectList;
    }

    /**
     * 将工作流添加到配置中。
     * Add work flow to config.
     *
     * @access public
     * @return void
     */
    public function setConfig4Workflow()
    {
        $workflowList = $this->dao->select('module,name,`table`')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->andWhere('status')->eq('normal')->fetchAll();
        foreach($workflowList as $workflow)
        {
            $this->config->custom->relateObjectList['workflow_' . $workflow->module] = $workflow->name;
            $this->config->objectTables['workflow_' . $workflow->module] = "`{$workflow->table}`";
        }
    }

    /**
     * 获取用户有权查看的需求池。
     * Get user demand pools.
     *
     * @access public
     * @return array
     */
    public function getUserDemandpools()
    {
        $account         = $this->app->user->account;
        $userDemandPools = $this->dao->select('id')->from(TABLE_DEMANDPOOL)
            ->where('acl')->eq('open')
            ->orWhere('(acl')->eq('private')
            ->andWhere('createdBy', true)->eq($account)
            ->orWhere("CONCAT(',', owner, ',')")->like("%,$account,%")
            ->orWhere("CONCAT(',', reviewer, ',')")->like("%,$account,%")
            ->markRight(2)
            ->fetchPairs('id');
        return $userDemandPools;
    }
}
