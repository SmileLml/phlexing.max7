<?php
helper::importControl('leave');
class myLeave extends leave
{
    /**
     * get data to export.
     *
     * @param  string $mode
     * @param  string $orderBy
     * @param  string $type personal|browsereview|company
     * @access public
     * @return void
     */
    public function export($mode = 'all', $orderBy = 'id_desc', $type = '')
    {
        $this->view->fileName = isset($this->lang->leave->$type) ? $this->lang->leave->$type : '';
        unset($this->lang->exportTypeList['selected']);

        return parent::export($mode, $orderBy);
    }
}
