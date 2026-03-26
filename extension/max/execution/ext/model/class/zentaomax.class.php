<?php
class zentaomaxExecution extends executionModel
{
    /**
     * 关闭迭代。
     * Close execution.
     *
     * @param  int       $executionID
     * @param  object    $postData
     * @access public
     * @return int|false
     */
    public function close($executionID, $postData)
    {
        $oldExecution = $this->fetchById($executionID);
        if(!empty($postData->deliverable))
        {
            $postData = $this->loadModel('project')->processDeliverable($oldExecution->project, $executionID, $postData, 'whenClosed');
        }
        else
        {
            unset($postData->deliverable);
        }

        $actionID = parent::close($executionID, $postData);
        if(dao::isError()) return false;

        if(!empty($postData->deliverable))
        {
            $this->project->uploadDeliverable($executionID, 'execution', 'deliverable', 'whenClosed');
            $this->project->moveDocToProjectLib($oldExecution->project, $executionID, 'whenClosed');

            /* 由于deliverable配置项里的文档ID变更， 需要重新记录历史记录。 */
            if($actionID) $this->dao->delete()->from(TABLE_ACTION)->where('id')->eq($actionID)->exec();
            $execution = $this->fetchByID($executionID);
            $changes   = common::createChanges($oldExecution, $execution);
            if($this->post->comment != '' || !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('execution', $executionID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
        }

        return $actionID;
    }

    /**
     * 创建迭代。
     * Create a execution.
     *
     * @param  object    $execution
     * @param  array     $postMembers
     * @access public
     * @return int|false
     */
    public function create($execution, $postMembers)
    {
        if(helper::hasFeature('deliverable'))
        {
            $project = $this->fetchByID($execution->project);
            if(!empty($project->deliverable) && !empty($project->multiple))
            {
                $projectModel = $project->model;
                if($projectModel == 'agileplus')     $projectModel = 'scrum';
                if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

                $projectType = !empty($project->hasProduct) ? 'product' : 'project';
                $objectCode  = $execution->attribute ? "{$projectType}_{$projectModel}_{$execution->attribute}" : "{$projectType}_{$projectModel}_{$execution->lifetime}";
                if($execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

                $projectDeliverable     = $project->deliverable;
                $projectDeliverable     = !empty($projectDeliverable) ? json_decode($projectDeliverable, true) : array();
                $executionDeliverable   = !empty($projectDeliverable[$objectCode]) ? array("{$objectCode}" => $projectDeliverable[$objectCode]) : array();
                $execution->deliverable = json_encode($executionDeliverable);
            }
        }
        return parent::create($execution, $postMembers);
    }

    /**
     * 检查迭代是否有已上传的交付物。
     * Check if the execution has uploaded deliverable.
     *
     * @param  object  $execution
     * @access public
     * @return bool
     */
    public function hasUploadedDeliverable($execution)
    {
        if(empty($execution->deliverable)) return false;

        $deliverables = json_decode($execution->deliverable, true);

        foreach($deliverables as $methods)
        {
            foreach($methods as $itemList)
            {
                foreach($itemList as $item)
                {
                    if(!empty($item['file']) || !empty($item['doc'])) return true;
                }
            }
        }

        return false;
    }

    /**
     * 更新一个迭代。
     * Update a execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function update($executionID, $postData)
    {
        if(helper::hasFeature('deliverable'))
        {
            $execution = $this->fetchByID($executionID);
            $postData  = $this->changeExecutionDeliverable($executionID, $postData);
            if($postData->status == 'closed' && $execution->status == 'doing')
            {
                $execution    = new stdclass();
                $oldExecution = $this->fetchByID($executionID);
                $execution->deliverable = !empty($postData->deliverable) ? $postData->deliverable : $oldExecution->deliverable;
                if(!$this->canCloseByDeliverable($execution)) return $this->app->control->send(array('result' => 'fail', 'load' => array('alert' => array('title' => $this->lang->execution->notClose, 'message' => $this->lang->execution->closeExecutionError))));
            }
        }
        return parent::update($executionID, $postData);
    }

    /**
     * 变更迭代交付物配置。
     * Change execution deliverable.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return object
     */
    public function changeExecutionDeliverable($executionID, $postData)
    {
        $oldExecution = $this->fetchByID($executionID);

        /* 对已关闭的迭代、看板迭代、迭代类型没有变更的迭代不做交付物配置变更处理。 */
        if($oldExecution->status == 'closed' || $oldExecution->type == 'kanban' || ((!empty($postData->attribute) && $oldExecution->attribute == $postData->attribute) || (!empty($postData->lifetime) && $oldExecution->lifetime == $postData->lifetime))) return $postData;

        $project = $this->fetchByID($oldExecution->project);
        if(!$project) return $postData;

        $projectModel = $project->model;
        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

        if(!isset($postData->lifetime)) $postData->lifetime = $oldExecution->lifetime;

        $projectType = !empty($project->hasProduct) ? 'product' : 'project';
        $objectCode  = !empty($postData->attribute) ? "{$projectType}_{$projectModel}_{$postData->attribute}" : "{$projectType}_{$projectModel}_{$postData->lifetime}";

        /* 根据修改后迭代的类型,从所属项目的交付物配置里获取交付物配置。 */
        $executionDeliverable = array();
        if(!empty($project->deliverable))
        {
            $projectDeliverable   = $project->deliverable;
            $projectDeliverable   = !empty($projectDeliverable) ? json_decode($projectDeliverable, true) : array();
            $executionDeliverable = !empty($projectDeliverable[$objectCode]) ? array("{$objectCode}" => $projectDeliverable[$objectCode]) : array();
        }

        /* 如果迭代曾经提交过交付物。 */
        if(!empty($oldExecution->deliverable))
        {
            $oldDeliverable = json_decode($oldExecution->deliverable, true);
            foreach($oldDeliverable as $code => $methodDeliverable)
            {
                foreach($methodDeliverable as $method => $deliverables)
                {
                    foreach($deliverables as $deliverable)
                    {
                        /* 过滤没有提交过交付物的交付物配置。 */
                        if(empty($deliverable['file']) && empty($deliverable['doc'])) continue;

                        /* 如果存在重复的交付物ID，按照合并文档附件处理。 */
                        $duplicated = false;
                        if(!empty($executionDeliverable[$objectCode][$method]))
                        {
                            foreach($executionDeliverable[$objectCode][$method] as $index => $configDeliverable)
                            {
                                if(strpos($deliverable['deliverable'], 'new_') !== false || $configDeliverable['deliverable'] != $deliverable['deliverable']) continue;

                                $executionDeliverable[$objectCode][$method][$index]['doc']  = !empty($deliverable['doc'])  ? $deliverable['doc']  : '';
                                $executionDeliverable[$objectCode][$method][$index]['file'] = !empty($deliverable['file']) ? $deliverable['file'] : '';
                                $duplicated = true;
                            }
                        }

                        /* 如果没有重复的交付物ID，将文档附件作为其他交付物处理。 */
                        if(!$duplicated)
                        {
                            $deliverable = array('deliverable' => 'new_0', 'required' => false, 'category' => $this->lang->other, 'doc' => zget($deliverable, 'doc', ''), 'file' => zget($deliverable, 'file', ''), 'template' => '');
                            $executionDeliverable[$objectCode][$method][] = $deliverable;
                        }
                    }
                }
            }
        }
        $postData->deliverable = json_encode($executionDeliverable);

        return $postData;
    }
}
