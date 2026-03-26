<?php
/**
 * Create from import
 *
 * @param  int    $productID
 * @access public
 * @return void
 */
public function createFromImport($productID, $branch = 0)
{
    $this->loadModel('action');
    $this->loadModel('story');
    $this->loadModel('file');
    $now    = helper::now();
    $branch = (int)$branch;
    $data   = fixer::input('post')->get();

    if(!empty($_POST['id']))
    {
        $oldSteps = $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
            ->where('t1.id')->in(($_POST['id']))
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t1.version=t2.version')
            ->orderBy('t2.id')
            ->fetchGroup('case');
        $oldCases = $this->dao->select('*')->from(TABLE_CASE)->where('id')->in($_POST['id'])->fetchAll('id');
    }

    $cases             = array();
    $line              = 1;
    $extendFields      = $this->getFlowExtendFields();
    $notEmptyRule      = $this->loadModel('workflowrule')->getByTypeAndRule('system', 'notempty');
    $storyVersionPairs = $this->story->getVersions($data->story);

    foreach($extendFields as $extendField)
    {
        if(strpos(",$extendField->rules,", ",$notEmptyRule->id,") !== false)
        {
            $this->config->testcase->create->requiredFields .= ',' . $extendField->field;
        }
    }

    foreach($data->product as $key => $product)
    {
        $caseData = new stdclass();

        $caseData->product      = $product;
        $caseData->branch       = isset($data->branch[$key]) ? $data->branch[$key] : $branch;
        $caseData->module       = $data->module[$key];
        $caseData->story        = (int)$data->story[$key];
        $caseData->title        = $data->title[$key];
        $caseData->pri          = (int)$data->pri[$key];
        $caseData->type         = $data->type[$key];
        $caseData->stage        = isset($data->stage[$key]) ? join(',', $data->stage[$key]) : '';
        $caseData->keywords     = $data->keywords[$key];
        $caseData->frequency    = 1;
        $caseData->precondition = $data->precondition[$key];

        foreach($extendFields as $extendField)
        {
            $dataArray = isset($_POST[$extendField->field]) ? $_POST[$extendField->field] : array();
            $caseData->{$extendField->field} = $dataArray[$key];
            if(is_array($caseData->{$extendField->field})) $caseData->{$extendField->field} = join(',', $caseData->{$extendField->field});

            $caseData->{$extendField->field} = htmlSpecialString($caseData->{$extendField->field});
        }

        if(isset($this->config->testcase->create->requiredFields))
        {
            $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
            foreach($requiredFields as $requiredField)
            {
                $requiredField = trim($requiredField);
                if(!isset($caseData->$requiredField)) continue;
                if(empty($caseData->$requiredField)) dao::$errors[] = sprintf($this->lang->testcase->noRequire, $line, $this->lang->testcase->$requiredField);
            }
        }

        $cases[$key] = $caseData;
        $line++;
    }
    if(dao::isError()) die(js::error(dao::getError()));

    $forceNotReview = $this->forceNotReview();
    foreach($cases as $key => $caseData)
    {
        $caseID = 0;
        if(!empty($_POST['id'][$key]) and empty($_POST['insert']))
        {
            $caseID = $data->id[$key];
            if(!isset($oldCases[$caseID])) $caseID = 0;
        }

        if($caseID)
        {
            $stepChanged = false;
            $steps       = array();
            $oldStep     = isset($oldSteps[$caseID]) ? $oldSteps[$caseID] : array();
            $oldCase     = $oldCases[$caseID];

            /* Remove the empty setps in post. */
            $steps = array();
            if(isset($_POST['desc'][$key]))
            {
                foreach($data->desc[$key] as $id => $desc)
                {
                    $desc = trim($desc);
                    if(empty($desc)) continue;
                    $step = new stdclass();
                    $step->type   = $data->stepType[$key][$id];
                    $step->desc    = htmlspecialchars($desc);
                    $step->expect  = htmlspecialchars(trim($data->expect[$key][$id]));

                    $steps[] = $step;
                }
            }

            /* If step count changed, case changed. */
            if((!$oldStep != !$steps) or (count($oldStep) != count($steps)))
            {
                $stepChanged = true;
            }
            else
            {
                /* Compare every step. */
                foreach($oldStep as $id => $oldStep)
                {
                    if(trim($oldStep->desc) != trim($steps[$id]->desc) or trim($oldStep->expect) != $steps[$id]->expect)
                    {
                        $stepChanged = true;
                        break;
                    }
                }
            }

            $version           = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
            $caseData->version = $version;
            $changes           = common::createChanges($oldCase, $caseData);
            if($caseData->story != $oldCase->story) $caseData->storyVersion = zget($storyVersionPairs, $caseData->story, 1);
            if(!$changes and !$stepChanged) continue;

            if($changes or $stepChanged)
            {
                $caseData->lastEditedBy   = $this->app->user->account;
                $caseData->lastEditedDate = $now;
                if($stepChanged and !$forceNotReview) $caseData->status = 'wait';
                $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->checkFlow()->exec();
                if(!dao::isError())
                {
                    if($stepChanged)
                    {
                        $parentStepID = 0;
                        foreach($steps as $id => $step)
                        {
                            $step = (array)$step;
                            if(empty($step['desc'])) continue;
                            $stepData = new stdclass();
                            $stepData->type    = ($step['type'] == 'item' and $parentStepID == 0) ? 'step' : $step['type'];
                            $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                            $stepData->case    = $caseID;
                            $stepData->version = $version;
                            $stepData->desc    = htmlspecialchars($step['desc']);
                            $stepData->expect  = htmlspecialchars($step['expect']);
                            $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                            if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                            if($stepData->type == 'step')  $parentStepID = 0;
                        }
                    }
                    $oldCase->steps  = $this->joinStep($oldStep);
                    $caseData->steps = $this->joinStep($steps);
                    $changes  = common::createChanges($oldCase, $caseData);
                    $actionID = $this->action->create('case', $caseID, 'Edited');
                    $this->updateCase2Project($oldCase, $caseData, $caseID);
                    $this->action->logHistory($actionID, $changes);
                }
            }
        }
        else
        {
            $caseData->version    = 1;
            $caseData->openedBy   = $this->app->user->account;
            $caseData->openedDate = $now;
            $caseData->branch     = isset($data->branch[$key]) ? $data->branch[$key] : $branch;
            if($caseData->story) $caseData->storyVersion = zget($storyVersionPairs, $caseData->story, 1);
            $caseData->status = !$forceNotReview ? 'wait' : 'normal';
            $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->checkFlow()->exec();

            if(!dao::isError())
            {
                $caseID       = $this->dao->lastInsertID();
                $this->dao->update(TABLE_CASE)->set('sort')->eq($caseID)->where('id')->eq($caseID)->exec();
                $parentStepID = 0;
                foreach($data->desc[$key] as $id => $desc)
                {
                    $desc = trim($desc);
                    if(empty($desc)) continue;
                    $stepData = new stdclass();
                    $stepData->type    = ($data->stepType[$key][$id] == 'item' and $parentStepID == 0) ? 'step' : $data->stepType[$key][$id];
                    $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                    $stepData->case    = $caseID;
                    $stepData->version = 1;
                    $stepData->desc    = htmlspecialchars($desc);
                    $stepData->expect  = htmlspecialchars($data->expect[$key][$id]);
                    $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();
                    if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                    if($stepData->type == 'step')  $parentStepID = 0;
                }

                $this->action->create('case', $caseID, 'Opened');
                $this->syncCase2Project($caseData, $caseID);
            }
        }
    }

    if($data->isEndPage)
    {
        unlink($this->session->importFile);
        unset($_SESSION['importFile']);
    }
}
