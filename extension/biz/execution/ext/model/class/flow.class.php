<?php
class flowExecution extends executionModel
{
    /**
     * Get kanban setting.
     *
     * @access public
     * @return object
     */
    public function getKanbanSetting()
    {
        $laneField      = 'status';
        $subStatus      = '';
        $subStatusColor = '';

        if(isset($this->config->execution->kanbanSetting->laneField))      $laneField      = $this->config->execution->kanbanSetting->laneField;
        if(isset($this->config->execution->kanbanSetting->subStatus))      $subStatus      = $this->config->execution->kanbanSetting->subStatus;
        if(isset($this->config->execution->kanbanSetting->subStatusColor)) $subStatusColor = $this->config->execution->kanbanSetting->subStatusColor;
        if(!is_array($subStatus))      $subStatus      = json_decode($subStatus, true);
        if(!is_array($subStatusColor)) $subStatusColor = json_decode($subStatusColor, true);

        $kanbanSetting = parent::getKanbanSetting();
        $kanbanSetting->laneField      = $laneField;
        $kanbanSetting->subStatus      = $subStatus;
        $kanbanSetting->subStatusColor = $subStatusColor;

        return $kanbanSetting;
    }

    /**
     * Get kanban columns.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColumns($kanbanSetting)
    {
        if($kanbanSetting->laneField == 'status') return parent::getKanbanColumns($kanbanSetting);

        $mode = $this->session->execution_kanban_mode;
        if(!$mode) $mode = 'task';

        return $kanbanSetting->subStatus[$mode];
    }

    /**
     * 根据状态和子状态的对应关系，以及父类方法获取的状态和方法的映射关系，计算子状态和方法的映射关系，
     * 以决定看板内容能否从一个子状态拖动到另一个子状态，以及拖动后执行什么方法。
     * According to the correspondence between the status and the sub-status, and the mapping relationship between the status
     * and the method extends from the parent class method, the mapping relationship between the sub-status and the method is calculated
     * to determine whether the kanban content can be dragged from one sub-status to another, and what method is executed after dragging.
     *
     * 映射关系的基本格式为 map[$mode][$fromStatus][$toStatus] = $methodName。
     * The basic format of the mapping relationship is map[$mode][$fromStatus][$toStatus] = $methodName.
     *
     * @param string $mode          看板内容类型，可选值 task|bug   The content mode of kanban, should be task or bug.
     * @param string $fromStatus    拖动内容的来源泳道              The origin lane the content draged from.
     * @param string $toStatus      拖动内容的目标泳道              The destination lane the content draged to.
     * @param string $methodName    拖动到目标泳道后执行的方法名    The method to execute after draged the content.
     *
     * 例如从父类中获取到 map['task']['doing']['done'] = 'close' 表示：任务(task)看板从进行中(doing)泳道拖动到已完成(done)泳道时，
     * 执行关闭(close)方法。
     * For example, get the mapping from parent class such as map['task']['doing']['done'] = 'close' means: when the task kanban is dragged
     * from the doing lane to the done lane, execute the close method.
     *
     * 根据工作流字段的设置 doing 状态有两个子状态 doing1, doing2，done 状态有两个子状态 done1, done2，则通过本方法可以得到结果如下：
     * According to the setting of the workflow field, the doing status has two sub-statuses doing1, doing2, and the done status has
     * two sub-statuses done1, done2, and the result can be obtained by this method as follows:
     *
     *  map['task']['doing1']['done1'] = 'close'
     *  map['task']['doing1']['done2'] = 'close'
     *  map['task']['doing2']['done1'] = 'close'
     *  map['task']['doing2']['done2'] = 'close'
     *
     * 上述内容表示从 doing1 和 doing2 状态均可以拖动到 done1 和 done2 状态，且执行 close 方法。
     * The above means that both doing11 and doing2 statuses can be dragged to the done1 and done2 statuses, and the close method is executed.
     *
     * @param  object $kanbanSetting    The settings of kanban.
     * @access public
     * @return string
     */
    public function getKanbanStatusMap($kanbanSetting)
    {
        /* 根据父类方法获取状态和方法的映射关系。Get parent status => method mapping. */
        $parentStatusMap = parent::getKanbanStatusMap($kanbanSetting);

        if($kanbanSetting->laneField == 'status') return $parentStatusMap;

        $parentStatusMap['bug'] = array();
        if(common::hasPriv('bug', 'resolve')) $parentStatusMap['bug']['active']['resolved'] = 'resolve';
        if(common::hasPriv('bug', 'close'))   $parentStatusMap['bug']['resolved']['closed'] = 'close';
        if(common::hasPriv('bug', 'activate'))
        {
            $parentStatusMap['bug']['resolved']['active'] = 'activate';
            $parentStatusMap['bug']['closed']['active']   = 'activate';
        }

        return $this->getSubStatusMap($parentStatusMap);
    }

