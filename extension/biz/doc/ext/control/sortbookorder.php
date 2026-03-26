<?php
helper::importControl('doc');
class mydoc extends doc
{
    public function sortBookOrder()
    {
        if($this->doc->sortBookOrder()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
        return $this->send(array('result' => 'fail', 'message' => dao::getError()));
    }
}
