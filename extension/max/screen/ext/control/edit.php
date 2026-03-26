<?php
class myScreen extends control
{
    /**
     * Edit a screen.
     *
     * @param  int    $screenID
     * @access public
     * @return void
     */
    public function edit($screenID)
    {
        $screen = $this->screen->getByID($screenID, 0, 0, 0, '', false);
        if($_POST)
        {
            $this->screen->update($screenID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }
        $this->view->title  = $this->lang->screen->editScreen;
        $this->view->screen = $screen;
        $this->display();
    }
}
