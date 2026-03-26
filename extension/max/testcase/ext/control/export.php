<?php
helper::importControl('testcase');
class mytestcase extends testcase
{
    /**
     * 导出用例。
     * export cases.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $taskID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $taskID = 0, $browseType = '')
    {
        $product     = $this->loadModel('product')->getByID($productID);
        $productName = !empty($product->name) ? $product->name : '';

        if($_POST)
        {
            $this->loadModel('transfer');

            $this->session->set('testcaseTransferParams', array('productID'=> $productID, 'orderBy' => $orderBy, 'taskID' => $taskID));

            $this->config->testcase->dtable->fieldList['branch']['dataSource']['params'] = ['productID' => (int)$productID, 'params' => 'active'];
            if(isset($this->config->testcase->dtable->fieldList['story']['dataSource']['params']['productIdList'])) $this->config->testcase->dtable->fieldList['story']['dataSource']['params']['productIdList'] = (int)$productID;
            if(isset($this->config->testcase->dtable->fieldList['module']['dataSource']['params']['productID'])) $this->config->testcase->dtable->fieldList['module']['dataSource']['params']['productID'] = (int)$productID;
            $this->transfer->export('testcase');

            $this->updateRows($_POST['rows'], $productName, $taskID);

            $this->fetch('file', 'export2' . $_POST['fileType'], $_POST);
        }

        $fileName    = $this->lang->testcase->common;
        $browseType  = isset($this->lang->testcase->featureBar['browse'][$browseType]) ? $this->lang->testcase->featureBar['browse'][$browseType] : '';

        if($product->type == 'normal') $this->config->testcase->exportFields = str_replace('branch,', '', $this->config->testcase->exportFields);
        if($product->shadow and $this->app->tab == 'project') $this->config->testcase->exportFields = str_replace('product,', '', $this->config->testcase->exportFields);
        if($taskID) $this->config->testcase->exportFields = str_replace('pri,', 'pri,assignedTo,', $this->config->testcase->exportFields);

        $this->view->fileName        = $productName . $this->lang->dash . $browseType . $fileName;
        $this->view->allExportFields = $this->config->testcase->exportFields;
        $this->view->customExport    = true;

        $this->display();
    }

    /**
     * Update rows for other field .
     *
     * @param  array  $rows
     * @param  string $productName
     * @param  int    $taskID
     * @access public
     * @return void
     */
    public function updateRows($rows = array(), $productName = '', $taskID = 0)
    {
        $caseIDList = array();
        foreach($rows as $row) $caseIDList[] = isset($row->case) ? $row->case : $row->id;

        $caseBugs    = $this->dao->select('COUNT(1) AS count, `case`')->from(TABLE_BUG)->where('`case`')->in($caseIDList)->andWhere('deleted')->eq(0)->groupBy('`case`')->fetchPairs('case', 'count');
        $resultCount = $this->dao->select('COUNT(1) AS count, `case`')->from(TABLE_TESTRESULT)->where('`case`')->in($caseIDList)->groupBy('`case`')->fetchPairs('case', 'count');

        $stmt = $this->dao->select('t1.*')->from(TABLE_TESTRESULT)->alias('t1')
            ->leftJoin(TABLE_TESTRUN)->alias('t2')->on('t1.run=t2.id')
            ->where('t1.`case`')->in($caseIDList)
            ->beginIF($taskID)->andWhere('t2.task')->eq($taskID)->fi()
            ->orderBy('id_desc')
            ->query();

        $results = array();
        while($result = $stmt->fetch())
        {
            if(!isset($results[$result->case])) $results[$result->case] = unserialize($result->stepResults);
        }

        $steps    = $this->testcase->getRelatedSteps($caseIDList);
        $products = $this->loadModel('product')->getPairs();
        foreach($rows as $row)
        {
            $caseLang = $this->lang->testcase;
            $caseID   = isset($row->case) ? $row->case : $row->id;
            $result   = isset($results[$caseID]) ? $results[$caseID] : array();

            $row->product       = zget($products, $row->product, $productName);
            $row->bugs          = isset($caseBugs[$caseID])  ? $caseBugs[$caseID]   : 0;
            $row->results       = isset($resultCount[$caseID]) ? $resultCount[$caseID]    : 0;
            $row->stepNumber    = 0;
            $row->stepDesc      = $row->stepExpect = $row->real = '';
            $row->lastRunResult = zget($this->lang->testcase->resultList, $row->lastRunResult);
            if(isset($row->branch) && isset($_POST['branchList'])) $row->branch = zget($this->post->branchList, $row->branch);
            if(isset($row->module) && isset($_POST['moduleList'])) $row->module = zget($this->post->moduleList, $row->module);
            if(isset($row->scene)  && isset($_POST['sceneList']))  $row->scene  = zget($this->post->sceneList,  $row->scene);

            $caseSteps = zget($steps, $caseID, array());
            $caseSteps = $this->testcase->processSteps($caseSteps);
            foreach($caseSteps as $step)
            {
                if($step->type == 'group') $steps[$caseID][] = $step;
                $sign = (in_array($this->post->fileType, array('html', 'xml'))) ? '<br />' : "\n";
                $row->stepDesc   .= $step->name . ". " . htmlspecialchars_decode($step->desc) . $sign;
                $row->stepExpect .= $step->name . ". " . htmlspecialchars_decode($step->expect) . $sign;
                $row->real       .= $step->name . ". " . (isset($result[$step->id]) ? $result[$step->id]['real'] : '') . $sign;
            }

            $row->stage = explode(',', $row->stage);
            foreach($row->stage as $key => $stage) $row->stage[$key] = isset($caseLang->stageList[$stage]) ? $caseLang->stageList[$stage] : $stage;
            $row->stage = join("\n", $row->stage);
        }

        $this->post->set('rows', $rows);
    }
}
