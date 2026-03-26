<?php
class zentaoipdProgramplan extends programplanModel
{
    /**
     * Create a plan.
     *
     * @param  array  $plans
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $parentID
     * @param  int    $syncData
     * @access public
     * @return bool
     */
    public function create($plans, $projectID = 0, $productID = 0, $parentID = 0, $syncData = 0)
    {
        $data = (array)fixer::input('post')->get();
        extract($data);

        /* Determine if a task has been created under the parent phase. */
        if(!$this->isCreateTask($parentID)) return dao::$errors['message'][] = $this->lang->programplan->error->createdTask;

        /* The child phase type setting is the same as the parent phase. */
        $parentAttribute = '';
        $parentPercent   = 0;
        if($parentID)
        {
            $parentStage     = $this->getByID($parentID);
            $parentAttribute = $parentStage->attribute;
            $parentPercent   = $parentStage->percent;
            $parentACL       = $parentStage->acl;
        }

        $names     = array_filter($name);
        $sameNames = array_diff_assoc($names, array_unique($names));

        $project   = $this->loadModel('project')->getByID($projectID);
        $setCode   = (isset($this->config->setCode) && $this->config->setCode == 1 && $project->model != 'research') ? true : false;
        $sameCodes = $setCode ? $this->checkCodeUnique($code, isset($id) ? $id: '') : false;

        $setPercent = (isset($this->config->setPercent) && $this->config->setPercent == 1 && $project->model != 'research') ? true : false;
        $datas = array();
        foreach($names as $key => $name)
        {
            if(empty($name)) continue;

            $plan = new stdclass();
            $plan->id         = isset($id[$key]) ? $id[$key] : '';
            $plan->type       = empty($type[$key]) ? 'stage' : $type[$key];
            $plan->project    = $projectID;
            $plan->parent     = $parentID ? $parentID : $projectID;
            $plan->name       = $names[$key];
            if($setCode)    $plan->code    = $code[$key];
            if($setPercent) $plan->percent = $percent[$key];
            $plan->attribute  = (empty($parentID) or $parentAttribute == 'mix') ? $attributes[$key] : $parentAttribute;
            $plan->milestone  = !empty($milestone[$key]) ? 1 : 0;
            $plan->output     = empty($output[$key]) ? '' : implode(',', $output[$key]);
            $plan->acl        = empty($parentID) ? $acl[$key] : $parentACL;
            $plan->PM         = empty($PM[$key]) ? '' : $PM[$key];
            $plan->desc       = empty($desc[$key]) ? '' : $desc[$key];
            $plan->hasProduct = $project->hasProduct;
            $plan->vision     = $this->config->vision;
            $plan->market     = $project->market;

            $plan->begin     = !empty($begin[$key])     ? $begin[$key]     : null;
            $plan->end       = !empty($end[$key])       ? $end[$key]       : null;
            $plan->realBegan = !empty($realBegan[$key]) ? $realBegan[$key] : null;
            $plan->realEnd   = !empty($realEnd[$key])   ? $realEnd[$key]   : null;

            $datas[] = $plan;
        }

        if(empty($datas))
        {
            dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->name);
            return false;
        }

        $totalPercent = 0;
        $totalDevType = 0;
        $milestone    = 0;
        foreach($datas as $index => $plan)
        {
            $index += 1;
            if(!empty($sameNames) and in_array($plan->name, $sameNames)) dao::$errors["name[$index]"][] = empty($type) ? $this->lang->programplan->error->sameName : str_replace($this->lang->execution->stage, '', $this->lang->programplan->error->sameName);
            if($setCode and $sameCodes !== true and !empty($sameCodes) and in_array($plan->code, $sameCodes)) dao::$errors["code[$index]"][] = sprintf($this->lang->error->repeat, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code, $plan->code);

            if($setPercent and $plan->percent and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $plan->percent))
            {
                dao::$errors[$index]['percent'] = $this->lang->programplan->error->percentNumber;
            }
            if(helper::isZeroDate($plan->begin))
            {
                dao::$errors["begin[$index]"][] = $this->lang->programplan->emptyBegin;
            }
            if(!validater::checkDate($plan->begin) and empty(dao::$errors["begin[$index]"]))
            {
                dao::$errors["begin[$index]"][] = $this->lang->programplan->checkBegin;
            }
            if(helper::isZeroDate($plan->end))
            {
                dao::$errors["end[$index]"][] = $this->lang->programplan->emptyEnd;
            }
            if(!validater::checkDate($plan->end) and empty(dao::$errors["end[$index]"]))
            {
                dao::$errors["end[$index]"][] = $this->lang->programplan->checkEnd;
            }
            if(!helper::isZeroDate($plan->end) and $plan->end < $plan->begin and empty(dao::$errors["begin[$index]"]))
            {
                dao::$errors["end[$index]"][] = $this->lang->programplan->error->planFinishSmall;
            }
            if(isset($parentStage) && !helper::isZeroDate($plan->begin) && $plan->begin < $parentStage->begin)
            {
                 dao::$errors["begin[$index]"][] = sprintf($this->lang->programplan->error->letterParent, $parentStage->begin);
            }
            if(isset($parentStage) && $plan->end > $parentStage->end)
            {
                 dao::$errors["end[$index]"][] = sprintf($this->lang->programplan->error->greaterParent, $parentStage->end);
            }
            if($plan->begin < $project->begin and empty(dao::$errors["begin[$index]"]))
            {
                dao::$errors["begin[$index]"][] = sprintf($this->lang->programplan->errorBegin, $project->begin);
            }
            if(!helper::isZeroDate($plan->end) and $plan->end > $project->end and empty(dao::$errors["end[$index]"]))
            {
                dao::$errors["end[$index]"][] = sprintf($this->lang->programplan->errorEnd, $project->end);
            }

