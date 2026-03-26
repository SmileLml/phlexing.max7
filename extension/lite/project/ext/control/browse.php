<?php
helper::importControl('project');
class myProject extends project
{
    /**
     * @param int $programID
     * @param string $browseType
     * @param string $param
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     */
    public function browse($programID = 0, $browseType = 'doing', $param = '', $orderBy = 'order_asc', $recTotal = 0, $recPerPage = 15, $pageID = 1)
    {
        $_COOKIE['projectType'] = 'bylist';
        return parent::browse($programID, $browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID);
    }
}
