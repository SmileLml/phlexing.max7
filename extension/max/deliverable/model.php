<?php
class deliverableModel extends model
{
    /**
     * 根据ID获取交付物。
     * Get deliverable by id.
     *
     * @param int     $id
     * @access public
     * @return object
     */
    public function getByID($id)
    {
        $deliverable = $this->dao->select('*')->from(TABLE_DELIVERABLE)->where('id')->eq($id)->fetch();
        if(empty($deliverable)) return false;

        $deliverable = $this->loadModel('file')->replaceImgURL($deliverable, 'desc');
        if($deliverable->files) $deliverable->files = $this->file->getByObject('deliverable', $id);
        return $deliverable;
    }

    /**
     * 获取交付物列表。
     * Get deliverable list.
     *
     * @param string $browseType
     * @param int    $queryID
     * @param string $orderBy
     * @param object $pager
     * @access public
     * @return array
     */
    public function getList($browseType = '', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $deliverableQuery = '';
        $browseType       = strtolower($browseType);
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('deliverableQuery', $query->sql);
                $this->session->set('deliverableForm', $query->form);
            }
            if($this->session->deliverableQuery == false) $this->session->set('deliverableQuery', ' 1=1');
            $deliverableQuery = $this->session->deliverableQuery;
        }

        return $this->dao->select('*')->from(TABLE_DELIVERABLE)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'all' && $browseType != 'bysearch')->andWhere('module')->eq($browseType)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($deliverableQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 构建交付物列表搜索表单。
     * Build deliverable list search form.
     *
     * @param int    $queryID
     * @param string $actionURL
     * @access public
     * @return void
     */
    public function buildBrowseSearchForm($queryID = 0, $actionURL = '')
    {
        $this->config->deliverable->search['actionURL']                 = $actionURL;
        $this->config->deliverable->search['queryID']                   = $queryID;
        $this->config->deliverable->search['params']['model']['values'] = $this->buildModelList('all');

        $this->loadModel('search')->setSearchParams($this->config->deliverable->search);
    }

    /**
     * 创建交付物。
     * Create deliverable.
     *
     * @param  object $deliverable
     * @access public
     * @return bool
     */
    public function create($deliverable)
    {
        $this->dao->insert(TABLE_DELIVERABLE)->data($deliverable)->exec();
        if(dao::isError()) return false;

        $deliverableID = $this->dao->lastInsertID();

        $files = $this->loadModel('file')->saveUpload('deliverable', $deliverableID);
        if(!empty($files))
        {
            $fileIdList = implode(',', array_keys($files));
            $this->dao->update(TABLE_DELIVERABLE)->set('files')->eq($fileIdList)->where('id')->eq($deliverableID)->exec();
        }

        $this->loadModel('action')->create('deliverable', $deliverableID, 'opened');

        return !dao::isError();
    }

    /**
     * 更新交付物。
     * Update deliverable.
     *
     * @param  int    $id
     * @param  object $deliverable
     * @access public
     * @return bool
     */
    public function update($id, $deliverable)
    {
        $oldDeliverable = $this->fetchByID($id);

        $this->dao->update(TABLE_DELIVERABLE)->data($deliverable, 'deleteFiles,renameFiles')->where('id')->eq($id)->exec();
        if(dao::isError()) return false;

        /* 删除文件。 */
        $oldDeliverable->files = $this->loadModel('file')->getByIdList($oldDeliverable->files);
        $this->loadModel('file')->processFileDiffsForObject('deliverable', $oldDeliverable, $deliverable);
        if(implode(',', array_keys($oldDeliverable->files)) != $deliverable->files) $this->dao->update(TABLE_DELIVERABLE)->set('files')->eq($deliverable->files)->where('id')->eq($id)->exec();

        $changes = common::createChanges($oldDeliverable, $deliverable);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('deliverable', $id, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 构造交付物适用范围列表。
     * Build deliverable model list.
     *
     * @param  string $type all|project|execution
     * @param  bool   $full
     * @access public
     * @return array
     */
    public function buildModelList($type = 'all', $full = true)
    {
        $this->app->loadLang('stage');
        $this->app->loadLang('execution');

        $modelList = array();

        if($type == 'all' || $type == 'project') $modelList = $this->lang->deliverable->modelList;
        if($type == 'all' || $type == 'execution')
        {
            $stageList    = $this->lang->stage->typeList;
            $lifeTimeList = $this->lang->execution->lifeTimeList;
            foreach($this->lang->deliverable->modelList as $key => $value)
            {
                if(strpos($key, 'waterfall') !== false)
                {
                    foreach($stageList as $stageKey => $stageValue)
                    {
                        $modelList[$key . '_' . $stageKey] = ($full ? ($value . '/') : '') . $stageValue . $this->lang->execution->typeList['stage'];
                    }
                }
                elseif(strpos($key, 'scrum') !== false)
                {
                    foreach($lifeTimeList as $lifeTimeKey => $lifeTimeValue)
                    {
                        $modelList[$key . '_' . $lifeTimeKey] = ($full ? ($value . '/') : '') . $lifeTimeValue . $this->lang->execution->typeList['sprint'];
                    }

                    $modelList[$key . '_' . 'kanban'] = ($full ? ($value . '/') : '') . $this->lang->execution->typeList['kanban'];
                }
            }
        }

        return $modelList;
    }

    /**
     * 删除交付物。
     * Delete deliverable.
     *
     * @param string   $table
     * @param int      $id
     * @access public
     * @return bool
     */
    public function delete($table, $id)
    {
        if(empty($id)) return false;

        $this->dao->update(TABLE_DELIVERABLE)->set('deleted')->eq(1)->where('id')->eq($id)->exec();

        $workflowGroups = $this->dao->select('id,deliverable')->from(TABLE_WORKFLOWGROUP)->where('deliverable')->ne('')->fetchAll();
        foreach($workflowGroups as $workflowGroup)
        {
            $groupDeliverable = json_decode($workflowGroup->deliverable, true);
            foreach($groupDeliverable as $objectType => $conditions)
            {
                foreach($conditions as $action => $deliverables)
                {
                    foreach($deliverables as $i => $condition)
                    {
                        if($condition['deliverable'] == $id) unset($groupDeliverable[$objectType][$action][$i]);
                    }
                    if(empty($groupDeliverable[$objectType][$action])) unset($groupDeliverable[$objectType][$action]);
                }
                if(empty($groupDeliverable[$objectType][$action])) unset($groupDeliverable[$objectType]);
            }
            $groupDeliverable = json_encode($groupDeliverable);
            if($groupDeliverable != $workflowGroup->deliverable) $this->dao->update(TABLE_WORKFLOWGROUP)->set('deliverable')->eq($groupDeliverable)->where('id')->eq($workflowGroup->id)->exec();
        }

        return !dao::isError();
    }
}
