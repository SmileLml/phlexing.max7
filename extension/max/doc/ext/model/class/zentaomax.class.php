<?php
class zentaomaxDoc extends docModel
{
    /**
     * 由模板创建文档时，获取文档模板的内容。
     * Get template content for document when create document by template.
     *
     * @param  int    $templateID
     * @param  int    $docID
     * @access public
     * @return string
     */
    public function getTemplateContent($templateID, $docID = 0)
    {
        $template = $this->dao->select('*')->from(TABLE_DOCCONTENT)->where('doc')->eq($templateID)->orderBy('version_desc')->fetch();

        $content = json_decode($template->rawContent);
        if(isset($content->blocks))
        {
            foreach($content->blocks as $blocks)
            {
                if(!is_array($blocks)) continue;

                foreach($blocks as $block)
                {
                    if(empty($block->children)) continue;

                    foreach($block->children as $blockChild)
                    {
                        if(empty($blockChild->props->content)) continue;

                        /* 从文档模板中获取到插入的区块的ID，根据该区块数据为引用该模板的文档生成一个新的区块，用新的区块的ID替换文档模板内容中的区块ID。*/
                        /* Getthe blockID from the document template, generate a new block for the document based on the block data, and replace the block ID in the document template content with the new block ID.*/
                        $blockContent = $blockChild->props->content;
                        preg_match('/__TML_ZENTAOLIST__(\d+)/', $blockContent->fetcher, $matches);
                        $blockID = $matches[1];

                        $docBlock = $this->dao->select('*')->from(TABLE_DOCBLOCK)->where('id')->eq($blockID)->fetch();
                        unset($docBlock->id);
                        $docBlock->doc      = $docID;
                        $docBlock->extra    = 'fromTemplate';
                        $docBlock->settings = inlink('buildZentaoConfig', "type={$docBlock->type}&oldBlockID={blockID}");

                        $this->dao->insert(TABLE_DOCBLOCK)->data($docBlock)->autoCheck()->exec();
                        $newBlockID = $this->dao->lastInsertID();

                        $template->rawContent = preg_replace("/__TML_ZENTAOLIST__(?<!\d){$blockID}(?!\d)/", "{$newBlockID}", $template->rawContent);
                    }
                }
            }
        }

        /* 处理升级后的文档模板。*/
        /* Process the upgraded document template. */
        $content = json_decode($template->rawContent, true);
        if(isset($content['$migrate']) && $content['$migrate'] == 'html')
        {
            /* 正则表达式匹配class="tml-zentaolist"的div，并提取data-export-url和data-fetcher。*/
            /* Match the div of class="tml zentaolist" with a regular expression and extract 'data-export-url' and 'data-fetcher'. */
            $html    = $content['$data'];
            $pattern = '/<div\s+class=[\'"]tml-zentaolist[\'"].*?data-export-url=[\'"](.*?)[\'"].*?data-fetcher=[\'"](.*?)[\'"]/s';
            $exportUrl = $fetcherUrl = '';
            if(preg_match($pattern, $html, $matches))
            {
                $exportUrl  = $matches[1];
                $fetcherUrl = $matches[2];
            }
            if(empty($exportUrl) || empty($fetcherUrl)) return $template->rawContent;

            preg_match('/__TML_ZENTAOLIST__(\d+)/', $fetcherUrl, $matches);
            $templateBlockID = $matches[1];

            /* 根据模板的区块内容为文档生成一条新的区块。*/
            /* Generate a new block for the document based on the block content of the template. */
            $templateBlock = $this->dao->select('*')->from(TABLE_DOCBLOCK)->where('id')->eq($templateBlockID)->fetch();
            unset($templateBlock->id);
            $templateBlock->doc      = $docID;
            $templateBlock->extra    = 'fromTemplate';
            $templateBlock->settings = inlink('buildZentaoConfig', "type={$templateBlock->type}&oldBlockID={blockID}");

            $this->dao->insert(TABLE_DOCBLOCK)->data($templateBlock)->autoCheck()->exec();
            $newTemplateBlockID = $this->dao->lastInsertID();

            /* 替换URL中的区块ID，并更新html中的URL。*/
            /* Replace the block ID in the URL and update the URL in the HTML. */
            $exportUrl    = preg_replace("/__TML_ZENTAOLIST__(?<!\d){$templateBlockID}(?!\d)/", "{$newTemplateBlockID}", $exportUrl);
            $fetcherUrl   = preg_replace("/__TML_ZENTAOLIST__(?<!\d){$templateBlockID}(?!\d)/", "{$newTemplateBlockID}", $fetcherUrl);
            $matchExport  = '/(<div\s+class=[\'"]tml-zentaolist[\'"].*?data-export-url=[\'"])(.*?)([\'"])/s';
            $matchFetcher = '/(<div\s+class=[\'"]tml-zentaolist[\'"].*?data-fetcher=[\'"])(.*?)([\'"])/s';
            $newHtml      = preg_replace(array($matchExport, $matchFetcher), array('${1}' . $exportUrl . '${3}', '${1}' . $fetcherUrl . '${3}'), $html);
            $template->rawContent = json_encode(array('$migrate' => 'html', '$data' => $newHtml));
        }
        return $template->rawContent;
    }

