<?php
/**
 * The model file of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: model.php 5079 2021-05-26 09:08:34Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php
class opportunityModel extends model
{
    /**
     * Create an opportunity.
     *
     * @param  int  $projectID
     * @access public
     * @return int|bool
     */
    public function create($projectID = 0)
    {
        $opportunity = fixer::input('post')
            ->add('project', $projectID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->cleanInt('execution')
            ->setIF(!$this->post->identifiedDate, 'identifiedDate', null)
            ->setIF(!$this->post->plannedClosedDate, 'plannedClosedDate', null)
            ->stripTags($this->config->opportunity->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $opportunity = $this->loadModel('file')->processImgURL($opportunity, $this->config->opportunity->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->batchCheck($this->config->opportunity->create->requiredFields, 'notempty')->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Batch create opportunities.
     *
     * @param  int  $projectID
     * @access public
     * @return array
     */
    public function batchCreate($projectID = 0)
    {
        $data              = fixer::input('post')->get();
        $opportunityIDList = array();

        $this->loadModel('action');
        $now = helper::now();
        foreach($data->name as $i => $name)
        {
            if(!$name) continue;

            $opportunity = new stdclass();
            $opportunity->name        = $name;
            $opportunity->project     = $projectID;
            $opportunity->source      = $data->source[$i];
            $opportunity->impact      = $data->impact[$i];
            $opportunity->chance      = $data->chance[$i];
            $opportunity->ratio       = $data->ratio[$i];
            $opportunity->execution   = isset($data->execution[$i]) ? (int)$data->execution[$i] : 0;
            $opportunity->pri         = isset($data->pri[$i]) ? $data->pri[$i] : 'middle';
            $opportunity->desc        = $data->desc[$i];
            $opportunity->createdBy   = $this->app->user->account;
            $opportunity->createdDate = $now;

            $this->dao->insert(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->exec();

            if(!dao::isError())
            {
                $opportunityID = $this->dao->lastInsertID();
                $opportunityIDList[$opportunityID] = $opportunityID;
                $this->action->create('opportunity', $opportunityID, 'Opened');
            }
        }

        return $opportunityIDList;
    }

    /**
     * Update an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function update($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $now  = helper::now();
        $opportunity = fixer::input('post')
            ->setDefault('identifiedDate, plannedClosedDate, actualClosedDate, hangupedDate, lastCheckedDate, canceledDate, closedDate, activatedDate', null)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'active' and !$this->post->activatedDate, 'activatedDate', $now)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'active' and !$this->post->activatedBy,   'activatedBy',   $this->app->user->account)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'canceled' and !$this->post->canceledDate, 'canceledDate', $now)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'canceled' and !$this->post->canceledBy,   'canceledBy',   $this->app->user->account)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'hangup' and !$this->post->hangupedDate, 'hangupedDate', $now)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'hangup' and !$this->post->hangupedBy,   'hangupedBy',   $this->app->user->account)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'closed' and !$this->post->closedDate, 'closedDate', $now)
            ->setIF($this->post->status != $oldOpportunity->status and $this->post->status == 'closed' and !$this->post->closedBy,   'closedBy',   $this->app->user->account)
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->cleanInt('execution')
            ->stripTags($this->config->opportunity->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)
            ->autoCheck()
            ->checkIF($opportunity->status == 'canceled', 'cancelReason', 'notempty')
            ->batchCheck($this->config->opportunity->edit->requiredFields, 'notempty')
            ->batchCheckIF($opportunity->status == 'active', 'canceledBy, canceledDate, cancelReason, closedBy, closedDate, hangupedBy, hangupedDate', 'empty')
            ->batchCheckIF($opportunity->status == 'canceled', 'closedBy, closedDate, hangupedBy, hangupedDate, activatedBy, activatedDate', 'empty')
            ->batchCheckIF($opportunity->status == 'hangup', 'canceledBy, canceledDate, cancelReason, closedBy, closedDate, activatedBy, activatedDate', 'empty')
            ->batchCheckIF($opportunity->status == 'closed', 'canceledBy, canceledDate, cancelReason, hangupedBy, hangupedDate,activatedBy, activatedDate', 'empty')
            ->where('id')->eq((int)$opportunityID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Batch update opportunities.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $allChanges        = array();
        $now               = helper::now();
        $data              = fixer::input('post')->get();
        $opportunityIDList = array_keys($data->name);
        $oldOpportunities  = $opportunityIDList ? $this->getByList($opportunityIDList) : array();
        foreach($opportunityIDList as $opportunityID)
        {
            $oldOpportunity = $oldOpportunities[$opportunityID];

            $opportunity = new stdclass();
            $opportunity->name       = $data->name[$opportunityID];
            $opportunity->source     = $data->source[$opportunityID];
            $opportunity->impact     = $data->impact[$opportunityID];
            $opportunity->chance     = $data->chance[$opportunityID];
            $opportunity->ratio      = $data->ratio[$opportunityID];
            $opportunity->pri        = $data->pri[$opportunityID];
            $opportunity->editedBy   = $this->app->user->account;
            $opportunity->editedDate = $now;

            $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)
                ->autoCheck()
                ->batchCheck($this->config->opportunity->edit->requiredFields, 'notempty')
                ->where('id')->eq((int)$opportunityID)
                ->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$opportunityID}]"] = $error;
                return false;
            }
            $allChanges[$opportunityID] = common::createChanges($oldOpportunity, $opportunity);
        }
        return $allChanges;
    }

    /**
     * Batch cancel opportunities.
     *
     * @param  array  $opportunityIDList
     * @param  string $cancelReason
     * @access public
     * @return void
     */
    public function batchCancel($opportunityIDList, $cancelReason)
    {
        $now                 = helper::now();
        $oldOpportunities    = $this->getByList($opportunityIDList);
        $ignoreOpportunities = '';
        $allChanges          = array();

        foreach($opportunityIDList as $opportunityID)
        {
            $oldOpportunity = $oldOpportunities[$opportunityID];
            if($oldOpportunity->status == 'closed' or $oldOpportunity->status == 'canceled') continue;

            $opportunity = new stdClass();
            $opportunity->status        = 'canceled';
            $opportunity->canceledBy    = $this->app->user->account;
            $opportunity->canceledDate  = $now;
            $opportunity->cancelReason  = $cancelReason;
            $opportunity->editedBy      = $this->app->user->account;
            $opportunity->editedDate    = $now;
            $opportunity->activatedBy   = '';
            $opportunity->activatedDate = '';
            $opportunity->hangupedBy    = '';
            $opportunity->hangupedDate  = '';

            $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->where('id')->eq($opportunityID)->exec();

            if(dao::isError()) return false;
            $allChanges[$opportunityID] = common::createChanges($oldOpportunity, $opportunity);
        }
        return $allChanges;
    }

    /**
     * Get opportunity by ID.
     *
     * @param  int    $opportunityID
     * @access public
     * @return object|bool
     */
    public function getByID($opportunityID)
    {
        $opportunity = $this->dao->select('*')->from(TABLE_OPPORTUNITY)->where('id')->eq((int)$opportunityID)->fetch();
        if(!$opportunity) return false;

        $opportunity = $this->loadModel('file')->replaceImgURL($opportunity, 'prevention,resolution,desc');
        if($opportunity->from) $opportunity->sourceName = $this->dao->select('name')->from(TABLE_OPPORTUNITY)->where('id')->eq($opportunity->from)->fetch('name');
        return $opportunity;
    }

    /**
     * Get opportunity list.
     *
     * @param  int|array|string $opportunityList
     * @param  bool             $autoExclude
     * @access public
     * @return array
     */
    public function getByList($opportunityIDList = 0, $autoExclude = true)
    {
        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('deleted')->eq(0)
            ->beginIF($opportunityIDList)->andWhere('id')->in($opportunityIDList)->fi()
            ->fetchAll('id', $autoExclude);
    }

    /**
     * Get opportunities list.
     *
     * @param  int    $projectID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($projectID, $browseType = '', $param = '', $orderBy = 'id_desc', $pager = null)
    {
        if($browseType == 'bysearch') return $this->getBySearch($projectID, $param, $orderBy, $pager);

        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('deleted')->eq(0)
            ->beginIF($browseType != 'all' and $browseType != 'assignTo')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'assignTo')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($this->app->tab == 'project' && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab == 'execution' && $projectID)->andWhere('execution')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get opportunities by search.
     *
     * @param  int    $projectID
     * @param  string $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySearch($projectID, $queryID = '', $orderBy = 'id_desc', $pager = null)
    {
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('opportunityQuery', $query->sql);
                $this->session->set('opportunityForm', $query->form);
            }
            else
            {
                $this->session->set('opportunityQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->opportunityQuery == false) $this->session->set('opportunityQuery', ' 1 = 1');
        }

        $opportunityQuery = $this->session->opportunityQuery;

        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where($opportunityQuery)
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->eq($projectID)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get not imported opportunities.
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
                $this->session->set('importOpportunityQuery', ' 1 = 1');
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('importOpportunityQuery', $query->sql);
                    $this->session->set('importOpportunityForm', $query->form);
                }
            }
            else
            {
                if($this->session->importOpportunityQuery == false) $this->session->set('importOpportunityQuery', ' 1 = 1');
            }

            $query  = $this->session->importOpportunityQuery;
            $allLib = "`lib` = 'all'";
            $withAllLib = strpos($query, $allLib) !== false;
            if($withAllLib)  $query  = str_replace($allLib, 1, $query);
            if(!$withAllLib) $query .= " AND `lib` = '$libID'";
        }

        $opportunities = $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->andWhere('lib')->in(array_keys($libraries))
            ->beginIF($browseType != 'bysearch')->andWhere('lib')->eq($libID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($query)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        $imported = $this->dao->select('`from`,version')->from(TABLE_OPPORTUNITY)
            ->where('lib')->eq(0)
            ->andWhere('`from`')->ne(0)
            ->andWhere('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('version_asc')
            ->fetchPairs();
        if(empty($imported)) return $opportunities;

        foreach($opportunities as $opportunity)
        {
            if(!isset($imported[$opportunity->id])) continue;
            if($opportunity->version == $imported[$opportunity->id]) unset($opportunities[$opportunity->id]);
        }

        return $opportunities;
    }

    /**
     * Import from library.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function importFromLib($projectID = 0, $executionID = 0)
    {
        $opportunityIdList = fixer::input('post')->get('opportunityIdList');
        $opportunityList   = $this->dao->select('*')->from(TABLE_OPPORTUNITY)->where('id')->in($opportunityIdList)->fetchAll();

        $now = helper::now();
        $this->loadModel('action');
        foreach($opportunityList as $opportunity)
        {
            $opportunity->project        = $projectID;
            $opportunity->execution      = $executionID;
            $opportunity->from           = $opportunity->id;
            $opportunity->createdBy      = $this->app->user->account;
            $opportunity->createdDate    = $now;

            $dateFields = array('identifiedDate', 'plannedClosedDate', 'actualClosedDate', 'activatedDate', 'closedDate', 'canceledDate', 'hangupedDate', 'resolvedDate', 'lastCheckedDate');
            foreach($dateFields as $dateField) $opportunity->{$dateField} = !empty($opportunity->{$dateField}) ? $opportunity->{$dateField} : null;

            $fromLib         = $opportunity->lib;
            $needUnsetFields = array('lib','id','editedBy','editedDate','assignedTo','assignedDate','approvedDate');
            foreach($needUnsetFields as $field) unset($opportunity->$field);

            $this->dao->insert(TABLE_OPPORTUNITY)->data($opportunity)->exec();

            if(!dao::isError())
            {
                $opportunityID = $this->dao->lastInsertID();
                $this->action->create('opportunity', $opportunityID, 'importfromopportunitylib', '', $fromLib);
            }
        }
    }

    /**
     * Import opportunity to asset lib.
     *
     * @param  int|array|string  $opportunityIDList
     * @access public
     * @return bool
     */
    public function importToLib($opportunityIDList = 0)
    {
        $data = fixer::input('post')->get();
        if(empty($data->lib))
        {
            dao::$errors['lib'] = sprintf($this->lang->error->notempty, $this->lang->opportunity->lib);
            return false;
        }

        $opportunities         = $this->getByList($opportunityIDList, false);
        $importedOpportunities = $this->dao->select('`from`,id')->from(TABLE_OPPORTUNITY)
            ->where('lib')->eq($data->lib)
            ->andWhere('`from`')->in($opportunityIDList)
            ->fetchPairs();

        if(is_numeric($opportunityIDList) and isset($importedOpportunities[$opportunityIDList]))
        {
            dao::$errors['message'] = $this->lang->opportunity->isExist;
            return false;
        }

        $now           = helper::now();
        $today         = helper::today();
        $hasApprovePiv = common::hasPriv('assetlib', 'approveOpportunity') or common::hasPriv('assetlib', 'batchApproveOpportunity');
        $this->loadModel('action');

        /* Create opportunity to asset lib. */
        foreach($opportunities as $opportunity)
        {
            if(isset($importedOpportunities[$opportunity->id])) continue;

            $assetOpportunity = new stdclass();
            $assetOpportunity->name        = $opportunity->name;
            $assetOpportunity->source      = $opportunity->source;
            $assetOpportunity->type        = $opportunity->type;
            $assetOpportunity->strategy    = $opportunity->strategy;
            $assetOpportunity->impact      = $opportunity->impact;
            $assetOpportunity->chance      = $opportunity->chance;
            $assetOpportunity->ratio       = $opportunity->ratio;
            $assetOpportunity->desc        = $opportunity->desc;
            $assetOpportunity->prevention  = $opportunity->prevention;
            $assetOpportunity->resolution  = $opportunity->resolution;
            $assetOpportunity->pri         = $opportunity->pri;
            $assetOpportunity->status      = $hasApprovePiv ? 'active' : 'draft';
            $assetOpportunity->lib         = $data->lib;
            $assetOpportunity->from        = $opportunity->id;
            $assetOpportunity->version     = 1;
            $assetOpportunity->createdBy   = $this->app->user->account;
            $assetOpportunity->createdDate = $now;
            if(!empty($data->assignedTo)) $assetOpportunity->assignedTo = $data->assignedTo;
            if($hasApprovePiv)
            {
                $assetOpportunity->assignedTo   = $this->app->user->account;
                $assetOpportunity->approvedDate = $today;
            }

            $this->dao->insert(TABLE_OPPORTUNITY)->data($assetOpportunity)->exec();
            $assetOppotunityID = $this->dao->lastInsertID();

            if(!dao::isError()) $this->action->create('opportunity', $assetOppotunityID, 'import2OpportunityLib');
        }

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
        $this->config->opportunity->search['actionURL'] = $actionURL;
        $this->config->opportunity->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->opportunity->search);
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  int    $opportunity
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($opportunity, $action)
    {
        $action = strtolower($action);

        if($action == 'cancel' or $action == 'close') return ($opportunity->status != 'canceled' and $opportunity->status != 'closed');
        if($action == 'hangup')   return $opportunity->status == 'active';
        if($action == 'activate') return $opportunity->status != 'active';

        return true;
    }

    /**
     * Print assignedTo html.
     *
     * @param  int    $opportunity
     * @param  array  $users
     * @access public
     * @return string
     */
    public function printAssignedHtml($opportunity, $users)
    {
        $btnTextClass   = '';
        $btnClass       = '';
        $assignedToText = zget($users, $opportunity->assignedTo);

        if(empty($opportunity->assignedTo))
        {
            $btnClass       = $btnTextClass = 'assigned-none';
            $assignedToText = $this->lang->opportunity->noAssigned;
        }
        if($opportunity->assignedTo == $this->app->user->account) $btnClass = $btnTextClass = 'assigned-current';
        if(!empty($opportunity->assignedTo) and $opportunity->assignedTo != $this->app->user->account) $btnClass = $btnTextClass = 'assigned-other';

        $btnClass    .= $opportunity->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass    .= ' iframe btn btn-icon-left btn-sm';
        $assignToLink = helper::createLink('opportunity', 'assignTo', "opportunityID=$opportunity->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span title='" . zget($users, $opportunity->assignedTo) . "'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('opportunity', 'assignTo', $opportunity) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    /**
     * Assign an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function assign($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $opportunity = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->setDefault('assignedDate', helper::today())
            ->stripTags($this->config->opportunity->editor->assignto['id'], $this->config->allowedTags)
            ->remove('uid,comment,files,label')
            ->get();

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->where('id')->eq((int)$opportunityID)->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Track an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function track($opportunityID)
    {
        $oldOpportunity = $this->dao->select('*')->from(TABLE_OPPORTUNITY)->where('id')->eq((int)$opportunityID)->fetch();

        $opportunity = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::today())
            ->stripTags($this->config->opportunity->editor->track['id'], $this->config->allowedTags)
            ->remove('isChange,comment,uid,files,label')
            ->get();

        $opportunity = $this->loadModel('file')->processImgURL($opportunity, $this->config->opportunity->editor->track['id'], $this->post->uid);

        $this->dao->update(TABLE_OPPORTUNITY)
            ->data($opportunity)
            ->autoCheck()
            ->batchCheck($this->config->opportunity->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$opportunityID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Close an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function close($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $now = helper::now();
        $opportunity = fixer::input('post')
            ->setDefault('status','closed')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('closedBy', $this->app->user->account)
            ->add('closedDate', $now)
            ->add('assignedTo', 'closed')
            ->add('actualClosedDate', helper::today())
            ->setDefault('activatedBy, hangupedBy', '')
            ->setDefault('activatedDate, hangupedDate', null)
            ->stripTags($this->config->opportunity->editor->close['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->where('id')->eq((int)$opportunityID)->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Cancel an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function cancel($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $now = helper::now();
        $opportunity = fixer::input('post')
            ->setDefault('status','canceled')
            ->add('editedBy, canceledBy', $this->app->user->account)
            ->add('editedDate, canceledDate', $now)
            ->add('canceledBy', $this->app->user->account)
            ->add('canceledDate', $now)
            ->setDefault('activatedBy, hangupedBy', '')
            ->setDefault('activatedDate, hangupedDate', null)
            ->stripTags($this->config->opportunity->editor->cancel['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->batchCheck($this->config->opportunity->cancel->requiredFields, 'notempty')->where('id')->eq((int)$opportunityID)->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Hangup an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function hangup($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $now = helper::now();
        $opportunity = fixer::input('post')
            ->setDefault('status','hangup')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('hangupedBy', $this->app->user->account)
            ->add('hangupedDate', $now)
            ->add('cancelReason', '')
            ->setDefault('activatedBy, closedBy, canceledBy', '')
            ->setDefault('actualClosedDate, activatedDate, closedDate, canceledDate', null)
            ->stripTags($this->config->opportunity->editor->hangup['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();

        if(isset($opportunity->assignedTo)) $opportunity->assignedDate = helper::today();

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->where('id')->eq((int)$opportunityID)->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Activate an opportunity.
     *
     * @param  int    $opportunityID
     * @access public
     * @return array|bool
     */
    public function activate($opportunityID)
    {
        $oldOpportunity = $this->getByID($opportunityID);

        $now = helper::today();
        $opportunity = fixer::input('post')
            ->setDefault('status','active')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', $now)
            ->add('activatedBy', $this->app->user->account)
            ->add('activatedDate', $now)
            ->add('cancelReason', '')
            ->setDefault('hangupedBy, closedBy, canceledBy, resolvedBy', '')
            ->setDefault('actualClosedDate, hangupedDate,, closedDate, canceledDate, resolvedDate', null)
            ->stripTags($this->config->opportunity->editor->activate['id'], $this->config->allowedTags)
            ->remove('uid,comment')
            ->get();
        if(isset($opportunity->assignedTo)) $opportunity->assignedDate = $now;
        if($oldOpportunity->status == 'closed')
        {
            $opportunity->assignedTo   = '';
            $opportunity->assignedDate = $now;
        }

        $this->dao->update(TABLE_OPPORTUNITY)->data($opportunity)->autoCheck()->where('id')->eq((int)$opportunityID)->exec();

        if(!dao::isError()) return common::createChanges($oldOpportunity, $opportunity);
        return false;
    }

    /**
     * Get opportunity pairs of a user.
     *
     * @param  string $account
     * @param  int    $limit
     * @param  string $status all|active|closed|hangup|canceled
     * @param  array  $skipProjectIDList
     * @access public
     * @return array
     */
    public function getUserOpportunityPairs($account, $limit = 0, $status = 'all', $skipProjectIDList = array())
    {
        $stmt = $this->dao->select('t1.id, t1.name, t2.name as project')
            ->from(TABLE_OPPORTUNITY)->alias('t1')
            ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.lib')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('t1.status')->in($status)->fi()
            ->beginIF(!empty($skipProjectIDList))->andWhere('t1.project')->notin($skipProjectIDList)->fi()
            ->beginIF($limit)->limit($limit)->fi()
            ->query();

        $opportunities = array();
        while($opportunity = $stmt->fetch())
        {
            $opportunities[$opportunity->id] = $opportunity->project . ' / ' . $opportunity->name;
        }

        return $opportunities;
    }

    /**
     * 获取机会的相关人员。
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

    /**
     * 获取导出数据。
     * Get export data.
     *
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getExportData($orderBy)
    {
        return $this->dao->select('*')->from(TABLE_OPPORTUNITY)
            ->where($this->session->opportunityQueryCondition)
            ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);
    }
}
