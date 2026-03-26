<?php
helper::importControl('doc');
class mydoc extends control
{
    /**
     * 导出团队空间下的文档。
     * Export documents in team space.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function custom2export($libID, $moduleID = 0, $docID = 0, $version = 0)
    {
        $custom = $this->doc->getLibByID($libID);
        if(empty($custom)) return $this->locate($this->createLink('doc', 'createLib', 'type=custom'));

        if($docID) $this->doc->setDocPOST($docID, $version);
        if($_POST)
        {
            $this->post->set('libID', $libID);
            $this->post->set('module', $moduleID);
            $this->post->set('docID', $this->post->range == 'selected' ? $this->cookie->checkedItem : $docID);
            $this->post->set('range', $this->post->range);
            $this->post->set('kind', 'custom');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);

            return $this->fetch('file', 'doc2word', $_POST);
        }

        $this->view->docID    = $docID;
        $this->view->data     = $custom;
        $this->view->title    = $this->lang->export;
        $this->view->kind     = 'custom';
        $this->view->chapters = array('listAll'  => $this->lang->doc->export->listAll, 'selected' => $this->lang->doc->export->selected);

        $this->display('doc', 'doc2export');
    }
}
