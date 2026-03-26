<?php
helper::importControl('doc');
class mydoc extends control
{
    /**
     * 导出执行文档库下的文档。
     * Export documents under an execution library.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function execution2export($libID, $moduleID = 0, $docID = 0, $version = 0)
    {
        $lib = $this->doc->getLibByID($libID);
        if(empty($lib)) return $this->locate($this->createLink('doc', 'createLib', 'type=execution'));

        if($docID) $this->doc->setDocPOST($docID, $version);
        if($_POST)
        {
            $this->post->set('executionID', $lib->execution);
            $this->post->set('libID', $libID);
            $this->post->set('module', $moduleID);
            $this->post->set('docID', $this->post->range == 'selected' ? $this->cookie->checkedItem : $docID);
            $this->post->set('range', $this->post->range);
            $this->post->set('kind', 'execution');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);

            return $this->fetch('file', 'doc2word', $_POST);
        }

        $this->view->docID     = $docID;
        $this->view->data      = $lib;
        $this->view->title     = $this->lang->export;
        $this->view->kind      = 'execution';
        $this->view->chapters  = array(
            'listAll'      => $this->lang->doc->export->listAll,
            'selected'     => $this->lang->doc->export->selected,
            'executionAll' => $this->lang->doc->export->executionAll
        );
        $this->display('doc', 'doc2export');
    }
}
