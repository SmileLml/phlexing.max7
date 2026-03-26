<?php
class release extends control
{
    /**
     * Get release by product ID.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxGetByProduct($productID)
    {
        $releases   = $this->release->getList($productID);
        $systemList = $this->loadModel('system')->getPairs($productID, '', 'active');

        $releaseList = array();
        foreach($releases as $release)
        {
            $systemID = zget($release, 'system', 0);
            $releaseList[] = array('text' => zget($systemList, $systemID, '') . $release->name, 'value' => $release->id);
        }

        echo json_encode($releaseList);
    }
}
