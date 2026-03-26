<?php
helper::importControl('doc');
class mydoc extends control
{
    /**
     * 导出产品空间下的文档。
     * Export documents under the product space.
     *
     * @param  int    $libID
     * @param  int    $moduleID
     * @param  int    $docID
     * @param  int    $version
     * @access public
     * @return void
     */
    public function product2export($libID, $moduleID = 0, $docID = 0, $version = 0)
    {
        $lib = $this->doc->getLibByID($libID);
        if(empty($lib)) return $this->locate($this->createLink('doc', 'createLib', 'type=project'));

        if($docID) $this->doc->setDocPOST($docID, $version);
        if($_POST)
        {
            $this->post->set('productID', $lib->product);
            $this->post->set('libID', $libID);
            $this->post->set('module', $moduleID);
            $this->post->set('docID', $this->post->range == 'selected' ? $this->cookie->checkedItem : $docID);
            $this->post->set('range', $this->post->range);
            $this->post->set('kind', 'product');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);

            return $this->fetch('file', 'doc2word', $_POST);
        }

        $this->view->docID    = $docID;
        $this->view->data     = $lib;
        $this->view->title    = $this->lang->export;
        $this->view->kind     = 'product';
        $this->view->chapters = array(
            'listAll'    => $this->lang->doc->export->listAll,
            'selected'   => $this->lang->doc->export->selected,
            'productAll' => $this->lang->doc->export->productAll
        );
        $this->display('doc', 'doc2export');
    }
}
