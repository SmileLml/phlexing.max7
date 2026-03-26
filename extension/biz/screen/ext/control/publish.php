<?php
class myScreen extends control
{
    /**
     * Publish a screen.
     *
     * @param  int    $screenID
     * @access public
     * @return void
     */
    public function publish($screenID)
    {
        if($_POST)
        {
            $screen = $this->screen->getByID($screenID);

            $this->screen->publish($screenID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->screen->saveThumbnail($screenID);

            $callback = array('target' => 'parent', 'name' => 'backBrowse');
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => $callback));
        }

        $this->view->title  = $this->lang->screen->publishScreen;
        $this->view->screen = $this->screen->getByID($screenID);
        $this->display();
    }
}