            if(helper::isZeroDate($plan->begin)) $plan->begin = '';
            if(helper::isZeroDate($plan->end))   $plan->end   = '';
            if($setCode and empty($plan->code))
            {
                dao::$errors["code[$index]"][] = sprintf($this->lang->error->notempty, $plan->type == 'stage' ? $this->lang->execution->code : $this->lang->code);
            }
            foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
            {
                $field = trim($field);

                if($field == 'begin' || $field == 'end') continue;

                if($field and empty($plan->$field))
                {
                    dao::$errors[$field . "[$index]"][] = sprintf($this->lang->error->notempty, $this->lang->programplan->$field);
                }
            }

            if($setPercent)
            {
                $plan->percent = (float)$plan->percent;
                $totalPercent += $plan->percent;
            }

            if($plan->milestone) $milestone = 1;
        }

        if($setPercent and $totalPercent > 100) dao::$errors['percent'] = $this->lang->programplan->error->percentOver;
        if(dao::isError()) return false;

        $this->loadModel('action');
        $this->loadModel('user');
        $this->loadModel('execution');
        $this->app->loadLang('doc');
        $account = $this->app->user->account;
        $now     = helper::now();

        if(!isset($orders)) $orders = array();
        asort($orders);
        if(count($orders) < count($datas))
        {
            $orderIndex = empty($orders) ? 0 : count($orders);
            $lastID     = $this->dao->select('id')->from(TABLE_EXECUTION)->orderBy('id_desc')->fetch('id');
            for($i = $orderIndex; $i < count($datas); $i ++)
            {
                $lastID ++;
                $orders[$i] = $lastID * 5;
            }
        }

        $linkProducts = array();
        $linkBranches = array();
        $productList  = $this->loadModel('product')->getProducts($projectID);
        if($project->stageBy == 'product' and $project->model != 'research')
        {
            $linkProducts = array(0 => $productID);
            $linkBranches = array(0 => $productList[$productID]->branches);
        }
        else
        {
            $linkProducts = array_keys($productList);
            foreach($linkProducts as $index => $productID) $linkBranches[$index] = $productList[$productID]->branches;
        }
        $this->post->set('products', $linkProducts);
        $this->post->set('branch', $linkBranches);

        foreach($datas as $data)
        {
            /* Set planDuration and realDuration. */
            if($this->config->edition == 'max' or $this->config->edition == 'ipd')
            {
                $data->planDuration = $this->getDuration($data->begin, $data->end);
                if(isset($data->realBegan) && isset($data->realEnd)) $data->realDuration = $this->getDuration($data->realBegan, $data->realEnd);
            }

            $projectChanged = false;
            $data->days     = helper::diffDate($data->end, $data->begin) + 1;
            $data->order    = current($orders);

            next($orders);

            if($data->id)
            {
                $stageID = $data->id;
                unset($data->id, $data->type);

                $oldStage    = $this->getByID($stageID);
                $planChanged = ($oldStage->name != $data->name || $oldStage->milestone != $data->milestone || $oldStage->begin != $data->begin || $oldStage->end != $data->end);

                if($planChanged) $data->version = $oldStage->version + 1;
                $this->dao->update(TABLE_PROJECT)->data($data)
                    ->autoCheck()
                    ->batchCheck($this->config->programplan->edit->requiredFields, 'notempty')
                    ->checkIF(!empty($data->percent) and $setPercent, 'percent', 'float')
                    ->where('id')->eq($stageID)
                    ->exec();

                /* Add PM to stage teams and project teams. */
                if(!empty($data->PM))
                {
                    $team = $this->user->getTeamMemberPairs($stageID, 'execution');
                    if(isset($team[$data->PM])) continue;

                    $roles  = $this->user->getUserRoles($data->PM);
                    $member = new stdclass();
                    $member->root    = $stageID;
                    $member->account = $data->PM;
                    $member->role    = zget($roles, $data->PM, '');
                    $member->join    = $now;
                    $member->type    = 'execution';
                    $member->days    = $data->days;
                    $member->hours   = $this->config->execution->defaultWorkhours;
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                    $this->execution->addProjectMembers($data->project, array($data->PM => $member));
                }

                if($data->acl != 'open') $this->user->updateUserView((array)$stageID, 'sprint');

                /* Record version change information. */
                if($planChanged)
                {
                    $spec = new stdclass();
                    $spec->project   = $stageID;
                    $spec->version   = $data->version;
                    $spec->name      = $data->name;
                    $spec->milestone = $data->milestone;
                    $spec->begin     = $data->begin;
                    $spec->end       = $data->end;
                    $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();
                }

                $changes  = common::createChanges($oldStage, $data);
                $actionID = $this->action->create('execution', $stageID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            else
            {
                unset($data->id);
                $data->status        = 'wait';
                $data->stageBy       = $project->stageBy;
                $data->version       = 1;
                $data->parentVersion = $data->parent == 0 ? 0 : $this->dao->findByID($data->parent)->from(TABLE_PROJECT)->fetch('version');
                $data->team          = substr($data->name,0, 30);
                $data->openedBy      = $account;
                $data->openedDate    = $now;
                $data->openedVersion = $this->config->version;
                if(!isset($data->acl)) $data->acl = $this->dao->findByID($data->parent)->from(TABLE_PROJECT)->fetch('acl');
                $this->dao->insert(TABLE_PROJECT)->data($data)
                    ->autoCheck()
                    ->batchCheck($this->config->programplan->create->requiredFields, 'notempty')
                    ->checkIF(!empty($data->percent) and $setPercent, 'percent', 'float')
                    ->exec();

                if(!dao::isError())
                {
                    $stageID = $this->dao->lastInsertID();

                    /* Ipd project create default review points. */
                    if($project->model == 'ipd' && $this->config->edition == 'ipd' && !$parentID) $this->loadModel('review')->createDefaultPoint($projectID, $productID, $data->attribute);

                    if($data->type == 'kanban')
                    {
                        $execution = $this->execution->getByID($stageID);
                        $this->loadModel('kanban')->createRDKanban($execution);
                    }

                    if($data->acl != 'open') $this->user->updateUserView((array)$stageID, 'sprint');

                    /* Create doc lib. */
                    $lib = new stdclass();
                    $lib->project   = $projectID;
                    $lib->execution = $stageID;
                    $lib->name      = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doclib->main['execution']);
                    $lib->type      = 'execution';
                    $lib->main      = '1';
                    $lib->acl       = 'default';
                    $lib->addedBy   = $this->app->user->account;
                    $lib->addedDate = helper::now();
                    $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

                    /* Add creators and PM to stage teams and project teams. */
                    $teamMembers = array();
                    $members     = array($this->app->user->account, $data->PM);
                    $roles       = $this->user->getUserRoles(array_values($members));
                    $team        = $this->user->getTeamMemberPairs($stageID, 'execution');
                    foreach($members as $teamMember)
                    {
                        if(empty($teamMember) or isset($team[$teamMember]) or isset($teamMembers[$teamMember])) continue;

                        $member = new stdclass();
                        $member->root    = $stageID;
                        $member->account = $teamMember;
                        $member->role    = zget($roles, $teamMember, '');
                        $member->join    = $now;
                        $member->type    = 'execution';
                        $member->days    = $data->days;
                        $member->hours   = $this->config->execution->defaultWorkhours;
                        $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                        $teamMembers[$teamMember] = $member;
                    }
                    $this->execution->addProjectMembers($data->project, $teamMembers);

                    $this->setTreePath($stageID);
                    if($data->acl != 'open') $this->user->updateUserView((array)$stageID, 'sprint');

                    /* Record version change information. */
                    $spec = new stdclass();
                    $spec->project   = $stageID;
                    $spec->version   = $data->version;
                    $spec->name      = $data->name;
                    $spec->milestone = $data->milestone;
                    $spec->begin     = $data->begin;
                    $spec->end       = $data->end;
                    $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();

                    if($project->hasProduct)
                    {
                        $this->action->create('execution', $stageID, 'opened', '', join(',', $_POST['products']));
                    }
                    else
                    {
                        $this->action->create('execution', $stageID, 'opened');
                    }

                    $this->computeProgress($stageID, 'create');
                }
            }
            $this->execution->updateProducts($stageID, array());

            /* If child plans has milestone, update parent plan set milestone eq 0 . */
            if($parentID and $milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($parentID)->exec();

            if(dao::isError()) return print(js::error(dao::getError()));
        }
        return true;
    }

    /**
     * Set stage tree path.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function setTreePath($planID)
    {
        $stage  = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
        $parent = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($stage->parent)->fetch();

        $this->loadModel('execution');
        if($parent->type == 'project')
        {
            $path['path']  =  ",{$parent->id},{$stage->id},";
            $path['grade'] = 1;
        }
        elseif(isset($this->lang->execution->typeList[$parent->type]))
        {
            $path['path']  = $parent->path . "{$stage->id},";
            $path['grade'] = $parent->grade + 1;
        }

        $children = $this->execution->getChildExecutions($planID);
        $this->dao->update(TABLE_PROJECT)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($stage->id)->exec();

        if(!empty($children))
        {
            foreach($children as $id => $child) $this->setTreePath($id);
        }

        return !dao::isError();
    }
}
