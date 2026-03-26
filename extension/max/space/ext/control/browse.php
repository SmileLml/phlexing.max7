<?php
helper::importControl('space');
class mySpace extends space
{
    /**
     * DevOps应用列表。
     * Browse departments and users of a space.
     *
     * @param  int    $spaceID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
       @access public
     * @return void
     */
    public function browse($spaceID = 0, $browseType = '', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        if($this->config->inQuickon)
        {
            $systemSpace = $this->space->getSpacesByAccount('system');
            if(empty($systemSpace)) $systemSpace = $this->space->getSystemSpace('system');

            $this->space->buildZenTaoApp();
        }

        parent::browse($spaceID, $browseType, $orderBy, $recTotal, $recPerPage, $pageID);
    }
}
