<?php
class oaDept extends deptModel
{
    public function getDeptManagedByMe($account)
    {
        if($this->app->user->admin) return $this->dao->select('*')->from(TABLE_DEPT)->fetchAll('id');

        $depts = $this->dao->select('*')->from(TABLE_DEPT)->where('manager')->ne('')->fetchAll('id');
        if(empty($depts)) return array();

        $noManagerDepts = $this->dao->select('*')->from(TABLE_DEPT)->where('manager')->eq('')->fetchAll('id');
        $myDept         = array();
        foreach($depts as $id => $dept)
        {
            if(trim($dept->manager, ',') == $account)
            {
                $myDept[$id] = $dept;
                foreach($noManagerDepts as $noManagerID => $noManagerDept)
                {
                    if($noManagerDept->grade > $dept->grade and strpos($noManagerDept->path, $dept->path) === 0)
                    {
                        $myDept[$noManagerID] = $noManagerDept;
                    }
                }
            }
        }
        return $myDept;
    }
}
