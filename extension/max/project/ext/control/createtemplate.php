<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * 创建项目模板页面。
     * Create project template.
     *
     * @param  string $model
     * @param  int    $programID
     * @param  int    $copyProjectID
     * @param  string $extra
     * @access public
     * @return void
     * @param string $pageType
     */
    public function createTemplate($model = 'scrum', $programID = 0, $copyProjectID = 0, $extra = '', $pageType = 'base')
    {
        if($copyProjectID)
        {
            $project = $this->project->fetchByID($copyProjectID);
            $model   = $project->model;
        }
        $this->lang->project->create                = $this->lang->project->createTemplate;
        $this->lang->project->name                  = $this->lang->project->templateName;
        $this->lang->project->copyProject->nameTips = str_replace($this->lang->projectCommon, $this->lang->project->template, $this->lang->project->copyProject->nameTips);
        $this->view->copyProjectList                = $this->project->getPairs(false, 'nokanban,haspriv');
        $this->view->copyWorkflowGroup              = !empty($project) ? $this->dao->select('id,name,objectID')->from(TABLE_WORKFLOWGROUP)->where('id')->eq($project->workflowGroup)->fetch() : array();

        if($_POST && empty($_POST['copyProjectID']))
        {
            dao::$errors['copyProjectID'] = sprintf($this->lang->error->notempty, $this->lang->project->copyProjectID);
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        if(in_array($this->config->edition, array('max', 'ipd'))) $this->createProjectForMax($model, $programID, $copyProjectID, $extra, $pageType);
        parent::create($model, $programID, $copyProjectID, $extra);
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

            if($_POST)
            {
                $this->project->checkCreate($copyProjectID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                if($copyType == 'all' || $copyType == 'previous') helper::setcookie('copyData', json_encode($_POST));
                $products = $this->post->products ? implode(',', $this->post->products) : '';
                return $this->send(array('result' => 'success', 'load' => $this->createLink('project', 'copyConfirm', "copyprojectID={$copyProjectID}&products={$products}")));
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
                            if(empty($productID)) continue;
                            $linkedProducts[$productID]->plans = array();
                            if(empty($copyData['plans'][$productID])) continue;
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

        $this->view->pageType = $pageType;
    }
}
