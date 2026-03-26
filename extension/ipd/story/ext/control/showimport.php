<?php
helper::importControl('story');
class mystory extends story
{
    /**
     * @param int $productID
     * @param string $branch
     * @param string $storyType
     * @param int $projectID
     * @param int $pagerID
     * @param int $maxImport
     * @param string $insert
     */
    public function showImport($productID, $branch = '0', $storyType = 'story', $projectID = 0, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        $this->loadModel('transfer');
        $this->loadModel('productplan');
        $this->loadModel('product')->setMenu($productID, $branch, $storyType);
        $this->session->set('storyTransferParams', array('productID' => $productID, 'branch' => $branch));

        $product     = $this->product->getById($productID);
        $forceReview = $this->story->checkForceReview();

        if($product->type == 'normal') $this->session->set('storyTemplateFields', str_ireplace('branch,', '', $this->session->storyTemplateFields));
        if($forceReview)
        {
            $this->session->set('storyTemplateFields', str_ireplace('needReview,', '', $this->session->storyTemplateFields));
            $this->config->story->create->requiredFields .= ',reviewer,';
        }

        $this->config->story->dtable->fieldList['product']['control']     = 'picker';
        $this->config->story->dtable->fieldList['product']['hidden']      = true;
        $this->config->story->dtable->fieldList['product']['value']       = $productID;
        $this->config->story->dtable->fieldList['reviewer']['control']    = 'multiple';
        $this->config->story->dtable->fieldList['reviewer']['dataSource'] = array('module' => 'story', 'method' => 'getProductReviewers', 'params' => array('productID' => (int)$productID));
        $this->config->story->dtable->fieldList['plan']['dataSource']['params'] = array('productIdList' => (int)$productID, 'branch' => 'all', 'param' => '', 'skipParent' => true);
        $this->config->story->dtable->fieldList['module']['dataSource']   = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => ['rootID' => (int)$productID, 'type' => 'story', 'startModule' => 0, 'branch' => 'all']);

        /* If or vision, unset plan field. */
        if($this->config->vision == 'or') $this->config->story->templateFields = str_replace(',plan,', ',', $this->config->story->templateFields);

        /* If edition is not ipd, and storyType is not story, unset level field. */
        if($this->config->edition != 'ipd' && $storyType != 'story') $this->config->story->templateFields = str_replace(',level,', ',', $this->config->story->templateFields);

        $this->session->set('storyType', $storyType);
        if($storyType != 'story') $this->story->replaceURLang($storyType);

        /* Append extend fields. */
        $extendFields = $this->loadModel('flow')->getExtendFields($storyType, 'showimport');
        $extendCols   = $this->loadModel('flow')->buildDtableCols($extendFields);
        $this->config->story->dtable->fieldList = array_merge($this->config->story->dtable->fieldList, $extendCols);
        $this->config->story->templateFields .= ',' . implode(',', array_keys($extendCols));
        $this->config->story->templateFields  = trim($this->config->story->templateFields, ',');

        if($_POST)
        {
            $message = $this->story->createFromImport($productID, $branch, $storyType, $projectID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = inlink('showImport', "productID=$productID&branch=$branch&type=$storyType&projectID=$projectID&pagerID=" . ($this->post->pagerID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''));
            if($this->post->isEndPage)
            {
                $locate = $projectID ? $this->createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=0&browseType=&param=0&storyType=story") : $this->createLink('product','browse', "productID=$productID&branch=$branch&browseType=unclosed&param=0&storyType=$storyType");
            }
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $locate));
        }

        if($product->type != 'normal') $this->config->story->create->requiredFields .= ',branch';
        $stories = $this->transfer->readExcel($storyType, $pagerID, $insert);
        if(empty($stories)) return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->story->noData)));

        if(!isset(reset($stories->datas)->id))
        {
            $index = 1;
            foreach($stories->datas as $data) $data->id = $index ++;
        }

        if($projectID)
        {
            $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
            if($project->type == 'project')
            {
                $model = in_array($project->model, $this->config->project->waterfallList) ? 'waterfall' : 'scrum';

                $this->loadModel('project')->setMenu($projectID);
                $this->app->rawModule = 'projectstory';
                $this->lang->navGroup->story = 'project';
                $this->lang->product->menu = $this->lang->{$model}->menu;
                $this->view->projectID = $projectID;
            }

            if(!$project->hasProduct) unset($stories->fields['branch']);
        }

        $branchModules = array();
        $branchPlans   = array();
        if($product->type == 'normal')
        {
            unset($stories->fields['branch']);
        }
        else
        {
            $branches = !empty($stories->fields['branch']['items']) && is_array($stories->fields['branch']['items']) ? array_keys($stories->fields['branch']['items']) : array();
            $this->loadModel('tree');
            $this->loadModel('productplan');
            foreach($branches as $branchID)
            {
                $modules = $this->tree->getOptionMenu($productID, 'story', 0, "0,{$branchID}");
                $plans   = $this->productplan->getPairs($productID, "0,{$branchID}");
                foreach($modules as $moduleID => $moduleName) $branchModules[$branchID][] = array('text' => $moduleName, 'value' => $moduleID);
                foreach($plans as $planID => $planName) $branchPlans[$branchID][] = array('text' => $planName, 'value' => $planID);
            }
        }

        $this->view->title         = $this->lang->story->common . $this->lang->hyphen . $this->lang->story->showImport;
        $this->view->datas         = $stories;
        $this->view->productID     = $productID;
        $this->view->branch        = $branch;
        $this->view->type          = $storyType;
        $this->view->forceReview   = $forceReview;
        $this->view->backLink      = $this->createLink('product', 'browse', "productID=$productID&branch=$branch&browseType=unclosed&param=0&storyType=$storyType");
        $this->view->hasBranch     = $product->type != 'normal';
        $this->view->branchModules = $branchModules;
        $this->view->branchPlans   = $branchPlans;
        $this->view->activeMenuID  = $storyType;

        $this->display();
    }
}
