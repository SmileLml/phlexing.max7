<?php
helper::importControl('caselib');
class mycaselib extends caselib
{
    /**
     * @param int $libID
     */
    public function exportTemplate($libID)
    {
        $this->loadModel('testcase');
        $this->loadModel('excel');
        $this->loadModel('transfer');

        if($_POST)
        {
            $this->session->set('caselibTransferParams', array('libID' => $libID));
            $this->config->caselib->templateFields = 'module,title,precondition,stepDesc,stepExpect,keywords,pri,type,stage';
            $this->config->testcase->dtable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$libID&caselib');

            $width['module']       = 20;
            $width['precondition'] = 30;
            $this->config->excel->cellHeight = 40;

            $this->post->set('width', $width);
            $this->post->set('kind', 'caselib');
            $this->post->set('fileName', 'libTemplate');
            $this->fetch('transfer', 'exportTemplate', 'model=caselib');
        }

        $this->display();
    }
}
