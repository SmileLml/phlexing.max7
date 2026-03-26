<?php
class clientapiApi extends model
{
    public function getFullTask($range = 0, $last = '', $records = 1000)
    {
        return $this->dao->select("DISTINCT t1.*, 'task' as dataType")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('1=1')
            ->beginIF($range > 0)->andWhere('t1.id')->le($range)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t1.id_desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementTask($range = 0, $last = '', $records = 1000)
    {
        $taskIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('task')
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        return $this->dao->select("DISTINCT t1.*, 'task' as dataType")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.id')->in($taskIDList)
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.id_desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getFullStory($range = 0, $last = '', $records = 1000)
    {
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("DISTINCT t1.*,t2.title,t2.spec,t2.verify,'story' as dataType")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.version=t2.version')
            ->beginIF($range > 0)->andWhere('t1.id')->le($range)->fi()
            ->andWhere('t2.id')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementStory($range = 0, $last = '', $records = 1000)
    {
        $storyIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('story')
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("DISTINCT t1.*,t2.title,t2.spec,t2.verify,'story' as dataType")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t1.version=t2.version')
            ->andWhere('t1.id')->in($storyIDList)
            ->andWhere('t3.id')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getFullBug($range = 0, $last = '', $records = 1000)
    {
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("DISTINCT t1.*, 'bug' as dataType")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('1=1')
            ->beginIF($range > 0)->andWhere('t1.id')->le($range)->fi()
            ->andWhere('t2.id')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementBug($range = 0, $last = '', $records = 1000)
    {
        $bugIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("DISTINCT t1.*, 'bug' as dataType")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.id')->in($bugIDList)
            ->andWhere('t2.id')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getFullTodo($account = '', $range = 0, $last = '', $records = 1000)
    {
        return $this->dao->select("*, 'todo' as dataType")->from(TABLE_TODO)
            ->where('account')->eq($account)
            ->beginIF($range > 0)->andWhere('id')->le($range)->fi()
            ->orderBy('id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementTodo($account, $range = 0, $last = '', $records = 1000)
    {
        $todoIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('todo')
            ->andWhere('actor')->eq($account)
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        return $this->dao->select("*, 'todo' as dataType")->from(TABLE_TODO)->where('id')->in($todoIDList)->orderBy('id desc')->limit($records)->fetchAll();
    }

    public function getFullProduct($account = '', $range = 0, $last = '', $records = 1000)
    {
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("*, 'product' as dataType")->from(TABLE_PRODUCT)
            ->where('1=1')
            ->beginIF($range > 0)->andWhere('id')->le($range)->fi()
            ->andWhere('id')->in(array_keys($products))
            ->andWhere('deleted')->eq('0')
            ->orderBy('id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementProduct($account, $range = 0, $last = '', $records = 1000)
    {
        $productIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('product')
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        $products = $this->loadModel('product')->getPairs();
        return $this->dao->select("DISTINCT t1.*, 'product' as dataType")->from(TABLE_PRODUCT)->alias('t1')
            ->where('t1.id')->in($productIDList)
            ->andWhere('t1.id')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getFullProject($account, $range = 0, $last = '', $records = 1000)
    {
        return $this->dao->select("*, 'project' as dataType")->from(TABLE_PROJECT)
            ->where('1=1')
            ->beginIF($range > 0)->andWhere('id')->le($range)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy('id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function getIncrementProject($account, $range = 0, $last = '', $records = 1000)
    {
        $projectIDList = $this->dao->select('DISTINCT objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('project')
            ->beginIF($range > 0)->andWhere('objectID')->le($range)->fi()
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->fetchPairs('objectID');
        return $this->dao->select("DISTINCT t1.*, 'project' as dataType")->from(TABLE_PROJECT)->alias('t1')
            ->where('t1.id')->in($projectIDList)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy('t1.id desc')
            ->limit($records)
            ->fetchAll();
    }

    public function process($type, $items, $last = '', $format = 'index')
    {
        $key = '';
        $set = array('set' => array());
        foreach($items as $item)
        {
            $item = $this->format($type, $item, $format);
            if(empty($item)) continue;

            $item = (array)$item;
            $set['set'][] = array_values($item);
            if(empty($key)) $key = array_keys($item);
        }

        $deletes = $this->getDeleteID($type, $last, $type === 'todo' ? 'erased' : 'deleted');
        $set['delete'] = array_keys($deletes);

        return $this->formatSet($set, $key);
    }

    public function getDataByID($id, $type)
    {
        if($type == 'story')
        {
            return $this->dao->select("DISTINCT *")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                ->where('t1.version=t2.version')
                ->andWhere('id')->eq($id)
                ->fetch();
        }
        else
        {
            if(!isset($this->config->objectTables[$type])) return false;
            $data = $this->dao->select("*")->from($this->config->objectTables[$type])->where('id')->eq($id)->fetch();

            // Get team members for project
            if($type == 'project')
            {
                $members     = $this->loadModel('project')->getTeamMembers($id);
                $teamMembers = array();
                $teamUsers   = array();
                foreach ($members as $member)
                {
                    unset($member->project);
                    unset($member->join);
                    $teamMembers[] = $member;
                    $teamUsers[]   = $member->account;
                }
                $data->teamMembers = $teamMembers;
                $data->teamUsers   = join(',', $teamUsers);
            }
            return $data;
        }
    }

    public function getHistoryByID($id, $type)
    {
        $this->app->loadLang($type);
        return $this->loadModel('action')->getList($type, $id);
    }

    public function getDeleteID($objectType, $last = '', $action = 'deleted')
    {
        return $this->dao->select('objectID,date,action')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->beginIF($last)->andWhere('date')->ge($last)->fi()
            ->andWhere('action')->eq($action)
            ->fetchAll('objectID');
    }

    public function compression($data)
    {
        $output = array();
        $output['status'] = 'success';
        if(!empty($data)) $output['data'] = $data;
        $output = json_encode($output);

        if(!headers_sent()
            && extension_loaded("zlib")
            && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")
        )
        {
            $output = gzencode($output . " \n", 9);

            header("Content-Encoding: gzip");
            header("Vary: Accept-Encoding");
            header("Content-Length: " . strlen($output));
        }

        return $output;
    }

    public function formatSet($set, $key)
    {
        if(!empty($set['set']))
        {
            $set['key'] = $key;
            if(empty($set['delete'])) unset($set['delete']);
        }
        else
        {
            unset($set['set']);
            if(empty($set['delete'])) $set = null;
        }
        return $set;
    }

    public function formatDateTime($datetimeStr)
    {
        $datetimeStr = strtotime($datetimeStr);
        if($datetimeStr === false)
        {
            $datetimeStr = 0;
        }
        return $datetimeStr;
    }

    public function format($type, $item, $format = 'all')
    {
        if(isset($item->files) && !empty($item->files)) $this->formatFile($item->files);

        switch ($type) {
            case 'bug':
                return $this->formatBug($item, $format);
            case 'task':
                return $this->formatTask($item, $format);
            case 'story':
                return $this->formatStory($item, $format);
            case 'todo':
                return $this->formatTodo($item, $format);
            case 'project':
                return $this->formatProject($item, $format);
            case 'product':
                return $this->formatProduct($item, $format);
            case 'history':
                return $this->formatHistory($item, $format);
            default:
                $funcName = 'format' . ucfirst($type);
                return $this->$funcName($item, $format);
                break;
        }
    }

    public function formatBug($bug, $format = 'all')
    {
        unset($bug->dataType);
        unset($bug->caseVersion);
        unset($bug->case);
        unset($bug->result);
        unset($bug->repo);
        unset($bug->entry);
        unset($bug->lines);
        unset($bug->v1);
        unset($bug->v2);
        unset($bug->repoType);
        unset($bug->linkBug);
        unset($bug->testtask);

        if($format == 'index')
        {
            unset($bug->steps);
            unset($bug->storyVersion);
            unset($bug->toTask);
            unset($bug->toStory);
            unset($bug->mailto);
            unset($bug->openedBuild);
            unset($bug->resolvedBuild);
            unset($bug->duplicateBug);
            unset($bug->activatedCount);
            unset($bug->hardware);
        }
        else
        {
            $bug->storyVersion   = (int) $bug->storyVersion;
            $bug->toTask         = (int) $bug->toTask;
            $bug->toStory        = (int) $bug->toStory;
            $bug->duplicateBug   = (int) $bug->duplicateBug;
            $bug->activatedCount = (int) $bug->activatedCount;
        }

        $bug->id             = (int) $bug->id;
        $bug->product        = (int) $bug->product;
        $bug->module         = (int) $bug->module;
        $bug->project        = (int) $bug->project;
        $bug->plan           = (int) $bug->plan;
        $bug->story          = (int) $bug->story;
        $bug->task           = (int) $bug->task;
        $bug->severity       = (int) $bug->severity;
        $bug->pri            = (int) $bug->pri;
        $bug->confirmed      = (int) $bug->confirmed;
        $bug->openedDate     = $this->formatDateTime($bug->openedDate);
        $bug->assignedDate   = $this->formatDateTime($bug->assignedDate);
        $bug->resolvedDate   = $this->formatDateTime($bug->resolvedDate);
        $bug->closedDate     = $this->formatDateTime($bug->closedDate);
        $bug->lastEditedDate = $this->formatDateTime($bug->lastEditedDate);
        $bug->deleted        = (int) $bug->deleted;

        return $bug;
    }

    public function formatTask($task, $format = 'all')
    {
        unset($task->dataType);

        if($format == 'index')
        {
            unset($task->desc);
            unset($task->doc);
            unset($task->mailto);
            unset($task->storyVersion);
        }
        else
        {
            $task->storyVersion = (int) $task->storyVersion;
        }

        $task->id             = (int) $task->id;
        $task->project        = (int) $task->project;
        $task->module         = (int) $task->module;
        $task->story          = (int) $task->story;
        $task->fromBug        = (int) $task->fromBug;
        $task->deleted        = (int) $task->deleted;
        $task->pri            = (int) $task->pri;
        $task->estimate       = (float) $task->estimate;
        $task->consumed       = (float) $task->consumed;
        $task->left           = (float) $task->left;
        $task->deadline       = $this->formatDateTime($task->deadline);
        $task->openedDate     = $this->formatDateTime($task->openedDate);
        $task->assignedDate   = $this->formatDateTime($task->assignedDate);
        $task->estStarted     = $this->formatDateTime($task->estStarted);
        $task->realStarted    = $this->formatDateTime($task->realStarted);
        $task->finishedDate   = $this->formatDateTime($task->finishedDate);
        $task->canceledDate   = $this->formatDateTime($task->canceledDate);
        $task->closedDate     = $this->formatDateTime($task->closedDate);
        $task->lastEditedDate = $this->formatDateTime($task->lastEditedDate);

        $task->format = $format;

        return $task;
    }

    public function formatStory($story, $format = 'all')
    {
        unset($story->dataType);
        unset($story->childStories);
        unset($story->linkStories);
        unset($story->type);

        if($format == 'index')
        {
            unset($story->spec);
            unset($story->verify);
            unset($story->mailto);
            unset($story->toBug);
            unset($story->duplicateStory);
        }
        else
        {
            $story->toBug = (int) $story->toBug;
            $story->duplicateStory = (int) $story->duplicateStory;
        }

        $story->id = (int) $story->id;
        $story->product = (int) $story->product;
        $story->module = (int) $story->module;
        $story->plan = (int) $story->plan;
        $story->fromBug = (int) $story->fromBug;
        $story->pri = (int) $story->pri;
        $story->estimate = (float) $story->estimate;
        $story->openedDate = $this->formatDateTime($story->openedDate);
        $story->assignedDate = $this->formatDateTime($story->assignedDate);
        $story->lastEditedDate = $this->formatDateTime($story->lastEditedDate);
        $story->reviewedDate = $this->formatDateTime($story->reviewedDate);
        $story->closedDate = $this->formatDateTime($story->closedDate);
        $story->version = (int) $story->version;
        $story->deleted = (int) $story->deleted;

        return $story;
    }

    public function formatTodo($todo, $format = 'all')
    {
        unset($todo->dataType);
        unset($todo->private);

        if($format == 'index')
        {
            unset($todo->desc);
        }

        $todo->id      = (int) $todo->id;
        $todo->idvalue = (int) $todo->idvalue;
        $todo->pri     = (int) $todo->pri;

        // format todo attributes: date, begin, end
        $todo->begin = strtotime($todo->date . substr($todo->begin, 0, 2) . ':' . substr($todo->begin, 2, 2));
        $todo->end = strtotime($todo->date . substr($todo->end, 0, 2) . ':' . substr($todo->end, 2, 2));
        unset($todo->date);
        unset($todo->account);

        return $todo;
    }

    public function formatProduct($product, $format = 'all')
    {
        // Check pri
        if(!$this->loadModel('product')->checkPriv($product->id)) return null;

        unset($product->dataType);

        if($format == 'index')
        {
            unset($product->desc);
        }

        $product->id          = (int) $product->id;
        $product->deleted     = (int) $product->deleted;
        $product->createdDate = $this->formatDateTime($product->createdDate);

        return $product;
    }

    public function formatProject($project, $format = 'all')
    {
        // Check pri
        if(!$this->loadModel('project')->checkPriv($project->id)) return null;

        unset($project->dataType);

        if($format == 'index')
        {
            unset($project->desc);
            unset($project->files);
        }

        $project->id           = (int) $project->id;
        $project->deleted      = (int) $project->deleted;
        $project->isCat        = (int) $project->isCat;
        $project->catID        = (int) $project->catID;
        $project->parent       = (int) $project->parent;
        $project->days         = (int) $project->days;
        $project->statge       = (int) $project->statge;
        $project->pri          = (int) $project->pri;
        $project->begin        = $this->formatDateTime($project->begin);
        $project->end          = $this->formatDateTime($project->end);
        $project->openedDate   = $this->formatDateTime($project->openedDate);
        $project->canceledDate = $this->formatDateTime($project->canceledDate);
        $project->closedDate   = $this->formatDateTime($project->closedDate);

        return $project;
    }

    public function formatHistory($actions, $format = 'all')
    {
        $this->loadModel('action');
        $users = $this->loadModel('user')->getPairs('noletter|nodeleted');

        $dataList = array();
        foreach($actions as $action)
        {
            $action->actor = zget($users, $action->actor, $action->actor);

            $data = new stdclass();
            $data->id      = (int)$action->id;
            $data->user    = $action->actor;
            $data->comment = strip_tags($action->comment);
            $data->date    = $this->formatDateTime($action->date);

            ob_start();
            $this->action->printAction($action);
            $data->content = strip_tags(trim(ob_get_contents()));
            ob_clean();

            $this->action->printChanges($action->objectType, $action->history);
            $data->history = strip_tags(trim(ob_get_contents()));
            ob_end_clean();

            $dataList[] = $data;
        }
        return $dataList;
    }

    public function formatFile($file)
    {
        if(is_array($file))
        {
            foreach($file as $f) $this->formatFile($f);
            return $file;
        }

        $this->loadModel('file')->setFileWebAndRealPaths($file);

        $file->id        = (int) $file->id;
        $file->objectID  = (int) $file->objectID;
        $file->size      = (int) $file->size;
        $file->downloads = (int) $file->downloads;
        $file->deleted   = (int) $file->deleted;
        $file->addedDate = $this->formatDateTime($file->addedDate);

        return $file;
    }
}
