<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Test mini program.
     *
     * @param string $appID
     * @access public
     * @return void
     */
    public function testMiniProgram($appID)
    {
        if(!empty($_POST))
        {
            $toPublish = $_POST['toPublish'];
            unset($_POST['toPublish']);
            $this->ai->editMiniProgram($appID);
            if($toPublish === '1') $this->ai->publishMiniProgram($appID, '1');
            return print(js::closeModal('parent', 'this'));
        }

        $miniProgram = $this->ai->getMiniProgramByID($appID);
        if(empty($miniProgram)) return $this->sendError($this->lang->ai->noMiniProgram);
        if($miniProgram->builtIn) return $this->sendError('Testing built-in program is not supported.');
        $this->view->currentFields = $this->ai->getMiniProgramFields($appID);
        $this->view->currentPrompt = $miniProgram->prompt;
        $this->view->appID         = $appID;
        $this->display();
    }
}
