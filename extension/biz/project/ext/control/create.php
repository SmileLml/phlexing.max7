<?php
class myProject extends project
{
    /**
     * Create project.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @param  string $pageType  base|copy
     * @access public
     * @return void
     */
    public function create($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '', $pageType = 'base')
    {
        if($this->config->edition != 'open')                      $this->createProjectForBiz($model, $programID, $copyProjectID, $extra);
        if(in_array($this->config->edition, array('max', 'ipd'))) $this->createProjectForMax($model, $programID, $copyProjectID, $extra, $pageType);
        if($this->config->edition == 'ipd')                       $this->createProjectForIpd($model, $programID, $copyProjectID, $extra, $pageType);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        if($pageType == 'copy') $extra .= ',from=global';

        $this->view->copyType = isset($output['copyType']) ? $output['copyType'] : '';
        $this->view->copyFrom = isset($output['copyFrom']) ? $output['copyFrom'] : 'project';
        $this->view->pageType = $pageType;

        return parent::create($model, $programID, $copyProjectID, $extra);
    }

    /**
     * Create project in biz edition.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function createProjectForBiz($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '')
    {
        $this->view->charters = $this->loadModel('charter')->getPairs('launched', 'completionDoing,cancelDoing');

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $linkType  = 'plan';
        $charterID = isset($output['charter']) ? $output['charter'] : '';

        if($programID)
        {
            $program = $this->loadModel('program')->fetchByID($programID);
            if(!$charterID && !empty($program->charter)) $charterID = $program->charter;
        }

        if($charterID)
        {
            $charterGroups   = $this->charter->getGroupDataByID($charterID);
            $charterProducts = $this->loadModel('product')->getByIdList(array_keys($charterGroups));
            $charterBranches = $this->charter->getLinkedBranchGroups($charterID);

            /* 构造立项产品的分支和路标。 */
            foreach($charterProducts as $product)
            {
                $product->branches = isset($charterBranches[$product->id]) ? $charterBranches[$product->id] : array();
                $product->roadmaps = array();
            }

            /* 确定立项的规划方式，是路标还是计划。 */
            $charterInfo = $this->charter->fetchByID($charterID);
            $linkType    = $charterInfo->type;

            /* 构造立项的分支和路标或计划下拉。 */
            $productRoadmaps = array();
            $productPlans    = array();
            $productObjects  = array();
            $titleField      = $linkType == 'plan' ? 'title' : 'name';
            foreach($charterGroups as $productID => $objects)
            {
                foreach($objects as $object)
                {
                    if($charterProducts[$productID]->type == 'normal') $productObjects[$productID][$object->id] = $object->$titleField . ' [' . $object->begin . '~' . $object->end . ']';
                    else $productObjects[$productID][$object->branch][$object->id] = $object->$titleField . ' [' . $object->begin . '~' . $object->end . ']';
                }
            }

