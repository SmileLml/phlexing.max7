<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Withdraw an assistant.
     * @param  int  $assistantId
     * @access public
     * @return void
     */
    public function assistantWithdraw($assistantId)
    {
        $result = $this->ai->toggleAssistant($assistantId, false);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
