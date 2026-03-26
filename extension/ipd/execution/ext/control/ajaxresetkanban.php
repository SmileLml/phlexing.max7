<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * Ajax reset kanban setting
     *
     * @param  int    $executionID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxResetKanban($executionID, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->kanbanSetting->noticeReset, inlink('ajaxResetKanban', "executionID=$executionID&confirm=yes")));

        /* Delete settings of sub status. */
        $this->loadModel('setting')->deleteItems("owner=system&module=execution&section=kanbanSetting&key=laneField");
        $this->setting->deleteItems("owner=system&module=execution&section=kanbanSetting&key=subStatus");
        $this->setting->deleteItems("owner=system&module=execution&section=kanbanSetting&key=subStatusColor");

        parent::ajaxResetKanban($executionID, $confirm);
    }
}
