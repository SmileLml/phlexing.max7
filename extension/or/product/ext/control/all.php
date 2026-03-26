<?php
class myProduct extends product
{
    /**
     * @param string $browseType
     * @param string $orderBy
     * @param int $param
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     * @param int $programID
     */
    public function all($browseType = '', $orderBy = 'order_asc', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1, $programID = 0)
    {
        if(empty($browseType)) $browseType = 'all';

        $this->view->roadmapGroup = $this->loadModel('roadmap')->getRoadmapCount();

        parent::all($browseType, $orderBy, $param, $recTotal, $recPerPage, $pageID, $programID);
    }
}
