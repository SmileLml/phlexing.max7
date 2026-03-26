<?php
class zentaomaxProject extends projectModel
{
    /**
     * Save copy project.
     *
     * @param  int    $copyProjectID
     * @param  string $model
     * @param  object $executions
     * @access public
     * @return string
     */
    public function saveCopyProject($copyProjectID, $model = 'scrum', $executions = array())
    {
        $this->loadModel('doc');
        $this->loadModel('execution');

        $copyProject = $this->getByID($copyProjectID);
        if($copyProject->multiple && empty($executions)) return false;

        $insertExecutions = array();
        if(in_array($model, array('waterfall', 'waterfallplus', 'ipd')) && !empty($executions->executionIDList))
        {
            $parentAcl = '';
            foreach($executions->executionIDList as $productID => $executionIdList)
            {
               list($insertExecutions, $parentAcl) = $this->setInsertExecutions($executions, $executionIdList, $model, $productID, $parentAcl);

                /* Check execution. */
                $checkExecution = $this->checkExecution($insertExecutions, $copyProjectID, $model);
                if(!$checkExecution) return false;
            }
        }
        elseif(!empty($executions->executionIDList))
        {
            list($insertExecutions) = $this->setInsertExecutions($executions, $executions->executionIDList, $model);

            /* Check execution. */
            $checkExecution = $this->checkExecution($insertExecutions, $copyProjectID, $model);
            if(!$checkExecution) return false;
        }

        $_POST   = json_decode($this->cookie->copyData, true);
        $project = form::data($this->config->project->form->create);
        $project = $project->setDefault('status', 'wait')
            ->setIF($this->post->longTime || $this->post->delta == '999', 'end', LONG_TIME)
            ->setIF($this->post->longTime || $this->post->delta == '999', 'days', 0)
            ->setIF($this->post->acl      == 'open', 'whitelist', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF($this->post->multiple != 'on', 'multiple', '0')
            ->setIF($this->post->multiple == 'on' || !in_array($this->post->model, array('scrum', 'kanban')) || $this->config->vision == 'lite', 'multiple', '1')
            ->setIF($this->post->model == 'ipd', 'stageBy', 'project')
            ->setIF($this->post->future, 'budget', 0)
            ->setDefault('stageBy', 'project')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', $this->post->name)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('days', '0')
            ->cleanINT('parent,charter')
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->join('auth', ',')
            ->join('storyType', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->remove('longTime,products,branch,plans,contactList,future,productName')
            ->get();

        if(!empty($project->isTpl)) $project->status = 'doing'; // 项目模板创建的项目，状态为使用中

        if($copyProject)
        {
            if(in_array($this->post->model, array('scrum', 'kanban'))) $project->multiple = $copyProject->multiple;
            $project->hasProduct = $copyProject->hasProduct;
        }

        if(!isset($this->config->setCode) || $this->config->setCode == 0) unset($project->code);

        /* Lean mode relation defaultProgram. */
        if($this->config->systemMode == 'light') $project->parent = $this->config->global->defaultProgram;

        $projectPostData = new stdClass();
        $projectPostData->rawdata = json_decode($this->cookie->copyData);
        $projectID = $this->create($project, $projectPostData);
        if(dao::isError()) return false;
        $this->loadModel('action')->create('project', $projectID, 'opened');

        /* Query the products and plans associated with the project. */
        $products       = array();
        $plans          = array();
        $productID      = 0;
        $linkedProducts = $this->dao->select('product,plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
        foreach($linkedProducts as $productID => $planID)
        {
            if(empty($planID)) $planID = '0';
            $products[]        = $productID;
            $plans[$productID] = explode(',', $planID);
        }
        $_POST['products'] = $products;
        $_POST['plans']    = $plans;

        if(empty($project->hasProduct)) $productID = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetch('product');

        if(!$copyProject->multiple)
        {
            $executionID = $this->execution->syncNoMultipleSprint($projectID);

            /* Sync task. */
            $copyExecutionID = $this->execution->getNoMultipleID($copyProjectID);
            $this->saveTask($copyProjectID, $projectID, $copyExecutionID, $executionID);
        }
        else
        {
            $this->saveExecutions($insertExecutions, $productID, $copyProjectID, $project, $projectID, $model);
            if(dao::isError()) return false;
        }

        /* Add QA. */
        if($model != 'ipd') $this->saveQA($copyProjectID, $projectID, 0, 0);

        /* Project doc lib */
        $this->saveProjectDocLib($copyProjectID, $projectID);

        /* Teams */
        $this->saveTeam($copyProjectID, $projectID);

        /* Stakeholder */
        $this->saveStakeholder($copyProjectID, $projectID);

        /* Group */
        $this->saveGroup($copyProjectID, $projectID);

        /* 如果项目是独有流程，则复制一份流程到新项目。 */
        if(!empty($project->workflowGroup))
        {
            $workflowGroup = $this->dao->select('*')->from(TABLE_WORKFLOWGROUP)->where('id')->eq((int)$project->workflowGroup)->fetch();
            if($workflowGroup->objectID)
            {
                $project->id = $projectID;
                $this->loadExtension('zentaobiz')->copyWorkflowGroup($project);
            }
        }

        return $projectID;
    }

    /**
     * 设置要插入的执行数据。
     * Set insert execution data.
     *
     * @param  array  $executions
     * @param  array  $executionIdList
     * @param  string $model
     * @param  int    $productID
     * @param  string $parentAcl
     * @access public
     * @return array
     */
    public function setInsertExecutions($executions = array(), $executionIdList = array(), $model = 'scrum', $productID = 0, $parentAcl = '')
    {
        if(empty($executionIdList)) return array();

        $this->loadModel('programplan');
        $originExecutions = $this->loadModel('execution')->getByIdList($executionIdList);
        $isStage          = in_array($model, array('waterfall', 'waterfallplus', 'ipd'));
        $nameList         = $isStage ? $executions->names[$productID] : $executions->names;
        $parentList       = $isStage ? $executions->parents[$productID] : $executions->parents;
        $PMList           = $isStage ? $executions->PMs[$productID] : $executions->PMs;
        $beginList        = $isStage ? $executions->begins[$productID] : $executions->begins;
        $endList          = $isStage ? $executions->ends[$productID] : $executions->ends;

        $now              = helper::now();
        $account          = $this->app->user->account;
        $lastID           = $this->dao->select('id')->from(TABLE_EXECUTION)->orderBy('id_desc')->fetch('id');
        $insertExecutions = array();
        foreach($executionIdList as $executionID)
        {
            $lastID ++;
            $insertExecution = new stdClass();
            $insertExecution->executionID   = (int)$executionID;
            $insertExecution->name          = $nameList[$executionID];
            $insertExecution->status        = 'wait';
            $insertExecution->parent        = $parentList[$executionID];
            $insertExecution->PM            = $PMList[$executionID];
            $insertExecution->begin         = $beginList[$executionID];
            $insertExecution->end           = $endList[$executionID];
            $insertExecution->acl           = $isStage ? $executions->acl[$productID][$executionID] : $originExecutions[$executionID]->acl;
            $insertExecution->team          = $originExecutions[$executionID]->team;
            $insertExecution->type          = $originExecutions[$executionID]->type;
            $insertExecution->whitelist     = $originExecutions[$executionID]->whitelist;
            $insertExecution->hasProduct    = $originExecutions[$executionID]->hasProduct;
            $insertExecution->grade         = $originExecutions[$executionID]->grade;
            $insertExecution->path          = $originExecutions[$executionID]->path;
            $insertExecution->stageBy       = $originExecutions[$executionID]->stageBy;
            $insertExecution->parallel      = $originExecutions[$executionID]->parallel;
            $insertExecution->multiple      = $originExecutions[$executionID]->multiple;
            $insertExecution->openedBy      = $account;
            $insertExecution->openedDate    = $now;
            $insertExecution->openedVersion = $this->config->version;

            if($isStage)
            {
                $insertExecution->attribute = $executions->attributes[$productID][$executionID];
                $insertExecution->product   = $productID;
                $insertExecution->percent   = $executions->percents[$productID][$executionID];
                $insertExecution->milestone = $executions->milestone[$productID][$executionID];
                $insertExecution->order     = $lastID * 5;
                $insertExecution->days      = $this->programplan->calcDaysForStage($insertExecution->begin, $insertExecution->end);

                $insertExecution->acl == 'same' ? $insertExecution->acl = $parentAcl : $parentAcl = $insertExecution->acl;
            }
            else
            {
                $insertExecution->lifetime = $executions->lifetimes[$executionID];
                $insertExecution->days     = $executions->dayses[$executionID] ? $executions->dayses[$executionID] : 0;
                if(isset($this->config->setCode) and $this->config->setCode) $insertExecution->code = $executions->codes[$executionID];
            }

            $insertExecutions[$executionID] = $insertExecution;
        }
        return array($insertExecutions, $parentAcl);
    }

    /**
     * 保存复制的执行。
     * Save copy executions.
     *
     * @param  array  $insertExecutions
     * @param  int    $productID
     * @param  int    $copyProjectID
     * @param  object $project
     * @param  int    $projectID
     * @param  string $model
     * @access public
     * @return bool
     */
    public function saveExecutions($insertExecutions = array(), $productID = 0, $copyProjectID = 0, $project = null, $projectID = 0, $model = 'scrum')
    {
        $this->loadModel('review');

        if(helper::hasFeature('deliverable') && $projectID)
        {
            $project = $this->fetchByID($projectID);
            foreach($insertExecutions as $execution)
            {
                if(!empty($project->deliverable) && !empty($project->multiple))
                {
                    $projectModel = $project->model;
                    if($projectModel == 'agileplus')     $projectModel = 'scrum';
                    if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

                    $projectType = !empty($project->hasProduct)  ? 'product' : 'project';
                    $objectCode  = !empty($execution->attribute) ? "{$projectType}_{$projectModel}_{$execution->attribute}" : "{$projectType}_{$projectModel}_{$execution->lifetime}";
                    if($execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

                    $projectDeliverable     = $project->deliverable;
                    $projectDeliverable     = !empty($projectDeliverable) ? json_decode($projectDeliverable, true) : array();
                    $executionDeliverable   = !empty($projectDeliverable[$objectCode]) ? array("{$objectCode}" => $projectDeliverable[$objectCode]) : array();
                    $execution->deliverable = json_encode($executionDeliverable);
                }
            }
        }

        $executionMap        = array();
        $isProcess           = false;
        $updateUVExecutionID = array();
        $insertIdList        = array();
        foreach($insertExecutions as $insertExecution)
        {
            $executionID = $insertExecution->executionID;
            if(!empty($project->hasProduct)) $productID = isset($insertExecution->product) ? $insertExecution->product : $productID;

            $insertExecution->isTpl   = $project->isTpl;
            $insertExecution->project = $projectID;
            $this->dao->insert(TABLE_EXECUTION)->data($insertExecution, 'product,executionID,path')->exec();

            $lastExecutionID = $this->dao->lastInsertId();
            $insertIdList[$executionID] = $lastExecutionID;

            $comment = !empty($project->hasProduct) ? join(',', $_POST['products']) : '';
            $this->action->create('execution', $lastExecutionID, 'opened', '', $comment);

            if(in_array($model, array('waterfall', 'waterfallplus', 'ipd')) and !empty($project->stageBy))
            {
                /* Add execution product */
                $this->dao->insert(TABLE_PROJECTPRODUCT)->set('project')->eq($lastExecutionID)->set('product')->eq($productID)->exec();
            }

            if(in_array($model, array('waterfall', 'waterfallplus', 'ipd')) and empty($project->stageBy) and !empty($_POST['products']))
            {
                /* Add execution product */
                $this->execution->updateProducts($lastExecutionID, $_POST['products']);
            }

            if(($model == 'scrum' or $model == 'agileplus') and !empty($_POST['products']))
            {
                unset($_POST['plans']);
                $this->execution->updateProducts($lastExecutionID, $_POST['products']);
            }
        }

        foreach($insertExecutions as $insertExecution)
        {
            $executionID     = $insertExecution->executionID;
            $lastExecutionID = $insertIdList[$executionID];

            if($insertExecution->parent == $copyProjectID)
            {
                $parentID = $projectID;
                $path     = ",{$projectID},{$lastExecutionID},";
            }
            else
            {
                if(!isset($insertIdList[$insertExecution->parent])) continue;
                $parentID = $insertIdList[$insertExecution->parent];
                $pathList = explode(',', $insertExecution->path);
                $path     = ",{$projectID}";
                foreach($pathList as $pathID)
                {
                    if($pathID == $copyProjectID || !isset($insertIdList[$pathID])) continue;
                    $path .= "," . $insertIdList[$pathID];
                }
                $path .= ",{$lastExecutionID},";
            }

            $this->dao->update(TABLE_EXECUTION)
                ->set('path')->eq($path)
                ->set('parent')->eq($parentID)
                ->where('id')->eq($lastExecutionID)->exec();

            if(dao::isError()) return false;

            /* Add review point for ipd project. */
            if($this->config->edition == 'ipd' && $model == 'ipd' && $parentID == $projectID && $insertExecution->type == 'stage') $this->review->createDefaultPoint($projectID, $productID, $insertExecution->attribute);

            /* Add execution team. */
            $this->saveTeam($executionID, $lastExecutionID, 'execution');

            /* Add execution whitelis. */
            $this->saveWhitelist($insertExecution->whitelist, $lastExecutionID);

            /* Add process. */
            if($model == 'scrum' or $model == 'agileplus' or (in_array($model, array('waterfall', 'waterfallplus')) and !$isProcess))
            {
                $this->saveProcess($copyProjectID, $projectID, $executionID, $lastExecutionID, $model);
                $isProcess = true;
            }

            /* Add QA. */
            if($model != 'ipd') $this->saveQA($copyProjectID, $projectID, $executionID, $lastExecutionID);

            /* Add RD kanban. */
            $this->saveKanban($executionID, $lastExecutionID);

            /* Add task. */
            $this->saveTask($copyProjectID, $projectID, $executionID, $lastExecutionID);

            /* execution doc lib */
            $this->saveExecutionDocLib($executionID, $lastExecutionID, $projectID);

            if($insertExecution->acl != 'open') $updateUVExecutionID[] = $lastExecutionID;
        }

        /* Update userview. */
        if(!empty($updateUVExecutionID)) $this->loadModel('user')->updateUserView($updateUVExecutionID, 'sprint');

        return !dao::isError();
    }

    /**
     * Check create.
     *
     * @param  int    $copyProjectID
     * @access public
     * @return bool
     */
    public function checkCreate($copyProjectID = 0)
    {
        $project = fixer::input('post')
            ->callFunc('name', 'trim')
            ->setDefault('status', 'wait')
            ->setIF($this->post->longTime || $this->post->delta == '999', 'end', LONG_TIME)
            ->setIF($this->post->longTime || $this->post->delta == '999', 'days', 0)
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF($this->post->multiple != 'on', 'multiple', '0')
            ->setIF($this->post->multiple == 'on' || !in_array($this->post->model, array('scrum', 'kanban')) || $this->config->vision == 'lite', 'multiple', '1')
            ->setIF($this->post->model == 'ipd', 'stageBy', 'project')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->cleanINT('parent,charter')
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->join('storyType', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->remove('products,branch,plans,roadmaps,delta,newProduct,productName,future,contactListMenu,teamMembers,isLinkStory')
            ->get();

        if($copyProjectID)
        {
            $copyProject = $this->getByID($copyProjectID);
            if(in_array($this->post->model, array('scrum', 'kanban'))) $project->multiple = $copyProject->multiple;
            $project->hasProduct = $copyProject->hasProduct;
        }

        $linkedProductsCount = 0;
        if($project->hasProduct && isset($_POST['products']))
        {
            foreach($_POST['products'] as $product)
            {
                if(!empty($product)) $linkedProductsCount++;
            }
        }

        $program = new stdClass();
        if($project->parent)
        {
            $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();

            /* Judge products not empty. */
            if($project->hasProduct and empty($linkedProductsCount) and !isset($_POST['newProduct']))
            {
                dao::$errors[] = $this->lang->project->productNotEmpty;
                return false;
            }
        }

        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($project->end, $project->begin) + 1;
        if(isset($project->days) and $project->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if(!empty($project->budget))
        {
            if(!is_numeric($project->budget))
            {
                dao::$errors['budget'] = sprintf($this->lang->project->budgetNumber);
                return false;
            }
            else if(is_numeric($project->budget) and ($project->budget < 0))
            {
                dao::$errors['budget'] = sprintf($this->lang->project->budgetGe0);
                return false;
            }
            else
            {
                $project->budget = round((float)$this->post->budget, 2);
            }
        }

        /* When select create new product, product name cannot be empty and duplicate. */
        if(isset($_POST['newProduct']))
        {
            if(empty($_POST['productName']))
            {
                $this->app->loadLang('product');
                dao::$errors['productName'] = sprintf($this->lang->error->notempty, $this->lang->product->name);
                return false;
            }
            else
            {
                $programID        = isset($project->parent) ? $project->parent : 0;
                $existProductName = $this->dao->select('name')->from(TABLE_PRODUCT)->where('name')->eq($_POST['productName'])->andWhere('program')->eq($programID)->fetch('name');
                if(!empty($existProductName))
                {
                    dao::$errors['productName'] = $this->lang->project->existProductName;
                    return false;
                }
            }
        }

        /* 交付物必填校验。 */
        if(!empty($project->deliverable))
        {
            $deliverables = $this->getProjectDeliverable(0, 0, (int)$project->workflowGroup, $project->hasProduct ? 'product' : 'project', $project->model, 'whenCreated');
            foreach($deliverables as $deliverable)
            {
                if(!empty($deliverable['required']) && empty($_FILES['deliverable']['name'][$deliverable['id']]) && empty($project->deliverable[$deliverable['id']]['doc']))
                {
                    dao::$errors["deliverable[{$deliverable['id']}]"] = sprintf($this->lang->error->notempty, $this->lang->project->deliverableAbbr);;
                }
            }
            if(dao::isError()) return false;
        }

        $requiredFields = $this->config->project->create->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $requiredFields) as $field)
        {
            if(isset($this->lang->project->$field)) $this->lang->project->$field = $this->lang->project->$field;
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF(!empty($project->name), 'name', 'unique', "`type`='project' and `parent` = " . $this->dao->sqlobj->quote($project->parent) . " and `model` = " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkIF(!empty($project->code), 'code', 'unique', "`type`='project' and `model` = " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkIF(!empty($project->end), 'end', 'gt', $project->begin)
            ->checkFlow();

        return true;
    }

    /**
     * check execution.
     *
     * @param array  $executions
     * @param int    $projectID
     * @param string $model
     * @access public
     * @return void
     */
    public function checkExecution($executions, $projectID = 0, $model = 'scrum')
    {
        if(!empty($_POST)) $project = $_POST;
        if(empty($project['end'])) $project['end'] = LONG_TIME;

        $productID = 0;
        $parents   = array();
        foreach($executions as $index => $execution)
        {
            if(isset($execution->product) && $productID != $execution->product)
            {
                $productID = $execution->product;
                $parents   = array();
            }

            if(!isset($parents[$execution->parent]))
            {
                $parent = new stdclass();
                $parent->type         = $execution->parent == $projectID ? 'project' : 'execution';
                $parent->totalPercent = 0;
                $parent->attribute    = $parent->type == 'project' ? '' : $executions[$execution->parent]->attribute;
                $parent->begin        = $parent->type == 'project' ? zget($project, 'begin', '') : $executions[$execution->parent]->begin;
                $parent->end          = $parent->type == 'project' ? zget($project, 'end', '')   : $executions[$execution->parent]->end;

                $parents[$execution->parent] = $parent;
            }

            $executionID   = $execution->executionID;
            $executionType = zget($this->lang->execution->typeList, $execution->type);
            $executionName = "『" . $executionType . $execution->name . "』";

            if(strpos(",{$this->config->execution->create->requiredFields},", ',code,') !== false && !empty($this->config->setCode) && empty($execution->code))
            {
                dao::$errors["codes{$index}"][] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
                return false;
            }

            if(isset($execution->percent) and !empty($execution->percent) and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $execution->percent))
            {
                dao::$errors["percent{$index}"][] = $this->lang->programplan->error->percentNumber;
                return false;
            }

            $parents[$execution->parent]->totalPercent += isset($execution->percent) ? (float)$execution->percent : 0;
            $parent = $parents[$execution->parent];

            if($parent->totalPercent > 100)
            {
                dao::$errors['message'][] = $this->lang->programplan->error->percentOver;
                return false;
            }

            if($parent->type != 'project' and $parent->attribute != 'mix' and $parent->attribute != $execution->attribute)
            {
                dao::$errors["attributes{$index}"][] = sprintf($this->lang->programplan->error->sameType, $this->lang->stage->typeList[$parent->attribute]);
                return false;
            }

            if(helper::isZeroDate($execution->begin))
            {
                dao::$errors["begins{$index}"][] = $executionName . $this->lang->programplan->emptyBegin;
                return false;
            }
            if(!validater::checkDate($execution->begin))
            {
                dao::$errors["begins{$index}"][] = $executionName . $this->lang->programplan->checkBegin;
                return false;
            }

            if(helper::isZeroDate($execution->end))
            {
                dao::$errors["ends{$index}"][] = $executionName . $this->lang->programplan->emptyEnd;
                return false;
            }
            if(!validater::checkDate($execution->end))
            {
                dao::$errors["ends{$index}"][] = $executionName . $this->lang->programplan->checkEnd;
                return false;
            }

            if(!helper::isZeroDate($execution->end) and $execution->end < $execution->begin)
            {
                dao::$errors["ends{$index}"][] = $executionName . $this->lang->programplan->error->planFinishSmall;
                return false;
            }

            if(!helper::isZeroDate($execution->begin) && !helper::isZeroDate($parent->begin) && $execution->begin < $parent->begin)
            {
                if($parent->type == 'project')
                {
                    if($model == 'scrum' or $model == 'agileplus')
                    {
                        dao::$errors["begins{$index}"][] = $executionName . sprintf($this->lang->execution->errorBegin, $project['begin']);
                    }
                    else
                    {
                        dao::$errors["begins{$index}"][] = $executionName . sprintf($this->lang->programplan->errorBegin, $project['begin']);
                    }
                    return false;
                }
                dao::$errors["begins{$index}"][] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
                return false;
            }

            if(!helper::isZeroDate($execution->end) && !helper::isZeroDate($parent->end) && $execution->end > $parent->end)
            {
                if($parent->type == 'project')
                {
                    if($model == 'scrum' or $model == 'agileplus')
                    {
                        dao::$errors["ends{$index}"][] = $executionName . sprintf($this->lang->execution->errorEnd, $project['end']);
                    }
                    else
                    {
                        dao::$errors["ends{$index}"][] = $executionName .  sprintf($this->lang->programplan->errorEnd, $project['end']);
                    }
                    return false;
                }
                dao::$errors["ends{$index}"][] = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
                return false;
            }

            if(helper::isZeroDate($execution->begin)) $execution->begin = '';
            if(helper::isZeroDate($execution->end))   $execution->end   = '';
            if($model == 'waterfall')
            {
                foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
                {
                    $field = trim($field);
                    if($field and empty($execution->$field))
                    {
                        dao::$errors["{$field}s{$index}"][] = sprintf($this->lang->error->notempty, $this->lang->programplan->$field);
                        return false;
                    }
                }
            }
            else if($model == 'scrum' or $model == 'agileplus')
            {
                foreach(explode(',', $this->config->project->create->requiredFields) as $field)
                {
                    $field = trim($field);
                    if($field and isset($execution->$field) and empty($execution->$field))
                    {
                        dao::$errors["{$field}s{$index}"][] = sprintf($this->lang->error->notempty, $this->lang->execution->$field);
                        return false;
                    }
                }

                /* Judge workdays is legitimate. */
                $workdays = helper::diffDate($execution->end, $execution->begin) + 1;
                if(!empty($execution->days))
                {
                    if(!preg_match("/^[0-9]\d*$/", $execution->days))
                    {
                        dao::$errors["dayses{$index}"][] = $this->lang->project->copyProject->daysTips;
                        return false;
                    }
                    if($execution->days > $workdays)
                    {
                        dao::$errors["dayses{$index}"][] = sprintf($this->lang->project->workdaysExceed, $workdays);
                        return false;
                    }
                }

                if(isset($execution->code))
                {
                    if($this->checkCodeUnique($execution->code) !== true)
                    {
                        dao::$errors["codes{$index}"][] = $this->lang->project->copyProject->executionCode;
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check code unique.
     *
     * @param  string $code
     * @access public
     * @return mix
     */
    public function checkCodeUnique($code)
    {
        $code = $this->dao->select('code')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage,kanban')
            ->andWhere('deleted')->eq('0')
            ->andWhere('code')->eq($code)
            ->fetch('code');
        return $code ? $code : true;
    }

    /**
     * Save process.
     *
     * @param int     $copyProjectID
     * @param int     $executionID
     * @param int     $lastExecutionID
     * @param string  $model
     * @access public
     * @return void
     */
    public function saveProcess($copyProjectID, $projectID, $executionID, $lastExecutionID, $model)
    {
        $projectActivities = $this->dao->select('*')
            ->from(TABLE_PROGRAMACTIVITY)
            ->where('project')->eq($copyProjectID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($model == 'scrum' or $model == 'agileplus')->andWhere('execution')->eq($executionID)->fi()
            ->fetchAll('id', false);

        if(!empty($projectActivities))
        {
            foreach($projectActivities as $projectActivity)
            {
                $insertProjectActivity = new stdClass();
                $insertProjectActivity->project     = $projectID;
                if($model == 'scrum' or $model == 'agileplus') $insertProjectActivity->execution = $lastExecutionID;
                $insertProjectActivity->process     = $projectActivity->process;
                $insertProjectActivity->activity    = $projectActivity->activity;
                $insertProjectActivity->name        = $projectActivity->name;
                $insertProjectActivity->content     = $projectActivity->content;
                $insertProjectActivity->reason      = $projectActivity->reason;
                $insertProjectActivity->result      = $projectActivity->result;
                $insertProjectActivity->linkedBy    = $this->app->user->account;
                $insertProjectActivity->createdBy   = $this->app->user->account;
                $insertProjectActivity->createdDate = helper::today();
                $this->dao->insert(TABLE_PROGRAMACTIVITY)->data($insertProjectActivity)->exec();
            }
        }

        $projectOutputs = $this->dao->select('*')
            ->from(TABLE_PROGRAMOUTPUT)
            ->where('project')->eq($copyProjectID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($model == 'scrum' or $model == 'agileplus')->andWhere('execution')->eq($executionID)->fi()
            ->fetchAll('id', false);

        if(!empty($projectOutputs))
        {
            foreach($projectOutputs as $projectOutput)
            {
                $insertProjectOutput = new stdClass();
                $insertProjectOutput->project     = $projectID;
                if($model == 'scrum' or $model == 'agileplus') $insertProjectOutput->execution = $lastExecutionID;
                $insertProjectOutput->process     = $projectOutput->process;
                $insertProjectOutput->activity    = $projectOutput->activity;
                $insertProjectOutput->output      = $projectOutput->output;
                $insertProjectOutput->name        = $projectOutput->name;
                $insertProjectOutput->content     = $projectOutput->content;
                $insertProjectOutput->reason      = $projectOutput->reason;
                $insertProjectOutput->result      = $projectOutput->result;
                $insertProjectOutput->linkedBy    = $this->app->user->account;
                $insertProjectOutput->createdBy   = $this->app->user->account;
                $insertProjectOutput->createdDate = helper::today();

                $this->dao->insert(TABLE_PROGRAMOUTPUT)->data($insertProjectOutput)->exec();
            }
        }
    }

    /**
     * Save QA.
     *
     * @param int  $copyProjectID
     * @param int  $projectID
     * @param int  $executionID
     * @param int  $lastExecutionID
     * @access public
     * @return void
     */
    public function saveQA($copyProjectID, $projectID, $executionID, $lastExecutionID)
    {
        $auditplans = $this->dao->select('*')->from(TABLE_AUDITPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($copyProjectID)
            ->andWhere('execution')->eq($executionID)
            ->fetchAll();

        if(!empty($auditplans))
        {
            foreach($auditplans as $auditplan)
            {
                $insertAuditplan = new stdClass();
                $insertAuditplan->objectID      = $auditplan->objectID;
                $insertAuditplan->objectType    = $auditplan->objectType;
                $insertAuditplan->process       = $auditplan->process;
                $insertAuditplan->processType   = $auditplan->processType;
                $insertAuditplan->status        = 'wait';
                $insertAuditplan->project       = $projectID;
                $insertAuditplan->execution     = $lastExecutionID;
                $insertAuditplan->createdBy     = $this->app->user->account;
                $insertAuditplan->assignedTo    = $auditplan->assignedTo;
                $insertAuditplan->createdDate   = helper::today();

                $this->dao->insert(TABLE_AUDITPLAN)->data($insertAuditplan)->exec();

                if(!dao::isError())
                {
                    $auditplanID = $this->dao->lastInsertID();
                    $this->loadModel('action')->create('auditplan', $auditplanID, 'Opened');
                }
            }
        }
    }

    /**
     * Save task.
     *
     * @param int  $copyProjectID
     * @param int  $projectID
     * @param int  $executionID
     * @param int  $lastExecutionID
     * @access public
     * @return void
     */
    public function saveTask($copyProjectID, $projectID, $executionID, $lastExecutionID)
    {
        $tasks = $this->dao->select('*,`desc`,mailto,path')->from(TABLE_TASK)
            ->where('project')->eq($copyProjectID)
            ->andWhere('execution')->eq($executionID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('isParent desc, id asc')
            ->fetchAll();
        $execution = $this->loadModel('execution')->getByID($executionID);
        $project   = $this->fetchByID($projectID);

        if(!empty($tasks))
        {
            $insertIdList = array();
            foreach($tasks as $task)
            {
                $insertTask = new stdClass();

                $insertTask->project     = $projectID;
                $insertTask->type        = $task->type;
                $insertTask->module      = $task->module;
                $insertTask->name        = $task->name;
                $insertTask->pri         = $task->pri;
                $insertTask->status      = 'wait';
                $insertTask->desc        = $task->desc;
                $insertTask->mode        = $task->mode;
                $insertTask->execution   = $lastExecutionID;
                $insertTask->estimate    = $task->estimate;
                $insertTask->assignedTo  = $task->assignedTo == 'closed' ? '' : $task->assignedTo;
                $insertTask->mailto      = $task->mailto;
                $insertTask->openedBy    = $this->app->user->account;
                $insertTask->openedDate  = helper::now();
                $insertTask->parent      = $task->parent;
                $insertTask->isParent    = $task->isParent;
                $insertTask->path        = $task->path;
                $insertTask->isTpl       = $project->isTpl;

                $this->dao->insert(TABLE_TASK)->data($insertTask)->exec();
                $lastTaskID = $this->dao->lastInsertId();
                $insertIdList[$task->id] = $lastTaskID;

                if(!dao::isError())
                {
                    $this->loadModel('action')->create('task', $lastTaskID, 'Opened', '');
                    if(!empty($insertTask->assignedTo)) $this->action->create('task', $lastTaskID, 'Assigned', '', $insertTask->assignedTo);
                }

                /* Save multi task. */
                if(!empty($task->mode))
                {
                    $taskTeams = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($task->id)->orderBy('order,id')->fetchAll();

                    if(!empty($taskTeams))
                    {
                        foreach($taskTeams as $taskTeam)
                        {
                            $insertTaskTeam = new stdClass();

                            $insertTaskTeam->task     = $lastTaskID;
                            $insertTaskTeam->account  = $taskTeam->account;
                            $insertTaskTeam->estimate = $taskTeam->estimate;
                            $insertTaskTeam->consumed = 0;
                            $insertTaskTeam->left     = $taskTeam->estimate;
                            $insertTaskTeam->order    = $taskTeam->order;
                            $insertTaskTeam->status   = 'wait';

                            $this->dao->insert(TABLE_TASKTEAM)->data($insertTaskTeam)->exec();
                        }
                    }
                }

                /* Update kanban cell. */
                if($execution->type == 'kanban')
                {
                    $this->dao->update(TABLE_KANBANCELL)->set("`cards` = REPLACE(`cards`, ',{$task->id},', ',{$lastTaskID},')")->where('kanban')->eq($lastExecutionID)->andWhere('type')->eq('task')->andWhere('cards')->like("%,{$task->id},%")->exec();
                }
            }

            $insertTasks = $this->dao->select('id,parent,path')->from(TABLE_TASK)->where('id')->in(array_values($insertIdList))->fetchAll();
            foreach($insertTasks as $insertTask)
            {
                $updateTask = new stdClass();
                $updateTask->parent = zget($insertIdList, $insertTask->parent, 0);
                $updateTask->path   = ',';

                $pathList = explode(',', trim($insertTask->path, ','));
                foreach($pathList as $path)
                {
                    if(!isset($insertIdList[$path])) continue;
                    $updateTask->path .= $insertIdList[$path] . ',';
                }

                $this->dao->update(TABLE_TASK)->data($updateTask)->where('id')->eq($insertTask->id)->exec();
            }
        }
    }

    /**
     * Save execution doc lib.
     *
     * @param int     $executionID
     * @param int     $lastExecutionID
     * @param int     $projectID
     * @access public
     * @return void
     */
    public function saveExecutionDocLib($executionID, $lastExecutionID, $projectID)
    {
        $executionDocLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('type')->eq('execution')
            ->andWhere('execution')->eq($executionID)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq('0')
            ->fetchAll();

        if(!empty($executionDocLibs))
        {
            foreach($executionDocLibs as $executionDocLib)
            {
                $executionDocLib->project   = $projectID;
                $executionDocLib->execution = $lastExecutionID;
                $originDocLibID = $executionDocLib->id;
                unset($executionDocLib->id);
                $this->dao->insert(TABLE_DOCLIB)->data($executionDocLib)->exec();
                $executionDoclibId = $this->dao->lastInsertId();

                /* execution doc module */
                $executionModules = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq($originDocLibID)
                    ->andWhere('type')->eq('doc')
                    ->andWhere('deleted')->eq('0')
                    ->orderBy('id asc')
                    ->fetchAll();
                $executionIdMap = array();
                if(!empty($executionModules))
                {
                    foreach($executionModules as $module)
                    {
                        $originModuleID = $module->id;
                        unset($module->id);
                        unset($module->root);
                        unset($module->path);
                        $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                        $moduleID = $this->dao->lastInsertId();
                        $executionIdMap[$originModuleID] = $moduleID;

                        if(!empty($module->parent))
                        {
                            $path   = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq($module->parent)->fetch('path');
                            $path  .= "{$moduleID},";
                            $parent = isset($executionIdMap[$module->parent]) ? $executionIdMap[$module->parent] : 0;
                        }
                        else
                        {
                            $path   = ",{$moduleID},";
                            $parent = 0;
                        }

                        $this->dao->update(TABLE_MODULE)
                            ->set('path')->eq($path)
                            ->set('root')->eq($executionDoclibId)
                            ->set('parent')->eq($parent)
                            ->where('id')->eq($moduleID)
                            ->limit(1)->exec();
                    }
                }
            }
        }
    }

    /**
     * Save project doc lib.
     *
     * @param int  $copyProjectID
     * @param int  $projectID
     * @access public
     * @return void
     */
    public function saveProjectDocLib($copyProjectID, $projectID)
    {
        $projectDocLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('type')->eq('project')
            ->andWhere('project')->eq($copyProjectID)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq('0')
            ->fetchAll();

        if(!empty($projectDocLibs))
        {
            /* Delete origin project main lib. */
            $this->dao->delete()->from(TABLE_DOCLIB)
                ->where('type')->eq('project')
                ->andWhere('project')->eq((int)$projectID)
                ->andWhere('vision')->eq($this->config->vision)
                ->andWhere('name')->eq($this->lang->doclib->main['project'])
                ->exec();

            foreach($projectDocLibs as $projectDocLib)
            {
                $projectDocLib->project = $projectID;
                $originDocLibID = $projectDocLib->id;
                unset($projectDocLib->id);
                $this->dao->insert(TABLE_DOCLIB)->data($projectDocLib)->exec();
                $projectDoclibId = $this->dao->lastInsertId();

                /* project doc module */
                $projectModules = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq($originDocLibID)
                    ->andWhere('type')->eq('doc')
                    ->andWhere('deleted')->eq('0')
                    ->fetchAll();
                $projectIdMap = array();
                if(!empty($projectModules))
                {
                    foreach($projectModules as $module)
                    {
                        $originModuleID = $module->id;
                        unset($module->id);
                        unset($module->root);
                        unset($module->path);
                        $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                        $moduleID = $this->dao->lastInsertId();
                        $projectIdMap[$originModuleID] = $moduleID;

                        if(!empty($module->parent))
                        {
                            $path   = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq($module->parent)->fetch('path');
                            $path  .= "{$moduleID},";
                            $parent = isset($projectIdMap[$module->parent]) ? $projectIdMap[$module->parent] : 0;
                        }
                        else
                        {
                            $path   = ",{$moduleID},";
                            $parent = 0;
                        }

                        $this->dao->update(TABLE_MODULE)
                            ->set('path')->eq($path)
                            ->set('root')->eq($projectDoclibId)
                            ->set('parent')->eq($parent)
                            ->where('id')->eq($moduleID)
                            ->limit(1)->exec();
                    }
                }
            }
        }
    }

    /**
     * Save team.
     *
     * @param int     $copyObjectID
     * @param int     $objectID
     * @param string  $type
     * @access public
     * @return void
     */
    public function saveTeam($copyObjectID, $objectID, $type = 'project')
    {
        $teams = $this->dao->select('*')->from(TABLE_TEAM)
            ->where('type')->eq($type)
            ->andWhere('root')->eq($copyObjectID)
            ->fetchAll();

        $this->loadModel('execution');
        $days = $this->dao->select('days')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('days');
        if(!empty($teams))
        {
            $today = helper::today();
            foreach($teams as $member)
            {
                $insertMenber = new stdClass();
                $insertMenber->root    = $objectID;
                $insertMenber->type    = $type;
                $insertMenber->account = $member->account;
                $insertMenber->role    = $member->role;
                $insertMenber->limited = $member->limited;
                $insertMenber->join    = $today;
                $insertMenber->days    = $days;
                $insertMenber->hours   = $this->config->execution->defaultWorkhours;

                $this->dao->replace(TABLE_TEAM)->data($insertMenber)->exec();
            }

            if($type == 'project')
            {
                $acl = $this->dao->select('acl')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('acl');
                if($acl != 'open') $this->loadModel('user')->updateUserView(array($objectID), 'project');
            }
        }
    }

    /**
     * Save whitelist.
     *
     * @param mixed $whitelist
     * @param mixed $executionID
     * @access public
     * @return void
     */
    public function saveWhitelist($whitelist, $executionID)
    {
        if($whitelist)
        {
            $whitelist = array_filter(explode(',', $whitelist));
            foreach($whitelist as $account)
            {
                $data = new stdClass();
                $data->account    = $account;
                $data->objectType = 'sprint';
                $data->objectID   = $executionID;
                $data->type       = 'whitelist';
                $data->source     = 'add';
                $this->dao->insert(TABLE_ACL)->data($data)->exec();
            }
        }
    }

    /**
     * Save stakeholder.
     *
     * @param int  $copyProjectID
     * @param int  $projectID
     * @access public
     * @return void
     */
    public function saveStakeholder($copyProjectID, $projectID)
    {
        $stakeholders = $this->dao->select('*')->from(TABLE_STAKEHOLDER)
            ->where('objectType')->eq('project')
            ->andWhere('objectID')->eq($copyProjectID)
            ->fetchAll();

        if(!empty($stakeholders))
        {
            foreach($stakeholders as $stakeholder)
            {
                $insertStakeholders = new stdClass();
                $insertStakeholders->objectID    = $projectID;
                $insertStakeholders->objectType  = 'project';
                $insertStakeholders->user        = $stakeholder->user;
                $insertStakeholders->type        = $stakeholder->type;
                $insertStakeholders->key         = $stakeholder->key;
                $insertStakeholders->createdBy   = $this->app->user->account;
                $insertStakeholders->createdDate = helper::now();

                $this->dao->insert(TABLE_STAKEHOLDER)->data($insertStakeholders)->exec();
            }
        }
    }

    /**
     * Save group.
     *
     * @param int  $copyProjectID
     * @param int  $projectID
     * @access public
     * @return void
     */
    public function saveGroup($copyProjectID, $projectID)
    {
        $groups = $this->dao->select('*,acl')->from(TABLE_GROUP)
            ->where('project')->eq($copyProjectID)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchAll('id');

        $groupPrivs = $this->dao->select('*')->from(TABLE_GROUPPRIV)->where('`group`')->in(array_keys($groups))->fetchGroup('group');

        if(!empty($groups))
        {
            foreach($groups as $groupID => $group)
            {
                $insertGroups = new stdClass();
                $insertGroups->project    = $projectID;
                $insertGroups->vision     = $group->vision;
                $insertGroups->name       = $group->name;
                $insertGroups->role       = $group->role;
                $insertGroups->desc       = $group->desc;
                $insertGroups->acl        = $group->acl;
                $insertGroups->desc       = $group->desc;
                $insertGroups->developer  = $group->developer;

                $this->dao->insert(TABLE_GROUP)->data($insertGroups)->exec();
                $lastGroupID = $this->dao->lastInsertID();

                /* Add group privs. */
                if(!empty($groupPrivs[$groupID]))
                {
                    foreach($groupPrivs[$groupID] as $groupPriv)
                    {
                        $priv = new stdClass();
                        $priv->group  = $lastGroupID;
                        $priv->module = $groupPriv->module;
                        $priv->method = $groupPriv->method;
                        $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
                    }
                }

                /* Add user group. */
                $groupID    = $group->id;
                $userGroups = $this->dao->select('*')->from(TABLE_USERGROUP)
                    ->where('`group`')->eq($groupID)
                    ->fetchAll();

                if(!empty($userGroups))
                {
                    foreach($userGroups as $userGroup)
                    {
                        $insertUserGroup = new stdClass();

                        $insertUserGroup->account = $userGroup->account;
                        $insertUserGroup->group   = $lastGroupID;

                        $this->dao->insert(TABLE_USERGROUP)->data($insertUserGroup)->exec();
                    }
                }
            }
        }
    }

    /**
     * Save RD kanban.
     *
     * @param  int    $executionID
     * @param  int    $lastExecutionID
     * @access public
     * @return void
     */
    public function saveKanban($executionID, $lastExecutionID)
    {
        $this->loadModel('kanban');
        $execution = new stdclass();
        $execution->id    = $lastExecutionID;
        $execution->space = 0;
        $this->kanban->copyRegions($execution, $executionID, 'execution', 'updateTaskCell');
    }

    /**
     * Set menu of project module.
     *
     * @param  int    $projectID
     * @access public
     * @return int|false
     */
    public function setMenu($projectID)
    {
        $projectID = parent::setMenu($projectID);
        $project   = $this->getByID($projectID);

        if(!$project) return false;

        $model         = isset($project->model) ? $project->model : '';
        $workflowGroup = $this->loadModel('workflowgroup')->getById($project->workflowGroup);

        if($model == 'ipd')
        {
            $this->loadModel('baseline');
            $objectList = $this->lang->baseline->ipd->pointList;

            unset($objectList['other']);
            $this->lang->baseline->ipd->objectList = $this->lang->baseline->objectList;
            $this->lang->baseline->objectList = $objectList + $this->lang->baseline->objectList;
        }

        if(in_array($model, array('scrum', 'waterfall', 'agileplus', 'waterfallplus')))
        {
            $featureList = $this->config->featureGroup->$model;
            $menuKey     = in_array($model, array('scrum', 'agileplus')) ? 'scrum' : 'waterfall';
            foreach($featureList as $feature)
            {
                if(!helper::hasFeature("{$model}_$feature"))
                {
                    if($feature == 'measrecord')   $feature = 'report';
                    if($feature == 'process')      $feature = 'pssp';
                    if($feature == 'gapanalysis')  $feature = 'train';
                    if($feature == 'researchplan') $feature = 'research';
                    unset($this->lang->{$menuKey}->menu->other['dropMenu']->{$feature});
                }
            }

            if($menuKey == 'waterfall' and !helper::hasFeature("{$model}_track")) unset($this->lang->waterfall->menu->track);
        }

        /* Move the Gantt navigation to the first one for waterfall project menu. */
        if(in_array($model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            list($stageCommon) = explode('|', $this->lang->waterfall->menu->execution['link']);
            $this->lang->waterfall->menu->execution['link'] = "{$stageCommon}|programplan|browse|projectID=$projectID&productID=0&type=gantt";
            if($this->app->rawMethod == 'execution') $this->lang->waterfall->menu->execution['subModule'] .= ',project';
        }

        if(!empty($project->isTpl))
        {
            $this->lang->projectCommon   = $this->lang->project->template;
            $this->lang->project->common = $this->lang->project->template;

            unset($this->lang->project->menu->index);
            unset($this->lang->project->menu->story);
            unset($this->lang->project->menu->projectplan);
            unset($this->lang->project->menu->qa);
            unset($this->lang->project->menu->design);
            unset($this->lang->project->menu->build);
            unset($this->lang->project->menu->release);
            unset($this->lang->project->menu->devops);
            unset($this->lang->project->menu->track);
            unset($this->lang->project->menu->review);
            unset($this->lang->project->menu->cm);
            unset($this->lang->project->menu->estimation);
            unset($this->lang->project->menu->weekly);
            unset($this->lang->project->menu->research);
            unset($this->lang->project->menu->report);
            unset($this->lang->project->menu->train);
            unset($this->lang->project->menu->kanban);
            unset($this->lang->project->menu->burn);
            unset($this->lang->project->menu->view);
            unset($this->lang->project->menu->effort);
            unset($this->lang->project->menu->settings['subMenu']->whitelist);
            unset($this->lang->project->menu->settings['subMenu']->products);
            unset($this->lang->project->menu->settings['subMenu']->stakeholder);
            unset($this->lang->project->menu->settings['subMenu']->module);
            if(empty($workflowGroup->objectID)) unset($this->lang->project->menu->settings['subMenu']->workflow);
            unset($this->lang->project->menu->settings['subMenu']->approval);

            $docOrder = 0;
            foreach ($this->lang->project->menuOrder as $order => $menu) if ($menu == 'doc') $docOrder = $order;

            if(!empty($this->lang->project->menu->other['dropMenu']->pssp))
            {
                $this->lang->project->menu->pssp               = $this->lang->project->menu->other['dropMenu']->pssp;
                $this->lang->project->menuOrder[$docOrder + 1] = 'pssp';
            }

            if(!empty($this->lang->project->menu->other['dropMenu']->auditplan))
            {
                $this->lang->project->menu->auditplan = $this->lang->project->menu->other['dropMenu']->auditplan;
                $this->lang->project->menuOrder[$docOrder + 2] = 'auditplan';
            }

            unset($this->lang->project->menu->other);
        }
        return $projectID;
    }

    /**
     * 检查名称唯一性.
     * Check name unique.
     *
     * @param  array  $names
     * @access public
     * @return bool
     */
    public function checkNameUnique($names)
    {
        $names = array_filter($names);
        return count(array_unique($names)) == count($names);
    }

    /**
     * 获取项目或执行的交付物。
     * Get project or execution deliverable.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  int    $groupID
     * @param  string $projectType
     * @param  string $projectModel
     * @param  string $method
     * @access public
     * @return array
     */
    public function getProjectDeliverable($projectID, $executionID, $groupID = 0, $projectType = 'project', $projectModel = 'scrum', $method = 'whenCreated')
    {
        if(!helper::hasFeature('deliverable')) return array();

        $deliverables    = array();
        $deliverableList = $this->dao->select('id,name,files')->from(TABLE_DELIVERABLE)->where('deleted')->eq('0')->fetchAll('id');
        if($projectID || $executionID)
        {
            $execution          = $executionID ? $this->fetchByID($executionID) : array();
            $project            = $this->fetchByID($executionID ? $execution->project : $projectID);
            $projectModel       = $project->model;
            $projectType        = !empty($project->hasProduct) ? 'product' : 'project';
            $projectDeliverable = $executionID ? $execution->deliverable : $project->deliverable;
            $fileList           = $this->dao->select('*')->from(TABLE_FILE)->where('objectID')->eq($executionID ? $executionID : $projectID)->andWhere('objectType')->eq($executionID ? 'execution' : 'project')->fetchAll('id');
            $docList            = $this->dao->select('*')->from(TABLE_DOC)->where('project')->eq($project->id)->fetchAll('id');

        }
        else if($groupID)
        {
            $projectDeliverable = $this->dao->select('deliverable')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($groupID)->fetch('deliverable');
        }

        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';
        $objectCode = "{$projectType}_{$projectModel}";
        if($executionID) $objectCode .= (!empty($execution->attribute) ? "_{$execution->attribute}" : "_{$execution->lifetime}");
        if($executionID && $execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

        $projectDeliverable = !empty($projectDeliverable) ? json_decode($projectDeliverable, true) : array();
        if(!empty($projectDeliverable[$objectCode][$method]))
        {
            foreach($projectDeliverable[$objectCode][$method] as $deliverable)
            {
                /* 创建时过滤掉已被删除的交付物 */
                if(!$projectID && !$executionID && empty($deliverableList[$deliverable['deliverable']])) continue;

                $file = $doc = array();
                if(!empty($deliverable['file']) && !empty($fileList[$deliverable['file']])) $file = $fileList[$deliverable['file']];
                if(!empty($deliverable['doc'])  && !empty($docList[$deliverable['doc']]))   $doc  = $docList[$deliverable['doc']];

                $item = array();
                if(strpos($deliverable['deliverable'], 'new_') === false) $item['id'] = $deliverable['deliverable'];                                               // 交付物ID。
                $item['required'] = !empty($deliverable['required']) ? true : false;                                                                               // 交付物是否必填。
                $item['category'] = isset($deliverable['category']) ? $deliverable['category'] : $deliverableList[$deliverable['deliverable']]->name;              // 交付物的名称。
                $item['template'] = isset($deliverable['template']) ? $deliverable['template'] : $deliverableList[$deliverable['deliverable']]->files;             // 交付物的模板ID。
                $item['file']     = !empty($file) ? array('id' => $file->id, 'name' => $file->title, 'size' => $file->size, 'extension' => $file->extension) : ''; // 交付物的附件ID。
                $item['doc']      = !empty($doc)  ? array('id' => $doc->id, 'title' => $doc->title, 'version' => $doc->version) : '';                              // 交付物的文档ID。

                $deliverables[] = $item;
            }
        }

        $deliverables[] = array('category' => $this->lang->other, 'required' => false);

        return $deliverables;
    }

    /**
     * 创建一个项目。
     * Create a project.
     *
     * @param  object   $project
     * @param  object   $postData
     * @access public
     * @return int|bool
     */
    public function create($project, $postData)
    {
        $isLinkStory = $this->post->isLinkStory == 'on' ? true : false;
        $charter     = $this->post->charter;
        if(isset($_POST['isLinkStory'])) unset($_POST['isLinkStory']);

        /* 将表单里的交付物字段数据处理后存到项目表里。 */
        if(!empty($project->deliverable))
        {
            $project = $this->processDeliverable(0, 0, $project, 'whenCreated');
        }
        else
        {
            unset($project->deliverable);
        }

        $projectID = parent::create($project, $postData);
        if(dao::isError()) return false;

        if(!empty($project->deliverable))
        {
            $this->uploadDeliverable($projectID, 'project', 'deliverable', 'whenCreated');
            $this->moveDocToProjectLib($projectID, 0, 'whenCreated');
        }

        if($this->config->edition == 'ipd' && $projectID && !empty($isLinkStory))
        {
            $project     = $this->fetchByID($projectID);
            $product     = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetch('product');
            $executionID = $projectID;
            if(empty($project->multiple)) $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);

            $roadmaps = $this->dao->select('roadmap')->from(TABLE_CHARTER)->where('id')->eq((int)$charter)->fetch('roadmap');
            $stories  = $this->dao->select('t1.story')->from(TABLE_ROADMAPSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                ->where('t1.roadmap')->in($roadmaps)
                ->andWhere('t2.type')->eq('requirement')
                ->fetchPairs();

            if(count($stories)) $this->execution->linkStory($executionID, $stories, '', array(), 'requirement');
        }

        return $projectID;
    }

    /**
     * 关闭项目并更改其状态
     * Close project and update status.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access public
     * @return array|false
     */
    public function close($projectID, $project)
    {
        $oldProject = $this->getByID($projectID);
        if(!empty($project->deliverable))
        {
            $project = $this->processDeliverable($projectID, 0, $project, 'whenClosed');
        }
        else
        {
            unset($project->deliverable);
        }
        $changes = parent::close($projectID, $project);
        if(dao::isError()) return false;

        if(!empty($project->deliverable))
        {
            $this->uploadDeliverable($projectID, 'project', 'deliverable', 'whenClosed');
            $this->moveDocToProjectLib($projectID, 0, 'whenClosed');
        }
        $project = $this->getByID($projectID);
        $changes = common::createChanges($oldProject, $project);

        return $changes;
    }

    /**
     *
     * 处理表单里的交付物数据。
     * Process deliverable.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  object $postData
     * @param  string $method whenCreate|whenClosed
     * @access public
     * @return object
     */
    public function processDeliverable($projectID, $executionID, $postData, $method = 'whenCreated')
    {
        $postDeliverable = $postData->deliverable;
        if($projectID)
        {
            $project            = $this->fetchByID($projectID);
            $execution          = $executionID ? $this->fetchByID($executionID) : array();
            $projectDeliverable = $execution ? $execution->deliverable : $project->deliverable;
        }
        else
        {
            $project            = clone $postData;
            $projectDeliverable = $this->dao->select('deliverable')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($project->workflowGroup)->fetch('deliverable');
        }

        $projectDeliverable = !empty($projectDeliverable) ? json_decode($projectDeliverable, true) : array();
        $deliverableList    = $this->dao->select('id,name,files')->from(TABLE_DELIVERABLE)->where('deleted')->eq('0')->fetchAll('id');

        if(empty($projectDeliverable))
        {
            $postData->deliverable = '';
            return $postData;
        }

        /* 将交付物名称和模板ID也保存起来，后台修改了也不影响已创建的项目。 */
        foreach($projectDeliverable as $stageCode => $methodDeliverable)
        {
            foreach($methodDeliverable as $methodCode => $deliverables)
            {
                foreach($deliverables as $index => $deliverable)
                {
                    $deliverableID = $deliverable['deliverable'];

                    /* 过滤掉被删除的交付物和其他交付物。 */
                    if((!$projectID && empty($deliverableList[$deliverableID])) || strpos($deliverableID, 'new_') !== false)
                    {
                        unset($projectDeliverable[$stageCode][$methodCode][$index]);
                        continue;
                    }

                    $projectDeliverable[$stageCode][$methodCode][$index]['category'] = isset($deliverable['category']) ? $deliverable['category'] : $deliverableList[$deliverableID]->name;
                    $projectDeliverable[$stageCode][$methodCode][$index]['template'] = isset($deliverable['template']) ? $deliverable['template'] : $deliverableList[$deliverableID]->files;
                }
            }
        }

        /* 将上传的件和选择的文档记录到配置里。 */
        $projectModel = $project->model;
        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

        $projectType = !empty($project->hasProduct) ? 'product' : 'project';
        $objectCode  = "{$projectType}_{$projectModel}";
        if($executionID) $objectCode .= (!empty($execution->attribute) ? "_{$execution->attribute}" : "_{$execution->lifetime}");
        if($executionID && $execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

        if(!empty($projectDeliverable[$objectCode][$method]))
        {
            foreach($projectDeliverable[$objectCode][$method] as $index => $deliverable)
            {
                $deliverableID = $deliverable['deliverable'];
                $projectDeliverable[$objectCode][$method][$index]['doc']  = $postDeliverable[$deliverableID]['doc'];
                $projectDeliverable[$objectCode][$method][$index]['file'] = $postDeliverable[$deliverableID]['fileID'];
                if(!empty($_FILES['deliverable']['name'][$deliverableID]))
                {
                    $projectDeliverable[$objectCode][$method][$index]['file'] = $_FILES['deliverable']['name'][$deliverableID];
                }
            }
        }

        /* 其他交付物最后追加进数组。 */
        foreach($postDeliverable as $deliverableID => $deliverable)
        {
            if(strpos($deliverableID, 'new_') !== false)
            {
                $otherDeliverable = array('deliverable' => $deliverableID, 'required' => false, 'doc' => $deliverable['doc'], 'file' => $deliverable['fileID']);
                $otherDeliverable['category'] = isset($deliverable['category']) ? $deliverable['category'] : $this->lang->other;
                $otherDeliverable['template'] = '';
                if(!empty($_FILES['deliverable']['name'][$deliverableID])) $otherDeliverable['file'] = $_FILES['deliverable']['name'][$deliverableID];

                if (!empty($otherDeliverable['doc'] || !empty($otherDeliverable['file']))) $projectDeliverable[$objectCode][$method][] = $otherDeliverable;
            }
        }

        $postData->deliverable = json_encode($projectDeliverable);

        return $postData;
    }

    /**
     * 上传交付物附件。
     * Upload deliverable file.
     *
     * @param  int    $projectID
     * @param  string $moduleName
     * @param  string $formName
     * @param  string $method
     * @access public
     * @return bool
     */
    public function uploadDeliverable($projectID, $moduleName = 'project', $formName = 'deliverable', $method = 'whenCreated')
    {
        if(!empty($_FILES[$formName]))
        {
            foreach($_FILES[$formName]['name'] as $id => $fileName)
            {
                $extra = strpos($id, 'new_') === false ? "deliverable_{$id}" : "deliverable_{$method}_{$id}"; // 同时保存创建、关闭交付物要区分，否则有两个deliverable_new_0
                $_FILES[$formName]['extra'][$id] = $extra;
                if(empty($fileName)) unset($_FILES[$formName]['error'][$id]);
            }

            $this->loadModel('file')->saveUpload($moduleName, $projectID, '', $formName);
            $this->processDeliverableFiles($projectID, $moduleName);
        }

        return true;
    }

    /**
     * 将交付物配置里的file存储为真实的fileID。
     * Process deliverable files.
     *
     * @param  int    $projectID
     * @param  string $moduleName
     * @access public
     * @return bool
     */
    public function processDeliverableFiles($projectID, $moduleName = 'project')
    {
        $fileList = $this->dao->select('extra,id')->from(TABLE_FILE)
            ->where('objectID')->eq($projectID)
            ->andWhere('objectType')->eq($moduleName == 'execution' ? 'execution' : 'project')
            ->fetchPairs();

        $project            = $this->fetchByID($projectID);
        $projectDeliverable = json_decode($project->deliverable);

        if(!$projectDeliverable) return true;

        foreach($projectDeliverable as $stageCode => $methodDeliverable)
        {
            foreach($methodDeliverable as $methodCode => $deliverables)
            {
                foreach($deliverables as $index => $deliverable)
                {
                    $fileKey = strpos($deliverable->deliverable, 'new_') === false ? "deliverable_{$deliverable->deliverable}" : "deliverable_{$methodCode}_{$deliverable->deliverable}";
                    if(!empty($deliverable->file) && !empty($fileList[$fileKey])) $deliverable->file = $fileList[$fileKey];
                }
            }
        }
        $projectDeliverable = json_encode($projectDeliverable);
        $this->dao->update(TABLE_PROJECT)->set('`deliverable`')->eq($projectDeliverable)->where('id')->eq($projectID)->exec();

        return !dao::isError();
    }


    /**
     *
     * 将交付物文档复制到项目库中。
     * Move doc to project doc lib.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $method
     * @access public
     * @return bool
     */
    public function moveDocToProjectLib($projectID, $executionID, $method = 'whenCreated')
    {
        $this->loadModel('doc');
        $this->loadModel('action');

        $project     = $this->fetchByID($projectID);
        $execution   = $executionID ? $this->fetchByID($executionID) : array();
        $projectType = !empty($project->hasProduct) ? 'product' : 'project';
        $projectLib  = $this->dao->select('id')->from(TABLE_DOCLIB)
            ->where('project')->eq($project->id)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->andWhere('type')->eq('execution')->fi()
            ->beginIF(!$executionID)->andWhere('type')->eq('project')->fi()
            ->andWhere('main')->eq('1')
            ->fetch('id');

        $projectModel = $project->model;
        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

        $projectType = !empty($project->hasProduct) ? 'product' : 'project';
        $objectCode  = "{$projectType}_{$projectModel}";
        if($executionID) $objectCode .= (!empty($execution->attribute) ? "_{$execution->attribute}" : "_{$execution->lifetime}");
        if($executionID && $execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

        $projectDeliverable = json_decode($executionID ? $execution->deliverable : $project->deliverable, true);
        if(!empty($projectDeliverable[$objectCode][$method]))
        {
            foreach($projectDeliverable[$objectCode][$method] as $index => $deliverable)
            {
                if(!empty($deliverable['doc']))
                {
                    /* 这个getByID会额外获取docContent表里的数据。 */
                    $oldDoc = $this->doc->getByID($deliverable['doc']);

                    /* 如果这个文档已经在本项目库下了就别复制了。 */
                    if($oldDoc->lib == $projectLib) continue;

                    $doc = new stdclass();
                    $doc->lib         = $projectLib;
                    $doc->title       = $oldDoc->title;
                    $doc->parent      = 0;
                    $doc->module      = 0;
                    $doc->type        = $oldDoc->type;
                    $doc->contentType = $oldDoc->contentType;
                    $doc->rawContent  = $oldDoc->rawContent;
                    $doc->content     = $oldDoc->content;
                    $doc->status      = $oldDoc->status;
                    $doc->acl         = 'open';
                    $doc->vision      = $oldDoc->vision;
                    $doc->copy        = true;
                    $doc->addedBy     = $this->app->user->account;
                    $doc->addedDate   = helper::now();

                    $docResult = $this->doc->create($doc);
                    if(dao::isError()) return false;

                    $docID = $docResult['id'];
                    $files = zget($docResult, 'files', '');

                    $fileAction = '';
                    if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";

                    $this->action->create('doc', $docID, 'Created', $fileAction, '', '', false);

                    $projectDeliverable[$objectCode][$method][$index]['doc'] = $docID;
                }
            }

            /* 复制完成后变更项目表交付物配置。 */
            $projectDeliverable = json_encode($projectDeliverable);
            $this->dao->update(TABLE_PROJECT)->set('`deliverable`')->eq($projectDeliverable)->where('id')->eq(!empty($execution) ? $execution->id : $project->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * 检查项目是否提交过交付物。
     * Check uploaded deliverable.
     *
     * @param  object $project
     * @access public
     * @return bool
     */
    public function checkUploadedDeliverable($project)
    {
        if($project->project)
        {
            $execution = clone $project;
            $project   = $this->fetchByID($project->project);
        }

        $projectModel = $project->model;
        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

        $projectType = !empty($project->hasProduct) ? 'product' : 'project';
        $objectCode  = "{$projectType}_{$projectModel}";
        if(!empty($execution)) $objectCode .= (!empty($execution->attribute) ? "_{$execution->attribute}" : "_{$execution->lifetime}");
        if(!empty($execution) && $execution->type == 'kanban') $objectCode = "{$projectType}_{$projectModel}_kanban";

        $projectDeliverable = json_decode(!empty($execution) ? $execution->deliverable : $project->deliverable, true);
        if(!empty($projectDeliverable[$objectCode]))
        {
            foreach($projectDeliverable[$objectCode] as $method => $methodDeliverable)
            {
                foreach($methodDeliverable as $index => $deliverable)
                {
                    if(!empty($deliverable['doc']) || !empty($deliverable['file'])) return true;
                }
            }
        }
        return false;
    }

    /**
     * 维护交付物页面的保存逻辑。
     * Save deliverable.
     *
     * @param  object $object
     * @param  string $objectType
     * @param  object $postData
     * @access public
     * @return bool
     */
    public function saveDeliverable($object, $objectType,$postData)
    {
        $objectCode = $this->getObjectCode($object, $objectType);
        if($object->deliverable)
        {
            $originDeliverables = json_decode($object->deliverable, true);
            $deliverables       = isset($originDeliverables[$objectCode]) ? $originDeliverables[$objectCode] : array();

            /* 后台没有配置过交付物，直接提交其他类型的情况。*/
            if(!isset($deliverables['whenCreated']) && $objectType != 'execution') $deliverables['whenCreated'] = array();
            if(!isset($deliverables['whenClosed']))  $deliverables['whenClosed']  = array();
        }
        else
        {
            $originDeliverables = $deliverables = $objectType == 'execution' ? array('whenClosed' => array()) : array('whenCreated' => array(), 'whenClosed' => array());
        }

        $newFiles    = array();
        $newDocs     = array();
        $delFiles    = array();
        $delDocs     = array();
        $renameFiles = array();
        $renameDocs  = array();

        $filePairs = $this->dao->select('id,title')->from(TABLE_FILE)
            ->where('objectID')->eq($object->id)
            ->andWhere('objectType')->eq($objectType)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();

        /* FILES和POST中的交付物处理，并检查必填。 */
        foreach($deliverables as $method => $deliverableList)
        {
            foreach($deliverableList as $index => $deliverable)
            {
                $deliverableID  = $deliverable['deliverable'];
                $newDeliverable = isset($postData->{$method}[$deliverableID]) ? $postData->{$method}[$deliverableID] : array();

                /* 原来有附件,新交付物把附件删除了。*/
                if(!empty($deliverable['file']) && empty($newDeliverable['fileID']) && empty($_FILES[$method]['name'][$deliverableID]) && empty($newDeliverable['doc']))
                {
                    $delFiles[] = $deliverable['file'];

                    $deliverables[$method][$index]['file'] = '';
                    if(strpos($deliverableID, 'new_') !== false) unset($deliverables[$method][$index]); // 其他类型的交付物什么也没有，则删除
                }
                /* 原来有附件,新交付物改成了另一个附件。*/
                elseif(!empty($deliverable['file']) && !empty($_FILES[$method]['name'][$deliverableID]))
                {
                    $newFile    = $_FILES[$method]['name'][$deliverableID];
                    $newFiles[] = $newFile;
                    $delFiles[] = $deliverable['file'];

                    $deliverables[$method][$index]['file'] = $newFile;
                }
                /* 原来有附件,新交付物把附件改名了。*/
                elseif(!empty($deliverable['file']) && !empty($newDeliverable['fileID']) && $newDeliverable['name'] && $deliverable['file'] != $newDeliverable['name'])
                {
                    $fileID               = $newDeliverable['fileID'];
                    $renameFiles[$fileID] = array('old' => $deliverable['file'], 'new' => $newDeliverable['name']);

                    $deliverables[$method][$index]['file'] = $newDeliverable['name'];
                }
                /* 原来有附件,新交付物把附件变成文档了。*/
                elseif(!empty($deliverable['file']) && !empty($newDeliverable['doc']))
                {
                    $newDocs[] = $newDeliverable['doc'];

                    $deliverables[$method][$index]['file'] = '';
                    $deliverables[$method][$index]['doc']  = $newDeliverable['doc'];
                }
                /* 原来是文档, 新交付物把文档删除了。*/
                elseif(!empty($deliverable['doc']) && empty($newDeliverable['doc']) && empty($_FILES[$method]['name'][$deliverableID]))
                {
                    $delDocs[] = $deliverable['doc'];

                    $deliverables[$method][$index]['doc'] = '';
                    if(strpos($deliverableID, 'new_') !== false) unset($deliverables[$method][$index]); // 其他类型的交付物什么也没有，则删除
                }
                /* 原来是文档, 新交付物把文档改名了。*/
                elseif(!empty($deliverable['doc']) && !empty($newDeliverable['doc']) && $newDeliverable['name'])
                {
                    $docID = $newDeliverable['doc'];
                    $renameDocs[$docID] = $newDeliverable['name'];
                }
                /* 原来是文档, 新交付物把文档改成另一个文档。*/
                elseif(!empty($deliverable['doc']) && !empty($newDeliverable['doc']) && $deliverable['doc'] != $newDeliverable['doc'])
                {
                    $delDocs[] = $deliverable['doc'];
                    $newDocs[] = $newDeliverable['doc'];

                    $deliverables[$method][$index]['doc'] = $newDeliverable['doc'];
                }
                /* 原来是文档, 新交付物把文档改成附件。*/
                elseif(!empty($deliverable['doc']) && !empty($newDeliverable['fileID']))
                {
                    $delDocs[]  = $deliverable['doc'];
                    $newFiles[] = $newDeliverable['name'];

                    $deliverables[$method][$index]['doc']  = '';
                    $deliverables[$method][$index]['file'] = $newDeliverable['name'];
                }
                /* 原来什么也没有,上传了附件。*/
                elseif(empty($deliverable['file']) && !empty($_FILES[$method]['name'][$deliverableID]))
                {
                    $newFile    = $_FILES[$method]['name'][$deliverableID];
                    $newFiles[] = $newFile;

                    $deliverables[$method][$index]['file'] = $newFile;
                }
                /* 原来什么也没有,关联了文档。*/
                elseif(empty($deliverable['doc']) && !empty($newDeliverable['doc']))
                {
                    $newDocs[] = $newDeliverable['doc'];

                    $deliverables[$method][$index]['file'] = '';
                    $deliverables[$method][$index]['doc']  = $newDeliverable['doc'];
                }

                if(strpos($deliverableID, 'new_') !== false && isset($postData->{$method}[$deliverableID])) unset($postData->{$method}[$deliverableID]); // 如果是其他类型的交付物，已经处理过文件，后面追加的时候不需要处理
                if($method == 'whenClosed' && $object->status != 'closed') continue; // 未关闭项目不检查关闭的交付物必填项
                if($method == 'whenCreated') continue; // 暂时隐藏创建时的交付物，不检查必填

                if(!empty($deliverable['required']) && empty($deliverables[$method][$index]['doc']) && empty($deliverables[$method][$index]['file']))
                {
                    $field = $method . '[' . $deliverableID . ']';
                    dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->project->deliverableAbbr);
                }
            }

            /* 新增了其他类型的交付物。*/
            foreach($postData->{$method} as $deliverableID => $postDeliverable)
            {
                if(strpos($deliverableID, 'new_') !== false)
                {
                    $otherDeliverable = array('deliverable' => $deliverableID, 'required' => false, 'doc' => $postDeliverable['doc'], 'template' => '', 'category' => $this->lang->other);
                    if(!empty($_FILES[$method]['name'][$deliverableID])) $otherDeliverable['file'] = $_FILES[$method]['name'][$deliverableID];

                    if(!empty($otherDeliverable['doc'] || !empty($otherDeliverable['file'])))
                    {
                        $deliverables[$method][] = $otherDeliverable;

                        if(!empty($otherDeliverable['doc']))  $newDocs[]  = $otherDeliverable['doc'];  // 新增的交付物文档ID
                        if(!empty($otherDeliverable['file'])) $newFiles[] = $otherDeliverable['file']; // 新增的交付物附件名称
                    }
                }
            }
        }

        if(dao::isError()) return false;

        $originDeliverables[$objectCode] = $deliverables;
        $this->dao->update(TABLE_PROJECT)->set('deliverable')->eq(json_encode($originDeliverables))->where('id')->eq($object->id)->exec();
        foreach(array('whenCreated', 'whenClosed') as $method)
        {
            $this->uploadDeliverable($object->id, $objectType, $method, $method);

            $executionID = $objectType == 'execution' ? $object->id : 0;
            $projectID   = $objectType == 'execution' ? $object->project : $object->id;
            $this->moveDocToProjectLib($projectID, $executionID, $method);
        }

        if(dao::isError()) return false;

        $docs = $this->dao->select('id,title')->from(TABLE_DOC)
            ->where('id')->in($newDocs)
            ->orWhere('id')->in($delDocs)
            ->orWhere('id')->in(array_keys($renameDocs))
            ->fetchPairs();

        /* 文档ID转换成标题。 */
        foreach($newDocs as $index => $docID) $newDocs[$index] = $docs[$docID];
        foreach($delDocs as $index => $docID) $delDocs[$index] = $docs[$docID];

        if($delFiles) $this->dao->update(TABLE_FILE)->set('deleted')->eq('1')->where('id')->in($delFiles)->exec();

        $comment = '';
        $this->app->loadLang('deliverable');
        if(!empty($newDocs))  $comment .= sprintf($this->lang->deliverable->addedDoc,    implode(',', $newDocs)) . '<br>';
        if(!empty($delDocs))  $comment .= sprintf($this->lang->deliverable->deletedDoc,  implode(',', $delDocs)) . '<br>';
        if(!empty($newFiles)) $comment .= sprintf($this->lang->deliverable->addedFile,   implode(',', $newFiles)) . '<br>';
        if(!empty($delFiles))
        {
            foreach($delFiles as $id => $file)
            {
                if(is_int($file)) $delFiles[$id] = $filePairs[$file];
            }

            $comment .= sprintf($this->lang->deliverable->deletedFile, implode(',', $delFiles)) . '<br>';
        }

        if($renameFiles)
        {
            foreach($renameFiles as $fileID => $file)
            {
                $oldID    = $file['old'];
                $comment .= sprintf($this->lang->deliverable->renamedFile, $filePairs[$oldID], $file['new']) . '<br>';
                $this->dao->update(TABLE_FILE)->set('title')->eq($file['new'])->where('id')->eq($fileID)->exec();
            }
        }

        if($renameDocs)
        {
            foreach($renameDocs as $docID => $newName)
            {
                $oldName  = $docs[$docID];
                $comment .= sprintf($this->lang->deliverable->renamedDoc, $oldName, $newName) . '<br>';
                $this->dao->update(TABLE_DOC)->set('title')->eq($newName)->where('id')->eq($docID)->exec();
                $this->dao->update(TABLE_DOCCONTENT)->set('title')->eq($newName)->where('id')->eq($docID)->exec();
            }
        }

        if($comment) $this->loadModel('action')->create($objectType, $object->id, 'managedeliverable', $comment);

        return true;
    }

    /**
     * 获取交付物对象的代号。
     * Get object code.
     *
     * @param  object $object
     * @param  string $objectType
     * @access public
     * @return string
    */
    public function getObjectCode($object, $objectType)
    {
        $projectType = !empty($object->hasProduct) ? 'product' : 'project';

        /* 执行随项目的model。 */
        if($objectType == 'execution')
        {
            $projectID     = isset($object->projectID) ? $object->projectID : $object->project;
            $project       = $this->fetchByID($projectID);
            $object->model = $project->model;
        }

        $projectModel = $object->model;
        if($projectModel == 'agileplus')     $projectModel = 'scrum';
        if($projectModel == 'waterfallplus') $projectModel = 'waterfall';

        if($objectType == 'project')  return "{$projectType}_{$projectModel}";
        if($object->type == 'kanban') return "{$projectType}_{$projectModel}_kanban";
        if($object->type == 'stage')  return "{$projectType}_{$projectModel}_{$object->attribute}";

        return "{$projectType}_{$projectModel}_{$object->lifetime}";
    }

    /**
     * 计算交付物数量。
     * Count deliverable.
     *
     * @param  object $object
     * @access public
     * @return string
     * @param string $objectType
     */
    public function countDeliverable($object, $objectType = 'project')
    {
        if($objectType == 'project' && ($object->model == 'kanban' || $object->model == 'ipd')) return '';
        if($objectType == 'execution' && $object->rawParent != $object->projectID) return ''; // 只有顶级执行和独立执行才有交付物
        if($objectType == 'execution' && ($object->projectModel == 'ipd' || $object->projectModel== 'kanban')) return ''; // IPD项目和看板项目不显示交付物

        $attr     = $objectType == 'execution' ? 'data-toggle="modal"' : '';
        $objectID = $objectType == 'project' ? $object->id : $object->rawID;
        if(empty($object->deliverable)) return (common::hasPriv($objectType, 'deliverable') && common::canModify($objectType, $object)) ? html::a(helper::createLink($objectType, 'deliverable', "projectID={$objectID}"), '0 / 0', '', "title='{$this->lang->project->deliverableTips}' {$attr}") : '0 / 0';

        $numerator    = 0;
        $denominator  = 0;
        $objectCode   = $this->getObjectCode($object, $objectType);
        $deliverables = json_decode($object->deliverable);
        $deliverables = isset($deliverables->{$objectCode}) ? $deliverables->{$objectCode} : array();

        foreach($deliverables as $itemList)
        {
            if(!is_array($itemList)) continue;
            array_map(function($item) use(&$numerator, &$denominator)
            {
                if(!empty($item->file) || !empty($item->doc)) $numerator ++;
                if(!empty($item->file) || !empty($item->doc) || !empty($item->required)) $denominator ++;
            }, $itemList);
        }

        return (common::hasPriv($objectType, 'deliverable') && common::canModify($objectType, $object)) ? html::a(helper::createLink($objectType, 'deliverable', "projectID={$objectID}"), $numerator . ' / ' . $denominator, '', "title='{$this->lang->project->deliverableTips}' {$attr}") : $numerator . ' / ' . $denominator;
    }
}
