<?php
class bug extends control
{
    /**
     * Change subStatus of a bug by ajax.
     *
     * @param  int    $bugID
     * @param  string $subStatus
     * @access public
     * @return void
     */
    public function ajaxChangeSubStatus($bugID, $subStatus)
    {
        $this->dao->update(TABLE_BUG)->set('subStatus')->eq($subStatus)->where('id')->eq($bugID)->exec();
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        return $this->send(array('result' => 'success'));
    }
}
