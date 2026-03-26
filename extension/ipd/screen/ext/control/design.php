<?php
class myScreen extends control
{
    /**
     * design a screen.
     *
     * @param  int    $screenID
     * @access public
     * @return void
     */
    public function design($screenID, $page = 'main')
    {
        $this->screen->checkAccess($screenID);

        if($page == 'detail')
        {
            $this->display('screen', 'designdetail');
            return;
        }
        if($_POST)
        {
            $screen = $this->screen->getByID($screenID);
            $locate = $this->createLink('screen', 'browse');
            if($screen->builtin) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));

            $this->screen->publish($screenID);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->screen->saveThumbnail($screenID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $locate));
        }

        $screen      = $this->screen->getByID($screenID);

        $this->view->title       = $this->lang->screen->designScreen;
        $this->view->screen      = $screen;
        $this->view->scopeList   = $this->loadModel('metric')->getScopePairs();
        $this->view->backLink    = $this->createLink('screen', 'browse');
        $this->display();
    }
}
