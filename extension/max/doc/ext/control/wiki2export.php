<?php
helper::importControl('doc');
class mydoc extends control
{
    public function wiki2export($libID, $moduleID = 0, $docID = 0, $version = 0)
    {
        $book = $this->doc->getLibByID($libID);
        if(empty($lib)) return print(js::locate($this->createLink('doc', 'createLib', 'type=custom')));

        if($docID) $this->doc->setDocPOST($docID, $version);
        if($_POST)
        {
            $this->post->set('libID', $libID);
            $this->post->set('module', $moduleID);
            $this->post->set('docID', $this->post->range == 'selected' ? $this->cookie->checkedItem : $docID);
            $this->post->set('range', $this->post->range);
            $this->post->set('kind', 'wiki');
            $this->post->set('format', $this->post->format);
            $this->post->set('fileName', $this->post->fileName);

            return $this->fetch('file', 'doc2word', $_POST);
        }

        $this->view->docID     = $docID;
        $this->view->data      = $book;
        $this->view->title     = $this->lang->export;
        $this->view->kind      = 'wiki';
        $this->view->chapters  = array(
            'current' => $this->lang->doc->export->currentDoc,
            'all'     => $this->lang->doc->export->allDoc
        );
        $this->display('doc', 'doc2export');
    }
}
