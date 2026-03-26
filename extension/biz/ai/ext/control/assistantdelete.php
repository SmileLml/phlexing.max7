<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Delete an assistant.
     * @param $assistantId
     * @return mixed
     */
    public function assistantDelete($assistantId)
    {
        $result = $this->ai->deleteAssistant($assistantId);
        if($result === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
