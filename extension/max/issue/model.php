<?php
/**
 * The model file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yong Lei <leiyong@easycorp.ltd>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class issueModel extends model
{
    /**
     * Create an issue.
     *
     * @param  int $projectID
     * @access public
     * @return int
     */
    public function create($projectID = 0)
    {
        $now   = helper::now();
        $issue = fixer::input('post')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('status', 'unconfirmed')
            ->add('project', $projectID)
            ->remove('labels,files')
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->setIF(!$this->post->deadline, 'deadline', null)
            ->cleanInt('execution')
            ->stripTags($this->config->issue->editor->create['id'], $this->config->allowedTags)
            ->get();

        $issue = $this->loadModel('file')->processImgURL($issue, $this->config->issue->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_ISSUE)->data($issue)->autoCheck()->batchCheck($this->config->issue->create->requiredFields, 'notempty')->exec();
        if(!dao::isError())
        {
            $issueID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('issue', $issueID);
            $this->file->updateObjectID($this->post->uid, $issueID, 'issue');
            if(!empty($issue->assignedTo)) $this->loadModel('action')->create('issue', $issueID, 'Assigned', '', $issue->assignedTo);

            return $issueID;
        }
        return false;
    }

    /**
     * Get stakeholder issue list data.
     *
     * @param  string $owner
     * @param  string $activityID
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getStakeholderIssue($owner = '', $activityID = 0, $pager = null)
    {
        $issueList = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq('0')
            ->beginIF($owner)->andWhere('owner')->eq($owner)->fi()
            ->beginIF($activityID)->andWhere('activity')->eq($activityID)->fi()
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll();

        return $issueList;
    }

    /**
     * Get a issue details.
     *
     * @param  int    $issueID
     * @access public
     * @return object|bool
     */
    public function getByID($issueID)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getIssue();

        $issue = $this->dao->select('*')->from(TABLE_ISSUE)->where('id')->eq($issueID)->fetch();
        if(!$issue) return false;

        $issue->risk  = $this->dao->select('risk')->from(TABLE_RISKISSUE)->where('issue')->eq($issueID)->fetch('risk');
        $issue->files = $this->loadModel('file')->getByObject('issue', $issue->id);
        $issue = $this->loadModel('file')->replaceImgURL($issue, 'desc');
        if(isset($issue->from)) $issue->sourceName = $this->dao->select('title')->from(TABLE_ISSUE)->where('id')->eq($issue->from)->fetch('title');

        return $issue;
    }

    /**
     * Get issue list data.
     *
     * @param  int       $projectID
     * @param  string    $browseType bySearch|open|assignTo|closed|suspended|canceled
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return object
     */
    public function getList($projectID = 0, $browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getIssues();

        $issueQuery = '';
        if(strtolower($browseType) == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('issueQuery', $query->sql);
                $this->session->set('issueForm', $query->form);
            }
            if($this->session->issueQuery == false) $this->session->set('issueQuery', ' 1=1');
            $issueQuery = $this->session->issueQuery;
        }

        $actionIDList = array();
        if($browseType == 'assignby')
        {
             $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                  ->where('objectType')->eq('issue')
                  ->andWhere('action')->eq('assigned')
                  ->andWhere('actor')->eq($this->app->user->account)
                  ->fetchPairs('objectID', 'objectID');
        }

        $issueList = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq('0')
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->beginIF($browseType == 'open')->andWhere('status')->in('active,confirmed,unconfirmed')->fi()
            ->beginIF($browseType == 'assignto')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assignby')->andWhere('id')->in($actionIDList)->andWhere('status')->ne('closed')->fi()
            ->beginIF($browseType == 'closed')->andWhere('status')->eq('closed')->fi()
            ->beginIF($browseType == 'resolved')->andWhere('status')->eq('resolved')->fi()
            ->beginIF($browseType == 'canceled')->andWhere('status')->eq('canceled')->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($issueQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        return $issueList;
    }

    /**
     * Get issue list.
     *
     * @param  int|array|string $issueList
     * @access public
     * @return array
     */
    public function getByList($issueIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq(0)
            ->beginIF($issueIDList)->andWhere('id')->in($issueIDList)->fi()
            ->fetchAll('id', false);
    }

    /**
     * Get the issue in the block.
     *
     * @param  int    $projectID
     * @param  string $browseType open|assignto|closed|suspended|canceled
     * @param  int    $limit
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getBlockIssues($projectID = 0, $browseType = 'all', $limit = 15, $orderBy = 'id_desc')
    {
        $actionIDList = array();
        if($browseType == 'assignby')
        {
             $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                  ->where('objectType')->eq('issue')
                  ->andWhere('action')->eq('assigned')
                  ->andWhere('actor')->eq($this->app->user->account)
                  ->fetchPairs('objectID', 'objectID');
        }

        $issueList = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq('0')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($browseType == 'open')->andWhere('status')->eq('active')->fi()
            ->beginIF($browseType == 'assignto')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'closed')->andWhere('status')->eq('closed')->fi()
            ->beginIF($browseType == 'suspended')->andWhere('status')->eq('suspended')->fi()
            ->beginIF($browseType == 'canceled')->andWhere('status')->eq('canceled')->fi()
            ->beginIF($browseType == 'resolved')->andWhere('status')->eq('resolved')->fi()
            ->beginIF($browseType == 'assignby')->andWhere('id')->in($actionIDList)->andWhere('status')->ne('close')->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();

        return $issueList;
    }

    /**
     * Get user issues.
     *
     * @param  string $browseType open|assignto|closed|suspended|canceled
     * @param  int    $queryID
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUserIssues($type = 'assignedTo', $queryID = 0, $account = '', $orderBy = 'id_desc', $pager = null)
    {
        if(empty($account)) $account = $this->app->user->account;

        $query = '';
        if(strtolower($type) == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('issueQuery', $query->sql);
                    $this->session->set('issueForm', $query->form);
                }
                else
                {
                    $this->session->set('issueQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->issueQuery == false) $this->session->set('issueQuery', ' 1 = 1');
            }
            $query = $this->session->issueQuery;

            if($this->app->rawMethod == 'work')
            {
                $type = 'assignedTo';
            }
            elseif($this->app->rawMethod == 'contribute')
            {
                $issues   = $this->loadModel('my')->getAssignedByMe($this->app->user->account, null, 'id_desc', 'issue');
                $issueIds = array_keys($issues);
                if(empty($issueIds)) $issueIds = array(0);

                $query .= " AND (`createdBy` = '{$account}' OR `closedBy` = '{$account}' OR id IN (" . implode(',', $issueIds) . "))";
            }
        }

        $deletedProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('project')->andWhere('deleted')->eq('1')->fetchPairs('id');
        $issueList       = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq('0')
            ->beginIF($deletedProjects)->andWhere('project')->notin($deletedProjects)->fi()
            ->beginIF(strtolower($type) != 'bysearch')->andWhere($type)->eq($account)->fi()
            ->andWhere('lib')->eq('0')->fi()
            ->beginIF($query)->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $issueList;
    }

    /**
     * Get project issue pairs.
     *
     * @param  int    $projectID
     * @param  bool   $excludeLinked
     * @param  string $append
     * @access public
     * @return array
     */
    public function getProjectIssuePairs($projectID, $excludeLinked = true, $append = '')
    {
        $projectID    = (int)$projectID;
        $linkedIssues = array();
        if($excludeLinked)
        {
            $linkedIssues = $this->dao->select('t2.issue')->from(TABLE_ISSUE)->alias('t1')
                ->leftJoin(TABLE_RISKISSUE)->alias('t2')->on('t1.id=t2.issue')
                ->where("t1.project")->eq($projectID)
                ->andWhere("t2.issue")->ne('')
                ->fetchPairs('issue', 'issue');
        }

        $issues = $this->dao->select('id,title')->from(TABLE_ISSUE)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('status')->notIN('canceled,closed')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($linkedIssues)->andWhere('id')->notIN($linkedIssues)->fi()
            ->fetchPairs('id', 'title');

        if($append) $issues += $this->dao->select('id,title')->from(TABLE_ISSUE)->where('id')->in($append)->fetchPairs('id', 'title');
        $issues = arrayUnion(array('' => ''), $issues);

        return $issues;
    }

    /**
     * Get activity list.
     *
     * @access public
     * @return object
     */
    public function getActivityPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_ACTIVITY)->where('deleted')->eq('0')->orderBy('id_desc')->fetchPairs();
    }

    /**
     * Get issue pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|unconfirmed|active|suspended|resolved|closed|canceled
     * @param  array  $skipProjectIDList
     * @access public
     * @return array
     */
    public function getUserIssuePairs($account, $limit = 0, $status = 'all', $skipProjectIDList = array())
    {
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as project')
            ->from(TABLE_ISSUE)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.lib')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipProjectIDList))->andWhere('t1.project')->notin($skipProjectIDList)->fi()
            ->beginIF($limit)->limit($limit)->fi()
            ->query();

        $issues = array();
        while($issue = $stmt->fetch())
        {
            $issues[$issue->id] = $issue->project . ' / ' . $issue->title;
        }
        return $issues;
    }

    /**
     * Get not imported issues.
     *
     * @param  array  $libraries
     * @param  int    $libID
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getNotImported($libraries, $libID, $projectID, $orderBy = 'id_desc', $browseType = '', $queryID = 0)
    {
        $query = '';
        if($browseType == 'bysearch')
        {
            if($queryID)
            {
                $this->session->set('importIssueQuery', ' 1 = 1');
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importIssueQuery', $query->sql);
                    $this->session->set('importIssueForm', $query->form);
                }
            }
            else
            {
                if($this->session->importIssueQuery == false) $this->session->set('importIssueQuery', ' 1 = 1');
            }

            $query  = $this->session->importIssueQuery;
            $allLib = "`lib` = 'all'";
            $withAllLib = strpos($query, $allLib) !== false;
            if($withAllLib)  $query  = str_replace($allLib, 1, $query);
            if(!$withAllLib) $query .= " AND `lib` = '$libID'";
        }

        $issues = $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->andWhere('lib')->in(array_keys($libraries))
            ->beginIF($browseType != 'bysearch')->andWhere('lib')->eq($libID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        $imported = $this->dao->select('`from`,version')->from(TABLE_ISSUE)
            ->where('lib')->eq(0)
            ->andWhere('`from`')->ne(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('version_asc')
            ->fetchPairs();
        if(empty($imported)) return $issues;

        foreach($issues as $issue)
        {
            if(!isset($imported[$issue->id])) continue;
            if($issue->version == $imported[$issue->id]) unset($issues[$issue->id]);
        }

        return $issues;
    }

    /**
     * Import from library.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function importFromLib($projectID = 0, $executionID = 0)
    {
        $issueIdList = fixer::input('post')->get('issueIdList');
        $issueList   = $this->dao->select('*')->from(TABLE_ISSUE)->where('id')->in($issueIdList)->fetchAll();
        $now         = helper::now();

        $this->loadModel('action');
        foreach($issueList as $issue)
        {
            $issue->project      = $projectID;
            $issue->execution    = $executionID;
            $issue->from         = $issue->id;
            $issue->createdBy    = $this->app->user->account;
            $issue->createdDate  = $now;
            $issue->resolvedDate = !empty($issue->resolvedDate) ? $issue->resolvedDate : null;
            $issue->activateDate = !empty($issue->activateDate) ? $issue->activateDate : null;
            $issue->closedDate   = !empty($issue->closedDate) ? $issue->closedDate : null;
            $issue->deadline     = !empty($issue->deadline) ? $issue->deadline : null;

            $fromLib         = $issue->lib;
            $needUnsetFields = array('lib','id','editedBy','editedDate','assignedTo','assignedBy','assignedDate','approvedDate');
            foreach($needUnsetFields as $field) unset($issue->$field);

            $this->dao->insert(TABLE_ISSUE)->data($issue)->exec();

            if(dao::isError()) return false;

            $issueID = $this->dao->lastInsertID();
            $this->action->create('issue', $issueID, 'importfromissuelib', '', $fromLib);
        }
    }

    /**
     * Update an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function update($issueID)
    {
        $oldIssue = $this->getByID($issueID);

        $now   = helper::now();
        $issue = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->remove('labels,files')
            ->setDefault('deadline', null)
            ->addIF($this->post->assignedTo, 'assignedBy', $this->app->user->account)
            ->addIF($this->post->assignedTo, 'assignedDate', $now)
            ->cleanInt('execution')
            ->stripTags($this->config->issue->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $issue = $this->loadModel('file')->processImgURL($issue, $this->config->issue->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_ISSUE)->data($issue, 'deleteFiles,renameFiles,files')
            ->where('id')->eq($issueID)
            ->batchCheck($this->config->issue->edit->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $this->file->processFileDiffsForObject('issue', $oldIssue, $issue);
        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Update assignor.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function assignTo($issueID)
    {
        $oldIssue = $this->getByID($issueID);

        $now   = helper::now();
        $issue = fixer::input('post')
            ->add('assignedBy', $this->app->user->account)
            ->add('assignedDate', $now)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->remove('uid,comment,label')
            ->get();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Close an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function close($issueID)
    {
        $oldIssue = $this->getByID($issueID);
        $issue    = fixer::input('post')
            ->add('closedBy', $this->app->user->account)
            ->add('status', 'closed')
            ->add('assignedTo', 'closed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Confirm an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function confirm($issueID)
    {
        $oldIssue = $this->getByID($issueID);
        $issue    = fixer::input('post')
            ->add('status', 'confirmed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->stripTags($this->config->issue->editor->confirm['id'], $this->config->allowedTags)
            ->get();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();

        $this->loadModel('file')->saveUpload('issue', $issueID);
        $this->file->updateObjectID($this->post->uid, $issueID, 'issue');

        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Cancel an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function cancel($issueID)
    {
        $oldIssue = $this->getByID($issueID);
        $issue    = fixer::input('post')
            ->add('status', 'canceled')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->stripTags($this->config->issue->editor->cancel['id'], $this->config->allowedTags)
            ->get();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();
        $this->loadModel('file')->saveUpload('issue', $issueID);
        $this->file->updateObjectID($this->post->uid, $issueID, 'issue');

        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Pirnt assignedTo html
     *
     * @param int $issue
     * @param int $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($issue, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $issue->assignedTo);

        if(empty($issue->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->issue->noAssigned;
        }
        if($issue->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($issue->assignedTo) and $issue->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $issue->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('issue', 'assignTo', "issueID=$issue->id", '', true);
        $class        = $issue->assignedTo == 'closed' ? "class='{$btnClass}' style='pointer-events:none';" : "class='{$btnClass}'";
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $issue->assignedTo) . "'>{$assignedToText}</span>", '', $class);

        echo !common::hasPriv('issue', 'assignTo', $issue) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Activate an issue.
     *
     * @param  int    $issueID
     * @access public
     * @return array
     */
    public function activate($issueID)
    {
        $oldIssue = $this->getByID($issueID);

        $now   = helper::now();
        $issue = fixer::input('post')
            ->add('status', 'active')
            ->add('activateBy', $this->app->user->account)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('assignedBy', $this->app->user->account)
            ->add('assignedDate', $now)
            ->addIF($this->post->assignedTo == '', 'assignedTo', $this->app->user->account)
            ->get();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();

        return common::createChanges($oldIssue, $issue);
    }

    /**
     * Batch create issue.
     *
     * @param  int $projectID
     * @access public
     * @return array
     */
    public function batchCreate($projectID = 0, $issues = array())
    {
        $this->loadModel('action');

        $now         = helper::now();
        $issueIdList = array();
        foreach($issues as $issue)
        {
            $issue->createdBy   = $this->app->user->account;
            $issue->createdDate = $now;
            $issue->project     = $projectID;
            $issue->status      = 'unconfirmed';
            if(!empty($issue->assignedTo))
            {
                $issue->assignedBy   = $this->app->user->account;
                $issue->assignedDate = $now;
            }
            $this->dao->insert(TABLE_ISSUE)->data($issue)->autoCheck()->exec();
            $issueID = $this->dao->lastInsertId();

            if(!dao::isError() && !empty($issue->assignedTo)) $this->action->create('issue', $issueID, 'Assigned', '', $issue->assignedTo);
            $issueIdList[] = $issueID;
        }

        if(dao::isError()) return false;
        return $issueIdList;
    }

    /**
     * Resolve an issue.
     *
     * @param  int    $issueID
     * @param  object $data
     * @access public
     * @return void
     */
    public function resolve($issueID, $data)
    {
        $issue = new stdClass();
        $issue->resolution        = $data->resolution;
        $issue->resolutionComment = isset($data->resolutionComment) ? $data->resolutionComment : '';
        $issue->resolvedBy        = $data->resolvedBy;
        $issue->resolvedDate      = $data->resolvedDate;
        $issue->status            = 'resolved';
        $issue->editedBy          = $this->app->user->account;
        $issue->editedDate        = helper::now();

        $this->dao->update(TABLE_ISSUE)->data($issue)->where('id')->eq($issueID)->exec();
    }

    /**
     * Create an task.
     *
     * @param  object    $data
     * @access public
     * @return false|int
     */
    public function createTask($data)
    {
        $this->loadModel('task');
        $team = $this->post->team ? array_filter($this->post->team) : array();
        if(!$this->post->multiple || $data->type == 'affair') $data->mode = '';
        if($data->mode && empty($team))
        {
            dao::$errors['assignedTo'] = $this->lang->task->teamNotEmpty;
            return false;
        }

        $data->version = 1;
        $data->parent  = 0;
        if(empty($data->estimate)) $data->estimate = 0;
        if(empty($data->estStarted)) unset($data->estStarted);
        if(empty($data->deadline)) unset($data->deadline);
        if(!empty($data->execution)) $data->project = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($data->execution)->fetch('project');

        if($data->type == 'affair')
        {
            $taskIdList = $this->task->createTaskOfAffair($data, $this->post->assignedTo);
            $taskID     = end($taskIdList);
        }
        elseif($this->post->multiple)
        {
            $teamData = form::data($this->config->task->form->team->create)->get();
            $taskID   = $this->task->createMultiTask($data, $teamData);
        }
        else
        {
            $taskID = $this->task->create($data);
        }

        if(dao::isError()) return false;

        return $taskID;
    }

    /**
     * Create a story.
     *
     * @param  object    $object
     * @param  int       $projectID
     * @access public
     * @return false|int
     * @param object $data
     */
    public function createStory($data, $projectID)
    {
        if(!$this->post->needNotReview && empty(array_filter((array)$_POST['reviewer'])))
        {
            $this->app->loadLang('story');
            dao::$errors['reviewer'] = sprintf($this->lang->error->notempty, $this->lang->story->reviewedBy);
            return false;
        }
        if(empty($data->estimate)) $data->estimate = 0;
        $data->status     = $this->post->needNotReview ? 'active' : 'reviewing';
        $data->grade      = 1;
        $data->openedBy   = $this->app->user->account;
        $data->openedDate = helper::now();
        $storyID = $this->loadModel('story')->create($data, $projectID);
        if(dao::isError()) return false;

        return $storyID;
    }

    /**
     * Create a bug.
     *
     * @param  object    $data
     * @access public
     * @return false|int
     */
    public function createBug($data)
    {
        $data->openedBuild = implode(',', $data->openedBuild);
        if(empty($data->deadline)) unset($data->deadline);

        $bugID = $this->loadModel('bug')->create($data);
        if(dao::isError()) return false;

        return $bugID;
    }

    /**
     * Create a risk.
     *
     * @param  int    $projectID
     * @access public
     * @return false|int
     */
    public function createRisk($projectID)
    {
        unset($_POST['resolution'], $_POST['resolvedBy'], $_POST['resolvedDate']);

        $riskID = $this->loadModel('risk')->create($projectID);
        if(dao::isError()) return false;

        return $riskID;
    }

    /**
     * Import issue to asset lib.
     *
     * @param  int|array|string  $issueIDList
     * @access public
     * @return bool
     */
    public function importToLib($issueIDList = 0)
    {
        $data = fixer::input('post')->get();
        if(empty($data->lib))
        {
            dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->issue->lib);
            return false;
        }

        $issues         = $this->getByList($issueIDList);
        $importedIssues = $this->dao->select('`from`,id')->from(TABLE_ISSUE)
            ->where('lib')->eq($data->lib)
            ->andWhere('`from`')->in($issueIDList)
            ->fetchPairs();

        if($this->app->rawMethod == 'importtolib' and isset($importedIssues[$issueIDList]))
        {
            dao::$errors['message'] = $this->lang->issue->isExist;
            return false;
        }

        $now           = helper::now();
        $today         = helper::today();
        $hasApprovePiv = common::hasPriv('assetlib', 'approveIssue') or common::hasPriv('assetlib', 'batchApproveIssue');
        $this->loadModel('action');

        /* Create issue to asset lib. */
        foreach($issues as $issue)
        {
            if(isset($importedIssues[$issue->id])) continue;

            $assetIssue = new stdclass();
            $assetIssue->title       = $issue->title;
            $assetIssue->desc        = $issue->desc;
            $assetIssue->pri         = $issue->pri;
            $assetIssue->type        = $issue->type;
            $assetIssue->severity    = $issue->severity;
            $assetIssue->deadline    = !empty($issue->deadline) ? $issue->deadline : null;
            $assetIssue->status      = $hasApprovePiv ? 'active' : 'draft';
            $assetIssue->lib         = $data->lib;
            $assetIssue->from        = $issue->id;
            $assetIssue->version     = 1;
            $assetIssue->createdBy   = $this->app->user->account;
            $assetIssue->createdDate = $now;
            if(!empty($data->assignedTo)) $assetIssue->assignedTo = $data->assignedTo;
            if($hasApprovePiv)
            {
                $assetIssue->assignedTo   = $this->app->user->account;
                $assetIssue->approvedDate = $today;
            }

            $this->dao->insert(TABLE_ISSUE)->data($assetIssue)->exec();
            $assetIssueID = $this->dao->lastInsertID();

            if(!dao::isError()) $this->action->create('issue', $assetIssueID, 'import2IssueLib');
        }

        return true;
    }

   /**
     * Build issue search form.
     *
     * @param  string $actionURL
     * @param  int    $queryID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID, $projectID = 0)
    {
        if($this->app->rawModule == 'my') $this->config->issue->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $this->loadModel('project')->getPairsByModel('all', 'noclosed')));
        $project = $this->loadModel('project')->fetchByID($projectID);

        if(empty($project->multiple)) unset($this->config->issue->search['fields']['execution'], $this->config->issue->search['params']['execution']);
        if(!empty($project->multiple)) $this->config->issue->search['params']['execution'] = array('operator' => '=', 'control' => 'select', 'values' => arrayUnion(array('' => ''), $this->loadModel('execution')->getPairs($projectID, 'all')));
        $this->config->issue->search['actionURL'] = $actionURL;
        $this->config->issue->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->issue->search);
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object  $issue
     * @param  string  $action
     *
     * @access public
     * @return bool
     */
    public static function isClickable($issue, $action)
    {
        $action = strtolower($action);

        if($action == 'confirm')  return $issue->status == 'unconfirmed';
        if($action == 'resolve')  return $issue->status == 'active' || $issue->status == 'confirmed';
        if($action == 'close')    return $issue->status != 'closed';
        if($action == 'activate') return $issue->status == 'closed';
        if($action == 'cancel')   return $issue->status != 'canceled' && $issue->status != 'closed';
        if($action == 'assignto') return $issue->status != 'closed';

        return true;
    }

    /**
     * 获取问题的相关人员。
     * Get to and cc list.
     *
     * @param  object $object
     *
     * @access public
     * @return array
     */
    public function getToAndCcList($object)
    {
        $toList = "{$object->assignedTo},{$object->createdBy}";
        $ccList = '';
        return array($toList, $ccList);
    }
}
