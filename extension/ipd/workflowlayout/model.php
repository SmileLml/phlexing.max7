<?php
/**
 * The model file of workflowlayout module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlayout
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowlayoutModel extends model
{
    const CONDITION_MUTEX   = 2;
    const CONDITION_ENABLE  = 1;
    const CONDITION_DISABLE = 0;

    /**
     * Get list of a flow.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getList($module, $ui = 0)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWLAYOUT)
            ->where('module')->eq($module)
            ->andWhere('ui')->eq($ui)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->fetchAll('id', false);
    }

    /**
     * Get layout fields of an action.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $ui
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getFields($module, $action, $ui = 0, $groupID = 0)
    {
        $workflowAction = $this->dao->select('id')->from(TABLE_WORKFLOWACTION)
              ->where('module')->eq($module)
              ->andWhere('action')->eq($action)
              ->andWhere('group')->eq($groupID)
              ->fetch('id');

        if(!$workflowAction) $groupID = 0;

        return $this->dao->select('t1.field, t2.name')->from(TABLE_WORKFLOWLAYOUT)->alias('t1')
            ->leftJoin(TABLE_WORKFLOWFIELD)->alias('t2')->on('t1.module = t2.module AND t1.field = t2.field')
            ->where('t1.module')->eq($module)
            ->andWhere('t1.action')->eq($action)
            ->andWhere('t1.ui')->eq($ui)
            ->andWhere('t1.group')->eq($groupID)
            ->beginIF(!empty($this->config->vision))->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->orderBy('t1.order_asc')
            ->fetchPairs();
    }

    /**
     * Check the fields count show in mobile device.
     *
     * @param  string $action
     * @access public
     * @return bool
     */
    public function checkMobileShow($action)
    {
        if($action != 'browse') return true;

        $count = 0;
        foreach($this->post->show as $field => $show)
        {
            if(!$show) continue;

            if(zget($this->post->mobileShow, $field, '')) $count++;

            if($count > 5)
            {
                dao::$errors = $this->lang->workflowlayout->error->mobileShow;
                return false;
            }
        }

        return true;
    }

    /**
     * Save layout of an action.
     *
     * @param  string $module
     * @param  string $action
     * @access public
     * @return bool
     */
    public function save($module, $action, $ui = 0)
    {
        $result = $this->checkMobileShow($action);
        if(!$result) return $result;

        $this->saveLayout($module, $action, $ui);
        if(dao::isError()) return false;

        $this->saveSubTables($action, $ui);
        if(dao::isError()) return false;

        $this->savePrevModules($module, $action, $ui);

        return !dao::isError();
    }

    /**
     * Save layout.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $ui
     * @access publi, $uic
     * @return bool
     */
    public function saveLayout($module, $action, $ui = 0)
    {
        $fields       = $this->loadModel('workflowfield')->getList($module);
        $flow         = $this->loadModel('workflow')->getByModule($module);
        $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

        foreach($this->post->show as $field => $show)
        {
            $defaultValue = isset($this->post->defaultValue[$field]) ? $this->post->defaultValue[$field] : '';

            if($defaultValue)
            {
                $fieldInfo = $fields[$field];
                $fieldInfo->default = $defaultValue;
                $result = $this->workflowfield->checkDefaultValue($fieldInfo);

                if(is_array($result) && $result['result'] == 'fail' && is_array($result['message'])) dao::$errors["defaultValue$field"] = $result['message']['default'];
                if(is_array($result) && $result['result'] == 'fail' && is_string($result['message'])) dao::$errors["defaultValue$field"] = $result['message'];
            }
        }

        if(!empty(dao::$errors)) return false;

        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)
            ->where('module')->eq($module)
            ->andWhere('action')->eq($action)
            ->andWhere('ui')->eq($ui)
            ->andWhere('group')->eq((int)$this->session->workflowGroupID)
            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
            ->exec();

        $order  = 1;
        $layout = new stdclass();
        $layout->module = $module;
        $layout->action = $action;
        foreach($this->post->show as $field => $show)
        {
            if(!$show) continue;

            /* Check width validate. */
            if(isset($this->post->width[$field]) && filter_var($this->post->width[$field], FILTER_VALIDATE_INT) === false && $this->post->width[$field] != 'auto')
            {
                dao::$errors['width' . $field] = sprintf($this->lang->error->int[0], $this->lang->workflowlayout->width);
                return false;
            }

            $defaultValue = isset($this->post->defaultValue[$field]) ? $this->post->defaultValue[$field] : '';
            if(is_array($defaultValue)) $defaultValue = implode(',', array_values(array_unique(array_filter($defaultValue))));

            $summary = isset($this->post->summary[$field]) ? $this->post->summary[$field] : '';
            if(is_array($summary)) $summary = implode(',', $summary);

            $layout->field        = $field;
            $layout->order        = $order++;
            $layout->width        = (isset($this->post->width[$field]) && $this->post->width[$field] != 'auto' && $this->post->width[$field] != '') ? $this->post->width[$field] : 0;
            $layout->position     = isset($this->post->position[$field])   ? $this->post->position[$field]   : '';
            $layout->readonly     = isset($this->post->readonly[$field])   ? $this->post->readonly[$field]   : '0';
            $layout->mobileShow   = isset($this->post->mobileShow[$field]) ? $this->post->mobileShow[$field] : '0';
            $layout->summary      = $summary;
            $layout->ui           = $ui;
            $layout->group        = (int)$this->session->workflowGroupID;
            $layout->defaultValue = $defaultValue;
            $layout->layoutRules  = isset($this->post->layoutRules[$field]) ? implode(',', $this->post->layoutRules[$field]) : '';
            if(!empty($this->config->vision)) $layout->vision = $this->config->vision;
            if(!empty($flow->belong) && $field == $flow->belong)
            {
                $layout->layoutRules  = $notEmptyRule->id;
                $layout->defaultValue = '';
            }

            $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($layout)->autoCheck()->exec();
        }

        return !dao::isError();
    }

    /**
     * Save sub tables.
     *
     * @param  string $action
     * @param  int    $ui
     * @access public
     * @return bool
     */
    public function saveSubTables($action, $ui = 0)
    {
        if(!$this->post->subTables) return true;

        $groupID = (int)$this->session->workflowGroupID;
        $data = new stdclass();
        $data->action = $action;
        foreach($this->post->subTables as $subModule => $child)
        {
            $subModule = str_replace('sub_', '', $subModule);

            $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)
                ->where('module')->eq($subModule)
                ->andWhere('action')->eq($action)
                ->andWhere('ui')->eq($ui)
                ->andWhere('group')->eq($groupID)
                ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                ->exec();

            if(!isset($this->post->show['sub_' . $subModule])) continue;

            $order = 1;

            $data->module = $subModule;
            foreach($child['show'] as $field => $show)
            {
                if(!$show) continue;

                /* Check width validate. */
                if(isset($child['width'][$field]) && filter_var($child['width'][$field], FILTER_VALIDATE_INT) === false && $child['width'][$field] != 'auto')
                {
                    dao::$errors['subTablessub_' . $subModule . 'width' . $field] = sprintf($this->lang->error->int[0], $this->lang->workflowlayout->width);
                    return false;
                }

                $defaultValue = isset($child['defaultValue'][$field]) ? $child['defaultValue'][$field] : '';
                if(is_array($defaultValue)) $defaultValue = implode(',', array_values(array_unique(array_filter($defaultValue))));

                $summary = isset($child['summary'][$field]) ? $child['summary'][$field] : '';
                if(is_array($summary)) $summary = implode(',', $summary);

                $data->field        = $field;
                $data->order        = $order++;
                $data->width        = (isset($child['width'][$field]) && $child['width'][$field] != 'auto' && $child['width'][$field] != '') ? $child['width'][$field] : 0;
                $data->position     = '';
                $data->readonly     = isset($child['readonly'][$field])   ? $child['readonly'][$field]   : '0';
                $data->mobileShow   = isset($child['mobileShow'][$field]) ? $child['mobileShow'][$field] : '0';
                $data->summary      = $summary;
                $data->ui           = $ui;
                $data->group        = $groupID;
                $data->defaultValue = $defaultValue;
                $data->layoutRules  = isset($child['layoutRules'][$field]) ? implode(',', $child['layoutRules'][$field]) : '';
                if(!empty($this->config->vision)) $data->vision = $this->config->vision;

                $this->dao->insert(TABLE_WORKFLOWLAYOUT)->data($data)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Save the layout of prev modules.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $ui
     * @access public
     * @return bool
     */
    public function savePrevModules($module, $action, $ui = 0)
    {
        if(!$this->post->prevModules) return true;

        $data = new stdclass();
        $data->next   = $module;
        $data->action = $action;
        foreach($this->post->prevModules as $prevModule => $prev)
        {
            $this->dao->delete()->from(TABLE_WORKFLOWRELATIONLAYOUT)
                ->where('prev')->eq($prevModule)
                ->andWhere('next')->eq($module)
                ->andWhere('action')->eq($action)
                ->andWhere('ui')->eq($ui)
                ->exec();

            $order = 1;

            $data->prev = $prevModule;
            foreach($prev['show'] as $field => $show)
            {
                if(!$show) continue;

                $data->field = $field;
                $data->ui    = $ui;
                $data->order = $order++;

                $this->dao->insert(TABLE_WORKFLOWRELATIONLAYOUT)->data($data)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * Save blocks.
     *
     * @param  int    $module
     * @access public
     * @return void
     */
    public function saveBlocks($module, $oldBlocks)
    {
        $blocks = array();
        foreach($this->post->blockName as $key => $blockName)
        {
            if(empty($blockName)) continue;

            $block = array();
            $block['name']     = $blockName;
            $block['showName'] = isset($this->post->showName[$key]) ? $this->post->showName[$key] : '0';

            $currentKey = $this->post->key[$key];

            $block['tabs'] = array();
            if($this->post->parent)
            {
                foreach($this->post->parent as $parentKey => $tabParent)
                {
                    if($currentKey == $tabParent && !empty($this->post->tabName[$parentKey])) $block['tabs'][$parentKey] = $this->post->tabName[$parentKey];
                }
            }

            $blocks[$key] = $block;
        }

        /* Delete fields from layout when delete their block or tab. */
        foreach($oldBlocks as $oldBlockKey => $oldBlock)
        {
            if(!isset($blocks[$oldBlockKey]))
            {
                $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)
                    ->where('module')->eq($module)
                    ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                    ->andWhere('position', true)->eq("block{$oldBlockKey}")
                    ->orWhere('position')->like("block{$oldBlockKey}\_tab%")
                    ->markRight(1)
                    ->exec();
                continue;
            }

            if(!empty($oldBlock['tabs']))
            {
                foreach($oldBlock['tabs'] as $oldTabKey => $oldTab)
                {
                    if(!isset($blocks[$oldBlockKey]['tabs'][$oldTabKey]))
                    {
                        $this->dao->delete()->from(TABLE_WORKFLOWLAYOUT)
                            ->where('module')->eq($module)
                            ->beginIF(!empty($this->config->vision))->andWhere('vision')->eq($this->config->vision)->fi()
                            ->andWhere('position')->eq("block{$oldBlockKey}_tab{$oldTabKey}")
                            ->exec();
                    }
                }
            }
        }

        $this->dao->update(TABLE_WORKFLOWACTION)->set('blocks')->eq(helper::jsonEncode($blocks))->where('module')->eq($module)->andWhere('action')->eq('view')->exec();

        return !dao::isError();
    }

    /**
     * Get UI pairs.
     *
     * @param  string $module
     * @param  object $action
     * @access public
     * @return array
     */
    public function getUIPairs($module, $action)
    {
        $pairs = array(0 => $action->name);
        $pairs = arrayUnion($pairs, $this->dao->select('id,name')->from(TABLE_WORKFLOWUI)->where('module')->eq($module)->andWhere('action')->eq($action->action)->andWhere('group')->eq((int)$this->session->workflowGroupID)->orderBy('id')->fetchPairs('id', 'name'));
        return $pairs;
    }

    /**
     * Get UI by id.
     *
     * @param  int    $id
     * @access public
     * @return object
     */
    public function getUIByID($id)
    {
        $ui = $this->dao->select('*')->from(TABLE_WORKFLOWUI)->where('id')->eq($id)->fetch();
        $ui->conditions = json_decode($ui->conditions);
        return $ui;
    }

    /**
     * 获取一个动作的界面列表。
     * Get UI list.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $excludeID
     * @access public
     * @return array
     */
    public function getUIList($module, $action, $excludeID = 0)
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWUI)
            ->where('module')->eq($module)
            ->andWhere('action')->eq($action)
            ->andWhere('group')->eq((int)$this->session->workflowGroupID)
            ->beginIF($excludeID)->andWhere('id')->ne($excludeID)->fi()
            ->orderBy('id')
            ->fetchAll('id', false);
    }

    /**
     * Create UI.
     *
     * @param  string $module
     * @param  string $action
     * @access public
     * @return int|false
     */
    public function createUI($module, $action)
    {
        $ui = fixer::input('post')
            ->add('group', (int)$this->session->workflowGroupID)
            ->add('module', $module)
            ->add('action', $action)
            ->get();

        $conditions     = $this->workflowlayoutTao->buildUIConditions($ui);
        $ui->conditions = json_encode($conditions);
        if(empty($conditions))
        {
            dao::$errors['conditions'][] = $this->lang->workflowlayout->error->emptyConditions;
            return false;
        }

        if(!$this->checkUniqueConditions($module, $action, $conditions)) return false;

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($module, $action);
        if($action->name == $ui->name)
        {
            dao::$errors['name'][] = sprintf($this->lang->error->repeat, $this->lang->workflowlayout->ui->name, $ui->name);
            return false;
        }

        $this->lang->workflowui = new stdclass();
        $this->lang->workflowui->name = $this->lang->workflowlayout->ui->name;
        $this->lang->error->unique    = $this->lang->error->repeat;

        $this->dao->insert(TABLE_WORKFLOWUI)->data($ui, 'field,operator,param')->autoCheck()
            ->check('name', 'notempty')
            ->check('name', 'unique', "`module`='{$ui->module}' AND `action`='{$ui->action}'")
            ->exec();
        return $this->dao->lastInsertId();
    }

    /**
     * Update UI.
     *
     * @param  int    $uiID
     * @access public
     * @return void
     */
    public function updateUI($uiID)
    {
        $oldUI = $this->getUIByID($uiID);
        $ui    = fixer::input('post')->get();

        $conditions     = $this->workflowlayoutTao->buildUIConditions($ui);
        $ui->conditions = json_encode($conditions);
        if(empty($conditions))
        {
            dao::$errors['conditions'][] = $this->lang->workflowlayout->error->emptyConditions;
            return false;
        }

        if(!$this->checkUniqueConditions($oldUI->module, $oldUI->action, $conditions, $uiID)) return false;

        $action = $this->loadModel('workflowaction')->getByModuleAndAction($oldUI->module, $oldUI->action);
        if($action->name == $ui->name)
        {
            dao::$errors['name'][] = sprintf($this->lang->error->repeat, $this->lang->workflowlayout->ui->name, $ui->name);
            return false;
        }

        $this->lang->workflowui = new stdclass();
        $this->lang->workflowui->name = $this->lang->workflowlayout->ui->name;
        $this->lang->error->unique    = $this->lang->error->repeat;

        $this->dao->update(TABLE_WORKFLOWUI)->data($ui, 'field,operator,param')->autoCheck()
            ->check('name', 'notempty')
            ->check('name', 'unique', "`module`='{$oldUI->module}' AND `action`='{$oldUI->action}' AND `id` != {$uiID}")
            ->where('id')->eq($uiID)
            ->exec();
    }

    /**
     * 检查触发条件是否唯一
     * Check unique conditions.
     *
     * @param  string $module
     * @param  string $action
     * @param  array  $conditions
     * @param  int    $excludeID
     * @access public
     * @return bool
     */
    public function checkUniqueConditions($module, $action, $conditions, $excludeID = 0)
    {
        if(empty($conditions)) return false;

        $fields          = $this->loadModel('workflowfield')->getFieldPairs($module);
        $uiList          = $this->getUIList($module, $action, $excludeID);
        $conditions      = $this->workflowlayoutTao->groupConditionsByField($conditions);
        $otherConditions = array();
        foreach($uiList as $ui) $otherConditions[$ui->id] = $this->workflowlayoutTao->groupConditionsByField(json_decode($ui->conditions));

        $summaryResult = false; //默认该条件跟其他界面的条件冲突
        $duplicateUI   = array();
        foreach($conditions as $field => $fieldConditions)
        {
            $secondConditions = $fieldConditions;
            $fieldResult      = true;
            foreach($fieldConditions as $key => $condition)
            {
                array_shift($secondConditions);
                if(!empty($secondConditions) && $this->workflowlayoutTao->checkUICondition($condition, $secondConditions)) dao::$errors['conditions'][] = $this->lang->workflowlayout->error->contradiction; // 该条件下，相同字段多个条件，不允许相互矛盾。
                if(dao::isError()) return false;

                $fieldHasOther = false;
                $thisResult    = true;    //默认该字段没有跟其他界面的条件冲突。如果其他界面没有设置该字段，则允许保存。
                foreach($otherConditions as $ui => $otherFields)
                {
                    if(!isset($otherFields[$field])) continue;

                    $fieldHasOther = true;
                    if($this->workflowlayoutTao->checkUICondition($condition, $otherFields[$field]) == workflowlayoutModel::CONDITION_DISABLE) //检查该字段是否与该界面的条件冲突，如果冲突，则不允许保存，并记录冲突界面字段。
                    {
                        $thisResult = false;
                        $duplicateUI["{$ui}_{$field}"] = sprintf($this->lang->workflowlayout->tips->duplicateUI, "#{$ui}:" . $uiList[$ui]->name, zget($fields, $field));
                    }
                }
                if(!$thisResult) $fieldResult = false;  //如果该字段跟其他界面的条件冲突，则不允许保存。
            }
            if($fieldResult) $summaryResult = true;  //如果至少一个字段没有跟其他界面的条件冲突，则允许保存
        }
        if(!$summaryResult && $duplicateUI) dao::$errors['conditions'][] = sprintf($this->lang->workflowlayout->error->duplicateConditions, implode(', ', $duplicateUI));
        return $summaryResult;
    }

    /**
     * 获取当前数据所匹配的界面
     * Match UI by data.
     *
     * @param  string $module
     * @param  string $action
     * @param  object $data
     * @access public
     * @return int
     */
    public function getUIByData($module, $action, $data)
    {
        if(empty($data)) return 0;
        if(!is_object($data)) return 0;

        $allConditions = $this->workflowlayoutTao->getAllGroupedConditions($module, $action);
        foreach($allConditions as $uiID => $uiConditions)
        {
            if($this->workflowlayoutTao->isMatchConditions($data, $uiConditions)) return $uiID;
        }
        return 0;
    }

    /**
     * 根据对象ID获取界面
     * Get UI by data id.
     *
     * @param  string $module
     * @param  string $action
     * @param  int    $dataID
     * @access public
     * @return int
     */
    public function getUIByDataID($module, $action, $dataID)
    {
        if(empty($dataID)) return 0;
        $flow = $this->loadModel('workflow')->getByModule($module);
        if(empty($flow)) return 0;

        $data = $this->loadModel('flow')->getDataByID($flow, $dataID);
        return $this->getUIByData($module, $action, $data);
    }
}
