<?php
helper::importControl('leave');
class myLeave extends leave
{
    /**
     * Edit leave.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function edit($id)
    {
        $leave = $this->leave->getByID($id);

        if($_POST)
        {
            $result = $this->leave->update($id);
            if(!empty($result['result']) && $result['result'] == 'fail') return $this->send($result);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($result)
            {
                $actionID = $this->loadModel('action')->create('leave', $id, 'edited');
                $this->action->logHistory($actionID, $result);
            }
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => 'reload'));
        }

        $this->view->title = $this->lang->leave->edit;
        $this->view->leave = $leave;
        $this->display();
    }

}
