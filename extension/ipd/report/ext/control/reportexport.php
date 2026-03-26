<?php
helper::importControl('report');
class myReport extends report
{
    /**
     * Export report.
     *
     * @param  string $type
     * @param  string $params
     * @param  string $extra
     * @access public
     * @return void
     */
    public function reportExport($type, $params = '', $extra = '')
    {
        if($_POST)
        {
            $exportFunc = 'export' . $type;
            if($type == 'storyLinkedBug') $this->{$exportFunc}($params, $extra);
            if($type != 'storyLinkedBug') $this->{$exportFunc}($params);

            if(empty($_POST['fileName']))  $this->post->set('fileName', $this->lang->report->$type);
            $this->post->set('kind', isset($this->lang->report->$type) ? $this->lang->report->$type : $type);
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
            die();
        }
        $this->view->fileName = isset($this->lang->report->$type) ? $this->lang->report->$type : '';
        $this->display();
    }

    /**
     * Export product summary table.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function exportProductSummary($conditions)
    {
        $this->app->loadLang('product');
        $this->app->loadLang('productplan');
        $this->app->loadLang('story');
        $products = $this->report->getProducts($conditions);
        $users    = $this->loadModel('user')->getPairs('noletter|noclosed');

        $fields['name']    = $this->lang->product->name;
        $fields['PO']      = $this->lang->product->PO;
        $fields['plan']    = $this->lang->productplan->common;
        $fields['begin']   = $this->lang->productplan->begin;
        $fields['end']     = $this->lang->productplan->end;
        $fields['draft']   = $this->lang->story->statusList['draft'];
        $fields['active']  = $this->lang->story->statusList['active'];
        $fields['changed'] = $this->lang->story->statusList['changed'];
        $fields['closed']  = $this->lang->story->statusList['closed'];
        $fields['total']   = $this->lang->report->total;

        $i = 0;
        foreach($products as $product)
        {
            $count = isset($product->plans) ? count($product->plans) : 1;
            if($count > 1)
            {
                $rowspan[$i]['rows']['name'] = $count;
                $rowspan[$i]['rows']['PO']   = $count;
            }
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->name = $product->name;
            $data[$i]->PO   = zget($users, $product->PO);

            if(isset($product->plans))
            {
                foreach($product->plans as $plan)
                {
                    if(!isset($data[$i])) $data[$i] = new stdclass();
                    $data[$i]->plan    = $plan->title;
                    $data[$i]->begin   = $plan->begin;
                    $data[$i]->end     = $plan->end;
                    $data[$i]->draft   = (isset($plan->status['draft']) ? $plan->status['draft'] : 0);
                    $data[$i]->active  = (isset($plan->status['active']) ? $plan->status['active'] : 0);
                    $data[$i]->changed = (isset($plan->status['changed']) ? $plan->status['changed'] : 0);
                    $data[$i]->closed  = (isset($plan->status['closed']) ? $plan->status['closed'] : 0);
                    $data[$i]->total   = $data[$i]->draft + $data[$i]->active + $data[$i]->changed + $data[$i]->closed;
                    $i++;
                }
            }
            else
            {
                $data[$i]->plan    = '';
                $data[$i]->begin   = '';
                $data[$i]->end     = '';
                $data[$i]->draft   = 0;
                $data[$i]->active  = 0;
                $data[$i]->changed = 0;
                $data[$i]->closed  = 0;
                $data[$i]->total   = 0;
                $i ++;
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export product roadmap table.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function exportRoadmap($conditions)
    {
        $this->app->loadConfig('productplan');

        /* Get product roadmap and set products and plans. */
        $roadmaps = $this->report->getRoadmaps($conditions);
        $products = $roadmaps['products'];
        $plans    = $roadmaps['plans'];

        /* Set fields. */
        $fields['product'] = $this->lang->report->product;
        $fields['plan']    = $this->lang->report->plan;

