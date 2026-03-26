<?php
class excelStory extends StoryModel
{
    public function setListValue($productID, $branch = 0)
    {
        $product    = $this->loadModel('product')->getByID($productID);
        $modules    = $this->loadModel('tree')->getOptionMenu($productID, 'story', 0, 'all');
        $plans      = $this->loadModel('productplan')->getPairs($productID, '', 'unexpired', true);
        $priList    = $this->lang->story->priList;
        $sourceList = $this->lang->story->sourceList;

        unset($plans['']);
        foreach($modules  as $id => $module) $modules[$id] .= "(#$id)";
        foreach($plans    as $id => $plan) $plans[$id] .= "(#$id)";

        if($product->type != 'normal')
        {
            $this->config->story->export->listFields[] = 'branch';

            $branches = $this->loadModel('branch')->getPairs($product->id);
            foreach($branches as $id => $branch) $branches[$id] .= "(#$id)";

            $this->post->set('branchList',   array_values($branches));
        }

        if($this->config->edition != 'open') $this->loadModel('workflowfield')->setFlowListValue('story');

        $this->post->set('moduleList', array_values($modules));
        $this->post->set('planList',   array_values($plans));
        $this->post->set('priList',    join(',', $priList));
        $this->post->set('sourceList', array_values($sourceList));
        $this->post->set('listStyle',  $this->config->story->export->listFields);
        $this->post->set('extraNum',   0);
        $this->post->set('product',    $product->name);
    }

