<?php
class custom extends control
{
    /**
     * 展示关系图谱。
     * Show relation graph.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function showRelationGraph($objectID, $objectType)
    {
        $relatedObjects          = $this->custom->getRelatedObjectList($objectID, $objectType);
        $this->view->graphData   = $this->customZen->getGraphData($relatedObjects, $objectID, $objectType);
        $this->view->usersAvatar = $this->loadModel('user')->getAvatarPairs('all');
        $this->view->objectID    = $objectID;
        $this->view->objectType  = $objectType;
        $this->display();
    }
}
