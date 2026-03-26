<?php
/**
 * The control file of approvalflow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     approvalflow
 * @version     $Id: control.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class approvalflow extends control
{
    /**
     * Design flow.
     *
     * @param int $flowID
     * @access public
     * @return void
     */
    public function design($flowID = 0)
    {
        $this->app->loadLang('workflowcondition');
        $flow = $this->approvalflow->getByID($flowID);

        if(!empty($_POST))
        {
            if($this->post->type == 'update')
            {
                $flow->nodes = $this->post->nodes;
            }
            else
            {
                $this->approvalflow->updateNodes($flow);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
            }
        }

        $fields = $flow->workflow ? $this->loadModel('workflowfield')->getList($flow->workflow) : array();
        if($fields)
        {
            foreach($fields as $id => $field)
            {
                if($field->options) $fields[$id]->options = $this->workflowfield->getFieldOptions($field);
            }
        }

        $this->view->flow            = $flow;
        $this->view->users           = $this->loadModel('user')->getPairs('noclosed|noletter|noempty');
        $this->view->depts           = $this->loadModel('dept')->getDeptPairs();
        $this->view->roles           = $this->approvalflow->getRolePairs();
        $this->view->positions       = $this->lang->user->roleList;
        $this->view->fields          = $fields;
        $this->view->title           = $flow->name . '-' . $this->lang->approvalflow->common;
        $this->view->node            = $this->post->node;
        $this->view->conditionFields = arrayUnion($this->lang->approvalflow->conditionTypeList, $this->loadModel('workflowfield')->getFieldPairs($flow->workflow));
        $this->display();
    }

    /**
     * Browse flows.
     *
     * @param string $type
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     *
     * @access public
     * @return void
     */
    public function browse($type = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($type == 'project' and !helper::hasFeature('waterfall') and !helper::hasFeature('waterfallplus')) $type = 'workflow';

        $uri = $this->app->getURI(true);
        $this->session->set('flowList', $uri, $this->app->tab);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->type      = $type;
        $this->view->flows     = $this->approvalflow->getList($type, $orderBy, $pager);
        $this->view->title     = $this->lang->approvalflow->common;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->workflows = $this->loadModel('workflow')->getPairs('all', '', '', 'normal');
        $this->view->module    = 'approvalflow';
        $this->view->pager     = $pager;
        $this->display();
    }

    /**
     * Create flow.
     *
     * @param  string $workflow
     * @access public
     * @return void
     */
    public function create($workflow = '')
    {
        if($this->config->edition == 'biz') return $this->send(array('result' => 'success', 'load' => true));
        if($_POST)
        {
            $flowID = $this->approvalflow->create($workflow);

            if(dao::isError())
            {
                $response['result']  = 'fail';
                $response['message'] = dao::getError();

                return print $this->send($response);
            }

            $this->loadModel('action')->create('approvalflow', $flowID, 'Opened');

            if($workflow)
            {
                $workflow = $this->loadModel('workflow')->getByModule($workflow);

                $response['result']     = 'success';
                $response['load']       = true;
                $response['closeModal'] = true;

                if($workflow->approval == 'disabled') $response['callback'] = "parent.enableApproval('{$workflow->module}', $flowID);";
                return print $this->send($response);
            }

            $response['result']     = 'success';
            $response['load']       = $this->createLink('approvalflow', 'design', "id=$flowID");
            $response['closeModal'] = true;
            $response['message']    = $this->lang->saveSuccess;

            return print $this->send($response);
        }

        $workflows     = $this->loadModel('workflow')->getList('browse', 'flow', 'all');
        $workflowPairs = array();
        foreach($workflows as $flow)
        {
            if(in_array($flow->module, $this->config->workflow->buildin->noApproval)) continue;
            $workflowPairs[$flow->module] = $flow->name;
        }

        $this->view->workflow  = $workflow;
        $this->view->title     = $this->lang->approvalflow->common;
        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->workflows = $workflowPairs;

        $this->display();
    }

    /**
     * View flow.
     *
     * @param  int    $flowID
     * @access public
     * @return void
     */
    public function view($flowID)
    {
        $flow = $this->approvalflow->getByID($flowID);

        $this->view->title     = $this->lang->approvalflow->common . $this->lang->hyphen . $flow->name;
        $this->view->actions   = $this->loadModel('action')->getList('approvalflow', $flowID);
        $this->view->users     = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $this->view->flow      = $flow;
        $this->view->workflows = $this->loadModel('workflow')->getPairs('all', '', '', 'normal');

        $this->display();
    }

    /**
     * Edit flow.
     *
     * @param  int    $flowID
     * @access public
     * @return void
     */
    public function edit($flowID)
    {
        $flow = $this->approvalflow->getByID($flowID);

        if($_POST)
        {
            if(empty($flow))
            {
                if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'message' => '404 Not found'));
                return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->notFound, 'locate' => $this->createLink('approvalflow', 'browse'))));
            }

            $changes = $this->approvalflow->update($flowID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $actionID = $this->loadModel('action')->create('approvalflow', $flowID, 'Edited');
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $workflows     = $this->loadModel('workflow')->getList();
        $workflowPairs = array();
        foreach($workflows as $workflow)
        {
            if(in_array($workflow->module, $this->config->workflow->buildin->noApproval)) continue;
            $workflowPairs[$workflow->module] = $workflow->name;
        }

        $this->view->title     = $this->lang->approvalflow->common . $this->lang->hyphen . $this->lang->approvalflow->edit;
        $this->view->flow      = $flow;
        $this->view->workflows = $workflowPairs;

        $this->display();
    }

    /**
     * Delete flow.
     *
     * @param  int    $flowID
     * @access public
     * @return void
     */
    public function delete($flowID)
    {
        if($this->config->edition == 'biz') return $this->send(array('result' => 'success', 'load' => true));

        $workflow   = $this->dao->select('workflow')->from(TABLE_APPROVALFLOW)->where('id')->eq($flowID)->fetch('workflow');
        $flowObject = $this->dao->select('*')->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq($workflow)->andWhere('flow')->eq($flowID)->fetch();
        if($flowObject)
        {
            $this->send(array('load' => array('locate' => $this->createLink('approvalflow', 'browse'), 'alert' => $this->lang->approvalflow->errorList['hasWorkflow'])));
        }

        $this->dao->update(TABLE_APPROVALFLOW)->set('deleted')->eq(1)->where('id')->eq($flowID)->exec();
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success'));
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
    }

    /**
     * Approval flow role list.
     *
     * @access public
     * @return void
     */
    public function role()
    {
        $this->view->title    = $this->lang->approvalflow->role->browse;
        $this->view->roleList = $this->approvalflow->getRoleList();
        $this->view->users    = $this->loadModel('user')->getPairs('nodeleted|noclosed|noletter');
        $this->view->module   = 'approvalflow';

        $this->display();
    }

    /**
     * Create a role.
     *
     * @access public
     * @return void
     */
    public function createRole()
    {
        if($_POST)
        {
            $this->approvalflow->createRole();
            if(dao::isError())
            {
                return print($this->send(array('result' => 'fail', 'message' => dao::getError())));
            }

            return print($this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true)));
        }

        $this->view->users = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->display();
    }

    /**
     * Edit a role.
     *
     * @param  int    $roleID
     * @access public
     * @return void
     */
    public function editRole($roleID = 0)
    {
        if($_POST)
        {
            $this->approvalflow->editRole($roleID);
            if(dao::isError())
            {
                return print($this->send(array('result' => 'fail', 'message' => dao::getError())));
            }

            return print($this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true)));
        }

        $this->view->role  = $this->dao->select('*')->from(TABLE_APPROVALROLE)->where('id')->eq($roleID)->fetch();
        $this->view->users = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->display();
    }

    /**
     * Delete role.
     *
     * @param  int    $roleID
     * @param  string $confirm yes|no
     * @access public
     * @return void
     */
    public function deleteRole($roleID = 0)
    {
        $this->dao->update(TABLE_APPROVALROLE)->set('deleted')->eq('1')->where('id')->eq($roleID)->exec();
        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Ajax 获取字段控件.
     * Ajax get field control.
     *
     * @param  string $field
     * @param  string $module
     * @access public
     * @return void
     */
    public function ajaxGetFieldControl($field, $module = '')
    {
        $response = array();
        $options  = array();
        $response['control'] = 'picker';

        if($field == 'submitUsers')
        {
            $options = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
        }
        elseif($field == 'submitDepts')
        {
            $options = $this->loadModel('dept')->getDeptPairs();
        }
        elseif($field == 'submitRoles')
        {
            $options = $this->approvalflow->getRolePairs();
        }
        elseif($field == 'submitPositions')
        {
            $options = $this->lang->user->roleList;
        }
        else
        {
            // 工作流字段
            if($field == 'reviewer') $field = 'reviewedBy';
            $field = $this->loadModel('workflowfield')->getByField($module, $field);
            if($field->field == 'lastRunResult')
            {
                $this->app->loadLang('testcase');
                $field->control = 'select';
                $field->options = $this->lang->testcase->resultList;
            }

            if(strpos(',select,multi-select,radio,checkbox,', ",{$field->control},") === false)
            {
                $response['control'] = $field->control == 'date' ? 'datePicker' : ($field->control == 'datetime' ? 'datetimePicker' : 'input');
            }

            $options = $this->workflowfield->getFieldOptions($field, true);
            if($field->options == 'user')
            {
                $options = $this->loadModel('user')->getPairs('noclosed|noletter|nodeleted');
            }
            elseif($field->options == 'dept')
            {
                $options = $this->loadModel('dept')->getDeptPairs();
            }
        }

        if($response['control'] == 'picker')
        {
            foreach($options as $key => $value)
            {
                if(!$value) continue;
                $response['options'][] = array('value' => $key, 'text' => $value);
            }
        }

        return $this->send($response);
    }
}