    public function createFromImport($productID, $branch = 0, $type = 'story', $projectID = 0)
    {
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('file');

        $forceReview = $this->story->checkForceReview();

        foreach($_POST['title'] as $index => $title)
        {
            if($_POST['title'][$index] and isset($_POST['reviewer'][$index])) $_POST['reviewer'][$index] = array_filter($_POST['reviewer'][$index]);
            if(empty($_POST['reviewer'][$index]) and $forceReview)
            {
                $realIndex = $index - 1;
                dao::$errors["reviewer_$realIndex"] = sprintf($this->lang->error->notempty, $this->lang->story->reviewedBy);
            }
        }

        if(dao::isError()) return false;

        $now    = helper::now();
        $branch = (int)$branch;
        $data   = fixer::input('post')->get();
        $levels = zget($data, 'level', array());

        $this->app->loadClass('purifier', true);
        $purifierConfig = HTMLPurifier_Config::createDefault();
        $purifierConfig->set('Filter.YouTube', 1);
        $purifier = new HTMLPurifier($purifierConfig);

        if(!$this->post->insert && !empty($_POST['id']))
        {
            $oldStories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in(($_POST['id']))->andWhere('product')->eq($productID)->fetchAll('id', false);
            $oldSpecs   = $this->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->in(array_keys($oldStories))->orderBy('version')->fetchAll('story', false);
        }

        $stories      = array();
        $line         = 1;
        $planIdList   = array();
        $extendFields = array();

        if($this->config->edition != 'open')
        {
            $extendFields = $this->loadModel('flow')->getExtendFields($type, 'showimport');
            $notEmptyRule = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');

            foreach($extendFields as $extendField)
            {
                if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
                {
                    $this->config->story->create->requiredFields .= ',' . $extendField->field;
                }
            }
        }

        foreach($data->product as $key => $product)
        {
            $storyData = new stdclass();
            $specData  = new stdclass();

            $storyData->product    = $product;
            $storyData->branch     = isset($data->branch[$key]) ? (int)$data->branch[$key] : $branch;
            $storyData->module     = (int)$data->module[$key];
            $storyData->plan       = !empty($data->plan[$key][0]) ? implode(',', $data->plan[$key]) : '';
            $storyData->source     = $data->source[$key];
            $storyData->sourceNote = $data->sourceNote[$key];
            $storyData->title      = trim($data->title[$key]);
            $storyData->pri        = (int)$data->pri[$key];
            $storyData->estimate   = (float)$data->estimate[$key];
            $storyData->keywords   = $data->keywords[$key];
            $storyData->type       = $type;
            $storyData->vision     = $this->config->vision;

            $specData->title  = ltrim(html_entity_decode($storyData->title), '>');
            $specData->spec   = nl2br($purifier->purify($this->post->spec[$key]));
            $specData->verify = nl2br($purifier->purify($this->post->verify[$key]));

            if(empty($specData->title)) continue;

            foreach($extendFields as $extendField)
            {
                $dataArray = $_POST[$extendField->field];
                $storyData->{$extendField->field} = $dataArray[$key];
                if(is_array($storyData->{$extendField->field})) $storyData->{$extendField->field} = join(',', $storyData->{$extendField->field});

                $storyData->{$extendField->field} = htmlSpecialString($storyData->{$extendField->field});
            }

            if(isset($this->config->story->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->story->create->requiredFields);
                $requiredFields = array_filter($requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    if($requiredField == 'reviewer') continue;

                    $realIndex     = $key - 1;
                    $requiredField = trim($requiredField);
                    $tmpData       = ($requiredField == 'spec' || $requiredField == 'verify') ? $specData : $storyData;

                    if(empty($tmpData->$requiredField)) dao::$errors["{$requiredField}_{$realIndex}"] = sprintf($this->lang->error->notempty, $this->lang->story->$requiredField);
                }
            }

            $stories[$key]['storyData'] = $storyData;
            $stories[$key]['specData']  = $specData;
            $line++;

            $planIdList[$storyData->plan] = $storyData->plan;
        }
        if(dao::isError()) return false;

        $maxGrade           = $this->dao->select('max(grade) as maxGrade')->from(TABLE_STORYGRADE)->where('type')->eq($type)->andWhere('status')->eq('enable')->fetch('maxGrade');
        $gradePairs         = $this->getGradePairs($type);
        $gradePairs         = array_keys($gradePairs);
        $maxPlanStoryOrders = $this->dao->select('plan,max(`order`) as `order`')->from(TABLE_PLANSTORY)->where('plan')->in($planIdList)->groupBy('plan')->fetchPairs('plan', 'order');

        $levelList = array();
        $message   = $this->lang->saveSuccess;
        foreach($stories as $key => $newStory)
        {
            $storyData = $newStory['storyData'];
            $specData  = $newStory['specData'];

            $storyID = 0;
            $version = 0;
            if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
            {
                $storyID = $data->id[$key];
                if(!isset($oldStories[$storyID])) $storyID = 0;
            }

            if($storyID)
            {
                $specData->spec   = str_replace('src="' . common::getSysURL() . '/', 'src="', $specData->spec);
                $specData->verify = str_replace('src="' . common::getSysURL() . '/', 'src="', $specData->verify);

                $oldSpec  = (array)$oldSpecs[$storyID];
                $newSpec  = (array)$specData;
                $oldStory = $oldStories[$storyID];

                /* Ignore updating stories for different products. */
                if($oldStory->product != $storyData->product) continue;

                $oldSpec['spec']   = trim($this->file->excludeHtml($oldSpec['spec'], 'noImg'));
                $oldSpec['verify'] = trim($this->file->excludeHtml($oldSpec['verify'], 'noImg'));
                $newSpec['spec']   = trim($this->file->excludeHtml($newSpec['spec'], 'noImg'));
                $newSpec['verify'] = trim($this->file->excludeHtml($newSpec['verify'], 'noImg'));
                $storyChanges = common::createChanges($oldStory, $storyData);
                $specChanges  = common::createChanges((object)$oldSpec, (object)$newSpec);

                if($specChanges)
                {
                    $storyData->version      = $oldStory->version + 1;
                    $storyData->reviewedBy   = '';
                    $storyData->closedBy     = '';
                    $storyData->closedReason = '';
                    $storyData->status       = (empty($_POST['reviewer'][$key]) and !$forceReview) ? 'active' : 'reviewing';
                    if($oldStory->reviewedBy) $storyData->reviewedDate   = null;
                    if($oldStory->closedBy) $storyData->closedDate       = null;

                    $newSpecData = $oldSpecs[$storyID];
                    $newSpecData->version += 1;

                    $version = $storyData->version;

                    foreach($specChanges as $specChange)$newSpecData->{$specChange['field']} = $specData->{$specChange['field']};
                }

                if($storyChanges or $specChanges)
                {
                    $storyData->lastEditedBy   = $this->app->user->account;
                    $storyData->lastEditedDate = $now;
                    $this->dao->update(TABLE_STORY)
                        ->data($storyData)
                        ->autoCheck()
                        ->checkFlow()
                        ->batchCheck($this->config->story->change->requiredFields, 'notempty')
                        ->where('id')->eq((int)$storyID)->exec();

                    if(!dao::isError())
                    {
                        if($specChanges)
                        {
                            $this->dao->insert(TABLE_STORYSPEC)->data($newSpecData)->exec();
                            $actionID = $this->action->create('story', $storyID, 'Changed', '');
                            $this->action->logHistory($actionID, $specChanges);
                        }

                        if($oldStory->plan != $storyData->plan)
                        {
                            if($oldStory->plan) $this->dao->delete()->from(TABLE_PLANSTORY)->where('plan')->eq($oldStory->plan)->andWhere('story')->eq($storyID)->exec();
                            if($storyData->plan)
                            {
                                $maxOrder  = (int)zget($maxPlanStoryOrders, $storyData->plan, 0) + 1;
                                $planStory = new stdclass();
                                $planStory->plan  = $storyData->plan;
                                $planStory->story = $storyID;
                                $planStory->order = $maxOrder;
                                $this->dao->replace(TABLE_PLANSTORY)->data($planStory)->exec();

                                $maxPlanStoryOrders[$storyData->plan] = $maxOrder;
                            }
                        }

                        if($storyChanges)
                        {
                            $actionID = $this->action->create('story', $storyID, 'Edited', '');
                            $this->action->logHistory($actionID, $storyChanges);
                            $message = $this->executeHooks($storyID);
                        }
                    }
                }
            }
            else
            {
                $storyData->title      = ltrim(html_entity_decode($storyData->title), '>');
                $storyData->status     = (empty($_POST['reviewer'][$key]) and !$forceReview) ? 'active' : 'reviewing';
                $storyData->version    = $version = 1;
                if($storyData->plan > 0) $storyData->stage = 'planned';
                $storyData->openedBy   = $this->app->user->account;
                $storyData->openedDate = $now;
                $storyData->product    = $productID;
                $storyData->grade      = 1;

                $this->dao->insert(TABLE_STORY)->data($storyData)->autoCheck()->checkFlow()->exec();

                if(!dao::isError())
                {
                    $storyID = $this->dao->lastInsertID();

                    $level       = zget($levels, $key, 0);
                    $story       = new stdclass();
                    $story->path = ",$storyID,";
                    $story->root = $storyID;
                    if($level && preg_match('/^\d+(\.\d+)*$/', $level) && !isset($levelList[$level]))
                    {
                        $grade        = count((array)explode('.', $level));
                        $story->grade = $grade > $maxGrade ? 1 : $grade;
                        $levelList[$level] = $storyID;
                    }

                    $this->dao->update(TABLE_STORY)->data($story)->where('id')->eq($storyID)->exec();

                    $specData->story   = $storyID;
                    $specData->version = 1;
                    $this->dao->insert(TABLE_STORYSPEC)->data($specData)->exec();

                    if($projectID)
                    {
                        $projectStory = new stdclass();
                        $projectStory->project = $projectID;
                        $projectStory->product = $storyData->product;
                        $projectStory->story   = $storyID;
                        $projectStory->version = $storyData->version;

                        $this->dao->insert(TABLE_PROJECTSTORY)->data($projectStory)->exec();
                        $this->setStage($storyID);
                    }

                    if($storyData->plan)
                    {
                        $maxOrder  = (int)zget($maxPlanStoryOrders, $storyData->plan, 0) + 1;
                        $planStory = new stdclass();
                        $planStory->plan  = $storyData->plan;
                        $planStory->story = $storyID;
                        $planStory->order = $maxOrder;
                        $this->dao->replace(TABLE_PLANSTORY)->data($planStory)->exec();

                        $maxPlanStoryOrders[$storyData->plan] = $maxOrder;
                    }

                    $this->action->create('story', $storyID, 'Opened', '');
                    $message = $this->executeHooks($storyID);
                }
            }

            /* Save the story reviewer to storyreview table. */
            if(isset($_POST['reviewer'][$key]))
            {
                $assignedTo = '';
                foreach($_POST['reviewer'][$key] as $reviewer)
                {
                    if(empty($reviewer)) continue;

                    $reviewData = new stdclass();
                    $reviewData->story    = $storyID;
                    $reviewData->version  = $version;
                    $reviewData->reviewer = $reviewer;
                    $this->dao->insert(TABLE_STORYREVIEW)->data($reviewData)->exec();

                    if(empty($assignedTo)) $assignedTo = $reviewer;
                }
                if($assignedTo) $this->dao->update(TABLE_STORY)->set('assignedTo')->eq($assignedTo)->set('assignedDate')->eq($now)->where('id')->eq($storyID)->exec();
            }
        }

        if($levelList)
        {
            foreach($levelList as $level => $storyID)
            {
                $parentLevel = substr($level, 0, strrpos($level, '.'));
                if(isset($levelList[$parentLevel]))
                {
                    $parentID    = $levelList[$parentLevel];
                    $parent      = $this->dao->select('id,root,grade,path')->from(TABLE_STORY)->where('id')->eq($parentID)->fetch();
                    $parentGrade = $parent->grade;

                    if(($parentGrade + 1) <= $maxGrade) // 说明子需求有效
                    {
                        $this->dao->update(TABLE_STORY)->set('isParent')->eq('1')->where('id')->eq($parentID)->exec();

                        $story  = new stdclass();
                        $story->parent = $levelList[$parentLevel];
                        $story->root   = $parent->root;
                        $story->grade  = ($parentGrade + 1) > $maxGrade ? 1 : ($parentGrade + 1);
                        $story->path   = $parent->path . $storyID . ',';
                        $this->dao->update(TABLE_STORY)->data($story)->where('id')->eq($storyID)->exec();

                        $this->updateParentStatus($storyID, $parentID, false);
                    }
                }
                else
                {
                    $story = new stdclass();
                    $story->root  = $storyID;
                    $story->path  = ",$storyID,";
                    $story->grade = 1;
                    $this->dao->update(TABLE_STORY)->data($story)->where('id')->eq($storyID)->exec();
                }
            }
        }

        if($this->post->isEndPage)
        {
            unlink($this->session->fileImportFileName);
            unset($_SESSION['fileImportFileName']);
            unset($_SESSION['fileImportExtension']);
        }

        return $message;
    }

    /**
     * @param string $type
     */
    public function replaceURLang($type)
    {
        parent::replaceURLang($type);
        if($type != 'requirement') return;

        $SRCommon    = $this->lang->SRCommon;
        $replacement = $type == 'requirement' ? $this->lang->URCommon : $this->lang->epic->common;

        $this->lang->story->importCase  = str_replace($SRCommon, $replacement, $this->lang->story->importCase);
        $this->lang->story->num         = str_replace($SRCommon, $replacement, $this->lang->story->num);
    }
}
