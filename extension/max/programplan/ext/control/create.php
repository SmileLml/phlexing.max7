<?php
helper::importControl('programplan');
class myProgramplan extends programplan
{
    /**
     * @param int $projectID
     * @param int $productID
     * @param int $planID
     * @param string $executionType
     * @param string $from
     * @param int $syncData
     */
    public function create($projectID = 0, $productID = 0, $planID = 0, $executionType = 'stage', $from = '', $syncData = 0)
    {
        $this->view->documentList = $this->programplan->getDocumentList();
        parent::create($projectID, $productID, $planID, $executionType, $from, $syncData);
    }
}
