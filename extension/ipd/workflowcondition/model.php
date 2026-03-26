<?php
/**
 * The model file of workflowcondition module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowcondition
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowconditionModel extends model
{
    /**
     * Process post data of a condition.
     *
     * @access public
     * @return array
     */
    public function processPostData()
    {
        $errors = array();

        $condition = new stdclass();
        $condition->conditionType = $this->post->conditionType;
        $condition->fields        = array();
        $condition->sql           = $this->post->sql;
        $condition->sqlResult     = $this->post->sqlResult;

        if($condition->conditionType == 'sql')
        {
            if(!$condition->sql)
            {
                $errors['sql'] = sprintf($this->lang->error->notempty, $this->lang->workflowcondition->sql);
            }
            else
            {
                $checkResult = $this->loadModel('workflowfield', 'flow')->checkSqlAndVars($condition->sql);
                if($checkResult !== true) $errors['sql'] = $checkResult;
            }
        }
        else
        {
            foreach($this->post->field as $key => $data)
            {
                if(empty($data)) continue;

                $param = $this->post->param[$key];
                if(is_array($param)) $param = implode(',', array_values(array_filter($param)));

                $field = new stdclass();
                $field->field           = $data;
                $field->operator        = $this->post->operator[$key];
                $field->param           = $param;
                $field->logicalOperator = $this->post->logicalOperator[$key];

                $condition->fields[] = $field;
            }
        }

        return array($condition, $errors);
    }

    /**
     * Create a condition.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function create($action)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $conditions  = $action->conditions;

        list($condition, $errors) = $this->processPostData();

        if($errors)
        {
            dao::$errors = $errors;
            return false;
        }

        if(!is_array($conditions)) $conditions = array();

        $conditions[] = $condition;

        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('conditions')->eq(helper::jsonEncode($conditions))
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update a condition.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function update($action, $key)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $conditions  = $action->conditions;

        list($condition, $errors) = $this->processPostData();

        if($errors)
        {
            dao::$errors = $errors;
            return false;
        }

        if(!is_array($conditions))
        {
            $conditions = array();
            $conditions[$key] = $condition;
        }
        else
        {
            $conditions[$key] = $condition;
        }

        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('conditions')->eq(helper::jsonEncode($conditions))
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }

    /**
     * Delete a condition.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function delete($action, $key)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);
        $conditions  = $action->conditions;

        if(is_array($conditions)  and isset($conditions[$key])) unset($conditions[$key]);
        if(is_object($conditions) and isset($conditions->$key))
        {
            unset($conditions->$key);
            $conditions = (array)$conditions;
        }

        /* Make sure conditions is a indexed array. */
        $conditions = array_values($conditions);

        $this->dao->update(TABLE_WORKFLOWACTION)
            ->set('conditions')->eq(helper::jsonEncode($conditions))
            ->autoCheck()
            ->where('id')->eq($action->id)
            ->exec();

        return !dao::isError();
    }
}
