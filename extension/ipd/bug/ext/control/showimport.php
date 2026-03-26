<?php
/**
 * The model file of excel module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     excel
 * @link        https://www.zentao.net
 */
helper::importControl('bug');
class mybug extends bug
{
    /**
     * Show import.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $pagerID
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = 0, $pagerID = 1, $insert = '')
    {
        $this->loadModel('transfer');

        $this->session->set('bugTransferParams', array('productID' => $productID, 'branch' => $branch));
        $this->qa->setMenu($productID, $branch);

        $product = $this->product->getById($productID);

        if($product->type == 'normal') $this->config->bug->templateFields = str_ireplace('branch,', '', $this->config->bug->templateFields);
        $locate = inlink('showImport', "productID=$productID&branch=$branch&pagerID=" . ($this->post->pagerID + 1) . "&insert=" . zget($_POST, 'insert', ''));

        if($_POST)
        {
            $this->loadModel('action');
            $message = $this->lang->saveSuccess;
            if($this->post->insert)
            {
                $this->config->bug->form->batchCreate['feedbackBy']  = array('required' => false, 'type' => 'string', 'default' => '');
                $this->config->bug->form->batchCreate['notifyEmail'] = array('required' => false, 'type' => 'string', 'default' => '');
                $this->config->bug->form->batchCreate['injection']   = array('required' => false, 'type' => 'int', 'default' => 0);
                $this->config->bug->form->batchCreate['identify']    = array('required' => false, 'type' => 'int', 'default' => 0);

                $bugs = $this->buildBugsForBatchCreate($productID, (string)$branch);
                $bugs = $this->checkBugsForBatchCreate($bugs, $productID);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                foreach($bugs as $bug)
                {
                    $bugID = $this->bug->create($bug);
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                    $message = $this->executeHooks($bugID);
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
            }
            else
            {
                $bugs = $this->buildBugsForImport();
                $this->checkBugsForBatchUpdate($bugs);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                foreach($bugs as $bug)
                {
                    unset($bug->product);
                    $this->bug->update($bug, 'Edited');
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                    $message = $this->executeHooks($bug->id);
                    if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($this->post->isEndPage) $locate = inlink('browse', "productID=$productID&branch=$branch");
            return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $locate));
        }

        $this->config->bug->dtable->fieldList['branch']['required']      = true;
        $this->config->bug->dtable->fieldList['story']['dataSource']     = array('module' => 'story',   'method' =>'getProductStoryPairs', 'params' => ['productIdList' => (int)$productID, 'branch' => 'all', 'moduleIdList' => '', 'status' => 'all', 'order' => 'id_desc', 'limit' => 0, 'type' => 'story', 'storyType' => 'story']);
        $this->config->bug->dtable->fieldList['project']['dataSource']   = array('module' => 'product', 'method' => 'getProjectPairsByProduct', 'params' => array('productID' => $productID, 'branch' => (string)$branch));
        $this->config->bug->dtable->fieldList['execution']['dataSource'] = array('module' => 'product', 'method' => 'getExecutionPairsByProduct', 'params' => array('productID' => $productID, 'branch' => (string)$branch));
        $this->config->bug->dtable->fieldList['branch']['dataSource']['params']['productID'] = $productID;
        if($this->config->edition == 'max')
        {
            $this->config->bug->dtable->fieldList['injection']['dataSource']['params']['productID'] = $productID;
            $this->config->bug->dtable->fieldList['identify']['dataSource']['params']['productID']  = $productID;
        }
        $bugData = $this->transfer->readExcel('bug', $pagerID, $insert);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'load' => array('alert' => dao::getError())));

        /* 设置模块。 */
        /* Set modules. */
        $modules       = array();
        $stories       = array();
        $branches      = $this->loadModel('branch')->getPairs($productID, 'active');
        $branchModules = $this->loadModel('tree')->getOptionMenu($productID, 'case', 0, empty($branches) ? array(0) : array_keys($branches));
        foreach($branchModules as $branchID => $moduleList)
        {
            $modules[$branchID] = array();
            foreach($moduleList as $moduleID => $moduleName)
            {
                $stories[$moduleID] = array();
                $modules[$branchID][] = array('value' => $moduleID, 'text' => $moduleName);
            }
        }

        /* Set stories. */
        $storyList = $this->loadModel('story')->getProductStories($productID, 'all',  array(), 'all', 'story', 'id_desc', false);
        foreach($storyList as $story)
        {
            $stories[0][] = array('value' => $story->id, 'text' => $story->title);
            if($story->module) $stories[$story->module][] = array('value' => $story->id, 'text' => $story->title);
        }

        $this->view->title     = $this->lang->bug->common . $this->lang->hyphen . $this->lang->bug->showImport;
        $this->view->datas     = $bugData;
        $this->view->productID = $productID;
        $this->view->branch    = $branch;
        $this->view->backLink  = inlink('browse', "productID=$productID&branch=$branch");
        $this->view->modules   = $modules;
        $this->view->stories   = $stories;

        $this->display();
    }
}
