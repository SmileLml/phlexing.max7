<?php
class myScreen extends control
{
    /**
     * Create screen.
     *
     * @param  int    $dimensionID
     * @access public
     * @return void
     */
    public function create($dimensionID)
    {
        if($_POST)
        {
            $screenID = $this->screen->create($dimensionID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $url = inlink('design', "screenID=$screenID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => "openUrl('$url')"));
        }
        $this->view->title       = $this->lang->screen->create;
        $this->view->dimensionID = $dimensionID;
        $this->display();
    }
}
