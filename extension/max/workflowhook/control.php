<?php
/**
 * The control file of workflowhook module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowhook
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowhook extends control
{
    /**
     * Browse hooks of an action.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function browse($action)
    {
        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title      = $this->lang->workflowhook->browse;
        $this->view->fields     = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->flow       = $this->loadModel('workflow', 'flow')->getByModule($action->module);
        $this->view->actions    = $this->workflowaction->getList($action->module);
        $this->view->action     = $action;
        $this->view->modalWidth = 1100;
        $this->display();
    }

    /**
     * Create a hook.
     *
     * @param  int    $action
     * @access public
     * @return void
     */
    public function create($action)
    {
        if($_POST)
        {
            $this->workflowhook->create($action);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title        = $action->name . $this->lang->minus . $this->lang->workflowhook->create;
        $this->view->tables       = arrayUnion($this->loadModel('workflow')->getPairs($parent = 'all'), $this->lang->workflowhook->tables);
        $this->view->fields       = $this->loadModel('workflowfield')->getFieldPairs($action->module);
        $this->view->flow         = $this->workflow->getByModule($action->module);
        $this->view->datasources  = $this->workflowhook->getDatasourcePairs();
        $this->view->action       = $action;
        $this->view->modalWidth   = 800;
        $this->display();
    }

    /**
     * Edit a hook.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function edit($action, $key)
    {
        if($_POST)
        {
            $this->workflowhook->update($action, $key);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse', "action=$action")));
        }

        $action = $this->loadModel('workflowaction', 'flow')->getByID($action);

        $this->view->title        = $this->lang->workflowhook->edit;
        $this->view->tables       = arrayUnion($this->loadModel('workflow', 'flow')->getPairs($parent = 'all'), $this->lang->workflowhook->tables);
        $this->view->fields       = $this->loadModel('workflowfield', 'flow')->getFieldPairs($action->module);
        $this->view->flow         = $this->workflow->getByModule($action->module);
        $this->view->layoutFields = $this->loadModel('workflowlayout', 'flow')->getFields($action->module, $action->action);
        $this->view->hookFields   = $this->workflowhook->getTableFields($action->hooks[$key]->table);
        $this->view->datasources  = $this->workflowhook->getDatasourcePairs();
        $this->view->action       = $action;
        $this->view->key          = $key;
        $this->view->modalWidth   = 800;
        $this->display();
    }

    /**
     * Delete a hook.
     *
     * @param  int    $action
     * @param  int    $key
     * @access public
     * @return void
     */
    public function delete($action, $key)
    {
        $this->workflowhook->delete($action, $key);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }

    /**
     * Get result fields by ajax.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function ajaxGetTableFields($table)
    {
        $flow = $this->loadModel('workflow', 'flow')->getByModule($table);

        $disabledFields = '';
        if($flow and $flow->type == 'table') $disabledFields = $this->config->workflowfield->disabledFields['subTables'];//If flow is table, filter useless fields.

        $html   = '';
        $fields = $this->workflowhook->getTableFields($table);
        foreach($fields as $field => $name)
        {
            if($disabledFields and strpos(",{$disabledFields},", ",{$field},") !== false) continue;

            $html .= "<option value='$field'>$name</option>";
        }
        echo $html;
    }

    /**
     * Get number fields by ajax.
     *
     * @param  string $table
     * @access public
     * @return void
     */
    public function ajaxGetNumberFields($table)
    {
        $flow = $this->loadModel('workflow', 'flow')->getByModule($table);
        if(!$flow) die('');

        $fields = $this->loadModel('workflowfield', 'flow')->getNumberFields($flow->module, true);
        if(!$fields) die('');

        $html = "<div module='$table' class='dynamic-target'>";
        foreach($fields as $fieldCode => $target)
        {
            $target = $flow->name . '_' . $target;
            $html  .= "<a href='javascript:;' data-type='target' data-module='$flow->module' data-field='$fieldCode' data-text='$target' class='btn btn-expression'>$target</a>";
        }
        $html .= '</div>';

        die($html);
    }
}
