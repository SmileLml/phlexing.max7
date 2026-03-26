<?php
class group extends control
{
    public function ajaxGetPairs($developer = 1)
    {
        $groups = $this->dao->select('id,name')->from(TABLE_GROUP)
            ->where('developer')->eq($developer)
            ->andWhere('project')->eq(0)
            ->fetchPairs('id', 'name');
        die(html::select('group', arrayUnion(array(0 => ''), $groups), 0, "class='form-control'"));
    }
}