        /* Get roadmap data. */
        $i = 0;
        foreach($products as $productID => $productName)
        {
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->product = $productName;

            $planContent = '';
            if(!empty($plans[$productID]))
            {
                foreach($plans[$productID] as $plan)
                {
                    $planContent .= '  ' . $plan->title;
                    if($plan->begin == $this->config->productplan->future and $plan->end == $this->config->productplan->future)
                    {
                        $planContent .= ' ' . $this->lang->report->future;
                    }
                    else
                    {
                        if($plan->begin == $this->config->productplan->future) $plan->begin = $this->lang->report->future;
                        if($plan->end == $this->config->productplan->future) $plan->end = $this->lang->report->future;
                        $planContent .= ' (' . $plan->begin . ' ~ ' . $plan->end . ') ';
                    }
                }
            }
            $data[$i]->plan = $planContent;
            $i++;
        }

        /* Set table fields and data. */
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export product invest table.
     *
     * @param  string $conditions
     * @access public
     * @return void
     */
    public function exportProductInvest($conditions)
    {
        $this->app->loadLang('product');

        /* Get product invest. */
        $data = $this->report->getProductInvest($conditions);

        /* Set fields. */
        $fields['name']          = $this->lang->product->name;
        $fields['projectCount']  = $this->lang->report->projects;
        $fields['storyConsumed'] = $this->lang->report->storyConsumed;
        $fields['taskConsumed']  = $this->lang->report->taskConsumed;
        $fields['bugConsumed']   = $this->lang->report->bugConsumed;
        $fields['caseConsumed']  = $this->lang->report->caseConsumed;
        $fields['totalConsumed'] = $this->lang->report->totalConsumed;

        /* Set table fields and data. */
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export execution deviation report.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportExecutionDeviation($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $executions = $this->report->getExecutions($begin, $end);

        $fields['id']            = $this->lang->report->id;
        $fields['project']       = $this->lang->report->project;
        $fields['execution']     = $this->lang->report->execution;
        $fields['estimate']      = $this->lang->report->estimate;
        $fields['consumed']      = $this->lang->report->consumed;
        $fields['deviation']     = $this->lang->report->deviation;
        $fields['deviationRate'] = $this->lang->report->deviationRate;

        $i = 0;
        foreach($executions as $id  => $execution)
        {
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->id = $id;
            $data[$i]->project   = $execution->projectName;
            $data[$i]->execution = $execution->name;
            $data[$i]->estimate  = $execution->estimate;
            $data[$i]->consumed  = $execution->consumed;

            $deviation = $execution->consumed - $execution->estimate;
            $data[$i]->deviation = (int)$deviation;

            $num = $execution->estimate ? round($deviation / $execution->estimate * 100, 2) : 0;
            $data[$i]->deviationRate = (int)$num . '%';
            $i ++;
        }

        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export bug creation table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportBugCreate($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $this->app->loadLang('bug');
        $bugs       = $this->report->getBugs($begin, $end, $product, $execution);
        $users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $executions = arrayUnion(array('' => ''), $this->loadModel('execution')->getPairs());
        $products   = arrayUnion(array('' => ''), $this->loadModel('product')->getPairs());

        $fields['openedBy']   = $this->lang->bug->openedBy;
        $fields['unResolved'] = $this->lang->bug->unResolved;
        foreach($this->lang->bug->resolutionList as $resolutionType => $resolution)
        {
            $fields[$resolutionType] = $resolution;
        }

        $fields['validRate'] = $this->lang->report->validRate;
        $fields['total']     = $this->lang->report->total;

        $i = 0;
        foreach($bugs as $user => $bug)
        {
            if(!isset($users[$user])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->openedBy   = $users[$user];
            $data[$i]->unResolved = isset($bug['']) ? $bug[''] : 0;

            foreach($this->lang->bug->resolutionList as $resolutionType => $resolution)
            {
                $fields[$resolutionType] = $resolution;
                $data[$i]->$resolutionType = zget($bug, $resolutionType, 0);
            }

            $data[$i]->validRate = round($bug['validRate'] * 100, 2) . '%';
            $data[$i]->total     = $bug['all'];
            $i++;
        }

        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export bug assignment table.
     *
     * @access public
     * @return void
     */
    public function exportBugAssign()
    {
        $assigns = $this->report->getBugAssign();
        $users   = $this->loadModel('user')->getPairs('noletter|noclosed');

        $fields['user']    = $this->lang->report->user;
        $fields['product'] = $this->lang->report->product;
        $fields['bug']     = $this->lang->report->bug;
        $fields['total']   = $this->lang->report->total;

        $i = 0;
        $data = array();
        foreach($assigns as $account => $assign)
        {
            if(!isset($users[$account])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();
            if(count($assign['bug']) > 1)
            {
                $rowspan[$i]['rows']['user']  = count($assign['bug']);
                $rowspan[$i]['rows']['total'] = count($assign['bug']);
            }

            $data[$i]->user = $users[$account];
            $id = 1;
            foreach($assign['bug'] as $product => $count)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                $data[$i]->product = $product;
                $data[$i]->bug     = $count['count'];
                if($id == 1) $data[$i]->total = $assign['total']['count'];
                $id++;
                $i++;
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export test case statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function exportTestcase($productID = 0)
    {
        $products = $this->loadModel('product')->getPairs();
        if(!$productID) $productID = key($products);

        $this->app->loadLang('testcase');
        $modules = $this->report->getTestcases($productID);

        $fields['module']   = $this->lang->report->moduleName;
        $fields['total']    = $this->lang->report->case->total;
        $fields['pass']     = $this->lang->testcase->resultList['pass'];
        $fields['fail']     = $this->lang->testcase->resultList['fail'];
        $fields['blocked']  = $this->lang->testcase->resultList['blocked'];
        $fields['run']      = $this->lang->report->case->run;
        $fields['passRate'] = $this->lang->report->case->passRate;

        $i = 0;
        foreach($modules as $module)
        {
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->module   = $module->name;
            $data[$i]->total    = $module->total;
            $data[$i]->pass     = $module->pass;
            $data[$i]->fail     = $module->fail;
            $data[$i]->blocked  = $module->blocked;
            $data[$i]->run      = $module->run;
            $data[$i]->passRate = $module->run ? round(($module->pass / $module->run) * 100, 2) . '%' : 'N/A';
            $i++;
        }

        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export build table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function exportBuild($productID = 0)
    {
        $products = $this->loadModel('product')->getPairs();
        if(!$productID) $productID = key($products);

        $this->app->loadLang('bug');
        $buildBugs  = $this->report->getBuildBugs($productID);
        $bugs       = $buildBugs['bugs'];
        $summary    = $buildBugs['summary'];
        $executions = $this->loadModel('product')->getExecutionPairsByProduct($productID, $branch = 0, 'nodeleted');
        $builds     = $this->loadModel('build')->getBuildPairs($productID);

        $fields['execution']   = $this->lang->report->execution;
        $fields['buildTitle']  = $this->lang->report->buildTitle;
        $fields['severity1']   = $this->lang->bug->severityList[1];
        $fields['severity2']   = $this->lang->bug->severityList[2];
        $fields['severity3']   = $this->lang->bug->severityList[3];
        $fields['severity4']   = $this->lang->bug->severityList[4];
        $fields['codeerror']   = $this->lang->report->bugTypeList['codeerror'];
        $fields['interface']   = $this->lang->report->bugTypeList['interface'];
        $fields['config']      = $this->lang->report->bugTypeList['config'];
        $fields['install']     = $this->lang->report->bugTypeList['install'];
        $fields['security']    = $this->lang->report->bugTypeList['security'];
        $fields['performance'] = $this->lang->report->bugTypeList['performance'];
        $fields['standard']    = $this->lang->report->bugTypeList['standard'];
        $fields['automation']  = $this->lang->report->bugTypeList['automation'];
        $fields['others']      = $this->lang->report->bugTypeList['others'];
        $fields['active']      = $this->lang->bug->statusList['active'];
        $fields['resolved']    = $this->lang->bug->statusList['resolved'];
        $fields['closed']      = $this->lang->bug->statusList['closed'];

        $i = 0;
        foreach($bugs as $key => $executionBuilds)
        {
            $count = count($executionBuilds);
            if($count > 1) $rowspan[$i]['rows']['execution'] = $count;

            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->execution = $executions[$key];
            foreach($executionBuilds as $buildId => $build)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                $data[$i]->buildTitle  = $builds[$buildId];
                $data[$i]->severity1   = isset($build['severity'][1])? $build['severity'][1] : 0;
                $data[$i]->severity2   = isset($build['severity'][2])? $build['severity'][2] : 0;
                $data[$i]->severity3   = isset($build['severity'][3])? $build['severity'][3] : 0;
                $data[$i]->severity4   = isset($build['severity'][4])? $build['severity'][4] : 0;
                $data[$i]->codeerror   = isset($build['type']['codeerror'])? $build['type']['codeerror'] : 0;
                $data[$i]->interface   = isset($build['type']['interface'])? $build['type']['interface'] : 0;
                $data[$i]->config      = isset($build['type']['config'])? $build['type']['config'] : 0;
                $data[$i]->install     = isset($build['type']['install'])? $build['type']['install'] : 0;
                $data[$i]->security    = isset($build['type']['security'])? $build['type']['security'] : 0;
                $data[$i]->performance = isset($build['type']['performance'])? $build['type']['performance'] : 0;
                $data[$i]->standard    = isset($build['type']['standard'])? $build['type']['standard'] : 0;
                $data[$i]->automation  = isset($build['type']['automation'])? $build['type']['automation'] : 0;
                $data[$i]->others      = isset($build['type']['others'])? $build['type']['others'] : 0;
                $data[$i]->active      = isset($build['status']['active'])? $build['status']['active'] : 0;
                $data[$i]->resolved    = isset($build['status']['resolved'])? $build['status']['resolved'] : 0;
                $data[$i]->closed      = isset($build['status']['closed'])? $build['status']['closed'] : 0;

                $i++;
            }
        }

        /* Constructing aggregate data. */
        $data[$i] = new stdclass();
        $data[$i]->execution   = $this->lang->report->bug->total;
        $data[$i]->severity1   = isset($summary['severity'][1]) ? count($summary['severity'][1]) : 0;
        $data[$i]->severity2   = isset($summary['severity'][2]) ? count($summary['severity'][2]) : 0;
        $data[$i]->severity3   = isset($summary['severity'][3]) ? count($summary['severity'][3]) : 0;
        $data[$i]->severity4   = isset($summary['severity'][4]) ? count($summary['severity'][4]) : 0;
        $data[$i]->codeerror   = isset($summary['type']['codeerror'])   ? count($summary['type']['codeerror'])   : 0;
        $data[$i]->interface   = isset($summary['type']['interface'])   ? count($summary['type']['interface'])   : 0;
        $data[$i]->config      = isset($summary['type']['config'])      ? count($summary['type']['config'])      : 0;
        $data[$i]->install     = isset($summary['type']['install'])     ? count($summary['type']['install'])     : 0;
        $data[$i]->security    = isset($summary['type']['security'])    ? count($summary['type']['security'])    : 0;
        $data[$i]->performance = isset($summary['type']['performance']) ? count($summary['type']['performance']) : 0;
        $data[$i]->standard    = isset($summary['type']['standard'])    ? count($summary['type']['standard'])    : 0;
        $data[$i]->automation  = isset($summary['type']['automation'])  ? count($summary['type']['automation'])  : 0;
        $data[$i]->others      = isset($summary['type']['others'])      ? count($summary['type']['others'])      : 0;
        $data[$i]->active      = isset($summary['status']['active'])    ? count($summary['status']['active'])    : 0;
        $data[$i]->resolved    = isset($summary['status']['resolved'])  ? count($summary['status']['resolved'])  : 0;
        $data[$i]->closed      = isset($summary['status']['closed'])    ? count($summary['status']['closed'])    : 0;

        $colspan = array();
        $colspan[$i]['cols'] = 'execution';
        $colspan[$i]['num']  = 2;

        $this->post->set("colspan", $colspan);

        if(isset($rowspan)) $this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export employee load table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportWorkload($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $workload = $this->report->getWorkload($dept);
        $users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $depts    = $this->loadModel('dept')->getOptionMenu();
        $allHour  = $days * $workday;

        $fields['user']         = $this->lang->report->user;
        $fields['project']      = $this->lang->report->project;
        $fields['execution']    = $this->lang->report->execution;
        $fields['task']         = $this->lang->report->task;
        $fields['remain']       = $this->lang->report->remain;
        $fields['taskTotal']    = $this->lang->report->taskTotal;
        $fields['manhourTotal'] = $this->lang->report->manhourTotal;
        $fields['workload']     = $this->lang->report->workloadAB;

        $i = 0;
        foreach($workload as $account => $load)
        {
            if(!isset($users[$account])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();

            if(count($load['task']['project']) > 0)
            {
                $count = 0;
                foreach($load['task']['project'] as $executions) $count += count($executions['execution']);

                $rowspan[$i]['rows']['user']         = $count;
                $rowspan[$i]['rows']['taskTotal']    = $count;
                $rowspan[$i]['rows']['manhourTotal'] = $count;
                $rowspan[$i]['rows']['workload']     = $count;
            }

            $data[$i]->user = $users[$account];

            $id = 1;
            foreach($load['task']['project'] as $projectName => $executions)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                $data[$i]->project = $projectName;

                if(count($executions['execution']) > 1) $rowspan[$i]['rows']['project'] = count($executions['execution']);
                foreach($executions['execution'] as $executionName => $info)
                {
                    if(!isset($data[$i])) $data[$i] = new stdclass();
                    $data[$i]->execution = $executionName;
                    $data[$i]->task      = $info['count'];
                    $data[$i]->remain    = $info['manhour'];
                    if($id == 1)
                    {
                        $data[$i]->taskTotal    = $load['total']['count'];
                        $data[$i]->manhourTotal = $load['total']['manhour'];
                        $data[$i]->workload     = round($load['total']['manhour'] / $allHour * 100, 2) . '%';
                    }
                    $id ++;
                    $i++;
                }
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export task completion summary table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportWorkSummary($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $this->app->loadLang('task');
        $users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $depts      = $this->loadModel('dept')->getOptionMenu();
        $projects   = $this->loadModel('project')->getPairsByProgram();
        $executions = $this->loadModel('execution')->getPairs();
        $userTasks  = $this->report->getWorkSummary($begin, $end, $dept, 'worksummary');

        $fields['finishedBy']        = $this->lang->task->finishedByAB;
        if($this->config->systemMode == 'new') $fields['project'] = $this->lang->task->project;
        $fields['execution']         = $this->lang->task->execution;
        $fields['id']                = $this->lang->task->id;
        $fields['name']              = $this->lang->task->name;
        $fields['pri']               = $this->lang->task->pri;
        $fields['estStarted']        = $this->lang->task->estStarted;
        $fields['realStarted']       = $this->lang->task->realStarted;
        $fields['deadline']          = $this->lang->task->deadline;
        $fields['finishedDate']      = $this->lang->task->finishedDate;
        $fields['delay']             = $this->lang->report->delay . '(' . $this->lang->report->day . ')';
        $fields['estimate']          = $this->lang->task->estimate;
        $fields['consumed']          = $this->lang->task->consumed;
        $fields['executionTotal']    = $this->lang->report->taskTotal;
        $fields['executionConsumed'] = $this->lang->report->consumed;
        $fields['totalConsumed']     = $this->lang->report->consumed;

        $i = 0;
        foreach($userTasks as $user => $projectTasks)
        {
            if(!isset($data[$i])) $data[$i] = new stdclass();

            $totalConsumed     = 0;
            $taskTotal         = 0;
            $executionConsumed = array();
            foreach($projectTasks as $executionTasks)
            {
                foreach($executionTasks as $tasks)
                {
                    $taskTotal += count($tasks);
                    foreach($tasks as $task)
                    {
                        if(!isset($executionConsumed[$task->execution])) $executionConsumed[$task->execution] = 0;
                        $executionConsumed[$task->execution] += $task->consumed;
                        $totalConsumed += $task->consumed;
                    }
                }
            }

            $data[$i]->finishedBy    = zget($users, $user);
            $data[$i]->totalConsumed = $totalConsumed;
            if($taskTotal > 1)
            {
                $rowspan[$i]['rows']['finishedBy']    = $taskTotal;
                $rowspan[$i]['rows']['totalConsumed'] = $taskTotal;
            }

            foreach($projectTasks as $projectID => $executionTasks)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                if($this->config->systemMode == 'new') $data[$i]->project              = zget($projects, $projectID, '');
                if($this->config->systemMode == 'new') $rowspan[$i]['rows']['project'] = count($executionTasks, 1) - count($executionTasks);

                foreach($executionTasks as $executionID => $tasks)
                {
                    if(!isset($data[$i])) $data[$i] = new stdclass();

                    $data[$i]->execution         = zget($executions, $executionID, '');
                    $data[$i]->executionTotal    = count($tasks);
                    $data[$i]->executionConsumed = zget($executionConsumed, $executionID, 0);

                    if($data[$i]->executionTotal > 1)
                    {
                        $rowspan[$i]['rows']['execution']         = $data[$i]->executionTotal;
                        $rowspan[$i]['rows']['executionTotal']    = $data[$i]->executionTotal;
                        $rowspan[$i]['rows']['executionConsumed'] = $data[$i]->executionTotal;
                    }

                    foreach($tasks as $id => $task)
                    {
                        if(!isset($data[$i])) $data[$i] = new stdclass();

                        $prefix = '';
                        if($task->parent > 0) $prefix .= "[{$this->lang->task->childrenAB}]";
                        if($task->multiple)   $prefix .= "[{$this->lang->task->multipleAB}]";
                        $data[$i]->id           = $task->id;
                        $data[$i]->name         = $prefix . $task->name;
                        $data[$i]->pri          = $task->pri;
                        $data[$i]->estStarted   = $task->estStarted;
                        $data[$i]->realStarted  = substr($task->realStarted, 0, 10);
                        $data[$i]->deadline     = $task->deadline;
                        $data[$i]->finishedDate = substr($task->finishedDate, 0, 10);
                        $data[$i]->delay        = '';
                        if(!helper::isZeroDate($task->deadline))
                        {
                            $finishedDate = strtotime(substr($task->finishedDate, 0, 10));
                            $deadline     = strtotime($task->deadline);
                            $days         = round(($finishedDate - $deadline) / 3600 / 24);
                            if($days > 0) $data[$i]->delay = $days;
                        }
                        $data[$i]->estimate = $task->estimate;
                        $data[$i]->consumed = $task->consumed;
                        $i++;
                    }
                }
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export task assignment summary table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportWorkAssignSummary($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $this->app->loadLang('task');
        $users      = $this->loadModel('user')->getPairs('noletter|noclosed');
        $depts      = $this->loadModel('dept')->getOptionMenu();
        $projects   = $this->loadModel('project')->getPairsByProgram();
        $executions = $this->loadModel('execution')->getPairs();
        $userTasks  = $this->report->getWorkSummary($begin, $end, $dept, 'workassignsummary');

        $fields['assignedTo']        = $this->lang->task->assignedTo;
        if($this->config->systemMode == 'new') $fields['project'] = $this->lang->task->project;
        $fields['execution']         = $this->lang->task->execution;
        $fields['id']                = $this->lang->task->id;
        $fields['name']              = $this->lang->task->name;
        $fields['pri']               = $this->lang->task->pri;
        $fields['estStarted']        = $this->lang->task->estStarted;
        $fields['realStarted']       = $this->lang->task->realStarted;
        $fields['deadline']          = $this->lang->task->deadline;
        $fields['assignedDate']      = $this->lang->task->assignedDate;
        $fields['delay']             = $this->lang->report->delay . '(' . $this->lang->report->day . ')';
        $fields['estimate']          = $this->lang->task->estimate;
        $fields['consumed']          = $this->lang->task->consumed;
        $fields['executionTotal']    = $this->lang->report->taskTotal;
        $fields['executionConsumed'] = $this->lang->report->consumed;
        $fields['totalConsumed']     = $this->lang->report->consumed;

        $i = 0;
        foreach($userTasks as $user => $projectTasks)
        {
            if(!isset($users[$user])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();

            $totalConsumed     = 0;
            $taskTotal         = 0;
            $executionConsumed = array();
            foreach($projectTasks as $executionTasks)
            {
                foreach($executionTasks as $tasks)
                {
                    $taskTotal += count($tasks);
                    foreach($tasks as $task)
                    {
                        if(!isset($executionConsumed[$task->execution])) $executionConsumed[$task->execution] = 0;
                        $executionConsumed[$task->execution] += $task->consumed;
                        $totalConsumed += $task->consumed;
                    }
                }
            }

            $data[$i]->assignedTo    = zget($users, $user);
            $data[$i]->totalConsumed = $totalConsumed;
            if($taskTotal > 1)
            {
                $rowspan[$i]['rows']['assignedTo']    = $taskTotal;
                $rowspan[$i]['rows']['totalConsumed'] = $taskTotal;
            }

            foreach($projectTasks as $projectID => $executionTasks)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                if($this->config->systemMode == 'new') $data[$i]->project              = zget($projects, $projectID, '');
                if($this->config->systemMode == 'new') $rowspan[$i]['rows']['project'] = count($executionTasks, 1) - count($executionTasks);

                foreach($executionTasks as $executionID => $tasks)
                {
                    if(!isset($data[$i])) $data[$i] = new stdclass();
                    $data[$i]->execution         = zget($executions, $executionID, '');
                    $data[$i]->executionTotal    = count($tasks);
                    $data[$i]->executionConsumed = zget($executionConsumed, $executionID, 0);
                    if($data[$i]->executionTotal > 1)
                    {
                        $rowspan[$i]['rows']['execution']         = $data[$i]->executionTotal;
                        $rowspan[$i]['rows']['executionTotal']    = $data[$i]->executionTotal;
                        $rowspan[$i]['rows']['executionConsumed'] = $data[$i]->executionTotal;
                    }

                    foreach($tasks as $id => $task)
                    {
                        if(!isset($data[$i])) $data[$i] = new stdclass();
                        $data[$i]->id           = $task->id;
                        $data[$i]->name         = $task->name;
                        $data[$i]->pri          = $task->pri;
                        $data[$i]->estStarted   = $task->estStarted;
                        $data[$i]->realStarted  = substr($task->realStarted, 0, 10);
                        $data[$i]->deadline     = $task->deadline;
                        $data[$i]->assignedDate = substr($task->assignedDate, 0, 10);
                        $data[$i]->delay        = $task->delay;
                        $data[$i]->estimate     = $task->estimate;
                        $data[$i]->consumed     = $task->consumed;
                        $i++;
                    }
                }
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export bug resolution summary table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportBugSummary($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $this->app->loadLang('bug');
        $users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $depts    = $this->loadModel('dept')->getOptionMenu();
        $userBugs = $this->report->getBugSummary($dept, $begin, $end, 'bugsummary');

        $fields['resolvedBy']   = $this->lang->bug->resolvedBy;
        $fields['id']           = $this->lang->bug->id;
        $fields['title']        = $this->lang->bug->title;
        $fields['pri']          = $this->lang->bug->pri;
        $fields['severity']     = $this->lang->bug->severity;
        $fields['openedBy']     = $this->lang->bug->openedBy;
        $fields['openedDate']   = $this->lang->bug->openedDate;
        $fields['resolution']   = $this->lang->bug->resolution;
        $fields['resolvedDate'] = $this->lang->bug->resolvedDate;
        $fields['status']       = $this->lang->bug->status;

        $i = 0;
        foreach($userBugs as $user => $bugs)
        {
            if(!isset($users[$user])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();

            $count = count($bugs);
            if($count > 1)
            {
                $rowspan[$i]['rows']['resolvedBy'] = $count;
            }
            $data[$i]->resolvedBy = zget($users, $user);
            foreach($bugs as $id => $bug)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                $data[$i]->id           = $bug->id;
                $data[$i]->title        = $bug->title;
                $data[$i]->pri          = $bug->pri;
                $data[$i]->severity     = $bug->severity;
                $data[$i]->openedBy     = zget($users, $bug->openedBy);
                $data[$i]->openedDate   = substr($bug->openedDate, 0, 10);
                $data[$i]->resolution   = $this->lang->bug->resolutionList[$bug->resolution];
                $data[$i]->resolvedDate = substr($bug->resolvedDate, 0, 10);
                $data[$i]->status       = $this->lang->bug->statusList[$bug->status];
                $i++;
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export bug assignment summary table.
     *
     * @param  string $params
     * @access public
     * @return void
     */
    public function exportBugAssignSummary($params)
    {
        $params = base64_decode($params);
        parse_str($params, $parsedParams);
        foreach($parsedParams as $varName => $varValue) $$varName = $varValue;

        $this->app->loadLang('bug');
        $users    = $this->loadModel('user')->getPairs('noletter|noclosed');
        $depts    = $this->loadModel('dept')->getOptionMenu();
        $userBugs = $this->report->getBugSummary($dept, $begin, $end, 'bugassignsummary');

        $fields['assignedTo']   = $this->lang->bug->assignedTo;
        $fields['id']           = $this->lang->bug->id;
        $fields['title']        = $this->lang->bug->title;
        $fields['pri']          = $this->lang->bug->pri;
        $fields['severity']     = $this->lang->bug->severity;
        $fields['openedBy']     = $this->lang->bug->openedBy;
        $fields['openedDate']   = $this->lang->bug->openedDate;
        $fields['assignedDate'] = $this->lang->bug->assignedDate;
        $fields['status']       = $this->lang->bug->status;

        $i = 0;
        foreach($userBugs as $user => $bugs)
        {
            if(!isset($users[$user])) continue;
            if(!isset($data[$i])) $data[$i] = new stdclass();

            $count = count($bugs);
            if($count > 1)
            {
                $rowspan[$i]['rows']['assignedTo'] = $count;
            }
            $data[$i]->assignedTo = zget($users, $user);
            foreach($bugs as $id => $bug)
            {
                if(!isset($data[$i])) $data[$i] = new stdclass();
                $data[$i]->id           = $bug->id;
                $data[$i]->title        = $bug->title;
                $data[$i]->pri          = $bug->pri;
                $data[$i]->severity     = $bug->severity;
                $data[$i]->openedBy     = zget($users, $bug->openedBy);
                $data[$i]->openedDate   = substr($bug->openedDate, 0, 10);
                $data[$i]->assignedDate = substr($bug->assignedDate, 0, 10);
                $data[$i]->status       = $this->lang->bug->statusList[$bug->status];
                $i++;
            }
        }

        if(isset($rowspan))$this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export case run statistics table.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function exportCasesRun($productID)
    {
        if(!$productID)
        {
            $products  = $this->loadModel('product')->getPairs();
            $productID = key($products);
        }

        $this->app->loadLang('testcase');
        $modules = $this->loadModel('report')->getCasesRun($productID);

        $fields['name']     = $this->lang->report->case->name;
        $fields['total']    = $this->lang->report->case->total;
        $fields['pass']     = $this->lang->testcase->resultList['pass'];
        $fields['fail']     = $this->lang->testcase->resultList['fail'];
        $fields['blocked']  = $this->lang->testcase->resultList['blocked'];
        $fields['passRate'] = $this->lang->report->case->passRate;

        $i = 0;
        foreach($modules as $module)
        {
            if(!isset($data[$i])) $data[$i] = new stdclass();
            $data[$i]->name     = $module['name'];
            $data[$i]->total    = $module['total'];
            $data[$i]->pass     = $module['pass'];
            $data[$i]->fail     = $module['fail'];
            $data[$i]->blocked  = $module['blocked'];
            $data[$i]->passRate = $module['pass'] ? round(($module['pass'] / ($module['pass'] + $module['fail'] + $module['blocked'])) * 100, 2) . '%' : 'N/A';
            $i++;
        }

        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }

    /**
     * Export story linked bug summary table.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function exportStoryLinkedBug($productID, $moduleID = 0)
    {
        $this->app->loadLang('bug');
        $stories = $this->loadModel('report')->getStoryBugs($productID, $moduleID);

        $fields['story']  = $this->lang->report->bug->story;
        $fields['title']  = $this->lang->report->bug->title;
        $fields['status'] = $this->lang->report->bug->status;
        $fields['total']  = $this->lang->report->bug->total;

        $i = 0;
        if(!empty($stories))
        {
            foreach($stories as $story)
            {
                if($story['total'] > 1)
                {
                    $rowspan[$i]['rows']['story'] = $story['total'];
                    $rowspan[$i]['rows']['total'] = $story['total'];
                }

                foreach($story['bugList'] as $bug)
                {
                    if(!isset($data[$i])) $data[$i] = new stdclass();
                    $data[$i]->story  = $story['title'];
                    $data[$i]->title  = $bug->title;
                    $data[$i]->status = $this->lang->bug->statusList[$bug->status];
                    $data[$i]->total  = $story['total'];
                    $i++;
                }
            }
        }

        if(isset($rowspan)) $this->post->set('rowspan', $rowspan);
        $this->post->set('fields', $fields);
        $this->post->set('rows', $data);
    }
}
