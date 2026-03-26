<?php
class myBug extends bug
{
    /**
     * AJAX: Get identify.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function ajaxGetIdentify($productID, $projectID)
    {
        $reviews = $this->loadModel('review')->getPairs($projectID, $productID, true);
        $items   = array();
        foreach($reviews as $reviewID => $reviewName) $items[] = array('text' => $reviewName, 'value' => $reviewID);
        return print(json_encode($items));
    }
}
