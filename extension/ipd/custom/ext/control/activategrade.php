<?php
helper::importControl('custom');
class myCustom extends custom
{
    /**
     * 启用需求等级。
     * Activate story grade.
     *
     * @param  string $type
     * @param  int    $gradeID
     * @access public
     * @return void
     */
    public function activateGrade($type = 'story', $gradeID = 0)
    {
        if($gradeID) $this->dao->update(TABLE_STORYGRADE)->set('status')->eq('enable')->where('grade')->eq($gradeID)->andWhere('type')->eq($type)->exec();
        return $this->sendSuccess(array('load' => true));
    }
}
