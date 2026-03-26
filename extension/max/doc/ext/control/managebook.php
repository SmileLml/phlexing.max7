<?php
helper::importControl('doc');
class mydoc extends doc
{
    public function manageBook($bookID = 0, $nodeID = 0)
    {
        $this->session->set('docList', $this->app->getURI(true), $this->app->tab);

        $book = $this->doc->getLibById($bookID);
        $libs = $this->doc->getLibsByObject('book', 0);

        $this->view->title      = $this->lang->doc->manageBook;
        $this->view->position[] = $this->lang->doc->manageBook;
        $this->view->book       = $book;
        $this->view->type       = 'book';
        $this->view->currentLib = $book;
        $this->view->libID      = $bookID;
        $this->view->moduleTree = $this->doc->getBookStructure($bookID);
        $this->view->catalog    = $this->doc->getAdminCatalog($bookID, $nodeID, $this->doc->computeSN($bookID));

        $this->display();
    }
}
