<?php
helper::importControl('testcase');
class mytestcase extends testcase
{
    /**
     * showImport
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = '0', $pagerID = 1, $maxImport = 0, $insert = '')
    {
        $this->loadModel('transfer');
        $product = $this->loadModel('product')->getByID($productID);
        if($product->type != 'normal') $this->config->testcase->templateFields = str_replace('module,', 'branch,module,', $this->config->testcase->templateFields);

        $this->session->set('testcaseTransferParams', array('productID' => $productID, 'branch' => $branch));

        $product = $this->product->getById($productID);

        if($product->type == 'normal') $this->config->testcase->templateFields = str_ireplace('branch,', '', $this->config->testcase->templateFields);
        if($this->app->tab == 'project')
        {
            $this->loadModel('project')->setMenu($this->session->project);
        }
        else
        {
            $this->testcase->setMenu((int)$productID, $branch);
        }

        if($_POST)
        {
            $cases = $this->buildCasesForShowImport((int)$productID);
            $cases = $this->checkCasesForShowImport($cases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $cases = $this->importCases($cases);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $message = $this->lang->saveSuccess;
            foreach($cases as $case) $message = $this->executeHooks($case->id);

            $file    = $this->session->fileImport;
            $tmpPath = $this->loadModel('file')->getPathOfImportedFile();
            $tmpFile = $tmpPath . DS . md5(basename($file));
            return $this->responseAfterShowImport((int)$productID, $branch, $maxImport, $tmpFile, $message);
        }

        $datas = $this->transfer->readExcel('testcase', $pagerID);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'load' => array('alert' => dao::getError())));

        $datas->datas = $this->processStepsAndExpectsForBatchEdit($datas->datas);

        /* 设置模块。 */
        /* Set modules. */
        $modules       = array();
        $branches      = $this->loadModel('branch')->getPairs($productID, 'active');
        $branchModules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, empty($branches) ? array(0) : array_keys($branches));
        foreach($branchModules as $branchID => $moduleList)
        {
            $modules[$branchID] = array();
            foreach($moduleList as $moduleID => $moduleName) $modules[$branchID][] = array('text' => $moduleName, 'value' => $moduleID);
        }

        /* Group by story module for cascade. */
        $storyList = array();
        $stories = $this->loadModel('story')->getProductStories($productID, array_keys($branches), 0, 'all', 'story', 'id_desc', false, false);
        foreach($stories as $story) $storyList[$story->module][] = array('text' => $story->title, 'value' => $story->id);

        $this->view->title         = $this->lang->testcase->common . $this->lang->hyphen . $this->lang->testcase->showImport;
        $this->view->productID     = $productID;
        $this->view->product       = $product;
        $this->view->branch        = $branch;
        $this->view->branches      = $branches;
        $this->view->cases         = $this->testcase->getByProduct($productID);
        $this->view->caseData      = $datas->datas;
        $this->view->backLink      = inlink('browse', "productID=$productID");
        $this->view->modules       = $modules;
        $this->view->allCount      = $datas->allCount;
        $this->view->stories       = $storyList;
        $this->view->isEndPage     = $datas->isEndPage;
        $this->view->pagerID       = $datas->pagerID;
        $this->view->dataInsert    = $datas->dataInsert;
        $this->view->suhosinInfo   = $datas->suhosinInfo;
        $this->view->maxImport     = $maxImport;
        $this->view->allPager      = $datas->allPager;
        $this->display();
    }
}
