<?php
helper::importControl('ai');
class myAI extends ai
{

    /**
     * Publish an assistant.
     * @param  int  $assistantId
     * @access public
     * @return void
     */
    public function assistantPublish($assistantId)
    {
        $result = $this->ai->toggleAssistant($assistantId, true);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
