<?php
/**
 * The model file of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: model.php 5079 2020-09-04 09:08:34Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php
class riskModel extends model
{
    /**
     * Create a risk.
     *
     * @param  int  $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $risk = fixer::input('post')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->setDefault('execution', 0)
            ->setIF(empty($_POST['identifiedDate']), 'identifiedDate', null)
            ->setIF(empty($_POST['plannedClosedDate']), 'plannedClosedDate', null)
            ->stripTags($this->config->risk->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $risk = $this->loadModel('file')->processImgURL($risk, $this->config->risk->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->batchCheck($this->config->risk->create->requiredFields, 'notempty')->exec();

        if(!dao::isError())
        {
            $riskID = $this->dao->lastInsertID();
            if(!empty($risk->assignedTo)) $this->loadModel('action')->create('risk', $riskID, 'Assigned', '', $risk->assignedTo);
            return $riskID;
        }
        return false;
    }

    /**
     * Batch create risk.
     *
     * @param  int  $projectID
     * @access public
     * @return bool
     */
    public function batchCreate($projectID = 0)
    {
        $data = fixer::input('post')->get();

        $this->loadModel('action');
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $risk = new stdclass();
            $risk->name        = $name;
            $risk->source      = $data->source[$i];
            $risk->category    = $data->category[$i];
            $risk->strategy    = $data->strategy[$i];
            $risk->execution   = (int)$data->execution[$i];
            $risk->impact      = $data->impact[$i];
            $risk->probability = $data->probability[$i];
            $risk->rate        = $data->rate[$i];
            $risk->pri         = $data->pri[$i];
            $risk->project     = $projectID;
            $risk->createdBy   = $this->app->user->account;
            $risk->createdDate = helper::now();

            $this->dao->insert(TABLE_RISK)->data($risk)->autoCheck()->exec();

            $riskID = $this->dao->lastInsertID();
            $this->action->create('risk', $riskID, 'Opened');
        }

        return true;
    }

    /**
     * Update a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function update($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setDefault('identifiedDate', null)
            ->setDefault('plannedClosedDate', null)
            ->setDefault('actualClosedDate', null)
            ->stripTags($this->config->risk->editor->edit['id'], $this->config->allowedTags)
            ->cleanInt('execution')
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)
            ->autoCheck()
            ->batchCheck($this->config->risk->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$riskID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);

        return false;
    }

    /**
     * Track a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function track($riskID)
    {
        $oldRisk = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setIF(!$this->post->trackedDate, 'trackedDate', null)
            ->stripTags($this->config->risk->editor->track['id'], $this->config->allowedTags)
            ->remove('isChange,comment,uid,files,label')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Get risks List.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getList($projectID, $browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRisks();

        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        $actionIDList = array();
        if($browseType == 'assignBy')
        {
            $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                 ->where('objectType')->eq('risk')
                 ->andWhere('action')->eq('assigned')
                 ->andWhere('actor')->eq($this->app->user->account)
                 ->fetchPairs('objectID', 'objectID');
        }
        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'all' and $browseType != 'assignTo' and $browseType != 'assignBy')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'assignTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assignBy')->andWhere('id')->in($actionIDList)->andWhere('status')->ne('closed')->fi()
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get risk list.
     *
     * @param  int|array|string $riskList
     * @access public
     * @return array
     */
    public function getByList($riskIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->beginIF($riskIDList)->andWhere('id')->in($riskIDList)->fi()
            ->fetchAll('id', false);
    }

    /**
     * Get risks by search
     *
     * @param  int    $projectID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return object
     */
    public function getBySearch($projectID, $queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('riskQuery', $query->sql);
                $this->session->set('riskForm', $query->form);
            }
            else
            {
                $this->session->set('riskQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->riskQuery == false) $this->session->set('riskQuery', ' 1 = 1');
        }

        $riskQuery = $this->session->riskQuery;

        return $this->dao->select('*')->from(TABLE_RISK)
            ->where($riskQuery)
            ->andWhere('deleted')->eq('0')
            ->beginIF($this->app->tab == 'project')->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution')->andWhere('execution')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get not imported risks.
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
                $this->session->set('importRiskQuery', ' 1 = 1');
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importRiskQuery', $query->sql);
                    $this->session->set('importRiskForm', $query->form);
                }
            }
            else
            {
                if($this->session->importRiskQuery == false) $this->session->set('importRiskQuery', ' 1 = 1');
            }

            $query  = $this->session->importRiskQuery;
            $allLib = "`lib` = 'all'";
            $withAllLib = strpos($query, $allLib) !== false;
            if($withAllLib)  $query  = str_replace($allLib, 1, $query);
            if(!$withAllLib) $query .= " AND `lib` = '$libID'";
        }

        $risks = $this->dao->select('*')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->andWhere('lib')->in(array_keys($libraries))
            ->beginIF($browseType != 'bysearch')->andWhere('lib')->eq($libID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        $imported = $this->dao->select('`from`,version')->from(TABLE_RISK)
            ->where('lib')->eq(0)
            ->andWhere('`from`')->ne(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('version_asc')
            ->fetchPairs();
        if(empty($imported)) return $risks;

        foreach($risks as $risk)
        {
            if(!isset($imported[$risk->id])) continue;
            if($risk->version == $imported[$risk->id]) unset($risks[$risk->id]);
        }

        return $risks;
    }

    /**
     * Import risk to asset lib.
     *
     * @param  int|array|string  $riskIDList
     * @access public
     * @return bool
     */
    public function importToLib($riskIDList = 0)
    {
        $data = fixer::input('post')->get();
        if(empty($data->lib))
        {
            dao::$errors['message'] = sprintf($this->lang->error->notempty, $this->lang->risk->lib);
            return false;
        }

        $risks         = $this->getByList($riskIDList);
        $importedRisks = $this->dao->select('`from`,id')->from(TABLE_RISK)
            ->where('lib')->eq($data->lib)
            ->andWhere('`from`')->in($riskIDList)
            ->fetchPairs();

        if(is_numeric($riskIDList) and isset($importedRisks[$riskIDList]))
        {
            dao::$errors['message'] = $this->lang->risk->isExist;
            return false;
        }

        $now           = helper::now();
        $today         = helper::today();
        $hasApprovePiv = common::hasPriv('assetlib', 'approveRisk') or common::hasPriv('assetlib', 'batchApproveRisk');
        $this->loadModel('action');

        /* Create risk to asset lib. */
        foreach($risks as $risk)
        {
            if(isset($importedRisks[$risk->id])) continue;

            $assetRisk = new stdclass();
            $assetRisk->name        = $risk->name;
            $assetRisk->source      = $risk->source;
            $assetRisk->category    = $risk->category;
            $assetRisk->strategy    = $risk->strategy;
            $assetRisk->impact      = $risk->impact;
            $assetRisk->probability = $risk->probability;
            $assetRisk->rate        = $risk->rate;
            $assetRisk->pri         = $risk->pri;
            $assetRisk->prevention  = $risk->prevention;
            $assetRisk->remedy      = $risk->remedy;
            $assetRisk->status      = $hasApprovePiv ? 'active' : 'draft';
            $assetRisk->lib         = $data->lib;
            $assetRisk->from        = $risk->id;
            $assetRisk->version     = 1;
            $assetRisk->createdBy   = $this->app->user->account;
            $assetRisk->createdDate = $now;
            if(!empty($data->assignedTo)) $assetRisk->assignedTo = $data->assignedTo;
            if($hasApprovePiv)
            {
                $assetRisk->assignedTo   = $this->app->user->account;
                $assetRisk->approvedDate = $today;
            }

            $this->dao->insert(TABLE_RISK)->data($assetRisk)->exec();
            $assetRiskID = $this->dao->lastInsertID();

            if(!dao::isError()) $this->action->create('risk', $assetRiskID, 'import2RiskLib');
        }

        return true;
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
        $riskIdList = fixer::input('post')->get('riskIdList');
        $riskList   = $this->dao->select('*')->from(TABLE_RISK)->where('id')->in($riskIdList)->fetchAll();
        $now        = helper::now();

        $this->loadModel('action');
        foreach($riskList as $risk)
        {
            $risk->project     = $projectID;
            $risk->execution   = $executionID;
            $risk->from        = $risk->id;
            $risk->createdBy   = $this->app->user->account;
            $risk->createdDate = $now;
            $risk->closedDate  = !empty($risk->closedDate) ? $risk->closedDate : null;
            if(helper::isZeroDate($risk->identifiedDate))    $risk->identifiedDate = null;
            if(helper::isZeroDate($risk->plannedClosedDate)) $risk->plannedClosedDate = null;

            $fromLib         = $risk->lib;
            $needUnsetFields = array('lib', 'id', 'editedBy', 'editedDate', 'assignedTo', 'assignedDate', 'approvedDate', 'actualClosedDate', 'activateDate', 'cancelDate', 'hangupDate', 'trackedDate');
            foreach($needUnsetFields as $field) unset($risk->$field);

            $this->dao->insert(TABLE_RISK)->data($risk)->exec();

            if(!dao::isError())
            {
                $riskID = $this->dao->lastInsertID();
                $this->action->create('risk', $riskID, 'importfromrisklib', '', $fromLib);
            }
        }
    }

    /**
     * Get risks of pairs
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPairs($projectID)
    {
        return $this->dao->select('id, name')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->fetchPairs();
    }

    /**
     * Get risk by ID
     *
     * @param  int    $riskID
     * @access public
     * @return object|bool
     */
    public function getByID($riskID)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRisk();

        $risk = $this->dao->select('*')->from(TABLE_RISK)->where('id')->eq((int)$riskID)->fetch();
        if(!$risk) return false;

        $linkedIssues = $this->dao->select('issue')->from(TABLE_RISKISSUE)->where('risk')->eq($riskID)->fetchPairs('issue');
        $risk->issues = join(',', $linkedIssues);

        $risk = $this->loadModel('file')->replaceImgURL($risk, 'prevention,remedy,resolution');
        if(!empty($risk->from)) $risk->sourceName = $this->dao->select('name')->from(TABLE_RISK)->where('id')->eq($risk->from)->fetch('name');
        return $risk;
    }

    /**
     * Get block risks
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  int    $limit
     * @param  string $orderBy
     * @access public
     * @return object
     */
    public function getBlockRisks($projectID, $browseType = 'all', $limit = 15, $orderBy = 'id_desc')
    {
        $actionIDList = array();
        if($browseType == 'assignBy')
        {
            $actionIDList = $this->dao->select('objectID')->from(TABLE_ACTION)
                 ->where('objectType')->eq('risk')
                 ->andWhere('action')->eq('assigned')
                 ->andWhere('actor')->eq($this->app->user->account)
                 ->fetchPairs('objectID', 'objectID');
        }

        return $this->dao->select('*')->from(TABLE_RISK)
            ->where('project')->eq($projectID)
            ->beginIF($browseType != 'all' and $browseType != 'assignTo' and $browseType != 'assignBy')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'assignTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assignBy')->andWhere('id')->in($actionIDList)->andWhere('status')->ne('closed')->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * Get user risks.
     *
     * @param  string $type    open|assignto|closed|suspended|canceled
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getUserRisks($type = 'assignedTo', $account = '', $orderBy = 'id_desc', $pager = null)
    {
        if(empty($account)) $account = $this->app->user->account;

        $riskList = $this->dao->select('t1.*')->from(TABLE_RISK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.' . $type)->eq($account)->fi()
            ->andWhere('t1.lib')->eq('0')->fi()
            ->orderBy('t1.' . $orderBy)
            ->page($pager)
            ->fetchAll();

        return $riskList;
    }

    /**
     * Get risk pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|active|closed|hangup|canceled
     * @param  array  $skipProjectIDList
     * @access public
     * @return array
     */
    public function getUserRiskPairs($account, $limit = 0, $status = 'all', $skipProjectIDList = array())
    {
        $stmt = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_RISK)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.lib')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipProjectIDList))->andWhere('t1.project')->notin($skipProjectIDList)->fi()
            ->beginIF($limit)->limit($limit)->fi()
            ->query();

        $risks = array();
        while($risk = $stmt->fetch())
        {
            $risks[$risk->id] = $risk->project . ' / ' . $risk->name;
        }
        return $risks;
    }

    /**
     * Get project risk pairs.
     *
     * @param  int    $projectID
     * @param  string $append
     * @access public
     * @return array
     */
    public function getProjectRiskPairs($projectID, $append = '')
    {
        $projectID = (int)$projectID;
        $risks     = $this->dao->select('id,name')->from(TABLE_RISK)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('status')->notIN('closed,canceled')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->fetchPairs('id', 'name');

        if($append) $risks += $this->dao->select('id,name')->from(TABLE_RISK)->where('id')->in($append)->fetchPairs('id', 'name');
        $risks = arrayUnion(array('' => ''), $risks);

        return $risks;
    }

    /**
     * Print assignedTo html
     *
     * @param  int    $risk
     * @param  int    $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($risk, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $risk->assignedTo);

        if(empty($risk->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->risk->noAssigned;
        }
        if($risk->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($risk->assignedTo) and $risk->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $risk->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('risk', 'assignTo', "riskID=$risk->id", '', true);
        $class        = $risk->assignedTo == 'closed' ? "class='{$btnClass}' style='pointer-events:none';" : "class='{$btnClass}'";
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $risk->assignedTo) . "'>{$assignedToText}</span>", '', $class);

        echo !common::hasPriv('risk', 'assignTo', $risk) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Assign a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function assign($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->risk->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Cancel a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function cancel($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->setDefault('status','canceled')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->stripTags($this->config->risk->editor->cancel['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Close a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function close($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->setDefault('status','closed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->add('closedBy', $this->app->user->account)
            ->add('closedDate', helper::today())
            ->add('assignedTo', 'closed')
            ->stripTags($this->config->risk->editor->close['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Hangup a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function hangup($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->setDefault('status','hangup')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Activate a risk.
     *
     * @param  int    $riskID
     * @access public
     * @return array|bool
     */
    public function activate($riskID)
    {
        $oldRisk = $this->getByID($riskID);

        $risk = fixer::input('post')
            ->setDefault('status','active')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_RISK)->data($risk)->autoCheck()->where('id')->eq((int)$riskID)->exec();

        if(!dao::isError()) return common::createChanges($oldRisk, $risk);
        return false;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  object $risk
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($risk, $action)
    {
        $action = strtolower($action);

        if($action == 'assignto' or $action == 'track' or $action == 'cancel') return $risk->status != 'canceled' and $risk->status != 'closed';
        if($action == 'hangup')   return $risk->status == 'active';
        if($action == 'activate') return $risk->status != 'active';
        if($action == 'close')    return $risk->status != 'closed';

        return true;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->risk->search['actionURL'] = $actionURL;
        $this->config->risk->search['queryID']   = $queryID;

        unset($this->config->risk->search['fields']['project']);

        $this->loadModel('search')->setSearchParams($this->config->risk->search);
    }

    /**
     * 获取看板的风险卡片数据。
     * Get the Kanban risk card data.
     *
     * @param  int         $executionID
     * @access public
     * @return array
     * @param mixed[] $excludeRisks
     */
    public function getKanbanRisks($executionID, $excludeRisks = array())
    {
        $risks = $this->dao->select('t1.*, t2.realname AS assignedToRealName')
            ->from(TABLE_RISK)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.assignedTo = t2.account')
            ->where('t1.execution')->eq($executionID)
            ->beginIF($excludeRisks)->andWhere('t1.id')->notIN($excludeRisks)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');

        foreach($risks as $id => $risk)
        {
            $risks[$id]->title = $risk->name;
        }

        return $risks;
    }

    /**
     * 获取风险的相关人员。
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
