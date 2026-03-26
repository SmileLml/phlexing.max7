<?php
/**
 * The control file of pssp of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     pssp
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class pssp extends control
{
    public function commonAction($projectID, $from = 'project')
    {
        if($from == 'project' || $from == 'execution') $this->loadModel($from)->setMenu($projectID);
        if($from == 'execution')
        {
            $this->executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
            if(!$this->executions and $this->app->getViewType() != 'mhtml') $this->locate($this->createLink('execution', 'create'));
            $execution = $this->loadModel('execution')->getByID($projectID);
            if($execution->attribute != 'dev' && !empty($execution->attribute)) $this->locate($this->createLink('execution', 'task', "taskID=$execution->id"));
        }
    }

    /**
     * Browse pssp.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $from = 'project', $executionID = 0)
    {
        $this->loadModel('process');
        $this->commonAction($projectID, $from);

        if($this->app->tab == 'execution')
        {
            $executionID = $projectID;
            $execution   = $this->loadModel('execution')->getByID($projectID);
            $projectID   = $execution->project;

        }

        $executions = $this->loadModel('execution')->getPairs($projectID);
        $project    = $this->loadModel('project')->getByID($projectID);

        if(!$projectID)
        {
            $this->config->menuGroup['pssp'] = 'company';
            $this->lang->moduleMenus['pssp'] = $this->lang->moduleMenus['process'];
            $this->config->currentTopMenu['pssp']['browse'] = 'process.browse';
        }

        $this->view->title       = $this->lang->pssp->browse;
        $this->view->processList = $this->pssp->getProcessData($projectID, $executionID, $project->model);
        $this->view->projectID   = $projectID;
        $this->view->from        = $from;
        $this->view->executionID = $executionID;
        $this->view->executions  = $executions;
        $this->view->object      = $project;
        $this->view->model       = $project->model;
        $this->view->classify    = $project->model == 'waterfall' ? 'classify' : $project->model . 'Classify';
        $this->display();
    }

    /**
     * Update pssp.
     *
     * @param  int    $projectID
     * @param  dtring $from
     * @access public
     * @return void
     */
    public function update($projectID, $from = 'project', $executionID = 0)
    {
        $this->app->loadLang('process');
        $project = $this->loadModel('project')->getByID($projectID);

        if($from == 'project') $this->loadModel($from)->setMenu((int)$projectID);
        if($from == 'execution') $this->loadModel($from)->setMenu((int)$executionID);

        if($_POST)
        {
            if(is_array($this->post->activity))
            {
                foreach($this->post->activity as $activityID => $activity)
                {
                    if(!$activityID || !isset($activity['result'])) continue;
                    $record = $this->dao->select('*')->from(TABLE_PROGRAMACTIVITY)
                        ->where('project')->eq($projectID)
                        ->andWhere('activity')->eq($activityID)
                        ->andWhere('execution')->eq($executionID)
                        ->fetch();

                    if(!empty($record))
                    {
                        $this->dao->update(TABLE_PROGRAMACTIVITY)->data($activity)->where('id')->eq($record->id)->exec();
                    }
                    else
                    {
                        $data = new stdclass();
                        $data->activity    = $activityID;
                        $data->project     = $projectID ;
                        $data->process     = $activity['process'];
                        $data->result      = $activity['result'];
                        $data->reason      = $activity['reason'];
                        $data->execution   = $this->post->execution;
                        $data->linkedBy    = $this->app->user->account;
                        $data->createdBy   = $this->app->user->account;
                        $data->createdDate = helper::now();
                        $this->dao->insert(TABLE_PROGRAMACTIVITY)->data($data)->exec();
                    }
                }
            }

            if(is_array($this->post->output))
            {
                foreach($this->post->output as $outputID => $output)
                {
                    if(!$outputID || !isset($output['result'])) continue;
                    $record = $this->dao->select('*')->from(TABLE_PROGRAMOUTPUT)
                        ->where('project')->eq($projectID)
                        ->andWhere('output')->eq($outputID)
                        ->andWhere('execution')->eq($executionID)
                        ->fetch();

                    if(!empty($record))
                    {
                        $this->dao->update(TABLE_PROGRAMOUTPUT)->data($output)->where('id')->eq($record->id)->exec();
                        if($output['result'] == "no")
                        {
                            $this->dao->update(TABLE_AUDITPLAN)
                                ->set('result')->eq('no')
                                ->where('project')->eq($projectID)
                                ->andWhere('execution')->eq($executionID)
                                ->andWhere('objectID')->eq($outputID)
                                ->andWhere('objectType')->eq('output')
                                ->exec();
                        }
                    }
                    else
                    {
                        $data = new stdclass();
                        $data->output      = $outputID;
                        $data->project     = $projectID;
                        $data->process     = $activity['process'];
                        $data->activity    = $output['activity'];
                        $data->result      = $output['result'];
                        $data->reason      = $output['reason'];
                        $data->execution   = $this->post->execution;
                        $data->linkedBy    = $this->app->user->account;
                        $data->createdBy   = $this->app->user->account;
                        $data->createdDate = helper::now();
                        $this->dao->insert(TABLE_PROGRAMOUTPUT)->data($data)->exec();
                    }
                }
            }

            $this->loadModel('action')->create('pssp', $projectID, 'Opened', '', $executionID);
            $link = inlink('browse', "projectID=$projectID&from=$from&executionID=$executionID");
            if($this->app->tab == 'execution') $link = inlink('browse', "executionID=$executionID&from=$from");
            return $this->send(array('result' => 'success', 'message' => $this->lang->pssp->updateSucess, 'locate' => $link));
        }

        $groupedProcessList = $this->pssp->getProcessData($projectID, $executionID, $project->model);
        $executionList      = $this->loadModel('execution')->getPairs($projectID, 'sprint,stage', 'all');

        $this->view->title       = $this->lang->pssp->update;
        $this->view->processList = $groupedProcessList;
        $this->view->executionID = $executionID;
        $this->view->projectID   = $projectID;
        $this->view->model       = $project->model;
        $this->view->from        = $from;
        $this->view->executions  = $executionList;
        $this->view->classify    = $project->model == 'waterfall' ? 'classify' : $project->model . 'Classify';
        $this->view->project     = $project;

        $this->display();
    }
}
