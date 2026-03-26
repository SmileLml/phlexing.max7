<?php
/**
 * The model file of auditplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     auditplan
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class auditplanModel extends model
{
    /**
     * Create a auditplan.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function create($projectID = 0)
    {
        $data = fixer::input('post')
            ->setDefault('checkDate', null)
            ->add('status', 'wait')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->remove('uid, comment')
            ->get();

        $data->processType = $this->dao->select('type')->from(TABLE_PROCESS)->where('id')->eq($data->process)->fetch('type');
        if($data->output)
        {
            $data->objectType = 'zoutput';
            $data->objectID   = $data->output;
        }
        else
        {
            $data->objectType = 'activity';
            $data->objectID   = $data->activity;
        }

        unset($data->output);
        unset($data->activity);

        $this->dao->insert(TABLE_AUDITPLAN)->data($data)->autoCheck()->batchCheck('objectID', 'notempty')->exec();
        $auditplanID = $this->dao->lastInsertID();
        return $auditplanID;
    }

    /**
     * Batch create auditplan.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function batchCreate($projectID = 0)
    {
        foreach($_POST['process'] as $i => $value)
        {
            if($_POST['process'][$i]    != 'ditto') $process    = $_POST['process'][$i];
            if($_POST['execution'][$i]  != 'ditto') $execution  = $_POST['execution'][$i];
            if($_POST['activity'][$i]   != 'ditto') $activity   = $_POST['activity'][$i];
            if($_POST['output'][$i]     != 'ditto') $output     = $_POST['output'][$i];
            if($_POST['assignedTo'][$i] != 'ditto') $assignedTo = $_POST['assignedTo'][$i];

            $_POST['process'][$i]    = $process;
            $_POST['execution'][$i]  = $execution;
            $_POST['activity'][$i]   = $activity;
            $_POST['output'][$i]     = $output;
            $_POST['assignedTo'][$i] = $assignedTo;

            if(!$_POST['activity'][$i] && !$_POST['output'][$i]) continue;
            $data = new stdclass();
            $data->processType = $this->dao->select('type')->from(TABLE_PROCESS)->where('id')->eq($_POST['process'][$i])->fetch('type');
            $data->process     = $_POST['process'][$i];
            $data->execution   = $_POST['execution'][$i];
            $data->status      = 'wait';
            $data->project     = $projectID;
            $data->checkDate   = !empty($_POST['checkDate'][$i]) ? $_POST['checkDate'][$i] : null;
            $data->dateType    = 1;
            $data->assignedTo  = $_POST['assignedTo'][$i];
            $data->createdBy   = $this->app->user->account;
            $data->createdDate = helper::today();
            if($_POST['output'][$i])
            {
                $data->objectType = 'zoutput';
                $data->objectID   = $_POST['output'][$i];
            }
            else
            {
                $data->objectType = 'activity';
                $data->objectID   = $_POST['activity'][$i];
            }

            $this->dao->insert(TABLE_AUDITPLAN)->data($data)->autoCheck()->exec();
            $auditplanID = $this->dao->lastInsertID();
            $this->loadModel('action')->create('auditplan', $auditplanID, 'Opened');
        }

        return !dao::isError();
    }

    /**
     * Update a auditplan.
     *
     * @param  int    $auditplanID
     * @access public
     * @return array
     */
    public function update($auditplanID)
    {
        $oldAuditplan = $this->getByID($auditplanID);
        $now  = helper::now();
        $data = fixer::input('post')
            ->setDefault('checkDate', '0000-00-00')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->remove('uid, comment')
            ->get();
        if($oldAuditplan->assignedTo != $data->assignedTo)
        {
            $data->assignedBy   = $this->app->user->account;
            $data->assignedDate = $now;
        }

        $data->processType = $this->dao->select('type')->from(TABLE_PROCESS)->where('id')->eq($data->process)->fetch('type');
        if($data->output)
        {
            $data->objectType = 'zoutput';
            $data->objectID   = $data->output;
        }
        else
        {
            $data->objectType = 'activity';
            $data->objectID   = $data->activity;
        }

        unset($data->output);
        unset($data->activity);

        //if($data->dateType != 3)
        //{
        //    unset($data->config);
        //}
        //else
        //{
        //    $data->config['begin'] = helper::today();
        //    if($data->config['type'] == 'day')
        //    {
        //        unset($data->config['week']);
        //        unset($data->config['month']);
        //    }
        //    if($data->config['type'] == 'week')
        //    {
        //        unset($data->config['day']);
        //        unset($data->config['month']);
        //        $data->config['week'] = join(',', $data->config['week']);
        //    }
        //    if($data->config['type'] == 'month')
        //    {
        //        unset($data->config['day']);
        //        unset($data->config['week']);
        //        $data->config['month'] = join(',', $data->config['month']);
        //    }
        //    $data->config = json_encode($data->config);
        //}

        $this->dao->update(TABLE_AUDITPLAN)->data($data)->where('id')->eq($auditplanID)->autoCheck()->batchCheck('objectID', 'notempty')->exec();
        //if($data->dateType == 3) $this->createCheckByCycle(array($auditplanID => $data));
        return common::createChanges($oldAuditplan, $data);
    }

    /**
     * Batch update.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchUpdate($projectID = 0)
    {
        $data = fixer::input('post')->get();
        $now  = helper::now();

        $processTypeList = $this->dao->select('id,type')->from(TABLE_PROCESS)->where('id')->in($data->process)->fetchPairs('id', 'type');
        $oldAuditPlans   = $this->dao->select('*')->from(TABLE_AUDITPLAN)->where('id')->in(array_keys($data->process))->fetchAll('id');

        $auditplans = array();
        foreach($data->process as $auditplanID => $processID)
        {
            $oldAuditPlan = $oldAuditPlans[$auditplanID];

            $auditplan = new stdclass();
            $auditplan->editedBy    = $this->app->user->account;
            $auditplan->editedDate  = $now;
            $auditplan->process     = $processID;
            $auditplan->processType = zget($processTypeList, $processID, '');
            $auditplan->execution   = $data->execution[$auditplanID];
            $auditplan->project     = $projectID;
            $auditplan->checkDate   = $data->checkDate[$auditplanID];
            $auditplan->assignedTo  = $data->assignedTo[$auditplanID];
            if($oldAuditPlan->assignedTo != $auditplan->assignedTo)
            {
                $auditplan->assignedBy   = $this->app->user->account;
                $auditplan->assignedDate = $now;
            }

            if($data->output[$auditplanID])
            {
                $auditplan->objectType = 'zoutput';
                $auditplan->objectID   = $data->output[$auditplanID];
            }
            else
            {
                $auditplan->objectType = 'activity';
                $auditplan->objectID   = $data->activity[$auditplanID];
            }

            if(empty($auditplan->objectID)) dao::$errors['message'][] = sprintf($this->lang->auditplan->errorBatchNotEmpyt, $auditplanID, $this->lang->auditplan->objectID);

            $auditplans[$auditplanID] = $auditplan;
        }

        if(dao::isError())
        {
            dao::$errors['message'] = join("<br />", dao::$errors['message']);
            return false;
        }

        foreach($auditplans as $auditplanID => $auditplan)
        {
            $changes = common::createChanges($oldAuditPlans[$auditplanID], $auditplan);
            if($changes)
            {
                $this->dao->update(TABLE_AUDITPLAN)->data($auditplan)->where('id')->eq($auditplanID)->exec();
                $actionID = $this->loadModel('action')->create('auditplan', $auditplanID, 'Edited');
                $this->action->logHistory($actionID, $changes);
            }
        }

        return !dao::isError();
    }

    /**
     * Check auditplan.
     *
     * @param  int    $auditplanID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function check($auditplanID, $projectID)
    {
        $mode     = $_POST['mode'];
        $hasDraft = $_POST['hasDraft'];
        $today    = helper::today();
        foreach($_POST['result'] as $listID => $result)
        {
            if($result == 'fail' and $mode == 'normal')
            {
                if(!$_POST['comment'][$listID])  dao::$errors["comment$listID"][]  = sprintf($this->lang->error->notempty, $this->lang->auditplan->comment);
                if(!$_POST['severity'][$listID]) dao::$errors["severity$listID"][] = sprintf($this->lang->error->notempty, $this->lang->auditplan->severity);
            }
        }

        if(dao::isError()) return false;

        foreach($_POST['result'] as $listID => $result)
        {
            $data = new stdclass();
            $data->auditplan   = $auditplanID;
            $data->listID      = $listID;
            $data->result      = $result;
            $data->status      = $mode;
            $data->severity    = $_POST['severity'][$listID];
            $data->comment     = $_POST['comment'][$listID];
            $data->checkedBy   = $this->app->user->account;
            $data->checkedDate = $today;

            if($hasDraft)
            {
                unset($data->auditplan);
                unset($data->listID);
                $this->dao->update(TABLE_AUDITRESULT)->data($data)
                    ->where('listID')->eq($listID)
                    ->andWhere('auditplan')->eq($auditplanID)
                    ->exec();
            }
            else
            {
                $this->dao->insert(TABLE_AUDITRESULT)->data($data)->exec();
            }

            if($result == 'fail' and $mode == 'normal')
            {
                /* Create NC*/
                $inserNc = new stdClass();
                $inserNc->project     = $projectID;
                $inserNc->auditplan   = $auditplanID;
                $inserNc->listID      = $listID;
                $inserNc->status      = 'active';
                $inserNc->severity    = $_POST['severity'][$listID];
                $inserNc->title       = $_POST['comment'][$listID];
                $inserNc->createdBy   = $this->app->user->account;
                $inserNc->createdDate = helper::now();

                $ncID = $this->createNc($inserNc);
            }
        }

        $status = $mode == 'draft' ? 'checking' : 'checked';
        $audit = new stdclass();
        $audit->status = $status;
        if($status == 'checked')
        {
            $audit->realCheckDate = helper::today();
            $audit->checkedBy     = $this->app->user->account;
        }
        $this->dao->update(TABLE_AUDITPLAN)->data($audit)->where('id')->eq($auditplanID)->exec();

        return !dao::isError();
    }

    /**
     * Assign an auditplan.
     *
     * @param  int    $auditplanID
     * @access public
     * @return array|bool
     */
    public function assign($auditplanID)
    {
        $oldAuditplan = $this->getByID($auditplanID);

        $audit = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setDefault('assignedDate', helper::now())
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_AUDITPLAN)->data($audit)->autoCheck()->where('id')->eq((int)$auditplanID)->exec();

        if(!dao::isError()) return common::createChanges($oldAuditplan, $audit);
        return false;
    }

    /**
     * Batch check auditplan.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function batchCheck($projectID)
    {
        $mode  = $_POST['mode'];
        $today = helper::today();

        foreach($_POST['result'] as $auditplanID => $lists)
        {
            foreach($lists as $listID => $result)
            {
                if($result == 'fail' and $mode == 'normal')
                {
                    if(!$_POST['comment'][$auditplanID][$listID])  dao::$errors["comment$listID"][]  = sprintf($this->lang->error->notempty, $this->lang->auditplan->comment);
                    if(!$_POST['severity'][$auditplanID][$listID]) dao::$errors["severity$listID"][] = sprintf($this->lang->error->notempty, $this->lang->auditplan->severity);
                }
            }
        }

        if(dao::isError()) return false;

        foreach($_POST['result'] as $auditplanID => $lists)
        {
            $hasDraft = $_POST['hasDraft'][$auditplanID];
            foreach($lists as $listID => $result)
            {
                $data = new stdclass();
                $data->auditplan   = $auditplanID;
                $data->listID      = $listID;
                $data->result      = $result;
                $data->status      = $mode;
                $data->severity    = $_POST['severity'][$auditplanID][$listID];
                $data->comment     = $_POST['comment'][$auditplanID][$listID];
                $data->checkedBy   = $this->app->user->account;
                $data->checkedDate = $today;

                if($hasDraft)
                {
                    unset($data->auditplan);
                    unset($data->listID);
                    $this->dao->update(TABLE_AUDITRESULT)->data($data)
                        ->where('listID')->eq($listID)
                        ->andWhere('auditplan')->eq($auditplanID)
                        ->exec();
                }
                else
                {
                    $this->dao->insert(TABLE_AUDITRESULT)->data($data)->exec();
                }

                if($result == 'fail' and $mode == 'normal')
                {
                    /* Create NC*/
                    $inserNc = new stdClass();
                    $inserNc->project     = $projectID;
                    $inserNc->auditplan   = $auditplanID;
                    $inserNc->listID      = $listID;
                    $inserNc->status      = 'active';
                    $inserNc->severity    = $_POST['severity'][$auditplanID][$listID];
                    $inserNc->title       = $_POST['comment'][$auditplanID][$listID];
                    $inserNc->createdBy   = $this->app->user->account;
                    $inserNc->createdDate = helper::today();

                    $ncID = $this->createNc($inserNc);
                }
            }

            $status = $mode == 'draft' ? 'checking' : 'checked';
            $audit = new stdclass();
            $audit->status = $status;
            if($status == 'checked') $audit->realCheckDate = helper::today();
            $this->dao->update(TABLE_AUDITPLAN)->data($audit)->where('id')->eq($auditplanID)->exec();
        }
        return !dao::isError();
    }

    /*
     *  Create check by cycle.
     *
     *  @param array $dataList
     *  @access public
     *  @return void
     */
    public function createCheckByCycle($dataList)
    {
        $this->loadModel('action');
        $today = helper::today();
        $now   = helper::now();
        $cycleList = $this->dao->select('*')->from(TABLE_AUDITPLAN)->where('dateType')->eq(3)->andWhere('id')->in(array_keys($dataList))->orderBy('createdDate')->fetchAll('id');
        foreach($dataList as $dataID => $data)
        {
            $data->config = json_decode($data->config);
            $begin        = isset($data->config->begin) ? $data->config->begin : '';
            $end          = isset($data->config->end) ? $data->config->end : '';
            $beforeDays   = (int)$data->config->beforeDays;
            if(!empty($beforeDays) && $beforeDays > 0) $begin = date('Y-m-d', strtotime("$begin -{$beforeDays} days"));
            if($today < $begin or (!empty($end) && $today > $end)) continue;

            $newData = new stdclass();
            $newData->checkedBy   = isset($data->checkedBy) ? $data->checkedBy : '';
            $newData->createdBy   = isset($data->createdBy) ? $data->createdBy : '';
            $newData->audit       = isset($data->audit) ? $data->audit : '';
            $newData->project     = $data->project;
            $newData->createdDate = $now;

            $start  = strtotime($begin);
            $finish = strtotime("$today +{$beforeDays} days");
            foreach(range($start, $finish, 86400) as $today)
            {
                $today     = date('Y-m-d', $today);
                $date      = '';

                if($data->config->type == 'day')
                {
                    $day = (int)$data->config->day;
                    if($day <= 0) continue;

                    $date = date('Y-m-d', strtotime("{$today} +" . ($day - 1) . " days"));
                }
                elseif($data->config->type == 'week')
                {
                    $week = date('w', strtotime($today));
                    if(strpos(",{$data->config->week},", ",{$week},") !== false) $date = $today;
                }
                elseif($data->config->type == 'month')
                {
                    $day = date('j', strtotime($today));
                    if(strpos(",{$data->config->month},", ",{$day},") !== false) $date = $today;
                }

                if(!$date)                         continue;
                if($date < $data->config->begin)   continue;
                if($date < date('Y-m-d'))          continue;
                if($date > date('Y-m-d', $finish)) continue;
                if(!empty($end) && $date > $end)   continue;

                $this->dao->insert(TABLE_AUDITPLAN)->data($newData)->exec();
                $this->action->create($module, $this->dao->lastInsertID(), 'opened', '', '', $newData->createdBy);
            }
        }
    }

    /**
     * Print cell data.
     *
     * @param  object $col
     * @param  object $auditplan
     * @param  array  $users
     * @param  string $process
     * @param  string $processType
     * @param  string $objectType
     * @param  array  $execution
     * @param  int    $projectID
     * @param  string $from
     * @param  string $mode
     * @param  bool   $checkBox
     * @access public
     * @return void
     */
    public function printCell($col, $auditplan, $users, $process, $processType, $objectType, $execution, $projectID, $from, $mode = 'datatable', $checkBox = true)
    {
        $account = $this->app->user->account;
        $id      = $col->id;

        if($col->show)
        {
            $class   = "c-$id";
            $title   = '';
            $delayed = '';

            if($id == 'id') $class .= ' cell-id';
            if($id == 'assignedTo')
            {
                $class .= ' has-btn text-left';
                if($auditplan->assignedTo == $account) $class .= ' red';
            }
            if($id == 'status')      $class .= " status-{$auditplan->status}";
            if($id == 'process')     $title  = $process;
            if($id == 'processType') $title  = $processType;
            if($id == 'objectID')    $title  = $objectType;

            if((helper::diffDate(helper::today(), $auditplan->checkDate) > -1) and !helper::isZeroDate($auditplan->checkDate)) $delayed = 'delayed';
            if($id == 'checkDate') $class .= " text-center $delayed";

            echo "<td class='" . $class . "' title='". $title ."'>";
            switch($id)
            {
                case 'id':
                    echo $checkBox ? html::checkbox('auditIDList', array($auditplan->id => '')) . sprintf('%03d', $auditplan->id) : sprintf('%03d', $auditplan->id);
                    break;
                case 'process':
                    echo $process;
                    break;
                case 'processType':
                    echo $processType;
                    break;
                case 'objectID':
                    echo $objectType;
                    break;
                case 'execution':
                    echo $auditplan->execution ? html::a(helper::createLink('execution', 'task', "executionID=$auditplan->execution"), $execution, '', "title={$execution}") : '';
                    break;
                case 'objectType':
                    echo zget($this->lang->auditplan, $auditplan->objectType, '');
                    break;
                case 'status':
                    echo zget($this->lang->auditplan->statusList, $auditplan->status);
                    break;
                case 'createdBy':
                    echo zget($users, $auditplan->createdBy);
                    break;
                case 'assignedTo':
                    $this->printAssignedHtml($auditplan, $users);
                    break;
                case 'checkDate':
                    echo helper::isZeroDate($auditplan->checkDate) ? '' : '<span>' . $auditplan->checkDate . '</span>';
                    break;
                case 'realCheckDate':
                    echo helper::isZeroDate($auditplan->realCheckDate) ? '' : $auditplan->realCheckDate;
                    break;
                case 'nc':
                    echo $auditplan->ncs ? $auditplan->ncs : '';
                    break;
                case 'actions':
                    if($auditplan->status == 'wait' || $auditplan->status == 'checking')
                      {
                          common::printIcon('auditplan', 'check', "auditplanID=$auditplan->id&projectID=$projectID", $auditplan, 'list', 'confirm', '', 'iframe', true, '', $this->lang->auditplan->check);
                      }
                      else
                      {
                          common::printIcon('auditplan', 'check', "auditplanID=$auditplan->id&projectID=$projectID", $auditplan, 'list', 'confirm', '', 'disabled');
                      }

                      common::printIcon('auditplan', 'result', "auditplanID=$auditplan->id", $auditplan, 'list', 'list-alt', '', 'iframe', true);
                      if($auditplan->ncs)
                      {
                          common::printIcon('auditplan', 'nc', "auditplanID=$auditplan->id", $auditplan, 'list', 'bug', '', 'iframe', true);
                      }
                      else
                      {
                          common::printIcon('auditplan', 'nc', "auditplanID=$auditplan->id", $auditplan, 'list', 'bug', '', 'disabled', true);
                      }

                      common::printIcon('auditplan', 'edit', "auditplanID=$auditplan->id" . "&from=$from", $auditplan, 'list');
                      common::printIcon('auditplan', 'delete', "auditplanID=$auditplan->id", $auditplan, 'list', 'trash', 'hiddenwin');
                    break;
            }
            echo '</td>';
        }
    }

    /**
     * Get auditplan list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $pager
     * @param  int    $processID
     * @access public
     * @return array
     */
    public function getList($projectID = 0, $browseType = 'all', $param = 0, $orderBy = 'id_desc', $pager = null, $processID = 0)
    {
        $activities = $this->loadModel('pssp')->getActivityPairs($projectID);
        $outputs    = $this->pssp->getOutputPairs($projectID);
        $project    = $this->loadModel('project')->fetchByID($projectID);

        $browseType = strtolower($browseType);
        $existStatus = array('wait', 'checked', 'checking');
        $auditplans = $this->dao->select('t1.*')->from(TABLE_AUDITPLAN)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.result')->ne('no')
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('t1.execution')->eq($projectID)->fi()
            ->beginIF($processID)->andWhere('t1.process')->eq($processID)->fi()
            ->beginIF(in_array($browseType, $existStatus))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'execution')->andWhere('t1.execution')->eq($param)->fi()
            ->beginIF($browseType == 'assignto')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'delay')->andWhere('t1.status')->eq('wait')->andWhere('checkDate')->lt(helper::today())->fi()
            ->beginIF($browseType == 'mychecking')->andWhere('t1.status')->in('wait,checking')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'today')->andWhere('t1.checkDate')->eq(helper::today())->fi()
            ->beginIF($browseType == 'yesterday')->andWhere('t1.checkDate')->eq(date("Y-m-d",strtotime("-1 day")))->fi()
            ->beginIF($browseType == 'lastmonth')->andWhere('t1.checkDate')->between(date('Y-m-01',strtotime('-1 month')), date("Y-m-d", strtotime(-date('d').'day')))->fi()
            ->beginIF($browseType == 'month')->andWhere('t1.checkDate')->between(date('Y-m-01'), date('Y-m-d'))->fi()
            ->beginIF($browseType == 'nextmonth')->andWhere('t1.checkDate')->between(date('Y-m-01',strtotime('next month')), date('Y-m-d',strtotime(date('Y-m-1',strtotime('next month')).'+1 month -1 day')))->fi()
            ->andWhere('((t1.objectID')->in(array_keys($activities))->andWhere('t1.objectType')->eq('activity')->markRight(1)
            ->orWhere('(t1.objectID')->in(array_keys($outputs))->andWhere('t1.objectType')->eq('zoutput')->markRight(2)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $ncs       = $this->dao->select('*')->from(TABLE_NC)->where('deleted')->eq(0)->fetchGroup('auditplan');
        $processes = $this->pssp->getProcesses($projectID);
        foreach($auditplans as $id => $auditplan)
        {
            $auditplan->audit = zget($processes, $auditplan->process);
            $auditplan->ncs   = zget($ncs, $auditplan->id, array());
        }

        return $auditplans;
    }

    /**
     * Get auditplan by search.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @param  int    $processID
     * @access public
     * @return array
     */
    public function getBySearch($projectID = 0, $queryID = 0, $orderBy = 'id_desc', $pager = null, $processID = 0)
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        $auditplanQuery = 'auditplanQuery';
        $auditplanForm  = 'auditplanForm';

        if($query)
        {
            $this->session->set($auditplanQuery, $query->sql);
            $this->session->set($auditplanForm,  $query->form);
        }

        if($this->session->auditplanQuery == false) $this->session->set($auditplanQuery, ' 1 = 1');
        $auditplanQuery = $this->session->auditplanQuery;

        $project    = $this->loadModel('project')->fetchByID($projectID);
        $auditplans = $this->dao->select('*')->from(TABLE_AUDITPLAN)
            ->where($auditplanQuery)
            ->andWhere('deleted')->eq(0)
            ->andWhere('result')->ne('no')
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(($this->app->tab == 'execution') && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->beginIF($processID)->andWhere('process')->eq($processID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $processes = $this->loadModel('pssp')->getProcesses($projectID);
        foreach($auditplans as $id => $auditplan) $auditplan->audit = zget($processes, $auditplan->process);

        return $auditplans;
    }

    /**
     * Get auditplans pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairs($projectID)
    {
        $auditCommon = $this->lang->auditplan->common;
        return $this->dao->select("id, CONCAT('$auditCommon', ' - ', id)")->from(TABLE_AUDITPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($this->app->tab == 'project'   && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->fetchPairs();
    }

    /**
     * Get by id.
     *
     * @param  int    $auditplanID
     * @access public
     * @return object
     */
    public function getByID($auditplanID)
    {
        return $this->dao->findByID($auditplanID)->from(TABLE_AUDITPLAN)->fetch();
    }

    /**
     * Get by id list.
     *
     * @param  array    $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        if(empty($idList)) return array();
        return $this->dao->select('*')->from(TABLE_AUDITPLAN)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get object name.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return string
     */
    public function getObjectName($objectType, $objectID)
    {
        $table = $objectType == 'zoutput' ? TABLE_ZOUTPUT : TABLE_ACTIVITY;
        return $this->dao->select('name')->from($table)->where('id')->eq($objectID)->fetch('name');
    }

    /**
     * Get list name.
     *
     * @param  int    $listID
     * @access public
     * @return string
     */
    public function getListName($listID)
    {
        return $this->dao->findByID($listID)->from(TABLE_AUDITCL)->fetch('title');
    }

    /**
     * Get result list.
     *
     * @param  int    $auditplanID
     * @access public
     * @return array
     */
    public function getResults($auditplanID, $status = '')
    {
        return $this->dao->select('*,comment')->from(TABLE_AUDITRESULT)
            ->where('auditplan')->eq($auditplanID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF($status == 'draft')->andWhere('checkedBy')->eq($this->app->user->account)->fi()
            ->fetchAll('listID');
    }

    /**
     * Get nc
     *
     * @param  int    $auditplanID
     * @access public
     * @return array
     */
    public function getNC($auditplanID)
    {
        return $this->dao->select('*')->from(TABLE_NC)
            ->where('auditplan')->eq($auditplanID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get nc count.
     *
     * @param  int    $auditplanID
     * @access public
     * @return int
     */
    public function getNcCount($auditplanID)
    {
        return $this->dao->select('COUNT(1) AS count')->from(TABLE_NC)
            ->where('auditplan')->eq($auditplanID)
            ->andWhere('deleted')->eq(0)
            ->fetch('count');
    }

    /**
     * Get check list.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getCheckList($objectType, $objectID)
    {
        return $this->dao->select('*')->from(TABLE_AUDITCL)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * Get check list pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getCheckListPairs($projectID)
    {
        return $this->dao->select('t1.id, t1.title')->from(TABLE_AUDITCL)->alias('t1')
            ->leftJoin(TABLE_AUDITPLAN)->alias('t2')
            ->on('t1.objectID=t2.objectID')
            ->where('t2.project')->eq($projectID)
            ->fetchPairs();
    }

    /**
     * Get check list by id list
     *
     * @param  array    $auditIDList
     * @access public
     * @return array
     */
    public function getCheckListByList($auditIDList)
    {
        $auditplans = $this->dao->select('*')->from(TABLE_AUDITPLAN)
            ->where('id')->in($auditIDList)
            ->andWhere('status')->ne('checked')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
        $checkList = array();

        foreach($auditplans as $auditplan)
        {
            $checkLists = $this->getCheckList($auditplan->objectType, $auditplan->objectID);
            if(!empty($checkLists)) $checkList[$auditplan->id] = $this->getCheckList($auditplan->objectType, $auditplan->objectID);
        }
        return $checkList;
    }

    /**
     * Create NC
     *
     * @param  object    $data
     * @access public
     * @return int
     */
    public function createNc($data)
    {
        $this->app->loadConfig('nc');
        $this->dao->insert(TABLE_NC)->data($data)->batchCheck($this->config->nc->create->requiredFields, 'notempty')->exec();

        if(dao::isError()) return false;

        $ncID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('nc', $ncID, 'Opened');

        return $ncID;
    }

    /**
     * Print assignedTo html.
     *
     * @param  array  $auditplan
     * @param  array  $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($auditplan, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $auditplan->assignedTo);

        if(empty($auditplan->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->auditplan->noAssigned;
        }
        if($auditplan->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($auditplan->assignedTo) and $auditplan->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $auditplan->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('auditplan', 'assignTo', "auditplanID=$auditplan->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $auditplan->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('auditplan', 'assignTo', $auditplan) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Get auditplan comment.
     *
     * @param  object  $auditplan
     * @access public
     * @return object
     */
    public function getComment($auditplan)
    {
        if(empty($auditplan->id)) return false;

        $auditplan->comment = $this->dao->select('comment')->from(TABLE_ACTION)
            ->where('objectType')->eq('auditplan')
            ->andWhere('objectID')->eq($auditplan->id)
            ->andWhere('action')->in('edited,opened')
            ->orderBy('id_desc')->fetch('comment');

        $auditplan = $this->loadModel('file')->replaceImgURL($auditplan, 'comment');

        return $auditplan;
    }

    /**
     * Build search form.
     *
     * @param  string $projectModel
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($projectModel, $projectID, $queryID, $actionURL)
    {
        $classify = $projectModel == 'waterfall' ? 'classify' : $projectModel . 'Classify';
        $this->app->loadLang('process');

        unset($this->lang->auditplan->statusList['all']);

        $this->config->auditplan->search['queryID']   = $queryID;
        $this->config->auditplan->search['actionURL'] = $actionURL;
        $this->config->auditplan->search['params']['status']['values']      = arrayUnion(array('' => ''), $this->lang->auditplan->statusList);
        $this->config->auditplan->search['params']['process']['values']     = arrayUnion(array('' => ''), $this->loadModel('auditcl')->getProcessList(false, 0, $projectModel));
        $this->config->auditplan->search['params']['objectID']['values']    = arrayUnion(array('' => ''), $this->getActivityOutputs($projectModel));
        $this->config->auditplan->search['params']['objectType']['values']  = arrayUnion(array('' => ''), $this->config->auditplan->objectTypeList);
        $this->config->auditplan->search['params']['processType']['values'] = $this->lang->process->$classify;

        $project = $this->loadModel('project')->fetchByID($projectID);
        if(empty($project->multiple)) unset($this->config->auditplan->search['fields']['execution'], $this->config->auditplan->search['params']['execution']);
        if(!empty($project->multiple)) $this->config->auditplan->search['params']['execution']['values'] = arrayUnion(array('' => ''), $this->loadModel('execution')->fetchPairs($projectID, 'all', false));

        $this->loadModel('search')->setSearchParams($this->config->auditplan->search);
    }

    /**
     * Get activityOutputs.
     *
     * @param  string $model
     * @access public
     * @return array
     */
    public function getActivityOutputs($model)
    {
        return $this->dao->select("t1.id, CONCAT(t2.name, '--', t1.name) as output")->from(TABLE_ZOUTPUT)->alias('t1')
            ->leftJoin(TABLE_ACTIVITY)->alias('t2')
            ->on('t1.activity=t2.id')
            ->leftJoin(TABLE_PROCESS)->alias('t3')
            ->on('t2.process=t3.id')
            ->leftJoin(TABLE_PROGRAMACTIVITY)->alias('t4')
            ->on('t1.activity=t4.activity')
            ->where('t3.model')->eq($model)
            ->andWhere('t4.result')->eq('yes')
            ->fetchPairs('id', 'output');
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object  $auditplan
     * @param  string  $action
     *
     * @access public
     * @return bool
     */
    public static function isClickable($auditplan, $action)
    {
        $action = strtolower($action);

        if($action == 'check')  return $auditplan->status == 'wait' || $auditplan->status == 'checking';
        if($action == 'nc')     return $auditplan->ncs;
        if($action == 'delete') return $auditplan->deleted == '0';

        return true;
    }
}
