<?php
helper::importControl('doc');
class mydoc extends control
{
    /**
     * AJAX: Get related objects.
     *
     * @param  int    $docID
     * @access public
     * @return void
     */
    public function ajaxGetRelatedObjects($docID)
    {
        $this->view->docID          = $docID;
        $this->view->relatedObjects = $this->loadModel('custom')->getRelatedObjectList($docID, 'doc', 'byObject');
        $this->display();
    }
}