    /**
     * 获取产品需求列表数据。
     * Get the data of product story table.
     *
     * @param mixed[] $product
     * @param  string $searchTab
     * @access public
     * @return array
     */
    public function getProductStoryTableData($product, $searchTab)
    {
        if(empty(array_filter($product))) return array('result' => 'fail', 'message' => array('product' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProduct)));

        $stories = $this->loadModel('product')->getStories($product, '', $searchTab, 0, 0);
        if(empty($stories)) return array();

        $stories = $this->loadModel('story')->mergeReviewer($stories);

        $storyIdList = array_keys($stories);
        $storyTasks = $this->loadModel('task')->getStoryTaskCounts($storyIdList);
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts($storyIdList);
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);
        $users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');

        foreach($stories as $story)
        {
            $story->taskCount = zget($storyTasks, $story->id, 0);
            $story->bugCount  = zget($storyBugs,  $story->id, 0);
            $story->caseCount = zget($storyCases, $story->id, 0);

            foreach(array('mailto', 'reviewer') as $fieldName)
            {
                if(!isset($story->{$fieldName})) continue;

                $fieldValue = is_string($story->{$fieldName}) ? array_filter(explode(',', $story->{$fieldName})) : array_filter($story->{$fieldName});

                foreach($fieldValue as $i => $account) $fieldValue[$i] = zget($users, $account);

                $story->{$fieldName} = implode(' ', $fieldValue);
            }
        }

        return $stories;
    }

    /**
     * 获取项目需求列表数据。
     * Get the data of project story table.
     *
     * @param  array  $project
     * @param  array  $product
     * @param  string $searchTab
     * @access public
     * @return array
     */
    public function getProjectStoryTableData($project, $product, $searchTab)
    {
        if(empty(array_filter($project))) return array('result' => 'fail', 'message' => array('project' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProject)));

        if($searchTab == 'unclosed')
        {
            $this->app->loadLang('story');
            unset($this->lang->story->statusList['closed']);
        }

        $executionStoryIdList = array();
        if(strpos(',linkedexecution,unlinkedexecution,', ",{$searchTab},") !== false)
        {
            $this->loadModel('execution');
            $executions = array();
            foreach($project as $projectID) $executions += $this->execution->getPairs($projectID);
            $executionStoryIdList = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in(array_keys($executions))->fetchPairs();
        }

        $productIdList = array_filter($product);
        $stories       = $this->dao->select("DISTINCT t1.*, t2.*, IF(t2.`pri` = 0, {$this->config->maxPriValue}, t2.`pri`) AS priOrder, t3.type AS productType, t2.version AS version")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->in($project)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->beginIF(strpos('withoutparent', $searchTab) !== false)->andWhere('t2.isParent')->eq('0')->fi()
            ->beginIF(!empty($productIdList))->andWhere('t1.product')->in($productIdList)->fi()
            ->andWhere('t2.type')->in('story')
            ->beginIF(strpos('draft|reviewing|changing|closed', $searchTab) !== false)->andWhere('t2.status')->eq($searchTab)->fi()
            ->beginIF($searchTab == 'unclosed')->andWhere('t2.status')->in(array_keys($this->lang->story->statusList))->fi()
            ->beginIF($searchTab == 'linkedexecution')->andWhere('t2.id')->in($executionStoryIdList)->fi()
            ->beginIF($searchTab == 'unlinkedexecution')->andWhere('t2.id')->notIn($executionStoryIdList)->fi()
            ->fetchAll('id');

        if(empty($stories)) return array();

        $stories = $this->loadModel('story')->mergeReviewer($stories);

        $users      = $this->loadModel('user')->getPairs('noletter|pofirst|nodeleted');
        $storyTasks = $this->loadModel('task')->getStoryTaskCounts(array_keys($stories));
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts(array_keys($stories));
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts(array_keys($stories));
        foreach($stories as $story)
        {
            $story->taskCount = zget($storyTasks, $story->id, 0);
            $story->bugCount  = zget($storyBugs,  $story->id, 0);
            $story->caseCount = zget($storyCases, $story->id, 0);

            foreach(array('mailto', 'reviewer') as $fieldName)
            {
                if(!isset($story->{$fieldName})) continue;

                $fieldValue = is_string($story->{$fieldName}) ? array_filter(explode(',', $story->{$fieldName})) : array_filter($story->{$fieldName});

                foreach($fieldValue as $i => $account) $fieldValue[$i] = zget($users, $account);

                $story->{$fieldName} = implode(' ', $fieldValue);
            }
        }

        return $stories;
    }

    /**
     * 获取执行需求列表。
     * Get execution story table data.
     *
     * @param  array  $execution
     * @param  string $searchTab
     * @access public
     * @return array
     */
    public function getExecutionStoryTableData($execution, $searchTab)
    {
        if(empty(array_filter($execution))) return array('result' => 'fail', 'message' => array('execution' => sprintf($this->lang->error->notempty, $this->lang->doc->selectExecution)));

        $this->session->set('executionStoryBrowseType', $searchTab);
        $stories = $this->loadModel('story')->getExecutionStories($execution, 0, '', $searchTab);

        $storyTasks = $this->loadModel('task')->getStoryTaskCounts(array_keys($stories));
        $storyBugs  = $this->loadModel('bug')->getStoryBugCounts(array_keys($stories));
        $storyCases = $this->loadModel('testcase')->getStoryCaseCounts(array_keys($stories));
        foreach($stories as $story)
        {
            $story->taskCount = zget($storyTasks, $story->id, 0);
            $story->bugCount  = zget($storyBugs,  $story->id, 0);
            $story->caseCount = zget($storyCases, $story->id, 0);
        }

        return $stories;
    }

    /**
     * 获取执行需求列表的列。
     * Get the columns of execution story table.
     *
     * @access public
     * @return array
     */
    public function getExecutionStoryTableCols()
    {
        $setting = $this->loadModel('datatable')->getSetting('execution', 'story', false, 'story');

        unset($setting['actions'], $setting['branch'], $setting['roadmap'], $setting['relatedObject'], $setting['childItem']);

        $setting['title']['minWidth'] = '60';
        if(isset($setting['assignedTo']))   $setting['assignedTo']['type']  = 'user';
        if(isset($setting['pri']))          $setting['pri']['priList']      = $this->lang->story->priList;
        if(isset($setting['category']))     $setting['category']['map']     = $this->lang->story->categoryList;
        if(isset($setting['source']))       $setting['source']['map']       = $this->lang->story->sourceList;
        if(isset($setting['closedReason'])) $setting['closedReason']['map'] = $this->lang->story->reasonList;
        if(isset($setting['plan']))         $setting['plan']['map']         = array('') + $this->loadModel('productplan')->getPairs();

        foreach($setting as $key => $col)
        {
            $setting[$key]['sortType'] = false;
            if(isset($col['link'])) unset($setting[$key]['link']);
            if($key == 'title') $setting[$key]['link'] = array('url' => helper::createLink('{type}', 'view', "storyID={id}&version={version}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }

        return array_values($setting);
    }

    /**
     * 获取产品需求列表的列。
     * Get the cols of product story table.
     *
     * @access public
     * @return array
     */
    public function getProductStoryTableCols()
    {
        $this->app->loadLang('story');

        $setting = $this->loadModel('datatable')->getSetting('product', 'browse', false, 'story');
        if(isset($setting['actions']))       unset($setting['actions']);
        if(isset($setting['roadmap']))       unset($setting['roadmap']);
        if(isset($setting['relatedObject'])) unset($setting['relatedObject']);

        $setting['title']['minWidth']     = '60';
        $setting['pri']['fixed']          = false;
        if(isset($setting['category']))     $setting['category']['map']     = $this->lang->story->categoryList;
        if(isset($setting['assignedTo']))   $setting['assignedTo']['type']  = 'user';
        if(isset($setting['pri']))          $setting['pri']['priList']      = $this->lang->story->priList;
        if(isset($setting['plan']))         $setting['plan']['map']         = array('') + $this->loadModel('productplan')->getPairs();
        if(isset($setting['source']))       $setting['source']['map']       = $this->lang->story->sourceList;
        if(isset($setting['closedReason'])) $setting['closedReason']['map'] = $this->lang->story->reasonList;

        foreach($setting as $key => $col)
        {
            $setting[$key]['sortType'] = false;
            if(isset($col['link'])) unset($setting[$key]['link']);
            if($key == 'title') $setting[$key]['link'] = array('url' => helper::createLink('{type}', 'view', 'storyID={id}&version={version}'), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }

        return array_values($setting);
    }

    /**
     * 获取任务列表数据。
     * Get the data of task table.
     *
     * @param  int    $executionID
     * @param  string $searchTab
     * @access public
     * @return array
     * @param mixed[] $execution
     */
    public function getTaskTableData($execution, $searchTab)
    {
        if(empty(array_filter($execution))) return array('result' => 'fail', 'message' => array('execution' => sprintf($this->lang->error->notempty, $this->lang->doc->selectExecution)));

        $tasks = $this->loadModel('execution')->getTasks(0, $execution, array(), $searchTab, 0, 0, 'id_desc');
        $taskRelatedObject = $this->loadModel('custom')->getRelatedObjectList(array_keys($tasks), 'task', 'byRelation', true);
        foreach($tasks as $task)
        {
            $task->story         = $task->storyTitle;
            $task->relatedObject = zget($taskRelatedObject, $task->id, 0);
        }
        return $tasks;
    }

    /**
     * 获取任务列表的列。
     * Get the cols of task table.
     *
     * @access public
     * @return array
     */
    public function getTaskTableCols()
    {
        $this->app->loadLang('task');
        $cols = $this->loadModel('datatable')->getSetting('execution', 'task');
        if(isset($cols['actions'])) unset($cols['actions']);
        foreach($cols as $key => $col)
        {
            $cols[$key]['sortType'] = false;
            if(isset($col['link'])) unset($cols[$key]['link']);
            if($key == 'assignedTo') $cols[$key]['type'] = 'user';
            if($key == 'pri') $cols[$key]['priList'] = $this->lang->task->priList;
            if($key == 'name') $cols[$key]['link'] = array('url' => helper::createLink('task', 'view', "taskID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }
        return array_values($cols);
    }

    /**
     * 获取 Bug 列表的列。
     * Get Bug table columns.
     *
     * @access public
     * @return array
     */
    public function getBugTableCols()
    {
        $cols = $this->loadModel('datatable')->getSetting('bug');

        unset($cols['branch']);
        unset($cols['task']);
        unset($cols['toTask']);
        unset($cols['story']);
        unset($cols['relatedObject']);
        unset($cols['actions']);

        if(isset($cols['project']))        $cols['project']['map']        = array('') + $this->loadModel('project')->getPairsByProgram();
        if(isset($cols['execution']))      $cols['execution']['map']      = array('') + $this->loadModel('execution')->fetchPairs(0, 'all', false);
        if(isset($cols['plan']))           $cols['plan']['map']           = array('') + $this->loadModel('productplan')->getPairs();
        if(isset($cols['activatedCount'])) $cols['activatedCount']['map'] = array('');

        foreach($cols as $key => $col)
        {
            $cols[$key]['sortType'] = false;

            if(isset($col['link'])) unset($cols[$key]['link']);

            if($key == 'assignedTo') $cols[$key]['type']         = 'user';
            if($key == 'pri')        $cols[$key]['priList']      = $this->lang->bug->priList;
            if($key == 'severity')   $cols[$key]['severityList'] = $this->lang->bug->severityList;
            if($key == 'title')      $cols[$key]['link']         = array('url' => helper::createLink('bug', 'view', "bugID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }

        return array_values($cols);
    }

    /**
     * 获取 Bug 列表。
     * Get bug table data.
     *
     * @param  array  $product
     * @param  string $searchTab
     * @access public
     * @return array
     */
    public function getBugTableData($product, $searchTab)
    {
        if(empty(array_filter($product))) return array('result' => 'fail', 'message' => array('product' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProduct)));

        return $this->loadModel('bug')->getList($searchTab, $product, 0, array());
    }

    /**
     * 获取产品用例列表的列。
     * Get product case table columns.
     *
     * @access public
     * @return array
     */
    public function getProductCaseTableCols()
    {
        $cols = $this->loadModel('datatable')->getSetting('testcase');

        unset($cols['branch']);
        unset($cols['story']);
        unset($cols['scene']);
        unset($cols['actions']);
        unset($cols['relatedObject']);

        if(isset($cols['title']))  $cols['title']['nestedToggle'] = false;
        if(isset($cols['status'])) $cols['status']['statusMap']['changed'] = $this->lang->story->changed;
        if(isset($cols['pri']))    $cols['pri']['priList'] = $this->lang->testcase->priList;
        foreach($cols as $key => $col)
        {
            $cols[$key]['sortType'] = false;
            if(isset($col['link'])) unset($cols[$key]['link']);
            if($key == 'title') $cols[$key]['link'] = array('url' => helper::createLink('testcase', 'view', "caseID={caseID}&version={version}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }
        return array_values($cols);
    }

    /**
     * 获取产品用例列表。
     * Get product case table data.
     *
     * @param  array  $product
     * @param  string $searchTab
     * @param  string $caseStage
     * @param  array  $project
     * @access public
     * @return array
     */
    public function getProductCaseTableData($product, $searchTab, $caseStage = '', $project = array())
    {
        $this->loadModel('testcase');
        $product = array_filter($product);
        $project = array_filter($project);

        if(empty($product) && !empty($project)) $product = array_keys($this->loadModel('product')->getProductPairsByProject($project));

        $scenes = array();
        if($searchTab == 'all') $scenes = $this->testcase->getSceneGroups($product, '', $searchTab);

        $cases  = $this->testcase->getTestCases($product, 'all', $searchTab, 0, 0);
        $scenes = $this->testcase->preProcessScenesForBrowse($scenes);
        $cases  = $this->testcase->preProcessCasesForBrowse($cases);

        $scenes = array_filter($scenes, function($scene) {return empty($scene->isScene);});
        if(!empty($project))
        {
            $projectStories = $this->dao->select('story')->from(TABLE_PROJECTSTORY)->where('project')->in($project)->fetchPairs();
            $cases  = array_filter($cases, function($case) use($project, $projectStories) {return (!empty($projectStories) && isset($projectStories[$case->story])) || in_array($case->project, $project);});
            $scenes = array_filter($scenes, function($scene) use($project, $projectStories) {return (!empty($projectStories) && isset($projectStories[$scene->story])) || in_array($scene->project, $project);});
        }

        $productCases = array_merge($scenes, $cases);
        if(!empty($caseStage))
        {
            $productCases = array_filter($productCases, function($case) use($caseStage) {return strpos(",{$case->stage},", ",{$caseStage},") !== false;});
        }

        return $productCases;
    }

    /**
     * 获取设计列表的列。
     * Get design table columns.
     *
     * @access public
     * @return array
     */
    public function getDesignTableCols()
    {
        $this->loadModel('design');
        $this->app->loadLang('product');
        unset($this->config->design->dtable->fieldList['actions']);
        unset($this->config->design->dtable->fieldList['relatedObject']);

        $cols = $this->loadModel('datatable')->getSetting('design', 'browse', true);
        if(isset($cols['product']))    $cols['product']['map']     = array(0 => $this->lang->product->all) + $this->loadModel('product')->getPairs('all');
        if(isset($cols['assignedTo'])) $cols['assignedTo']['type'] = 'user';
        foreach($cols as $key => $col)
        {
            $cols[$key]['sortType'] = false;
            if(isset($col['link'])) unset($cols[$key]['link']);
            if($key == 'name') $cols[$key]['link'] = array('url' => helper::createLink('design', 'view', "designID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
        }

        return $cols;
    }

    /**
     * 获取设计列表数据。
     * Get design table data.
     *
     * @param  array  $project
     * @param  array  $product
     * @param  string $string HLDS|DDS|DBDS|ADS
     * @access public
     * @return array
     */
    public function getDesignTableData($project, $product, $type)
    {
        if(empty(array_filter($project))) return array('result' => 'fail', 'message' => array('project' => sprintf($this->lang->error->notempty, $this->lang->doc->selectProject)));
        return $this->loadModel('design')->getList(array_filter($project), array_filter($product), $type);
    }

    /**
     * 获取甘特图数据。
     * Get gantt data.
     *
     * @param  int    $project
     * @access public
     * @return array
     */
    public function getGanttData($project)
    {
        $selectCustom = $this->loadModel('setting')->getItem("owner={$this->app->user->account}&module=programplan&section=browse&key=stageCustom");
        return $this->loadModel('programplan')->getDataForGantt($project, 0, 0, $selectCustom, false);
    }

    /**
     * 甘特图区块展示的字段。
     * Get gantt fields.
     *
     * @access public
     * @return array
     */
    public function getGanttFields()
    {
        $this->app->loadLang('programplan');

        $ganttFields = array();
        $ganttFields['column_text']         = $this->lang->programplan->ganttBrowseType['gantt'];
        $ganttFields['column_owner_id']     = $this->lang->programplan->PMAB;
        $ganttFields['column_status']       = $this->lang->statusAB;
        $ganttFields['column_percent']      = $this->lang->programplan->percentAB;
        $ganttFields['column_taskProgress'] = $this->lang->programplan->taskProgress;
        $ganttFields['column_begin']        = $this->lang->programplan->begin;
        $ganttFields['column_start_date']   = $this->lang->programplan->begin;
        $ganttFields['column_deadline']     = $this->lang->programplan->end;
        $ganttFields['column_end_date']     = $this->lang->programplan->end;
        $ganttFields['column_realBegan']    = $this->lang->programplan->realBegan;
        $ganttFields['column_realEnd']      = $this->lang->programplan->realEnd;
        $ganttFields['column_duration']     = $this->lang->programplan->duration;
        $ganttFields['column_estimate']     = $this->lang->programplan->estimate;
        $ganttFields['column_consumed']     = $this->lang->programplan->consumed;
        $ganttFields['column_delay']        = $this->lang->programplan->delay;
        $ganttFields['column_delayDays']    = $this->lang->programplan->delayDays;

        return $ganttFields;
    }
}
