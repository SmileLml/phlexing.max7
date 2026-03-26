<?php
/**
 * The model file of marketresearch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou<hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
class marketresearchModel extends Model
{
    /**
     * create a marketresearch.
     *
     * @access public
     * @return int|false
     */
    public function create()
    {
        $now        = helper::now();
        $marketName = $this->post->marketName;
        $account    = $this->app->user->account;

        $this->loadModel('execution');

        $research = fixer::input('post')
            ->callFunc('name', 'trim')
            ->setIF(!empty($_POST['longTime']), 'end', LONG_TIME)
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->remove('uid,newMarket,marketName,contactList,teamMembers,longTime')
            ->setDefault('status', 'wait')
            ->setDefault('vision', 'or')
            ->setDefault('model', 'research')
            ->setDefault('market', 0)
            ->setDefault('type', 'project')
            ->setDefault('multiple', '1')
            ->setDefault('team', $this->post->name)
            ->setDefault('openedBy', $account)
            ->setDefault('openedDate', $now)
            ->setDefault('days', '0')
            ->join('whitelist', ',')
            ->stripTags($this->config->marketresearch->editor->create['id'], $this->config->allowedTags)
            ->get();

        $research = $this->loadModel('file')->processImgURL($research, $this->config->marketresearch->editor->create['id'], $this->post->uid);

        $this->lang->project->name = $this->lang->marketresearch->name;

        if(empty($_POST['market']) && !empty($marketName)) $this->config->marketresearch->create->requiredFields = trim(str_replace(',market,', ',', ",{$this->config->marketresearch->create->requiredFields},"), ',');
        $this->dao->insert(TABLE_MARKETRESEARCH)->data($research)
            ->batchCheck($this->config->marketresearch->create->requiredFields, 'notempty')
            ->checkIF($research->end != '', 'end', 'ge', $research->begin)
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $researchID = $this->dao->lastInsertID();
            $this->loadModel('program')->setTreePath($researchID);

            /* Set team of research. */
            $members = array_unique(array($research->openedBy, $research->PM));
            $roles   = $this->loadModel('user')->getUserRoles(array_values($members));

            $teamMembers = array();
            foreach($members as $account)
            {
                if(empty($account)) continue;

                $member = new stdClass();
                $member->root    = $researchID;
                $member->type    = 'project';
                $member->account = $account;
                $member->role    = zget($roles, $account, '');
                $member->join    = helper::today();
                $member->days    = zget($research, 'days', 0);
                $member->hours   = $this->config->execution->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                $teamMembers[$account] = $member;
            }
            $this->execution->addProjectMembers($researchID, $teamMembers);

            if($research->acl != 'open' && isset($_POST['whitelist']))
            {
                $whitelist = $_POST['whitelist'];
                $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $researchID);
                $this->loadModel('user')->updateUserView(array($researchID), 'project');
            }

            if($marketName)
            {
                $marketID = $this->loadModel('market')->createMarketByName($marketName);
                if(!dao::isError())
                {
                    $this->loadModel('action')->create('market', $marketID, 'created');
                    $this->dao->update(TABLE_MARKETRESEARCH)
                        ->set('market')->eq($marketID)
                        ->where('id')->eq($researchID)
                        ->exec();
                }
            }

            return $researchID;
        }

        return false;
    }

    /**
     * Update a market research.
     *
     * @param  object  $oldResearch
     * @access public
     * @return array
     */
    public function update($oldResearch)
    {
        $research = fixer::input('post')
            ->add('id', $oldResearch->id)
            ->callFunc('name', 'trim')
            ->setDefault('team', $this->post->name)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF(!empty($_POST['longTime']), 'end', LONG_TIME)
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF(empty($_POST['market']), 'market', 0)
            ->join('whitelist', ',')
            ->stripTags($this->config->marketresearch->editor->edit['id'], $this->config->allowedTags)
            ->remove('delta,contactList,longTime,uid')
            ->get();

        $research = $this->loadModel('file')->processImgURL($research, $this->config->marketresearch->editor->edit['id'], $this->post->uid);

        $requiredFields = $this->config->marketresearch->edit->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $executionsCount = $this->dao->select('COUNT(1) AS count')->from(TABLE_PROJECT)->where('project')->eq($research->id)->andWhere('deleted')->eq('0')->fetch('count');

        if(!empty($executionsCount))
        {
            $minExecutionBegin = $this->dao->select('`begin` as minBegin')->from(TABLE_PROJECT)->where('project')->eq($research->id)->andWhere('deleted')->eq('0')->orderBy('begin_asc')->fetch();
            $maxExecutionEnd   = $this->dao->select('`end` as maxEnd')->from(TABLE_PROJECT)->where('project')->eq($research->id)->andWhere('deleted')->eq('0')->orderBy('end_desc')->fetch();
            if($minExecutionBegin and $research->begin > $minExecutionBegin->minBegin) dao::$errors['begin'] = sprintf($this->lang->project->begigLetterExecution, $minExecutionBegin->minBegin);
            if($maxExecutionEnd and $research->end < $maxExecutionEnd->maxEnd) dao::$errors['end'] = sprintf($this->lang->project->endGreateExecution, $maxExecutionEnd->maxEnd);
            if(dao::isError()) return false;
        }

        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($research->end, $research->begin) + 1;
        if(isset($research->days) and $research->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if(!isset($research->days)) $research->days = '0';

        $this->dao->update(TABLE_MARKETRESEARCH)->data($research)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF($research->begin != '', 'begin', 'date')
            ->checkIF($research->end != '', 'end', 'date')
            ->checkIF($research->end != '', 'end', 'gt', $research->begin)
            ->checkFlow()
            ->where('id')->eq($oldResearch->id)
            ->exec();
        if(dao::isError()) return false;

        $this->file->updateObjectID($this->post->uid, $research->id, 'project');

        /* Add PM to team. */
        $this->loadModel('execution');
        $members = array($research->PM);
        $roles   = $this->loadModel('user')->getUserRoles(array_values($members));

        $teamMembers = array();
        foreach($members as $account)
        {
            if(empty($account)) continue;

            $member = new stdClass();
            $member->root    = $research->id;
            $member->type    = 'project';
            $member->account = $account;
            $member->role    = zget($roles, $account, '');
            $member->join    = helper::today();
            $member->days    = zget($research, 'days', 0);
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();
            $teamMembers[$account] = $member;
        }
        if(!empty($members)) $this->execution->addProjectMembers($research->id, $teamMembers);

        if($research->acl != 'open')
        {
            $whitelist = explode(',', $research->whitelist);
            $this->loadModel('user')->updateUserView(array($research->id), 'project');
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $research->id);
        }

        return common::createChanges($oldResearch, $research);
    }

    /**
     * Close research.
     *
     * @param  int    $researchID
     * @access public
     * @return array
     */
    public function close($researchID)
    {
        $oldResearch = $this->loadModel('project')->getByID($researchID);
        $now         = helper::now();

        $editorIdList = $this->config->marketresearch->editor->close['id'];

        $research = fixer::input('post')
            ->add('id', $researchID)
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->remove('comment,uid')
            ->get();

        $this->lang->error->ge = $this->lang->project->ge;

        $research = $this->loadModel('file')->processImgURL($research, $editorIdList, $this->post->uid);

        $this->dao->update(TABLE_MARKETRESEARCH)->data($research)
            ->autoCheck()
            ->batchcheck($this->config->marketresearch->close->requiredFields, 'notempty')
            ->checkIF($research->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($research->realEnd != '', 'realEnd', 'ge', $oldResearch->realBegan)
            ->checkFlow()
            ->where('id')->eq((int)$researchID)
            ->exec();

        if(dao::isError())
        {
           if(count(dao::$errors['realEnd']) > 1) dao::$errors['realEnd'] = dao::$errors['realEnd'][0];
           return false;
        }
        return common::createChanges($oldResearch, $research);
    }

    /**
     * Activate stage.
     *
     * @param  int    $stageID
     * @access public
     * @return false|array
     */
    public function activateStage($stageID)
    {
        $oldStage = $this->getById($stageID);

        $stage = form::data($this->config->marketresearch->form->activatestage)
            ->add('id', $stageID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->remove('comment,readjustTask')
            ->get();

        if(empty($oldStage->totalConsumed) and helper::isZeroDate($oldStage->realBegan)) $stage->status = 'wait';

        $begin = $stage->begin;
        $end   = $stage->end;

        if($begin > $end) dao::$errors["message"][] = sprintf($this->lang->execution->errorLesserPlan, $end, $begin);

        if($oldStage->grade > 1)
        {
            $parent      = $this->dao->select('begin,end')->from(TABLE_PROJECT)->where('id')->eq($oldStage->parent)->fetch();
            $parentBegin = $parent->begin;
            $parentEnd   = $parent->end;
            if($begin < $parentBegin)
            {
                dao::$errors["message"][] = sprintf($this->lang->execution->errorLesserParent, $parentBegin);
            }

            if($end > $parentEnd)
            {
                dao::$errors["message"][] = sprintf($this->lang->execution->errorGreaterParent, $parentEnd);
            }
        }
        if(dao::isError()) return false;

        $stage = $this->loadModel('file')->processImgURL($stage, $this->config->execution->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($stage)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$stageID)
            ->exec();

        /* Readjust task. */
        if($this->post->readjustTask)
        {
            $this->loadModel('researchtask');
            $this->researchtask->readjustTask($oldStage, $stage, 'execution');
        }

        $changes = common::createChanges($oldStage, $stage);
        if($this->post->comment != '' or !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $stageID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * Close stage.
     *
     * @param  int    $stageID
     * @access public
     * @return array
     */
    public function closeStage($stageID)
    {
        $this->app->loadLang('project');
        $oldStage = $this->loadModel('execution')->getById($stageID);

        $stage = form::data($this->config->marketresearch->form->closestage)
            ->add('id', $stageID)
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->remove('comment')
            ->get();

        $this->lang->error->ge = $this->lang->execution->ge;

        $stage = $this->loadModel('file')->processImgURL($stage, $this->config->execution->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_MARKETRESEARCH)->data($stage)
            ->autoCheck()
            ->check($this->config->execution->close->requiredFields,'notempty')
            ->checkIF($stage->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($stage->realEnd != '', 'realEnd', 'ge', $oldStage->realBegan)
            ->checkFlow()
            ->where('id')->eq((int)$stageID)
            ->exec();

        if(!dao::isError())
        {
            $changes = common::createChanges($oldStage, $stage);
            if($this->post->comment != '' or !empty($changes))
            {
                $actionID = $this->loadModel('action')->create('execution', $stageID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            return $changes;
        }
    }

    /**
     * Activate research.
     *
     * @param  int    $researchID
     * @access public
     * @return array
     */
    public function activate($researchID)
    {
        $oldResearch = $this->loadModel('project')->getById($researchID);
        $now         = helper::now();

        $editorIdList = $this->config->marketresearch->editor->activate['id'];

        $research = fixer::input('post')
            ->add('id', $researchID)
            ->setDefault('realEnd', null)
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('closedReason', '')
            ->setIF(!helper::isZeroDate($oldResearch->realBegan), 'realBegan', helper::today())
            ->stripTags($editorIdList, $this->config->allowedTags)
            ->remove('comment,readjustTime,readjustTask')
            ->get();

        if(!$this->post->readjustTime)
        {
            unset($research->begin);
            unset($research->end);
        }

        $research = $this->loadModel('file')->processImgURL($research, $editorIdList, $this->post->uid);
        $this->dao->update(TABLE_MARKETRESEARCH)->data($research)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$researchID)
            ->exec();

        if(dao::isError()) return false;

        /* Readjust task. */
        if($this->post->readjustTime and $this->post->readjustTask)
        {
            $this->readjustTask($oldResearch, $research, 'project');
        }

        return common::createChanges($oldResearch, $research);
    }

    /**
     * Get market research by id.
     *
     * @param  int    $researchID
     * @access public
     * @return object
     */
    public function getById($researchID)
    {
        $research = $this->dao->select('*')->from(TABLE_MARKETRESEARCH)
            ->where('id')->eq($researchID)
            ->fetch();

        if(!$research) return false;

        if(helper::isZeroDate($research->end)) $research->end = '';
        $research = $this->loadModel('file')->replaceImgURL($research, 'desc');
        return $research;
    }

    /**
     * Get market research list.
     *
     * @param  int    $marketID
     * @param  string $status     all|doing|closed
     * @param  string $orderBy
     * @param  int    $involved
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($marketID = 0, $status = 'doing', $orderBy = 'id_desc', $involved = 0, $pager = null)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getProjectStats();

        $stmt = $this->dao->select('DISTINCT t1.*')->from(TABLE_MARKETRESEARCH)->alias('t1')
        ->leftJoin(TABLE_MARKET)->alias('t2')->on('t1.market=t2.id');

        if($this->cookie->involvedResearch || $involved) $stmt->leftJoin(TABLE_TEAM)->alias('t3')->on('t1.id=t3.root');

        $stmt->where('t1.`type`')->eq('project')
            ->andWhere('vision')->eq('or')
            ->andWhere('model')->eq('research')
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($marketID)->andWhere('market')->eq($marketID)->fi()
            ->beginIF(!$marketID)->andWhere('t2.deleted')->eq(0)->fi()
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi();

        if($this->cookie->involvedResearch || $involved)
        {
            $stmt->andWhere('(t3.type', true)->eq('project')
                ->andWhere('t3.account')->eq($this->app->user->account)
                ->markRight(1)
                ->orWhere('t1.openedBy')->eq($this->app->user->account)
                ->orWhere('t1.PM')->eq($this->app->user->account)
                ->orWhere("CONCAT(',', t1.whitelist, ',')")->like("%,{$this->app->user->account},%")
                ->markRight(1);
        }

        return $stmt->orderBy($orderBy)->page($pager, 't1.id')->fetchAll('id');
    }

    /**
     * Get pairs.
     *
     * @access public
     * @param  int    $marketID
     * @return array
     */
    public function getPairs($marketID = 0)
    {
        return $this->dao->select('id,name')->from(TABLE_MARKETRESEARCH)
            ->where('deleted')->eq(0)
            ->andWhere('model')->eq('research')
            ->andWhere('type')->eq('project')
            ->andWhere('vision')->eq('or')
            ->beginIF(!empty($marketID))->andWhere('market')->eq($marketID)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Print cell data.
     *
     * @param object $col
     * @param object $research
     * @param array  $users
     * @param array  $markets
     *
     * @access public
     * @return void
     */
    public function printCell($col, $research, $users, $markets)
    {
        $canView      = common::hasPriv('marketresearch', 'stage');
        $researchLink = helper::createLink('marketresearch', 'task', "id=$research->id&browseType=unclosed&param=0&orderBy=id_desc&recTotal=0&recPerPage=100&pageID=1");
        $id           = $col->id;
        if($col->show)
        {
            $class = "c-{$id}";
            if($id == 'status') $class .= ' c-status';
            if($id == 'name' || $id == 'market') $class .= ' text-left c-name';

            $title = '';
            if($id == 'name') $title = " title='{$research->name}'";
            if($id == 'market') $title = " title='" . zget($markets, $research->market, '') . "'";
            if($id == 'openedBy') $title = " title='" . zget($users, $research->openedBy) . "'";

            echo "<td class='" . $class . "'" . $title . ">";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('marketresearch', $research, $id);
            switch($id)
            {
            case 'id':
                printf('%03d', $research->id);
                break;
            case 'name':
                echo $canView ? html::a($researchLink, trim($research->name), '', "title='$research->name'") : "<span>$research->name</span>";
                break;
            case 'status':
                echo "<span class='status-{$research->status}'>" . $this->processStatus('marketresearch', $research) . "</span>";
                break;
            case 'market':
                echo zget($markets, $research->market, '');
                break;
            case 'PM':
                echo zget($users, $research->PM);
                break;
            case 'begin':
                echo helper::isZeroDate($research->begin) ? '' : $research->begin;
                break;
            case 'end':
                echo helper::isZeroDate($research->end) ? '' : $research->end;
                break;
            case 'realBegan':
                echo helper::isZeroDate($research->realBegan) ? '' : $research->realBegan;
                break;
            case 'realEnd':
                echo helper::isZeroDate($research->realEnd) ? '' : $research->realEnd;
                break;
            case 'openedBy':
                echo zget($users, $research->openedBy);
                break;
            case 'progress':
                echo html::ring($research->progress);
                break;
            case 'actions':
                echo $this->buildOperateMenu($research, 'browse');
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Build operate menu.
     *
     * @param  object $research
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($research, $type = 'view')
    {
        $menu = '';

        if($research->deleted) return $menu;

        if($type == 'view')
        {
            $startClass = $research->status == 'doing' ? 'hidden' : '';
            if($research->status == 'wait' || $research->status == 'doing') $menu .= $this->buildMenu('marketresearch', 'start', "researchID=$research->id&browseType=unclosed&param=0&orderBy=id_desc&recTotal=0&recPerPage=100&pageID=1", $research, 'view', 'play', '', $startClass . ' iframe', true, '', $this->lang->marketresearch->start);

            if($research->status == 'closed') $menu .= $this->buildMenu('marketresearch', 'activate', "researchID=$research->id", $research, 'view', 'magic', '', 'iframe', true, '', $this->lang->marketresearch->activate);
            if($research->status != 'closed') $menu .= $this->buildMenu('marketresearch', 'close', "researchID=$research->id", $research, 'view', 'off', '', 'iframe', true, '', $this->lang->marketresearch->close);
            $menu .= $this->buildMenu('marketresearch', 'edit', "researchID=$research->id", $research, 'browse');
        }

        if($type == 'browse')
        {
            $startClass  = $research->status == 'doing' ? ($type == 'view' ? 'hidden' : 'disabled') : '';
            if($research->status == 'wait' || $research->status == 'doing') $menu .= $this->buildMenu('marketresearch', 'start', "researchID=$research->id", $research, 'browse', 'play', '', $startClass . ' iframe', true, '', $this->lang->marketresearch->start);
            if($research->status == 'closed') $menu .= $this->buildMenu('marketresearch', 'activate', "researchID=$research->id", $research, 'browse', 'magic', '', 'iframe', true, '', $this->lang->marketresearch->activate);

            $closeClass = $research->status == 'doing' ? '' : 'disabled';
            $menu .= $this->buildMenu('marketresearch', 'close', "researchID=$research->id", $research, 'browse', 'off', '', $closeClass . ' iframe', true);

            $menu .= $this->buildMenu('marketresearch', 'edit', "researchID=$research->id", $research, 'browse');
            $menu .= $this->buildMenu('marketresearch', 'team', "researchID=$research->id", $research, 'browse', 'group');

            $reportClass = $research->status == 'wait' ? 'disabled' : '';
            $menu .= $this->buildMenu('marketresearch', 'reports', "researchID=$research->id", $research, 'browse', 'list-alt', '', $reportClass);
        }

        $menu .= $this->buildMenu('marketresearch', 'delete', "researchID=$research->id", $research, 'browse', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Get stat data.
     *
     * @param  int    $researchID
     * @param  array  $stageTasks
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return void
     */
    public function getStatData($researchID = 0, $stageTasks = array(), $orderBy = 'id_asc', $pager = null)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getResearchStageStats();

        $stages = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('type')->in('stage')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($researchID)->andWhere('project')->eq($researchID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* 获取所有父阶段。 */
        /* Get all parent stage. */
        $parentStages = $this->dao->select('*')->from(TABLE_EXECUTION)->where('deleted')->eq('0')->andWhere('type')->eq('stage')->andWhere('project')->eq($researchID)->andWhere('vision')->eq($this->config->vision)->fetchPairs('parent', 'id');

        $today       = helper::today();
        $stageIDList = array();
        foreach($stages as $stage)
        {
            $stageIDList[$stage->id] = $stage->id;

            /* Process the end time. */
            $stage->stageID = $stage->id;
            $stage->id      = 'sid_' . $stage->id;
            $stage->parent  = 'sid_' . $stage->parent;
            $stage->end     = date(DT_DATE1, strtotime($stage->end));

            /* 检查当前阶段是否可以创建任务。*/
            /* Check to see if tasks can be created at the current stage. */
            $stage->canCreateTask = isset($parentStages[$stage->stageID]) ? false : true;

            /* Judge whether the stage is delayed. */
            if($stage->status != 'done' and $stage->status != 'closed' and $stage->status != 'suspended')
            {
                $delay = helper::diffDate($today, $stage->end);
                if($delay > 0) $stage->delay = $delay;
            }
        }

        /* Delete the tasks that do not belong to the stage. */
        foreach($stageTasks as $taskID => $task)
        {
            if(!isset($stageIDList[$task->execution])) unset($stageTasks[$taskID]);

            if(isset($stages[$task->execution])) $stages[$task->execution]->hasTask = true;

            $task->PM        = $task->assignedTo;
            $task->rawParent = $task->parent;
            $task->isParent  = false;
            if($task->parent <= 0)
            {
                $task->isParent = true;
                $task->parent   = 'sid_' . $task->execution;
                continue;
            }
        }

        $stages = array_merge($stageTasks, $stages);
        return array_values($stages);
    }

    /**
     * Build task search form.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTaskSearchForm($executionID, $queryID, $actionURL)
    {
        $this->loadModel('execution');
        $this->config->execution->search['actionURL'] = $actionURL;
        $this->config->execution->search['queryID']   = $queryID;
        unset($this->config->execution->search['fields']['module']);
        unset($this->config->execution->search['fields']['execution']);
        unset($this->config->execution->search['fields']['fromBug']);
        unset($this->config->execution->search['fields']['closedReason']);

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    public function getTasks($executionID, $browseType, $queryID, $sort = 'id_desc', $pager = null)
    {

        $this->loadModel('task');
        $this->loadModel('execution');

        /* Get tasks. */
        $tasks = array();
        if($browseType != "bysearch")
        {
            $queryStatus = $browseType == 'byexecution' ? 'all' : $browseType;
            if($queryStatus == 'unclosed')
            {
                $queryStatus = $this->lang->task->statusList;
                unset($queryStatus['closed']);
                $queryStatus = implode(',', array_keys($queryStatus));
            }
            $tasks = $this->task->getResearchTasks($executionID, $queryStatus, $sort, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('taskQuery', $query->sql);
                    $this->session->set('taskForm', $query->form);
                }
                else
                {
                    $this->session->set('taskQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->taskQuery == false) $this->session->set('taskQuery', ' 1 = 1');
            }

            if(strpos($this->session->taskQuery, "deleted =") === false) $this->session->set('taskQuery', $this->session->taskQuery . " AND deleted = '0'");

            $taskQuery = $this->session->taskQuery;
            $this->session->set('taskQueryCondition', $taskQuery, $this->app->tab);
            $this->session->set('taskOnlyCondition', true, $this->app->tab);

            $tasks = $this->execution->getSearchTasks($taskQuery, $sort, $pager);

            /* 将子任务加入到父任务中。 */
            $childs = array();
            foreach($tasks as $task)
            {
                if(!empty($task->children)) $childs += $task->children;
            }

            if($childs) $tasks += $childs; // 保留键
        }

        return $tasks;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $object research|stage
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($object, $action)
    {
        $action = strtolower($action);

        if($action == 'close')     return $object->status != 'closed';
        if($action == 'start')     return $object->status == 'wait';
        if($action == 'activate')  return $object->status == 'done' || $object->status == 'closed';
        if($action == 'reports')   return $object->status != 'wait';

        if($action == 'startstage')    return $object->status == 'wait';
        if($action == 'closestage')    return $object->status != 'closed';
        if($action == 'activatestage') return $object->status == 'suspended' || $object->status == 'closed';

        return true;
    }

    /**
     * Create a task.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function createTask($executionID, $researchID = 0)
    {
        $this->loadModel('task');
        if((float)$this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->task->error->recordMinus;
            return false;
        }

        $executionID    = (int)$executionID;
        $estStarted     = null;
        $deadline       = null;
        $assignedTo     = '';
        $taskIdList     = array();
        $taskDatas      = array();
        $taskFiles      = array();
        $requiredFields = "," . $this->config->task->create->requiredFields . ",";

        $this->loadModel('file');
        $task = fixer::input('post')
            ->setDefault('execution', $executionID)
            ->setDefault('estimate,left,story', 0)
            ->setDefault('status', 'wait')
            ->setDefault('project', $researchID)
            ->setIF($this->post->estimate != false, 'left', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'estStarted') !== false, 'estStarted', helper::isZeroDate($this->post->estStarted) ? '' : $this->post->estStarted)
            ->setIF(strpos($requiredFields, 'deadline') !== false, 'deadline', helper::isZeroDate($this->post->deadline) ? '' : $this->post->deadline)
            ->setIF(strpos($requiredFields, 'estimate') !== false, 'estimate', $this->post->estimate)
            ->setIF(strpos($requiredFields, 'left') !== false, 'left', $this->post->left)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->setIF(is_numeric($this->post->consumed), 'consumed', (float)$this->post->consumed)
            ->setIF(is_numeric($this->post->left),     'left',     (float)$this->post->left)
            ->setIF(!$this->post->estStarted, 'estStarted', null)
            ->setIF(!$this->post->deadline, 'deadline', null)
            ->setDefault('openedBy',   $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('vision', $this->config->vision)
            ->cleanINT('execution,story,module')
            ->stripTags($this->config->task->editor->create['id'], $this->config->allowedTags)
            ->join('mailto', ',')
            ->remove('after,files,labels,assignedTo,uid,storyEstimate,storyDesc,storyPri,team,teamSource,teamEstimate,teamConsumed,teamLeft,teamMember,multiple,teams,contactListMenu,selectTestStory,testStory,testPri,testEstStarted,testDeadline,testAssignedTo,testEstimate,sync,otherLane,region,lane,estStartedDitto,deadlineDitto')
            ->add('version', 1)
            ->get();

        foreach($this->post->assignedTo as $assignedTo)
        {
            /* When type is affair and has assigned then ignore none. */
            if($task->type == 'affair' and count($this->post->assignedTo) > 1 and empty($assignedTo)) continue;

            $task->assignedTo = $assignedTo;
            if($assignedTo) $task->assignedDate = helper::now();

            $task = $this->loadModel('file')->processImgURL($task, $this->config->task->editor->create['id'], $this->post->uid);

            /* Fix Bug #1525 */
            $execution = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($task->execution)->fetch();

            if(strpos($requiredFields, ',estimate,') !== false)
            {
                if(strlen(trim($task->estimate)) == 0) dao::$errors['estimate'] = sprintf($this->lang->error->notempty, $this->lang->task->estimate);
                $requiredFields = str_replace(',estimate,', ',', $requiredFields);
            }

            if(strpos($requiredFields, ',estStarted,') !== false and !isset($task->estStarted)) dao::$errors['estStarted'] = sprintf($this->lang->error->notempty, $this->lang->task->estStarted);
            if(strpos($requiredFields, ',deadline,') !== false and !isset($task->deadline)) dao::$errors['deadline'] = sprintf($this->lang->error->notempty, $this->lang->task->deadline);
            if(isset($task->estStarted) and isset($task->deadline) and !helper::isZeroDate($task->deadline) and $task->deadline < $task->estStarted) dao::$errors['deadline'] = sprintf($this->lang->error->ge, $this->lang->task->deadline, $task->estStarted);

            if(dao::isError()) return false;

            $requiredFields = trim($requiredFields, ',');

            /* Replace the error tip when execution is empty. */
            $this->lang->task->execution = $this->lang->marketresearch->execution;

            $this->dao->insert(TABLE_TASK)->data($task, $skip = 'gitlab,gitlabProject')
                ->autoCheck()
                ->batchCheck($requiredFields, 'notempty')
                ->checkIF($task->estimate != '', 'estimate', 'float')
                ->checkIF(!helper::isZeroDate($task->deadline), 'deadline', 'ge', $task->estStarted)
                ->checkFlow()
                ->exec();

            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();

            $taskSpec = new stdClass();
            $taskSpec->task       = $taskID;
            $taskSpec->version    = $task->version;
            $taskSpec->name       = $task->name;

            if($task->estStarted) $taskSpec->estStarted = $task->estStarted;
            if($task->deadline)   $taskSpec->deadline   = $task->deadline;

            $this->dao->insert(TABLE_TASKSPEC)->data($taskSpec)->autoCheck()->exec();
            if(dao::isError()) return false;

            $this->file->updateObjectID($this->post->uid, $taskID, 'task');
            if(!empty($taskFiles))
            {
                foreach($taskFiles as $taskFile)
                {
                    $taskFile->objectID = $taskID;
                    $this->dao->insert(TABLE_FILE)->data($taskFile)->exec();
                }
            }
            else
            {
                $taskFileTitle = $this->file->saveUpload('task', $taskID);
                $taskFiles     = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in(array_keys($taskFileTitle))->fetchAll('id');
                foreach($taskFiles as $fileID => $taskFile) unset($taskFiles[$fileID]->id);
            }

            if(!dao::isError()) $this->loadModel('score')->create('task', 'create', $taskID);
            $taskIdList[$assignedTo] = array('status' => 'created', 'id' => $taskID);
        }
        return $taskIdList;
    }
}
