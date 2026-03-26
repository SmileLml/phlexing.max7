<?php
class zentaobizDept extends deptModel
{
    /**
     * Get department manager.
     *
     * @param  int    $deptID
     * @access public
     * @return string
     */
    public function getManager($deptID)
    {
        if(empty($deptID)) return '';

        $dept = $this->getById($deptID);
        if($dept->manager) return $dept->manager;

        $parentManger = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->in($dept->path)->andWhere('id')->ne($deptID)->andWhere('manager')->ne('')->orderBy('grade_desc')->limit(1)->fetch('manager');
        if(!empty($parentManger)) return $parentManger;

        return '';
    }
}
