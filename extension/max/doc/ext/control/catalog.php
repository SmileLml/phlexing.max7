<?php
helper::importControl('doc');
class mydoc extends doc
{
    public function catalog($bookID = 0, $nodeID = 0)
    {
        if($_POST)
        {
            $result = $this->doc->manageCatalog($bookID, $nodeID);
            if($result) return $this->send(array('result' => 'success', 'message'=>$this->lang->saveSuccess, 'locate' => $this->createLink('doc', 'manageBook', "bookID=$bookID&nodeID=0") . "#node" . $nodeID));
            return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $book = $this->doc->getLibById($bookID);
        $libs = $this->doc->getLibsByObject('book', 0);

        $this->view->title      = $this->lang->doc->catalog;
        $this->view->position[] = $this->lang->doc->catalog;
        $this->view->book       = $book;
        $this->view->type       = 'book';
        $this->view->currentLib = $book;
        $this->view->libID      = $bookID;
        $this->view->moduleTree = $this->doc->getBookStructure($bookID);
        $this->view->node       = $this->doc->getById($nodeID);
        $this->view->children   = $this->doc->getChildren($bookID, $nodeID);

        $this->display();
    }
}
