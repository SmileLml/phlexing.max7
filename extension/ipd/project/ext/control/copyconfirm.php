<?php
class project extends control
{
    /**
     * Copy project confirm.
     *
     * @param  int    $copyProjectID
     * @param  string $products
     * @param  string $copyFrom
     * @access public
     * @return void
     */
    public function copyConfirm($copyProjectID, $products = '', $copyFrom = '')
    {
        dao::$filterTpl = 'never';
        $this->loadModel('stage');
        $this->loadModel('programplan');
        $project = $this->project->getByID($copyProjectID);

        if($_POST || !$project->multiple)
        {
            if(!$project->multiple) $_POST = json_decode($this->cookie->copyData, true);
            /* Get the data from the post. */
            $postData = fixer::input('post')->get();

            $projectData = !empty($postData->project) ? $postData->project : array();
            $model       = $project->model;
            $projectData['products']  = isset($postData->products) ? $postData->products : array();
            $projectData['plans']     = isset($postData->plans) ? $postData->plans : array();
            $projectData['whitelist'] = isset($postData->whitelist) ? $postData->whitelist : array();
            $projectData['branch']    = isset($postData->branch) ? $postData->branch : array();
            $projectData['stageBy']   = isset($project->stageBy) ? $project->stageBy : 'project';
            unset($postData->project);
            unset($postData->products);
            unset($postData->plans);
            unset($postData->whitelist);
            $execution = $postData;
            $_POST     = $projectData;
            if(in_array($model, array('waterfall', 'waterfallplus', 'ipd')))
            {
                $copiedProjectProducts = $this->loadModel('product')->getProductPairsByProject($project->id);
                $products = $this->product->getByIdList(array_keys($copiedProjectProducts));
                $showProduct = !(count($copiedProjectProducts) <= 1 and count($products) <= 1);
                if(isset($execution->names))
                {
                    foreach($execution->names as $productID => $name)
                    {
                        if(!$this->project->checkNameUnique($name)) dao::$errors['message'][] = ($showProduct ? $products[$productID]->name . ': ' : '') . $this->lang->programplan->error->sameName;
                    }
                }
            }
            else
            {
                if(isset($execution->names) && !$this->project->checkNameUnique($execution->names)) dao::$errors['message'][] = $this->lang->programplan->error->sameName;
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $projectID = $this->project->saveCopyProject($copyProjectID, $model, $execution);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $project = $this->project->fetchByID($projectID);
            if($project->isTpl) return $this->send(array('result' => 'success', 'locate' => helper::createLink('project', 'execution', "status=undone&projectID=$projectID")));
            if(!$project->multiple) return $this->send(array('result' => 'success', 'locate' => inlink('create', "model=$model&programID={$project->parent}&copyProjectID={$project->id}&extra=showTips=1,project={$projectID}")));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('create', "model=$model&programID={$project->parent}&copyProjectID={$project->id}&extra=showTips=1,project={$projectID}")));
        }

        $this->loadModel('execution');
        $this->loadModel('user');


        /* Get expand execution list. */
        $copyExecutions   = $this->execution->fetchExecutionList($project->id, 'all', 0, 0, 'grade_desc,order_asc');
        $mergedExecutions = array();
        foreach($copyExecutions as $executionID => $execution)
        {
            if(isset($copyExecutions[$execution->parent]))
            {
                $copyExecutions[$execution->parent]->children[$executionID] = $execution;
            }
            else
            {
                $mergedExecutions[$executionID] = $execution;
            }
        }
        $executionIdList = $this->projectZen->expandExecutionIdList($mergedExecutions);

        if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            $oldProductPairs   = $this->loadModel('product')->getProductPairsByProject($project->id);
            $products          = $this->product->getByIdList(array_keys($oldProductPairs));

            $productExecutions = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->where('product')->in(array_keys($oldProductPairs))->andWhere('project')->in(array_keys($copyExecutions))->fetchGroup('product', 'project');

            $productIdList = array();
            foreach($products as $productID => $product)
            {
                $productID = (int)$productID;
                if(empty($productID)) continue;

                $productIdList[] = $productID;
            }

            $executionGroupIdList = array();
            foreach($productExecutions as $productID => $executions)
            {
                foreach($executionIdList as $stageID)
                {
                    if(!isset($executions[$stageID])) continue;

                    $executionGroupIdList[$productID][$stageID] = $stageID;
                    unset($executionIdList[$stageID]);
                }
            }

            $executionIdList = array();
            $productPairs    = array();
            if($productIdList)
            {
                $products = $this->loadModel('product')->getByIdList($productIdList);
                foreach($products as $product)
                {
                    $productPairs[$product->id] = $product->name;
                    $executionIdList[$product->id] = isset($executionGroupIdList[$product->id]) ? $executionGroupIdList[$product->id] : ($executionGroupIdList ? reset($executionGroupIdList) : array());
                }
            }

            $this->view->oldProductPairs = $oldProductPairs;
            $this->view->productPairs    = $productPairs;
        }

        $copyProject = json_decode($this->cookie->copyData);
        if(!empty($copyProject->isTpl))
        {
            $this->lang->project->homeMenu->browse['alias']    = str_replace(',copyconfirm', '', $this->lang->project->homeMenu->browse['alias']);
            $this->lang->project->homeMenu->template['alias'] .= ',copyconfirm';
            $this->lang->project->copyProjectConfirm   = str_replace($this->lang->projectCommon, $this->lang->project->template, $this->lang->project->copyProjectConfirm);
            $this->lang->project->executionInfoConfirm = $this->lang->project->copyProjectConfirm;
        }

        $this->view->title           = $this->lang->project->executionInfoConfirm;
        $this->view->project         = $project;
        $this->view->executions      = $copyExecutions;
        $this->view->executionIdList = $executionIdList;
        $this->view->users           = $this->user->getPairs('noclosed|nodeleted');
        $this->view->copyProjectID   = $copyProjectID;
        $this->view->copyProject     = $copyProject;
        $this->view->copyFrom        = $copyFrom;

        $this->display();
    }
}
