<?php
/**
 * The model file of workflowlabel module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlabel
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowlabelModel extends model
{
    /**
     * Get a label by id.
     *
     * @param  int    $id
     * @access public
     * @return object|bool
     */
    public function getByID($id)
    {
        $label = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('id')->eq($id)->fetch();
        if(!$label) return false;

        if(is_string($label->params))  $label->params  = json_decode($label->params, true);
        if(is_string($label->orderBy)) $label->orderBy = json_decode($label->orderBy, true);
        return $label;
    }

    /**
     * Get label list.
     *
     * @param  string $module
     * @param  bool   $decodeParams
     * @access public
     * @return array
     */
    public function getList($module, $decodeParams = true)
    {
        $labels = $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($module)->andWhere('group')->eq((int)$this->session->workflowGroupID)->orderBy('order')->fetchAll('id', false);
        if(!$decodeParams) return $labels;

        foreach($labels as $label)
        {
            $label->params = !empty($label->params) ? json_decode($label->params, true) : array();
            if(is_string($label->orderBy)) $label->orderBy = json_decode($label->orderBy, true);
        }

        return $labels;
    }

    /**
     * Get label pairs.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPairs($module)
    {
        return $this->dao->select('id, label')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($module)->andWhere('group')->eq((int)$this->session->workflowGroupID)->orderBy('order')->fetchPairs();
    }

    /**
     * Get label list by group.
     *
     * @access public
     * @return array
     */
    public function getGroupList()
    {
        return $this->dao->select('*')->from(TABLE_WORKFLOWLABEL)->where('group')->eq((int)$this->session->workflowGroupID)->orderBy('order')->fetchGroup('module');
    }

    /**
     * Create a label.
     *
     * @param  string
     * @access public
     * @return bool
     */
    public function create()
    {
        $label = fixer::input('post')
            ->add('group', (int)$this->session->workflowGroupID)
            ->add('params', array())
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->remove('fields, operators, values, values2, orderFields, orderTypes')
            ->get();

        $label->params  = $this->processParams();
        $label->orderBy = $this->processOrderBy();

        if(empty($label->params))
        {
            dao::$errors = $this->lang->workflowlabel->error->emptyParams;
            return false;
        }

        $label->params  = helper::jsonEncode($label->params);
        $label->orderBy = helper::jsonEncode($label->orderBy);

        $maxOrder = $this->dao->select('MAX(`order`) AS `order`')->from(TABLE_WORKFLOWLABEL)->where('module')->eq($label->module)->fetch('order');
        $label->order = $maxOrder + 1;

        $this->dao->insert(TABLE_WORKFLOWLABEL)->data($label)
            ->autoCheck()
            ->batchCheck($this->config->workflowlabel->require->create, 'notempty')
            ->exec();

        $id = $this->dao->lastInsertID();
        $this->dao->update(TABLE_WORKFLOWLABEL)->set('code')->eq('browse' . $id)->where('id')->eq($id)->exec();

        return $id;
    }

    /**
     * Update a label.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update($id)
    {
        $oldLabel = $this->getByID($id);
        $label = fixer::input('post')
            ->add('params', array())
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('fields, operators, values, values2, orderFields, orderTypes')
            ->get();

        $label->params  = $this->processParams();
        $label->orderBy = $this->processOrderBy();

        if(empty($label->params))
        {
            dao::$errors = $this->lang->workflowlabel->error->emptyParams;
            return false;
        }

        $label->params  = helper::jsonEncode($label->params);
        $label->orderBy = helper::jsonEncode($label->orderBy);

        $this->dao->update(TABLE_WORKFLOWLABEL)->data($label)
            ->autoCheck()
            ->batchCheck($this->config->workflowlabel->require->edit, 'notempty')
            ->where('id')->eq($id)
            ->exec();

        return commonModel::createChanges($oldLabel, $label);
    }

    /**
     * Process the post params.
     *
     * @access public
     * @return array
     */
    public function processParams()
    {
        $deleted = false;
        $params  = array();
        foreach($this->post->fields as $key => $field)
        {
            if(empty($field)) continue;

            $operator = $this->post->operators[$key];
            $value    = !empty($this->post->values[$key]) ? $this->post->values[$key] : '';
            if(is_array($value)) $value = implode(',', array_values(array_unique(array_filter($value))));

            $param = new stdclass();
            $param->field    = $field;
            $param->operator = $field == 'deleted' ? '=' : $operator;
            $param->value    = $field == 'deleted' ? '0' : $value;

            if($operator == 'between')
            {
                $value2 = !empty($this->post->values2[$key]) ? $this->post->values2[$key] : '';
                if(is_array($value2)) $value2 = implode(',', array_values(array_unique(array_filter($value2))));

                $param->value2 = $value2;
            }

            $params[] = $param;

            $deleted = ($deleted or ($field == 'deleted'));
        }

        /* Make sure the params has the condition deleted='0'. */
        if(!$deleted)
        {
            $param = new stdclass();
            $param->field    = 'deleted';
            $param->operator = '=';
            $param->value    = '0';

            array_unshift($params, $param);
        }

        return $params;
    }

    /**
     * Process the post orderBy.
     *
     * @access public
     * @return array
     */
    public function processOrderBy()
    {
        $orderBy = array();
        foreach($this->post->orderFields as $key => $field)
        {
            if(empty($field)) continue;

            $item = new stdclass();
            $item->field = $field;
            $item->type  = $this->post->orderTypes[$key];

            $orderBy[] = $item;
        }

        return $orderBy;
    }
}
