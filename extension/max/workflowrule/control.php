<?php
/**
 * The control file of workflowrule module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrule
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
class workflowrule extends control
{
    /**
     * Browse rule list.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title   = $this->lang->workflowrule->browse;
        $this->view->users   = $this->loadModel('user')->getDeptPairs();
        $this->view->rules   = $this->workflowrule->getList($orderBy, $pager);
        $this->view->orderBy = $orderBy;
        $this->view->pager   = $pager;
        $this->display();
    }

    /**
     * Check regular expression.
     *
     * @param  int    $level
     * @param  int    $message
     * @param  int    $file
     * @param  int    $line
     * @access public
     * @return void
     */
    public function checkRegex($level, $message, $file, $line)
    {
        $message = ltrim($message, 'preg_match():');
        return $this->send(array('result' => 'fail', 'message' => array('rule' => $this->lang->workflowrule->error->wrongRegex . $message)));
    }

    /**
     * Create a rule.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            if($this->post->rule)
            {
                set_error_handler(array($this, 'checkRegex'));
                @preg_match($this->post->rule, '');
            }

            $id = $this->workflowrule->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('workflowrule', $id, 'Created');

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->workflowrule->create;
        $this->display();
    }

    /**
     * Edit a rule.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id = 0)
    {
        if($_POST)
        {
            if($this->post->rule)
            {
                set_error_handler(array($this, 'checkRegex'));
                @preg_match($this->post->rule, '');
            }

            $changes = $this->workflowrule->update($id);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if(!empty($changes))
            {
                $actionID = $this->loadModel('action')->create('workflowrule', $id, 'edited');
                $this->action->logHistory($actionID, $changes);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->view->title = $this->lang->workflowrule->edit;
        $this->view->rule  = $this->workflowrule->getByID($id);
        $this->display();
    }

    /**
     * View a rule.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function view($id = 0)
    {
        $this->view->title = $this->lang->workflowrule->view;
        $this->view->rule  = $this->workflowrule->getByID($id);
        $this->display();
    }

    /**
     * Delete a rule.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function delete($id = 0)
    {
        $this->dao->delete()->from(TABLE_WORKFLOWRULE)->where('id')->eq($id)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success', 'load' => true));
    }
}