            if($linkType == 'plan')    $productPlans    = $productObjects;
            if($linkType == 'roadmap') $productRoadmaps = $productObjects;
        }

        $this->view->charter             = $charterID;
        $this->view->charterProductPairs = isset($charterProducts) ? array_column($charterProducts, 'name', 'id') : array();
        $this->view->charterProducts     = isset($charterProducts) ? $charterProducts : array();
        $this->view->productRoadmaps     = isset($productRoadmaps) ? $productRoadmaps : array();
        $this->view->charterPlans        = isset($productPlans)    ? $productPlans    : array();
        $this->view->branchPairs         = isset($charterBranches) ? $charterBranches : array();
        $this->view->hasProduct          = isset($output['hasProduct']) && $output['hasProduct'] !== ''? $output['hasProduct'] : null;
        $this->view->linkType            = $linkType;
    }

    /**
     * Create project in max edition.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @param  string $pageType      base|copy
     * @access public
     * @return void
     */
    public function createProjectForMax($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '', $pageType = 'base')
    {
        if($pageType == 'base')
        {
            $this->view->programListSet = $this->loadModel('program')->getParentPairs();
        }
        elseif($pageType == 'copy')
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);
            $copyType = isset($output['copyType']) ? $output['copyType'] : '';
            $copyFrom = isset($output['copyFrom']) ? $output['copyFrom'] : '';

            if($_POST)
            {
                $this->project->checkCreate($copyProjectID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                if($copyType == 'all' || $copyType == 'previous') helper::setcookie('copyData', json_encode($_POST));
                $products = $this->post->products ? implode(',', $this->post->products) : '';
                return $this->send(array('result' => 'success', 'load' => $this->createLink('project', 'copyConfirm', "copyprojectID={$copyProjectID}&products={$products}&copyFrom={$copyFrom}")));
            }

            $copyData    = $copyType == 'previous' ? json_decode($this->cookie->copyData, true) : '';
            $copyProject = $this->project->getByID($copyProjectID);
            $programs    = $this->loadModel('program')->getParentPairs();
            if(!empty($copyData))
            {
                $linkedBranches = array();
                foreach($copyData as $key => $value)
                {
                    if($key == 'uid') continue;
                    if($key == 'products')
                    {
                        $linkedProducts = $this->loadModel('product')->getByIdList($value);
                        foreach($value as $rowID => $productID)
                        {
                            if(!isset($linkedProducts[$productID])) continue;

                            $linkedProducts[$productID]->plans = array();
                            foreach($copyData['plans'][$productID] as $planID)
                            {
                                if(!$planID) continue;
                                $linkedProducts[$productID]->plans[$planID] = $planID;
                            }
                        }
                        $this->view->linkedProducts = $linkedProducts;
                    }
                    elseif($key == 'branch')
                    {
                        foreach($value as $rowID => $branches)
                        {
                            $branchPairs = array();
                            foreach($branches as $branch) $branchPairs[$branch] = $branch;
                            $linkedBranches[$copyData['products'][$rowID]] = $branchPairs;
                        }
                        $this->view->linkedBranches = $linkedBranches;
                    }
                    elseif($key == 'longTime' && $value == 'on')
                    {
                        $copyProject->end = LONG_TIME;
                    }
                    else
                    {
                        $copyProject->{$key} = $value;
                    }
                }
            }

            $extra .= ',from=global';
            $this->view->copyType           = $copyType;
            $this->view->programListSet     = $programs;
            $this->view->copyProjectsLatest = array_slice($this->project->getPairsByModel($model), 0, 10, true);
            $this->view->copyProject        = $copyProject;
        }

        if(empty($_POST))
        {
            $extra = str_replace(array(',', ' '), array('&', ''), $extra);
            parse_str($extra, $output);

            if(!empty($output['workflowGroup']))
            {
                $projectType   = isset($output['hasProduct']) && $output['hasProduct'] === '1' ? 'product' : 'project';
                $workflowGroup = $output['workflowGroup'];
            }
            else
            {
                if($copyProjectID) $copyProject = $this->project->fetchByID($copyProjectID);
                $hasProduct     = isset($copyProject->hasProduct) ? $copyProject->hasProduct : 1;
                $workflowGroups = $this->loadModel('workflowgroup')->getPairs('project', $model, $hasProduct, 'normal', '0');
                $workflowGroups = array_keys($workflowGroups);
                $workflowGroup  = !empty($workflowGroups) ? reset($workflowGroups) : 0;
                $projectType    = $hasProduct ? 'product' : 'project';

                if(!empty($copyProject->workflowGroup)) $workflowGroup = $copyProject->workflowGroup;
            }

            $this->view->deliverables = $this->project->getProjectDeliverable(0, 0, (int)$workflowGroup, $projectType, $model, 'whenCreated');
        }

        $this->view->pageType = $pageType;
    }

    /**
     * Create project in ipd edition.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @param  string $pageType      base|copy
     * @access public
     * @return void
     */
    public function createProjectForIpd($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '', $pageType = 'base')
    {
        $this->loadModel('roadmap');
        if($model == 'ipd')
        {
            $this->config->project->create->requiredFields .= ',category';
            $this->config->project->form->create['category']['required'] = true;
        }
    }
}
