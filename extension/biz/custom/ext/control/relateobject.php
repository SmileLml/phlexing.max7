<?php
class custom extends control
{
    /**
     * 关联对象。
     * Relate Object.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $relatedObjectType
     * @param  string $actionType  link|unlink
     * @param  int    $relatedObjectID
     * @param  string $browseType bySearch
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function relateObject($objectID, $objectType = '', $relatedObjectType = '', $browseType = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if($_POST)
        {
            $this->custom->relateObject($objectID, $objectType, $this->post->relation, $relatedObjectType);
            if(dao::isError()) return $this->sendError(array('message' => dao::getError()));

            return $this->sendSuccess($objectType == 'doc' ? array('callback' => array('name' => 'updateRelatedObjects'), 'closeModal' => true) : array('load' => true));
        }

        $this->custom->setConfig4Workflow();
        $this->customZen->buildSearchForm($objectID, $objectType, $relatedObjectType);

        /* Load page. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->recTotal          = $recTotal;
        $this->view->recPerPage        = $recPerPage;
        $this->view->pageID            = $pageID;
        $this->view->pager             = $pager;
        $this->view->orderBy           = $orderBy;
        $this->view->objectID          = $objectID;
        $this->view->objectType        = $objectType;
        $this->view->relatedObjectType = $relatedObjectType;
        $this->view->browseType        = $browseType;
        $this->view->users             = $this->loadModel('user')->getPairs('noletter');
        $this->view->objects           = $this->custom->getObjects($relatedObjectType, $browseType, $orderBy, $pager, $relatedObjectType == $objectType ? $objectID : 0);
        $this->view->cols              = $this->custom->getObjectCols($relatedObjectType);
        $this->view->relationPairs     = $this->custom->getRelationList(true);
        $this->display();
    }
}
