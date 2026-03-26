<?php
/**
 * The model file of nc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     nc
 * @version     $Id: model.php 5107 2020-09-09 09:46:12Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
class ncModel extends model
{
    public function getNcs($programID, $browseType, $param, $orderBy, $pager, $executionID = 0)
    {
        $this->loadModel('auditplan');
        $ncQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $param ? $this->loadModel('search')->getQuery((int)$param) : '';
            if($query)
            {
                $this->session->set('ncQuery', $query->sql);
                $this->session->set('ncForm', $query->form);
            }

            if($this->session->ncQuery == false) $this->session->set('ncQuery', ' 1 = 1');

            $ncQuery = $this->session->ncQuery;
        }

        $actionIDList = array();
        if($browseType == 'assignedbyme')
        {
            $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                 ->where('objectType')->eq('nc')
                 ->andWhere('action')->eq('assigned')
                 ->andWhere('actor')->eq($this->app->user->account)
                 ->fetchPairs('objectID', 'objectID');
        }

        $ncQuery = preg_replace_callback('/`([^`]+)`/', function ($matches) {
            return "t1." . $matches[0];
        }, $ncQuery);
        if(strpos($ncQuery, 't1.`execution`') !== false) $ncQuery = str_replace('t1.`execution`', 't2.`execution`', $ncQuery);

        $ncs = $this->dao->select('t1.*, t2.objectID, t2.objectType, t2.execution')->from(TABLE_NC)->alias('t1')
            ->leftjoin(TABLE_AUDITPLAN)->alias('t2')->on('t1.auditplan = t2.id')
            ->where('t1.project')->eq($programID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($browseType == 'unclosed')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($ncQuery)->fi()
            ->beginIF($browseType == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assignedbyme')->andWhere('t1.id')->in($actionIDList)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($this->app->tab == 'execution' && $executionID)->andWhere('t2.execution')->eq($executionID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $ncs;
    }

    /**
     * Build search form.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($projectID, $queryID, $actionURL)
    {
        $project = $this->loadModel('project')->fetchByID($projectID);

        $this->config->nc->search['queryID']   = $queryID;
        $this->config->nc->search['actionURL'] = $actionURL;
        if(empty($project->multiple)) unset($this->config->nc->search['fields']['execution'], $this->config->nc->search['params']['execution']);
        if(!empty($project->multiple)) $this->config->nc->search['params']['execution']['values'] = arrayUnion(array('' => ''), $this->loadModel('execution')->fetchPairs($projectID, 'all', false));
        $this->config->nc->search['params']['auditplan']['values'] = arrayUnion(array('' => ''), $this->loadModel('auditplan')->getPairs($projectID));
        $this->config->nc->search['params']['listID']['values']    = arrayUnion(array('' => ''), $this->loadModel('auditplan')->getCheckListPairs($projectID));
        $this->config->nc->search['params']['status']['values']    = arrayUnion(array('' => ''), $this->lang->nc->statusList);

        $this->loadModel('search')->setSearchParams($this->config->nc->search);
    }

    public function create($auditplanID, $projectID)
    {
        $mode     = $_POST['mode'];
        $hasDraft = $this->loadModel('auditplan')->getResults($auditplanID, 'draft');
        $listID   = $_POST['listID'];
        $today    = helper::today();
        $data = new stdclass();
        $data->auditplan   = $auditplanID;
        $data->listID      = $listID;
        $data->result      = 'fail';
        $data->status      = $mode;
        $data->comment     = $_POST['title'];
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
            $this->dao->insert(TABLE_AUDITRESULT)
                ->data($data)
                ->exec();
        }

        /* Create NC*/
        $inserNc = new stdClass();
        $inserNc->project     = $projectID;
        $inserNc->auditplan   = $auditplanID;
        $inserNc->listID      = $listID;
        $inserNc->status      = 'active';
        $inserNc->severity    = $_POST['severity'];
        $inserNc->title       = $_POST['title'];
        $inserNc->type        = $_POST['type'];
        $inserNc->createdBy   = $this->app->user->account;
        $inserNc->createdDate = helper::now();

        $ncID = $this->auditplan->createNc($inserNc);
        if(dao::isError()) return false;
        $status = $mode == 'draft' ? 'checking' : 'checked';
        $audit = new stdclass();
        $audit->status = $status;
        if($status == 'checked') $audit->realCheckDate = helper::today();
        $this->dao->update(TABLE_AUDITPLAN)->data($audit)->where('id')->eq($auditplanID)->exec();
    }

    public function getByID($ncID)
    {
        $nc = $this->dao->findByID($ncID)->from(TABLE_NC)->fetch();
        $auditplan      = $this->loadModel('auditplan')->getByID($nc->auditplan);
        $nc->objectID   = $auditplan->objectID;
        $nc->objectType = $auditplan->objectType;
        $nc->execution  = $auditplan->execution;

        $nc = $this->loadModel('file')->replaceImgURL($nc, 'desc');
        $nc->desc = $this->file->setImgSize($nc->desc);

        return $nc;
    }

    public function update($ncID)
    {
        $oldNc = $this->getByID($ncID);
        $data  = fixer::input('post')
            ->setDefault('deadline', null)
            ->stripTags($this->config->nc->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();
        $data = $this->loadModel('file')->processImgURL($data, $this->config->nc->editor->edit['id'], $this->post->uid);

        unset($data->execution);
        if($data->auditplan != $oldNc->auditplan)
        {
            $this->dao->update(TABLE_AUDITRESULT)->data(array('listID' => $data->listID, 'auditplan' => $data->auditplan))
                 ->where('listID')->eq($oldNc->listID)
                 ->andWhere('auditplan')->eq($oldNc->auditplan)
                 ->exec();

            $this->dao->update(TABLE_AUDITPLAN)->data(array('status' => 'wait'))->where('id')->eq($oldNc->auditplan)->exec();
            $this->dao->update(TABLE_AUDITPLAN)->data(array('status' => 'checked'))->where('id')->eq($data->auditplan)->exec();
           if(dao::isError()) die(js::error(dao::getError()));
        }

        $this->dao->update(TABLE_NC)->data($data)->batchCheck($this->config->nc->edit->requiredFields, 'notempty')->where('id')->eq($ncID)->exec();
        return common::createChanges($oldNc, $data);
    }

    public function resolve($ncID)
    {
        $oldNc = $this->getByID($ncID);
        $data  = fixer::input('post')
            ->stripTags($this->config->nc->editor->resolve['id'], $this->config->allowedTags)
            ->remove('uid')
            ->add('resolvedBy', $this->app->user->account)
            ->get();
        $data = $this->loadModel('file')->processImgURL($data, $this->config->nc->editor->resolve['id'], $this->post->uid);

        $this->dao->update(TABLE_NC)->data($data)->where('id')->eq($ncID)->exec();

        return common::createChanges($oldNc, $data);
    }

    /**
     * Activate a nc.
     *
     * @param  int    $ncID
     * @access public
     * @return array|bool
     */
    public function activate($ncID)
    {
        $oldNc = $this->getByID($ncID);

        $nc = fixer::input('post')
            ->setDefault('status','active')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->remove('uid,comment')
            ->get();
        $this->dao->update(TABLE_NC)->data($nc)->autoCheck()->where('id')->eq((int)$ncID)->exec();

        if(!dao::isError()) return common::createChanges($oldNc, $nc);
        return false;
    }

    public function close($ncID)
    {
        $oldNc = $this->getByID($ncID);
        $data  = fixer::input('post')
            ->add('status', 'closed')
            ->add('closedDate', helper::today())
            ->add('closedBy', $this->app->user->account)
            ->remove('uid, comment')
            ->get();

        $this->dao->update(TABLE_NC)->data($data)->where('id')->eq($ncID)->exec();

        return common::createChanges($oldNc, $data);
    }

    /**
     * Assign an nc.
     *
     * @param  int    $ncID
     * @access public
     * @return array|bool
     */
    public function assign($ncID)
    {
        $oldNc = $this->getByID($ncID);

        $nc = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->nc->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_NC)->data($nc)->autoCheck()->where('id')->eq((int)$ncID)->exec();

        if(!dao::isError()) return common::createChanges($oldNc, $nc);
        return false;
    }

    public function printCell($col, $nc, $users, $activities, $outputs, $projectID = 0, $from = 'project')
    {
        $id      = $col->id;
        $ncLink  = helper::createLink('nc', 'view', "ncID=$nc->id&from=$from&projectID=$projectID");
        $account = $this->app->user->account;

        $class = '';
        if($col->show)
        {
            $class = "c-$id";
            if($id == 'id')         $class .= ' cell-id';
            if($id == 'status')     $class .= ' nc-' . $nc->status;

            echo "<td class=$class>";
            switch($id)
            {
            case 'id':
                echo html::a(helper::createLink('nc', 'view', "ncID=$nc->id&from=$from&projectID=$projectID"), sprintf('%03d', $nc->id), '', "data-app={$this->app->tab}");
                break;
            case 'severity':
                echo "<span class='severity-{$nc->severity}'>" . zget($this->lang->nc->severityList, $nc->severity) . "</span>";
                break;
            case 'title':
                echo html::a($ncLink, $nc->title, '', "data-app={$this->app->tab}");
                break;
            case 'auditplan':
                echo $nc->objectType == 'activity' ? zget($activities, $nc->objectID) : zget($outputs, $nc->objectID);
                break;
            case 'type':
                echo zget($this->lang->nc->typeList, $nc->type);
                break;
            case 'status':
                echo zget($this->lang->nc->statusList, $nc->status);
                break;
            case 'assignedTo':
                echo $this->printAssignedHtml($nc, $users);
                break;
            case 'deadline':
                echo helper::isZeroDate($nc->deadline) ? '' : $nc->deadline;
                break;
            case 'createdBy':
                echo zget($users, $nc->createdBy);
                break;
            case 'createdDate':
                echo substr($nc->createdDate, 0 , 11);
                break;
            case 'resolution':
                echo zget($this->lang->nc->resolutionList, $nc->resolution);
                break;
            case 'resolvedBy':
                echo zget($users, $nc->resolvedBy);
                break;
            case 'resolvedDate':
                echo helper::isZeroDate($nc->resolvedDate) ? '' : $nc->resolvedDate;
                break;
            case 'closedBy':
                echo zget($users, $nc->closedBy);
                break;
            case 'closedDate':
                echo helper::isZeroDate($nc->closedDate) ? '' : $nc->closedDate;
                break;
            case 'actions':
                $params    = "ncID=$nc->id";
                $project   = $this->loadModel('project')->fetchByID($projectID);
                $canModify = common::canModify($from, $project);
                $canModify && common::printIcon('nc', 'resolve', $params, $nc, 'list', 'checked', '', 'iframe', true);
                $canModify && common::printIcon('nc', 'activate', $params, $nc, 'list', '', '', 'iframe', 'true');
                $canModify && common::printIcon('nc', 'edit', $params . "&from=$from&projectID=$projectID", $nc, 'list');
                $canModify && common::printIcon('nc', 'close', $params, $nc, 'list', 'off', '', 'iframe', true);
                $canModify && common::printIcon('nc', 'delete', $params . "&from=$from", $nc, 'list', 'trash', 'hiddenwin');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Print assignedTo html.
     *
     * @param  int    $nc
     * @param  array  $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($nc, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $nc->assignedTo);

        if(empty($nc->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->nc->noAssigned;
        }
        if($nc->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($nc->assignedTo) and $nc->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $nc->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('nc', 'assignTo', "ncID=$nc->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $nc->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('nc', 'assignTo', $nc) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    public static function isClickable($object, $action)
    {
        $action = strtolower($action);
        if($action == 'resolve')    return $object->status == 'active';
        if($action == 'close')      return $object->status == 'resolved';
        if($action == 'activate')   return $object->status != 'active';

        return true;
    }
}
