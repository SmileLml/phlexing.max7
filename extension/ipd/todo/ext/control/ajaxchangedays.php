<?php
class todo extends control
{
    public function ajaxChangeDays($id, $milliseconds)
    {
        $date = date('Y-m-d', $milliseconds / 1000);
        $this->dao->update(TABLE_TODO)->set('date')->eq($date)->where('id')->eq($id)->exec();
    }
}
