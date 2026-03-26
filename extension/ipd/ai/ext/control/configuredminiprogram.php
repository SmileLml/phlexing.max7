<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Configured a mini program.
     *
     * @param string $appID
     * @access public
     * @return void
     */
    public function configuredMiniProgram($appID)
    {
        if(!common::hasPriv('ai', 'editMiniProgram') && !common::hasPriv('ai', 'createMiniProgram')) return $this->locate($this->createLink('my', 'index'));
        if(!empty($_POST))
        {
            $toPublish = $_POST['toPublish'];
            unset($_POST['toPublish']);
            $this->ai->saveMiniProgramFields($appID);
            if($toPublish === '1') $this->ai->publishMiniProgram($appID, '1');
            return $this->sendSuccess(array());
        }

        $program = $this->ai->getMiniProgramByID($appID);
        if(empty($program)) return $this->sendError($this->lang->ai->noMiniProgram);

        $this->view->currentFields = $this->ai->getMiniProgramFields($appID);
        $this->view->currentPrompt = $program->prompt;
        $this->view->title         = $this->lang->ai->miniPrograms->common;
        $this->view->appID         = $appID;
        $this->display();
    }
}