    /**
     * 根据状态和子状态的对应关系，以及父类方法获取的状态和方法的映射关系，计算子状态和方法的映射关系。
     * The mapping relationship between the sub-status and the method is calculated, according to the correspondence between the status and the sub-status, and the mapping relationship between the status and the method extends from the parent class method.
     *
     * @param  array  $parentStatusMap
     * @access public
     * @return array
     */
    public function getSubStatusMap($parentStatusMap)
    {
        $subStatusMap = array();
        foreach($parentStatusMap as $parent => $statusMap)
        {
            /* 根据工作流字段设置获取状态和子状态的映射关系。Get status => subStatus mapping according to the subStatus field's options. */
            $subStatusField = $this->loadModel('workflowfield')->getByField($parent, 'subStatus', $mergeOptions = false);
            foreach($statusMap as $fromStatus => $toStatuses)
            {
                $fromSubStatusList = array();
                if(isset($subStatusField->options[$fromStatus])) $fromSubStatusList = zget($subStatusField->options[$fromStatus], 'options', array());
                if(empty($fromSubStatusList)) $fromSubStatusList = array($fromStatus => $fromStatus);

                foreach($fromSubStatusList as $fromSubStatus => $name)
                {
                    foreach($toStatuses as $toStatus => $method)
                    {
                        $toSubStatusList = array();
                        if(isset($subStatusField->options[$toStatus])) $toSubStatusList = zget($subStatusField->options[$toStatus], 'options', array());
                        if(empty($toSubStatusList)) $toSubStatusList = array($toStatus => $toStatus);

                        foreach($toSubStatusList as $toSubStatus => $name) $subStatusMap[$parent][$fromSubStatus][$toSubStatus] = $method;
                    }

                    foreach($fromSubStatusList as $toSubStatus => $name)
                    {
                        if($fromSubStatus == $toSubStatus) continue;
                        $subStatusMap[$parent][$fromSubStatus][$toSubStatus] = 'ajaxChangeSubStatus';
                    }
                }
            }
        }

        return $subStatusMap;
    }

    /**
     * Get status list of kanban.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return string
     */
    public function getKanbanStatusList($kanbanSetting)
    {
        if($kanbanSetting->laneField == 'status') return parent::getKanbanStatusList($kanbanSetting);

        $mode = $this->session->execution_kanban_mode;
        if(!$mode) $mode = 'task';

        $field = $this->loadModel('workflowfield')->getByField($mode, 'subStatus');

        return $field->options;
    }

    /**
     * Get color list of kanban.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColorList($kanbanSetting)
    {
        if($kanbanSetting->laneField == 'status') return parent::getKanbanColorList($kanbanSetting);

        $mode = $this->session->execution_kanban_mode;
        if(!$mode) $mode = 'task';

        return $kanbanSetting->subStatusColor[$mode];
    }

    /**
     * 任务子状态和Bug子状态完全由用户自定义，无法统一。使用子状态展示看板内容时，任务看板和Bug看板分开展示，通过mode参数区分。
     * The task substate and the bug substate are completely user-defined and cannot be unified. When the kanban content is displayed
     * using the substate, the task kanban and the Bug kanban are displayed separately, and are distinguished by the mode parameter.
     *
     * @param  array  $stories
     * @param  array  $tasks
     * @param  array  $bugs
     * @param  string $type
     * @access public
     * @return array
     */
    public function getKanbanGroupData($stories, $tasks = [], $bugs = [], $type = 'story')
    {
        $setting = $this->getKanbanSetting();
        if($setting->laneField == 'status') return parent::getKanbanGroupData($stories, $tasks, $bugs, $type);

        $mode = $this->session->execution_kanban_mode;
        if(!$mode) $mode = 'task';

        $kanbanGroup = array();
        if($type == 'story') $kanbanGroup = $stories;
        $kanbanGroup['nokey'] = new stdclass();

        /* Display task kanban */
        $field = $this->loadModel('workflowfield')->getByField('task', 'subStatus', $mergeOptions = false);
        $subStatusOptions = $field->options;
        if($mode == 'task')
        {
            foreach($tasks as $task)
            {
                $groupKey = $type == 'story' ? $task->storyID : $task->$type;
                $status   = $task->subStatus;
                if(empty($status) and $subStatusOptions)
                {
                    $status = $subStatusOptions[$task->status]['default'];
                    $task->subStatus = $status;
                }

                if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->tasks[$status][] = $task;
                }
                else
                {
                    $noKeyTasks[$status][] = $task;
                }
            }

            if(isset($noKeyTasks)) $kanbanGroup['nokey']->tasks = $noKeyTasks;
        }

        /* Display bug kanban */
        $field = $this->loadModel('workflowfield')->getByField('bug', 'subStatus', $mergeOptions = false);
        $subStatusOptions = $field->options;
        if($mode == 'bug')
        {
            foreach($bugs as $bug)
            {
                $groupKey = $type == 'finishedBy' ? $bug->resolvedBy : $bug->$type;
                $status   = $bug->subStatus;
                if(empty($status) and $subStatusOptions)
                {
                    $status = $subStatusOptions[$bug->status]['default'];
                    $bug->subStatus = $status;
                }

                if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->bugs[$status][] = $bug;
                }
                else
                {
                    $noKeyBugs[$status][] = $bug;
                }
            }

            if(isset($noKeyBugs)) $kanbanGroup['nokey']->bugs = $noKeyBugs;
        }

        return $kanbanGroup;
    }

    public function getProjectLink($module, $method, $extra)
    {
        $link = parent::getProjectLink($module, $method, $extra);

        if($module == 'flow' and !empty($extra))
        {
            $flow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('id')->eq((int)$extra)->fetch();
            if($flow)
            {
                $labels = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($flow->module)->orderBy('order')->fetchAll();
                foreach($labels as $label)
                {
                    if($label->buildin) continue;
                    if(!commonModel::hasPriv($flow->module, $label->id)) continue;

                    $link = helper::createLink($flow->module, 'browse', "mode=browse&label={$label->id}");
                    break;
                }
            }
        }

        return $link;
    }
}
