<?php
/**
 * The model file of workflowgroup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
class workflowgroupModel extends model
{
    /**
     * 获取一个流程组。
     * Get a workflow group.
     *
     * @param  int    $groupID
     * @access public
     * @return object|false
     */
    public function getByID($groupID)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($groupID)->fetch();
    }

    /**
     * 根据流程组ID列表获取流程组。
     * Get workflow groups by id list.
     *
     * @param  array    $groupIdList
     * @access public
     * @return object|false
     */
    public function getByIdList($groupIdList)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)->where('id')->in($groupIdList)->andWhere('deleted')->eq('0')->fetchAll('id', false);
    }

    /**
     * 获取流程组列表数据。
     * Get workflow group list.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($type = 'product', $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)
             ->where('type')->eq($type)
             ->andWhere('deleted')->eq('0')
             ->andWhere('objectID')->eq('0')
             ->beginIF($type == 'project' && $this->config->systemMode == 'light')->andWhere('projectModel')->eq('scrum')->fi()
             ->orderBy($orderBy)
             ->page($pager)
             ->fetchAll('id', false);
    }

    /**
     * 获取流程规则列表数据。
     * Get workflow group list.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getRuleList($groupID, $orderBy = 'id_desc', $pager = null)
    {
        return $this->dao->select('*')->from(TABLE_RULE)
            ->where('type')->eq('group')
            ->andWhere("FIND_IN_SET('{$groupID}', workflowGroup)")
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取流程组键值对。
     * Get workflow group pairs.
     *
     * @param  string $type
     * @param  string $projectModel
     * @param  int    $hasProduct
     * @param  string $status
     * @param  string $exclusive
     * @access public
     * @return array
     */
    public function getPairs($type = 'product', $projectModel = 'scrum', $hasProduct = 1, $status = 'normal', $exclusive = 'all')
    {
        if($projectModel == 'agileplus') $projectModel = 'scrum';
        if(in_array($projectModel, array('waterfallplus', 'ipd'))) $projectModel = 'waterfall';
        $projectType = $hasProduct == 1 ? 'product' : 'project';

        return $this->dao->select('id, name')->from(TABLE_WORKFLOWGROUP)
             ->where('type')->eq($type)
             ->andWhere('objectID')->eq('0')
             ->beginIF($status != 'all')->andWhere('deleted')->eq('0')->fi()
             ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
             ->beginIF($type == 'project')
             ->andWhere('projectModel')->eq($projectModel)
             ->andWhere('projectType')->eq($projectType)
             ->fi()
             ->beginIF($exclusive != 'all')->andWhere('exclusive')->eq($exclusive)->fi()
             ->orderBy('main_desc, id_asc')
             ->fetchPairs();
    }

    /**
     * 获取所有流程组键值对。
     * Get all workflow group pairs.
     *
     * @param  string $exclusive
     * @access public
     * @return array
     */
    public function getAllPairs($exclusive = '0')
    {
        return $this->dao->select('id, name')->from(TABLE_WORKFLOWGROUP)
             ->where('deleted')->eq('0')
             ->beginIF($exclusive != 'all')->andWhere('exclusive')->eq($exclusive)->fi()
             ->orderBy('main_desc, id_asc')
             ->fetchPairs();
    }

    /**
     * 获取流程组的流程。
     * Get flows by group.
     *
     * @param  object $group
     * @param  string $orderBy
     * @param  bool   $excludeDisabled
     * @access public
     * @return array
     */
    public function getFlows($group, $orderBy = 'id', $excludeDisabled = false)
    {
        $modules = array();
        if($group->type == 'product') $modules = $this->config->workflowgroup->modules['product'];
        if($group->type == 'project') $modules = $this->config->workflowgroup->modules['project'][$group->projectType];

        $typeList = $group->type;
        if($group->type == 'project') $typeList .= ',execution';
        $flows = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('status')->eq("normal")
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('module', true)->in($modules)
            ->orWhere('belong')->in($typeList)
            ->markRight(1)
            ->beginIF($excludeDisabled)->andWhere('module')->notin($group->disabledModules)->fi()
            ->beginIF($group->type == 'project')->andWhere('app')->ne($group->projectModel == 'scrum' ? 'waterfall' : 'scrum')->fi()
            ->andWhere('group')->in("0,{$group->id}")
            ->orderBy($orderBy)
            ->fetchAll('id', false);

        $uniqueFlows = array();
        foreach($flows as $flow)
        {
            $flow->defaultStatus = 'normal';
            if(!isset($uniqueFlows[$flow->module])) $uniqueFlows[$flow->module] = $flow;
            if(isset($uniqueFlows[$flow->module]))
            {
                if($flow->group == $group->id)
                {
                    if($flow->group == $uniqueFlows[$flow->module]->group) $flow->defaultStatus = 'pause'; // 如果没有重复流程，说明默认流程被停用了。
                    $uniqueFlows[$flow->module] = $flow;
                }
            }
        }

        return $uniqueFlows;
    }

    /**
     * 创建一个流程组。
     * Create a workflow group.
     *
     * @param  object $group
     * @access public
     * @return int|false
     */
    public function create($group)
    {
        $this->dao->insert(TABLE_WORKFLOWGROUP)->data($group)
            ->check('name', 'notempty')
            ->checkIF($group->name != '', 'name', 'unique', "type='{$group->type}'")
            ->checkIF($group->type == 'project', 'projectModel', 'notempty')
            ->checkIF($group->type == 'project', 'projectType', 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * 更新一个流程组。
     * Update a workflow group.
     *
     * @param  object $group
     * @param  object $oldGroup
     * @access public
     * @return array|false
     */
    public function update($group, $oldGroup)
    {
        $this->dao->update(TABLE_WORKFLOWGROUP)->data($group)
            ->check('name', 'notempty')
            ->checkIF($group->name != '', 'name', 'unique', "type = '{$oldGroup->type}' AND id != '{$oldGroup->id}'")
            ->autoCheck()
            ->where('id')->eq($oldGroup->id)
            ->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldGroup, $group);
    }

    /**
     * 判断操作按钮是否可点击。
     * Determine if the operation button is clickable.
     *
     * @param  int    $groupID
     * @param  string $status    wait|pause|normal
     * @access public
     * @return bool
     */
    public function changeStatus($groupID, $status)
    {
        if(!isset($this->lang->workflowgroup->statusList[$status])) return false;
        $this->dao->update(TABLE_WORKFLOWGROUP)->set('status')->eq($status)->where('id')->eq((int)$groupID)->exec();
        return true;
    }

    /**
     * 判断操作按钮是否可点击。
     * Determine if the operation button is clickable.
     *
     * @param  object  $data
     * @param  string  $action
     * @access public
     * @return bool
     */
    public static function isClickable($data, $action)
    {
        $action = strtolower($action);

        if($action == 'release')      return $data->status == 'wait' || $data->status == 'pause';
        if($action == 'deactivate')   return $data->status == 'normal';

        return true;
    }

    /**
     * 将一个流程设置分组专属。
     * Set a flow as exclusive for a group.
     *
     * @param  int    $flowID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function setExclusive($flowID, $groupID)
    {
        $this->unsetExclusive($flowID, $groupID);

        $flow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('id')->eq($flowID)->andWhere('group')->eq(0)->fetch();
        if(empty($flow)) return;

        $this->insertGroupObject($flow, 'flow', $groupID);

        $fieldModules = array($flow->module);
        $subTables    = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('parent')->eq($flow->module)->andWhere('group')->eq(0)->andWhere('type')->eq('table')->andWhere('vision')->eq($this->config->vision)->fetchAll('id', false);
        foreach($subTables as $table)
        {
            $fieldModules[] = $table->module;
            $this->insertGroupObject($table, 'subtable', $groupID);
        }

        $fields = $this->dao->select('*')->from(TABLE_WORKFLOWFIELD)->where('module')->in($fieldModules)->andWhere('group')->eq(0)->fetchAll('id', false);
        foreach($fields as $field) $this->insertGroupObject($field, 'flowfield', $groupID);

        $actions = $this->dao->select('*')->from(TABLE_WORKFLOWACTION)->where('module')->eq($flow->module)->andWhere('group')->eq(0)->andWhere('vision')->eq($this->config->vision)->fetchAll('id', false);
        foreach($actions as $action) $this->insertGroupObject($action, 'flowaction', $groupID);

        $labels     = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($flow->module)->andWhere('group')->eq(0)->fetchAll('id', false);
        $labelPrivs = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('module')->eq($flow->module)->andWhere('method')->in(array_keys($labels))->fetchGroup('method', 'group');
        foreach($labels as $labelID => $label)
        {
            $newLabelID = $this->insertGroupObject($label, 'flowlabel', $groupID);
            if(isset($labelPrivs[$labelID]))
            {
                foreach($labelPrivs[$labelID] as $priv)
                {
                    $priv->method = $newLabelID;
                    $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
                }
            }
        }

        $layouts = $this->dao->select('*')->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($flow->module)->andWhere('group')->eq(0)->andWhere('vision')->eq($this->config->vision)->fetchAll('id', false);
        foreach($layouts as $layout)$this->insertGroupObject($layout, 'flowlayout', $groupID);
    }

    /**
     * 删除分组专属流程。
     * Unset a flow as exclusive for a group.
     *
     * @param  int    $flowID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function unsetExclusive($flowID, $groupID)
    {
        $flow = $this->dao->select('*')->from(TABLE_WORKFLOW)->where('id')->eq($flowID)->fetch();
        $this->dao->delete()->from(TABLE_WORKFLOW)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOW)->where('parent')->eq($flow->module)->andWhere('type')->eq('table')->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWFIELD)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWACTION)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWLABEL)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_WORKFLOWUI)->where('module')->eq($flow->module)->andWhere('group')->eq($groupID)->exec();
    }

    /**
     * 插入所属分组的数据。
     * Insert data belonging to the group.
     *
     * @param  object $object
     * @param  string $objectType
     * @param  int    $groupID
     * @access public
     * @return int|false
     */
    public function insertGroupObject($object, $objectType, $groupID)
    {
        $table = '';
        if($objectType == 'flow')       $table = TABLE_WORKFLOW;
        if($objectType == 'subtable')   $table = TABLE_WORKFLOW;
        if($objectType == 'flowfield')  $table = TABLE_WORKFLOWFIELD;
        if($objectType == 'flowaction') $table = TABLE_WORKFLOWACTION;
        if($objectType == 'flowlabel')  $table = TABLE_WORKFLOWLABEL;
        if($objectType == 'flowlayout') $table = TABLE_WORKFLOWLAYOUT;
        if(empty($table)) return false;

        unset($object->id);
        if(isset($object->editedBy)) unset($object->editedBy, $object->editedDate);

        $object->group = $groupID;
        if(isset($object->createdBy))   $object->createdBy = $this->app->user->account;
        if(isset($object->createdDate)) $object->createdDate = helper::now();
        if($objectType == 'subtable'  && $object->role == 'custom') $object->role = 'quote';
        if($objectType == 'flowfield' && $object->role == 'custom') $object->role = 'quote';

        $this->dao->insert($table)->data($object)->exec();
        return $this->dao->lastInsertId();
    }

    /**
     * 根据对象获取对象绑定的工作流模板ID.
     * Get the workflow group ID of the object.
     *
     * @param  string $moduleName
     * @param  object $object
     * @access public
     * @return int
     */
    public function getGroupIDByData($moduleName, $object)
    {
        if(empty($object) || !isset($object->id)) return $this->getGroupIDBySession($moduleName);

        $flow    = $this->loadModel('workflow')->getByModule($moduleName);
        $belong  = $flow ? $flow->belong : '';
        $groupID = 0;
        if($moduleName == 'product')
        {
            $groupID = $object->workflowGroup;
        }
        elseif($moduleName == 'project')
        {
            $groupID = $object->workflowGroup;
        }
        elseif(in_array($moduleName, $this->config->workflowgroup->modules['product']) || $belong == 'product')
        {
            $product = $this->dao->select('id,shadow,workflowGroup')->from(TABLE_PRODUCT)->where('id')->eq($object->product)->fetch();
            if($product)
            {
                $groupID = $product->workflowGroup;
                if($product->shadow && in_array($moduleName, $this->config->workflowgroup->modules['project']['project']))
                {
                    $projectID = $this->dao->select('t1.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                        ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                        ->where('t1.product')->eq($product->id)
                        ->andWhere('t2.type')->eq('project')
                        ->fetch('project');
                    $groupID = $this->dao->select('workflowGroup')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('workflowGroup');
                }
            }
        }
        elseif(in_array($moduleName, $this->config->workflowgroup->modules['project']['product']) || $belong == 'project')
        {
            $groupID = isset($object->project) ? $this->dao->select('workflowGroup')->from(TABLE_PROJECT)->where('id')->eq($object->project)->fetch('workflowGroup') : 0;
        }
        elseif($belong == 'execution')
        {
            $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($object->execution)->fetch('project');
            $groupID   = $this->dao->select('workflowGroup')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('workflowGroup');
        }

        if(empty($groupID)) return 0;

        $group = $this->getByID($groupID);
        return (empty($group) || $group->main) ? 0 : $groupID;
    }

    /**
     * 根据Session获取对象绑定的工作流模板ID.
     * Get the workflow group ID of the object by session.
     *
     * @param  string $moduleName
     * @param  bool   $convertMain
     * @access public
     * @return int
     */
    public function getGroupIDBySession($moduleName = '', $convertMain = true)
    {
        $result = $this->getObjectAndTypeBySession($moduleName);
        if(empty($result)) return 0;

        $object  = $result['data'];
        $groupID = $object ? (int)zget($object, 'workflowGroup', 0) : 0;
        if(empty($groupID)) return 0;

        $group = $this->getByID($groupID);

        /* 如果模板停用了，则取默认模板ID。*/
        if($group && $group->status != 'normal')
        {
            if($result['type'] == 'product')
            {
                $groupID = $this->dao->select('id')->from(TABLE_WORKFLOWGROUP)->where('code')->eq('productproject')->andWhere('main')->eq('1')->fetch('id');
            }
            elseif($result['type'] == 'project')
            {
                if(in_array($object->model, array('scrum', 'agileplus')))
                {
                    $code = $object->hasProduct ? 'scrumproduct' : 'scrumproject';
                }
                else
                {
                    $code = $object->hasProduct ? 'waterfallproduct' : 'waterfallproject';
                }

                $groupID = $this->dao->select('id')->from(TABLE_WORKFLOWGROUP)->where('code')->eq($code)->andWhere('main')->eq('1')->fetch('id');
            }
        }

        if(empty($group)) return 0;
        if($convertMain && $group->main) return 0;
        return $groupID;
    }

    /**
     * 根据Session获取对象.
     * Get object and type by session.
     *
     * @param  string $moduleName
     * @access public
     * @return array
     */
    public function getObjectAndTypeBySession($moduleName = '')
    {
        $tab       = $this->app->tab;
        $productID = 0;
        $projectID = 0;
        if(empty($moduleName)) $moduleName = $this->app->rawModule;
        if($moduleName == 'projectrelease') $moduleName = 'release';

        $objectType = '';
        if($tab == 'product')   $objectType = 'product';
        if($tab == 'project')   $objectType = 'project';
        if($tab == 'qa')        $objectType = 'product';
        if($tab == 'execution') $objectType = 'execution';
        if($tab == 'feedback')
        {
            $objectType = 'product';
            if($moduleName == 'feedback') $objectType = 'feedbackProduct';
            if($moduleName == 'ticket')   $objectType = 'ticketProduct';
        }

        $navGroup = zget($this->lang->navGroup, $moduleName);
        if(($navGroup == 'product' || $navGroup == 'qa') && empty($productID)) $objectType = 'product';
        if($tab == 'project' && $moduleName == 'release')                      $objectType = 'project';
        if($tab == 'feedback' && $moduleName == 'task')                        $objectType = 'execution';
        if($moduleName == 'execution' && $this->app->methodName == 'create')   $objectType = 'project';

        $flow = $this->loadModel('workflow')->getByModule($moduleName);
        if($flow && $flow->buildin == '0' && !empty($flow->belong))
        {
            if($flow->belong == 'product')   $objectType = 'product';
            if($flow->belong == 'project')   $objectType = 'project';
            if($flow->belong == 'execution') $objectType = 'execution';
        }

        if(empty($objectType)) return array();
        if($tab == 'execution' && $moduleName == 'execution' && $this->app->methodName == 'batchedit') return array();

        $objectID = (int)$this->session->{$objectType};
        if(in_array($objectType, array('feedbackProduct', 'ticketProduct'))) $objectType = 'product';
        if($objectType == 'execution')
        {
            $objectType = 'project';
            $execution  = $this->loadModel('execution')->fetchByID($objectID);
            if($execution) $objectID = $execution->project;
        }

        $object = null;
        if($objectType == 'product' && $objectID)
        {
            $object = $this->loadModel('product')->fetchById($objectID);
            if($object && $object->shadow && in_array($moduleName, $this->config->workflowgroup->modules['project']['project']))
            {
                $object     = null;
                $objectType = 'project';
                $objectID   = $this->dao->select('t1.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                    ->where('t1.product')->eq($objectID)
                    ->andWhere('t2.type')->eq('project')
                    ->fetch('project');
            }
        }
        if($objectType == 'project' && $objectID) $object = $this->loadModel('project')->fetchById($objectID);

        if(empty($object)) return array();
        return array('data' => $object, 'type' => $projectID ? 'project' : 'product');
    }

    /**
     * 根据对象ID获取对象绑定的工作流模板ID.
     * Get the workflow group ID of the object.
     *
     * @param  string $moduleName
     * @param  object $object
     * @access public
     * @return int
     */
    public function getGroupIDByDataID($module, $dataID = 0)
    {
        if(empty($dataID)) return $this->getGroupIDBySession($module);
        $flow = $this->loadModel('workflow')->getByModule($module);
        if(empty($flow)) return 0;

        $data = $this->loadModel('flow')->getDataByID($flow, $dataID);
        return $this->getGroupIDByData($module, $data);
    }

    /**
     * 更新 disabledModules 字段
     * Update disabledModules field.
     *
     * @param  string $module
     * @param  string $action  add|remove
     * @param  string $type    single|custom|all
     * @access public
     * @return void
     */
    public function updateDisabledModules($module, $action = 'add', $type = 'all')
    {
        $set       = "disabledModules = CONCAT(disabledModules, ',', '{$module}')";
        $condition = "CONCAT(',', disabledModules, ',') NOT LIKE '%,{$module},%'";
        if($action == 'remove')
        {
            $set       = "disabledModules = REPLACE(disabledModules, ',{$module}', '')";
            $condition = "CONCAT(',', disabledModules, ',') LIKE '%,{$module},%'";
        }

        $this->dao->update(TABLE_WORKFLOWGROUP)->set($set)->where($condition)
             ->beginIF($type == 'single')->andWhere('code')->ne('')->fi()
             ->beginIF($type == 'custom')->andWhere('code')->eq('')->fi()
             ->exec();
        if($action == 'add') $this->dao->update(TABLE_WORKFLOWGROUP)->set("disabledModules")->eq(",{$module}")->where("disabledModules is null")->exec();
    }

    /**
     * 追加内置标签
     * Append buildin label
     *
     * @param  array  $pairs
     * @access public
     * @return array
     */
    public function appendBuildinLabel($pairs)
    {
        $groupItems = array();
        $pinyins    = common::convert2Pinyin($pairs);
        $buildins   = $this->dao->select('id')->from(TABLE_WORKFLOWGROUP)->where('main')->eq('1')->andWhere('id')->in(array_keys($pairs))->fetchPairs('id', 'id');
        foreach($pairs as $groupID => $groupName)
        {
            $groupItems[$groupID] = array('value' => $groupID, 'text' => $groupName, 'keys' => $groupID . $groupName . zget($pinyins, $groupID, ''));
            if(isset($buildins[$groupID])) $groupItems[$groupID]['content'] = array('html' => "<div class='flex clip'>{$groupName}</div><label class='label bg-primary-50 text-primary ml-1 flex-none'>{$this->lang->workflowgroup->workflow->buildin}</label>", 'class' => 'w-full flex nowrap');
        }

        return array_values($groupItems);
    }
}
