<?php
class excelCaselib extends caselibModel
{
    public function setListValue($libID)
    {
        $lib       = $this->getById($libID);
        $modules   = $this->loadModel('tree')->getOptionMenu($libID, $viewType = 'caselib', $startModuleID = 0);
        $typeList  = $this->lang->testcase->typeList;
        $priList   = $this->lang->testcase->priList;
        $stageList = $this->lang->testcase->stageList;

        unset($typeList['']);
        unset($stageList['']);
        foreach($modules as $id => $module)  $modules[$id] .= "(#$id)";

        if($this->config->edition != 'open') $this->loadModel('workflowfield')->setFlowListValue('testcase');

        $this->post->set('moduleList', array_values($modules));
        $this->post->set('typeList',   join(',', $typeList));
        $this->post->set('priList',    join(',', $priList));
        $this->post->set('stageList',  join(',', $stageList));
        $this->post->set('listStyle',  $this->config->testcase->export->listFields);
        $this->post->set('extraNum',   0);
        $this->post->set('libname',    $lib->name);
    }
}
