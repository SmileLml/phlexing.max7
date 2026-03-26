<?php
/**
 * The model file of pivot module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     pivot
 * @link        https://www.zentao.net
 */
class reportPivot extends pivotModel
{
    /**
     * Get work summary.
     *
     * @param  date    $begin
     * @param  date    $end
     * @param  int     $dept
     * @param  string  $type worksummary|workassignsummary
     * @param  obejct  $pager
     * @access public
     * @return array
     */
    public function getWorkSummary($begin, $end, $dept, $type, $pager = null)
    {
        $today = helper::today();
        $end   = date('Y-m-d', strtotime("$end +1 day"));
        $depts = array();
        if($dept) $depts = $this->loadModel('dept')->getAllChildId($dept);
        $condition = $type == 'worksummary';

        $projects   = $this->loadModel('project')->getPairsByProgram();
        $executions = $this->loadModel('execution')->getPairs();

        $userField = $type == 'worksummary' ? 'finishedBy' : 'assignedTo';

        $users = $this->dao->select('DISTINCT t3.account')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_TASKTEAM)->alias('t2')->on("t1.id = t2.task")
            ->leftJoin(TABLE_USER)->alias('t3')->on("t1.$userField = t3.account || t2.account = t3.account")
            ->where('t3.deleted')->eq('0')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ge(0)
            ->beginIF($type == 'worksummary')
            ->andWhere('(t1.status', true)->in('done,closed')
            ->andWhere("t1.finishedDate")->lt($end)->andWhere("t1.finishedDate")->ge($begin)
            ->markRight(1)
            ->orWhere('(t1.mode')->in('multi, linear')->andWhere('t1.status')->in('doing,done,closed')->markRight(1)
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'workassignsummary')
            ->andWhere("(t1.assignedDate", true)->lt($end)->andWhere("t1.assignedDate")->ge($begin)
            ->markRight(1)
            ->orWhere('t1.mode')->eq('multi')
            ->markRight(1)
            ->fi()
            ->beginIF($dept)->andWhere('t3.dept')->in($depts)->fi()
            ->orderBy('t3.account asc')
            ->page($pager, 't3.account')
            ->fetchPairs();
        if(empty($users)) return array();

        $tasks = $this->dao->select('t1.*')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_TASKTEAM)->alias('t2')->on("t1.id = t2.task")
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.parent')->ge('0')
            ->beginIF($type == 'worksummary')
            ->andWhere('(t1.status', true)->in('done,closed')
            ->andWhere("t1.finishedDate")->lt($end)->andWhere("t1.finishedDate")->ge($begin)
            ->andWhere("t1.finishedBy", true)->in($users)->orWhere('t2.account')->in($users)->markRight(1)
            ->markRight(1)
            ->orWhere('(t1.mode')->in('multi, linear')->andWhere('t1.status')->eq('doing,done,closed')->markRight(1)
            ->markRight(1)
            ->fi()
            ->beginIF($type == 'workassignsummary')
            ->andWhere("t1.assignedDate")->lt($end)->andWhere("t1.assignedDate")->ge($begin)
            ->andWhere("t1.assignedTo", true)->in($users)->orWhere('t2.account')->in($users)->markRight(1)
            ->fi()
            ->orderBy('execution')
            ->fetchAll('id', false);

        $teams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))
            ->beginIF($users)->andWhere('account')->in($users)->fi()
            ->fetchGroup('task', 'id');

        $userTasks = array();
        foreach($tasks as $task)
        {
            if(!isset($executions[$task->execution]) or (!isset($projects[$task->project]) and $this->config->systemMode == 'new')) continue;
            if($this->config->systemMode == 'classic') $task->project = 0;
            if(!helper::isZeroDate($task->deadline) and strpos('|wait|doing|', "|{$task->status}|") !== false)
            {
                $delay = helper::diffDate($today, $task->deadline);
                if($delay > 0) $task->delay = $delay;
            }

            if(isset($teams[$task->id]))
            {
                foreach($teams[$task->id] as $team)
                {
                    if($type == 'worksummary' && !in_array($team->status, array('done', 'closed'))) continue;
                    if($type == 'workassignsummary' && $task->mode == 'linear')
                    {
                        if(strpos('wait,doing', $team->status) === false) continue;
                        if(strpos('wait,doing', $team->status) !== false && $task->assignedTo != $team->account) continue;
                    }

                    $task->estimate = round($team->estimate, 1);
                    $task->consumed = round($team->consumed, 1);
                    $task->left     = round($team->left, 1);
                    $task->multiple = true;

                    $account = $team->account;
                    if($type == 'workassignsummary' && $task->status == 'closed') $account = 'closed';
                    if(!isset($userTasks[$account][$task->execution][$task->id]))
                    {
                        $userTasks[$account][$task->project][$task->execution][$task->id] = clone $task;
                    }
                    else
                    {
                        $userTasks[$account][$task->project][$task->execution][$task->id]->estimate += $task->estimate;
                        $userTasks[$account][$task->project][$task->execution][$task->id]->consumed += $task->consumed;
                        $userTasks[$account][$task->project][$task->execution][$task->id]->left     += $task->left;
                    }
                }
            }
            elseif(!in_array($task->mode, array('multi', 'linear')))
            {
                $task->multiple = false;
                if($condition)  $userTasks[$task->finishedBy][$task->project][$task->execution][$task->id] = $task;
                if(!$condition) $userTasks[$task->assignedTo][$task->project][$task->execution][$task->id] = $task;
            }
        }

        return $userTasks;
    }

    /**
     * Get bug summary.
     *
     * @param  int     $dept
     * @param  date    $begin
     * @param  date    $end
     * @param  string  $type worksummary|workassignsummary
     * @access public
     * @return array
     */
    public function getBugSummary($dept, $begin, $end, $type)
    {
        $depts = array();
        if($dept) $depts = $this->loadModel('dept')->getAllChildId($dept);

        $userField = $type == 'bugsummary' ? 'resolvedBy' : 'assignedTo';
        $dateField = $type == 'bugsummary' ? 'resolvedDate' : 'assignedDate';

        $end = date('Y-m-d', strtotime("$end +1 day"));

        $userBugs = $this->dao->select('t1.*')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on("t1.$userField=t2.account")
            ->where('t1.deleted')->eq(0)
            ->andWhere("t1.$dateField")->lt($end)
            ->andWhere("t1.$dateField")->ge($begin)
            ->beginIF($type == 'bugsummary')->andWhere('t1.status')->in('resolved, closed')->fi()
            ->beginIF($dept)->andWhere('t2.dept')->in($depts)->fi()
            ->fetchGroup($userField);
        return $userBugs;
    }

    /**
     * Get test cases.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getTestcases($productID)
    {
        /* Get createdVersion. */
        $createdVersion = $this->dao->select('createdVersion')->from(TABLE_PRODUCT)
            ->where('id')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('createdVersion_desc')
            ->limit(1)
            ->fetch('createdVersion');

        /* Check if it is new version. */
        $new = (!empty($createdVersion) and (!is_numeric($createdVersion[0]) or version_compare($createdVersion, '4.1', '>'))) ? true : false;

        $modules = $this->dao->select('id, name, path')->from(TABLE_MODULE)
            ->where('root')->eq($productID)
            ->beginIF($new)->andWhere('type')->in('story,case')->fi()
            ->beginIF(!$new)->andWhere('type')->eq('case')->fi()
            ->andWhere('grade')->eq('1')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $baseModule = new stdclass();
        $baseModule->name = '/';
        $baseModule->path = '';
        $modules = arrayUnion(array($baseModule), $modules);
        foreach($modules as $module)
        {
            $children = empty($module->path) ? 0 : $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like($module->path . '%')->andWhere('deleted')->eq(0)->fetchPairs();
            $cases    = $this->dao->select('id, status, lastRunResult')->from(TABLE_CASE)->where('module')->in($children)->andWhere('product')->eq($productID)->andWhere('deleted')->eq('0')->fetchAll();

            $module->pass    = 0;
            $module->blocked = 0;
            $module->fail    = 0;
            $module->run     = 0;
            $module->total   = count($cases);

            foreach($cases as $case)
            {
                if($case->status == 'normal' and $case->lastRunResult == 'pass')
                {
                    $module->pass ++;
                    $module->run  ++;
                }
                else if($case->status == 'normal' and $case->lastRunResult == 'fail')
                {
                    $module->fail ++;
                    $module->run  ++;
                }
                else if($case->status == 'normal' and $case->lastRunResult == 'blocked')
                {
                    $module->blocked ++;
                    $module->run     ++;
                }
            }
        }
        return $modules;
    }

    /**
     * Get build bugs.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getBuildBugs($productID)
    {
        $this->loadModel('build');
        $builds    = $this->dao->select('id, name, project, builds, bugs, stories, execution')->from(TABLE_BUILD)->where('product')->eq($productID)->andWhere('project')->ne('0')->andWhere('deleted')->eq('0')->fetchAll();
        $buildBugs = array();
        $summary   = array();
        foreach($builds as $build)
        {
            $build = $this->build->joinChildBuilds($build);
            $bugs  = $this->dao->select('id, severity, type, status')->from(TABLE_BUG)->where('id')->in($build->allBugs)->andWhere('deleted')->eq(0)->fetchAll();

            $buildBugs[$build->project][$build->id]['execution'] = $build->execution;
            $buildBugs[$build->project][$build->id]['severity']  = array();
            $buildBugs[$build->project][$build->id]['type']      = array();
            $buildBugs[$build->project][$build->id]['status']    = array();
            foreach($bugs as $bug)
            {
                $buildBugs[$build->project][$build->id]['severity'][$bug->severity] = isset($buildBugs[$build->project][$build->id]['severity'][$bug->severity]) ? ($buildBugs[$build->project][$build->id]['severity'][$bug->severity] + 1) : 1;
                $buildBugs[$build->project][$build->id]['type'][$bug->type]         = isset($buildBugs[$build->project][$build->id]['type'][$bug->type])         ? ($buildBugs[$build->project][$build->id]['type'][$bug->type] + 1) : 1;
                $buildBugs[$build->project][$build->id]['status'][$bug->status]     = isset($buildBugs[$build->project][$build->id]['status'][$bug->status])     ? ($buildBugs[$build->project][$build->id]['status'][$bug->status] + 1) : 1;

                $summary['severity'][$bug->severity][$bug->id] = 1;
                $summary['type'][$bug->type][$bug->id]         = 1;
                $summary['status'][$bug->status][$bug->id]     = 1;
            }
        }
        return array('bugs' => $buildBugs, 'summary' => $summary);
    }

    /**
     * Get roadmaps.
     *
     * @param  string $conditions
     * @access public
     * @return array
     */
    public function getRoadmaps($conditions = '')
    {
        $permission = common::hasPriv('pivot', 'showProduct') or $this->app->user->admin;
        $products   = $this->dao->select('t1.id as id,t1.name as name')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0)
            ->beginIF(empty($conditions))->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!$permission)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->filterTpl('skip')
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->fetchPairs('id', 'name');

        $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->andWhere('end')->gt(date('Y-m-d'))
            ->orderBy('begin')
            ->fetchGroup('product', 'id');
        return array('products' => $products, 'plans' => $plans);
    }

    /**
     * Get cases run data.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getCasesRun($productID)
    {
        $testtasks = $this->dao->select('t1.id,t1.name,t2.id as taskID')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t2.task=t1.id')
            ->leftJoin(TABLE_CASE)->alias('t3')->on('t2.case=t3.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->fetchGroup('id', 'taskID');

        $data = array();
        if(!empty($testtasks))
        {
            foreach($testtasks as $id => $tasks)
            {
                $data[$id]['name']    = $tasks[key($tasks)]->name;
                $data[$id]['fail']    = 0;
                $data[$id]['pass']    = 0;
                $data[$id]['blocked'] = 0;

                if(!key($tasks) && (count($tasks) == 1))
                {
                    $data[$id]['total'] = 0;
                }
                else
                {
                    $data[$id]['total'] = count(array_keys($tasks));

                    $results = $this->dao->select('caseResult')->from(TABLE_TESTRESULT)->where('run')->in(array_keys($tasks))->fetchAll();
                    if(!empty($results))
                    {
                        foreach($results as $result)
                        {
                            if(!isset($data[$id][$result->caseResult])) $data[$id][$result->caseResult] = 0;
                            $data[$id][$result->caseResult] += 1;
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Get story bugs.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getStoryBugs($productID, $moduleID)
    {
        /* Set modules and browse type. */
        $modules = $moduleID ? $this->loadModel('tree')->getAllChildId($moduleID) : '0';
        $bugs    = $this->dao->select('id,title,status,story')->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('story')->ne('0')
            ->beginIF($modules)->andWhere('module')->in($modules)->fi()
            ->fetchAll();

        $dataList = array();
        if(!empty($bugs))
        {
            foreach($bugs as $bug) $dataList[$bug->story]['bugList'][] = $bug;

            $stories = $this->dao->select('id,title')->from(TABLE_STORY)
                ->where('id')->in(array_keys($dataList))
                ->andWhere('deleted')->eq('0')
                ->fetchPairs('id', 'title');

            foreach($stories as $id => $title)
            {
                $dataList[$id]['title'] = $title;
                $dataList[$id]['total'] = count($dataList[$id]['bugList']);
            }
        }
        return $dataList;
    }

    /**
     * Get product invest.
     *
     * @param  string $conditions
     * @param  array  $filters
     * @access public
     * @return array
     */
    public function getProductInvest($conditions = '', $filters = array())
    {
        $productPairs = $this->loadModel('product')->getPairs();
        $productList  = $this->product->getByIdList(array_keys($productPairs));

        $productID     = isset($filters['productID'])     ? $filters['productID']     : 0;
        $productStatus = isset($filters['productStatus']) ? $filters['productStatus'] : '';
        $productType   = isset($filters['productType'])   ? $filters['productType']   : '';

        foreach($productPairs as $id => $productName)
        {
            $product = $productList[$id];
            if(($productID     && $productID != $product->id)
            || ($productStatus && $productStatus != $product->status)
            || ($productType   && $productType != $product->type))
            {
                unset($productPairs[$id]);
            }
        }

        /* Get project counts by product. */
        $projects = $this->dao->select('DISTINCT t1.project ,t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->in(array_keys($productPairs))
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll();

        $projectGroup = array();
        foreach($projects as $project)
        {
            if(!isset($projectGroup[$project->product])) $projectGroup[$project->product] = 0;
            $projectGroup[$project->product]++;
        }

        /* Get consumed. */
        $consumedGroup = $this->dao->select('product,objectType,sum(consumed) as consumed')->from(TABLE_EFFORT)
            ->where('deleted')->eq(0)
            ->groupBy('product, objectType')
            ->fetchGroup('product', 'objectType');

        /* Format invest data. */
        $investData = array();
        foreach($productPairs as $productID => $productName)
        {
            $productKey = ",$productID,";

            $invest = new stdclass();
            $invest->name          = $productName;
            $invest->projectCount  = isset($projectGroup[$productID]) ? $projectGroup[$productID] : 0;
            $invest->storyConsumed = isset($consumedGroup[$productKey]['story']->consumed)    ? round($consumedGroup[$productKey]['story']->consumed, 2)    : 0;
            $invest->taskConsumed  = isset($consumedGroup[$productKey]['task']->consumed)     ? round($consumedGroup[$productKey]['task']->consumed, 2)     : 0;
            $invest->bugConsumed   = isset($consumedGroup[$productKey]['bug']->consumed)      ? round($consumedGroup[$productKey]['bug']->consumed, 2)      : 0;
            $invest->caseConsumed  = isset($consumedGroup[$productKey]['testcase']->consumed) ? round($consumedGroup[$productKey]['testcase']->consumed, 2) : 0;
            $invest->totalConsumed = $invest->storyConsumed + $invest->taskConsumed + $invest->bugConsumed + $invest->caseConsumed;

            $investData[$productID] = $invest;
        }

        return $investData;
    }
}
