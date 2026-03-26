<?php
helper::importControl('caselib');
class mycaselib extends caselib
{
    public function showImport($libID, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        $this->loadModel('testcase');
        $this->loadModel('transfer');

        if($_POST)
        {
            $this->caselib->createFromImport($libID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $locate = inlink('showImport', "libID=$libID&pagerID=" . ($this->post->pagerID + 1) . "&maxImport=$maxImport&insert=" . zget($_POST, 'insert', ''));
            if($this->post->isEndPage) $locate = inlink('browse', "libID=$libID");
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => $locate));
        }

        $libraries = $this->caselib->getLibraries();
        if(empty($libraries)) $this->locate(inlink('createLib'));

        $this->caselib->setLibMenu($libraries, $libID);

        $this->config->testcase->templateFields = 'module,title,precondition,keywords,pri,type,stage,steps,stepDesc,stepExpect';
        $this->session->set('testcaseTransferParams', array('libID' => $libID));
        $this->config->testcase->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$libID&caselib');

        $datas = $this->transfer->readExcel('testcase', $pagerID, $insert);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'load' => array('alert' => dao::getError())));

        $index    = 1;
        foreach($datas->datas as $data)
        {
            if(isset($data->id))  $data->idIndex = $data->id;
            if(!isset($data->id)) $data->idIndex = $index ++;
            $data->lib     = $libID;
            $data->steps   = $data->stepDesc;
            $data->expects = $data->stepExpect;
        }

        $this->view->title     = $this->lang->testcase->common . $this->lang->hyphen . $this->lang->testcase->showImport;
        $this->view->datas     = $datas;
        $this->view->libID     = $libID;
        $this->view->backLink  = inlink('browse', "libID=$libID");
        $this->display();
    }
}
