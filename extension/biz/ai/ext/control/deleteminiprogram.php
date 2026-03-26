<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Change mini program `deleted` value.
     *
     * @param string $appID
     * @param string $deleted
     * @access public
     * @return void
     */
    public function deleteMiniProgram($appID, $deleted)
    {
        $result = $this->ai->deleteMiniProgram($appID, $deleted);
        if($result === true) return $this->send(array('result' => 'success', 'locate' => $this->createLink('ai', 'miniPrograms')));
        if(is_string($result)) return $this->sendError($result);
        $this->sendError(dao::getError());
    }
}
