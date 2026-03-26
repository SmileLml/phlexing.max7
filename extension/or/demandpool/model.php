<?php
class demandpoolModel extends model
{
    /**
     * Get demandpool list.
     * @param  string  $browseType
     * @param  int     $queryID
     * @param  string  $orderBy
     * @param  object  $pager
     * @access public
     * @return void
     */
    public function getList($browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
    {
        $account = $this->app->user->account;

        $demandpoolQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('demandpoolQuery', $query->sql);
                $this->session->set('demandpoolForm', $query->form);
            }

            if($this->session->demandpoolQuery == false) $this->session->set('demandpoolQuery', ' 1 = 1');
            $demandpoolQuery = $this->session->demandpoolQuery;
        }

        $superReviewers = array();
        $isAdmin        = $this->app->user->admin;

        if(!empty($this->config->demand->superReviewers)) $superReviewers = explode(',', $this->config->demand->superReviewers);

        $pools = $this->dao->select('*')->from(TABLE_DEMANDPOOL)
            ->where('deleted')->eq('0')
            ->beginIF(!in_array($this->app->user->account, $superReviewers) && !$isAdmin)
            ->andWhere('acl', true)->eq('open')
            ->orWhere('(acl')->eq('private')
            ->andWhere("CONCAT(',', owner, ',')")->like("%,$account,%")
            ->orWhere("CONCAT(',', reviewer, ',')")->like("%,$account,%")
            ->markRight(1)
            ->markRight(1)
            ->fi()
            ->beginIF($browseType == 'closed' or $browseType == 'normal')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'mine')->andWhere("CONCAT(',', owner, ',')")->like("%,$account,%")->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($demandpoolQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'demandpool', $browseType != 'bysearch');
        return $this->mergeSummary($pools);
    }

    /**
     * Get demandpool pairs.
     *
     * @param  string $param
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getPairs($param = '', $orderBy = 'id_desc')
    {
        $this->loadModel('demand');

        $superReviewers = array();
        $isAdmin        = $this->app->user->admin;
        if(!empty($this->config->demand->superReviewers)) $superReviewers = explode(',', $this->config->demand->superReviewers);

        $account = $this->app->user->account;

        $demandpools = $this->dao->select('id,name')->from(TABLE_DEMANDPOOL)
            ->where('status')->ne('deleted')
            ->beginIF(!in_array($this->app->user->account, $superReviewers) && !$isAdmin)
            ->andWhere('acl', true)->eq('open')
            ->orWhere('(acl')->eq('private')
            ->andWhere('createdBy', true)->eq($account)
            ->orWhere("CONCAT(',', owner, ',')")->like("%,$account,%")
            ->orWhere("CONCAT(',', reviewer, ',')")->like("%,$account,%")
            ->markRight(1)
            ->markRight(1)
            ->markRight(1)
            ->fi()
            ->beginIF(strpos($param, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchPairs();

        return $demandpools;
    }

    /**
     * Get demand by id.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function getByID($poolID)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDemandpool();

        $demandpool = $this->dao->findByID($poolID)->from(TABLE_DEMANDPOOL)->fetch();
        if(!$demandpool) return false;
        $demandpool->files = $this->loadModel('file')->getByObject('demandpool', $poolID);

        $demandpool = $this->loadModel('file')->replaceImgURL($demandpool, 'background,overview,desc');

        return $demandpool;
    }

    /**
     * Create a demandpool.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $demandpool = fixer::input('post')
            ->add('status', 'normal')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::today())
            ->remove('uid')
            ->setDefault('owner', '')
            ->join('reviewer', ',')
            ->join('owner', ',')
            ->join('products', ',')
            ->stripTags($this->config->demandpool->editor->create['id'], $this->config->allowedTags)
            ->get();

        $demandpool = $this->loadModel('file')->processImgURL($demandpool, $this->config->demandpool->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_DEMANDPOOL)->data($demandpool)
            ->autoCheck()
            ->batchCheck($this->config->demandpool->create->requiredFields, 'notempty')->exec();

        if(!dao::isError())
        {
            $poolID = $this->dao->lastInsertID();

            $this->loadModel('file')->updateObjectID($this->post->uid, $poolID, 'demandpool');
            $this->file->saveUpload('demandpool', $poolID);

            return $poolID;
        }

        return false;
    }

    /**
     * Update a demandpool.
     *
     * @access int $poolID
     * @access public
     * @return void
     */
    public function update($poolID)
    {
        $oldPool = $this->getByID($poolID);
        $demandpool = fixer::input('post')
            ->setDefault('owner', '')
            ->setDefault('products', '')
            ->join('owner', ',')
            ->join('reviewer', ',')
            ->join('products', ',')
            ->remove('uid,files,labels,comment,contactListMenu')
            ->stripTags($this->config->demandpool->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $demandpool = $this->loadModel('file')->processImgURL($demandpool, $this->config->demandpool->editor->edit['id'], $this->post->uid);

        $this->dao->update(TABLE_DEMANDPOOL)->data($demandpool)->autoCheck()
            ->batchCheck($this->config->demandpool->edit->requiredFields, 'notempty')
            ->where('id')->eq($poolID)
            ->exec();

        if(!dao::isError())
        {
            $this->loadModel('file')->updateObjectID($this->post->uid, $poolID, 'demandpool');
            $this->file->saveUpload('demandpool', $poolID);

            return common::createChanges($oldPool, $demandpool);
        }

        return false;
    }

    /**
     * Close a demandpool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function close($poolID)
    {
        $oldPool = $this->dao->findById($poolID)->from(TABLE_DEMANDPOOL)->fetch();
        $demandpool = fixer::input('post')
            ->add('status', 'closed')
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_DEMANDPOOL)->data($demandpool)->autoCheck()->where('id')->eq((int)$poolID)->exec();
        if(!dao::isError()) return common::createChanges($oldPool, $demandpool);
        return false;
    }

    /**
     * Activate a demandpool.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function activate($poolID)
    {
        $oldPool = $this->dao->findById($poolID)->from(TABLE_DEMANDPOOL)->fetch();
        $demandpool = fixer::input('post')
            ->add('status', 'normal')
            ->remove('uid,comment')
            ->get();

        $this->dao->update(TABLE_DEMANDPOOL)->data($demandpool)->autoCheck()->where('id')->eq((int)$poolID)->exec();

        if(!dao::isError()) return common::createChanges($oldPool, $demandpool);
        return false;
    }

    /**
     * Set demand pool menu.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function setMenu($poolID)
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;

        $this->lang->switcherMenu = $this->getSwitcher($poolID, $moduleName, $methodName);

        $poolID = $this->saveState($poolID, $this->getPairs());

        common::setMenuVars('demandpool', $poolID);
        return $poolID;
    }

    public function saveState($poolID = 0, $demandpools = array())
    {
        if($poolID == 0 and $this->cookie->lastDemandpool) $poolID = $this->cookie->lastDemandpool;
        if($poolID == 0 and (int)$this->session->demandpool == 0) $poolID = key($demandpools);
        if($poolID == 0) $poolID = key($demandpools);

        $this->session->set('demandpool', (int)$poolID, $this->app->tab);

        if(!isset($demandpools[$this->session->demandpool])) $this->accessDenied();

        return $this->session->demandpool;
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    public function accessDenied()
    {
        if(defined('TUTORIAL')) return true;

        echo(js::alert($this->lang->demandpool->accessDenied));
        $this->session->set('demandpool', '');

        return print(js::locate(helper::createLink('demandpool', 'browse')));
    }

    /**
     * Get reviewers.
     *
     * @param  int    $poolID
     * @param  string $excludeUser
     * @param  string $type
     * @access public
     * @return void
     */
    public function getReviewers($poolID = 0, $excludeUser = '', $type = 'reviewer')
    {
        if(!$poolID) return array();
        $this->loadModel('demand');

        $demandpool     = $this->getByID($poolID);
        $owners         = explode(',', trim($demandpool->owner, ','));
        $reviewer       = explode(',', trim($demandpool->reviewer, ','));
        $superReviewers = array();

        if(!empty($this->config->demand->superReviewers)) $superReviewers = explode(',', $this->config->demand->superReviewers);


        if($type == 'assign')
        {
            $reviewers = array_merge($owners, $reviewer, $superReviewers);
        }
        else
        {
            $reviewers = array_merge($reviewer, $superReviewers, $owners);
        }

        $reviewers = array_unique($reviewers);

        $reviewerPairs = $this->dao->select('*')->from(TABLE_USER)
            ->where('deleted')->eq(0)
            ->andWhere('account')->in($reviewers)
            ->fetchPairs('account', 'realname');

        $reviewers = array_flip($reviewers);

        foreach($reviewers as $account => $reviewer) $reviewers[$account] = isset($reviewerPairs[$account]) ? $reviewerPairs[$account] : $reviewer;

        if(isset($reviewers[$excludeUser]) and $type == 'reviewer') unset($reviewers[$excludeUser]);

        return arrayUnion(array('' => ''), $reviewers);
    }

    /**
     * Get AssignedTo pairs.
     *
     * @param  int    $poolID
     * @access public
     * @return void
     */
    public function getAssignedTo($poolID = 0)
    {
        if(!$poolID) return array();
        $demandpool = $this->getByID($poolID);
        $reviewers  = $this->getReviewers($poolID, '', 'assign');

        $assignToList     = $reviewers ? $reviewers : array();
        $assignToList[''] = '';

        if($demandpool->acl == 'private') return $assignToList;

        $groups = $this->dao->select('*')->from(TABLE_GROUPPRIV)
            ->where(1)
            ->andWhere('((module')->eq('demand')->andWhere('method')->eq('view')
            ->markRight(1)
            ->orWhere('(module')->eq('demand')->andWhere('method')->eq('browse')
            ->markRight(1)
            ->orWhere('(module')->eq('demandpool')->andWhere('method')->eq('browse')
            ->markRight(2)
            ->fetchGroup('group');

        if($groups)
        {
            foreach($groups as $groupID => $group) if(count($group) == 3) $groupIDList[] = $groupID;

            if($groupIDList)
            {
                $users = $this->dao->select('t2.account, t2.realname')
                    ->from(TABLE_USERGROUP)->alias('t1')
                    ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
                    ->where('`group`')->in($groupIDList)
                    ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")
                    ->andWhere('t2.deleted')->eq(0)
                    ->orderBy('t2.account')
                    ->fetchPairs();

                if($users) $assignToList += $users;
                $assignToList = array_unique($assignToList);
            }
        }

        return $assignToList;
    }

    /**
     * Get switcher.
     *
     * @param  int    $poolID
     * @param  int    $currentModule
     * @param  int    $currentMethod
     * @access public
     * @return void
     */
    public function getSwitcher($poolID, $currentModule, $currentMethod)
    {
        if($currentModule == 'demandpool' and $currentMethod == 'browse') return;

        $currentDemandpoolName = $this->lang->demandpool->common;
        if($poolID)
        {
            $currentDemandpool     = $this->getById($poolID);
            $currentDemandpoolName = $currentDemandpool->name;
        }

        if($this->app->viewType == 'mhtml' and $poolID)
        {
            $output  = $this->lang->demandpool->common . $this->lang->hyphen;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('demandpool', '$poolID', '$currentModule', '$currentMethod', '')\">{$currentDemandpoolName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('demandpool', 'ajaxGetOldDropMenu', "objectID=$poolID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentDemandpoolName}'><span class='text'>{$currentDemandpoolName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * Get pool link for dropmenu.
     *
     * @param  string    $module
     * @param  string    $method
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function getPoolLink($module, $method, $extra = '')
    {
        $link = helper::createLink($module, $method, 'poolID=%s');
        if($module == 'demand' && $method == 'view') $link = helper::createLink('demand', 'browse', 'poolID=%s');

        return $link;
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
        $this->config->demandpool->search['actionURL'] = $actionURL;
        $this->config->demandpool->search['queryID']   = $queryID;
        $this->config->demandpool->search['params']['product']['values'] = arrayUnion(array('' => ''), $this->loadModel('product')->getPairs());
        $this->config->demandpool->search['params']['dept']['values']    = arrayUnion(array('' => ''), $this->loadModel('dept')->getOptionMenu());

        $this->loadModel('search')->setSearchParams($this->config->demandpool->search);
    }

    public function printAssignedHtml($demandpool, $users)
    {
        $this->loadModel('task');
        $btnTextClass   = '';
        $assignedToText = zget($users, $demandpool->assignedTo);

        if(empty($demandpool->assignedTo))
        {
            $btnTextClass   = 'text-primary';
            $assignedToText = $this->lang->task->noAssigned;
        }
        if($demandpool->assignedTo == $this->app->user->account) $btnTextClass = 'text-red';

        $btnClass     = $demandpool->assignedTo == 'closed' ? ' disabled' : '';
        $btnClass     = "iframe btn btn-icon-left btn-sm {$btnClass}";
        $assignToLink = helper::createLink('demandpool', 'assignTo', "poolID=$demandpool->id", '', true);
        $assignToHtml = html::a($assignToLink, "<i class='icon icon-hand-right'></i> <span class='{$btnTextClass}'>{$assignedToText}</span>", '', "class='$btnClass'");

        echo !common::hasPriv('demandpool', 'assignTo', $demandpool) ? "<span style='padding-left: 21px' class='{$btnTextClass}'>{$assignedToText}</span>" : $assignToHtml;
    }

    public static function isClickable($demandpool, $action)
    {
        global $app;
        $action = strtolower($action);

        if($action == 'close')  return $demandpool->status != 'closed';

        return true;
    }

    /**
     * Merge demand summary.
     *
     * @param  array $pools
     * @access public
     * @return void
     */
    public function mergeSummary($pools)
    {
        $statusSummary = $this->dao->select('pool, status, COUNT(1) AS count')->from(TABLE_DEMAND)
            ->where('pool')->in(array_keys($pools))
            ->andWhere('deleted')->eq('0')
            ->andWhere('status')->in('draft,reviewing')
            ->groupBy('pool,status')
            ->fetchGroup('pool', 'status');

        $stageSummary = $this->dao->select('pool, stage, COUNT(1) AS count')->from(TABLE_DEMAND)
            ->where('pool')->in(array_keys($pools))
            ->andWhere('deleted')->eq('0')
            ->groupBy('pool,stage')
            ->fetchGroup('pool', 'stage');

        foreach($pools as $pool)
        {
            $pool->summary['draft']       = new stdclass();
            $pool->summary['reviewing']   = new stdclass();
            $pool->summary['wait']        = new stdclass();
            $pool->summary['willCharter'] = new stdclass();
            $pool->summary['inCharter']   = new stdclass();

            $pool->summary['draft']->count       = isset($statusSummary[$pool->id]['draft'])     ? $statusSummary[$pool->id]['draft']->count     : 0;
            $pool->summary['reviewing']->count   = isset($statusSummary[$pool->id]['reviewing']) ? $statusSummary[$pool->id]['reviewing']->count : 0;
            $pool->summary['wait']->count        = isset($stageSummary[$pool->id]['wait'])       ? $stageSummary[$pool->id]['wait']->count       : 0;
            $pool->summary['inCharter']->count   = isset($stageSummary[$pool->id]['incharter'])  ? $stageSummary[$pool->id]['incharter']->count  : 0;
            $pool->summary['willCharter']->count = 0;
            if(isset($stageSummary[$pool->id]))
            {
                $waitCount        = $pool->summary['wait']->count;
                $distributedCount = isset($stageSummary[$pool->id]['distributed']) ? $stageSummary[$pool->id]['distributed']->count : 0;
                $inRoadmapCount   = isset($stageSummary[$pool->id]['inroadmap'])   ? $stageSummary[$pool->id]['inroadmap']->count   : 0;

                $pool->summary['willCharter']->count = $waitCount + $distributedCount + $inRoadmapCount;
            }

        }

        return $pools;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $demandpool
     * @access public
     * @return array
     */
    public function getToAndCcList($demandpool)
    {
        /* Set toList and ccList. */
        $toList = trim($demandpool->owner, ',') . ',' . trim($demandpool->reviewer, ',');
        $ccList = '';
        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }
}
