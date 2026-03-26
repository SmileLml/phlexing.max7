<?php
helper::importControl('custom');
class myCustom extends custom
{
    /**
     * 停用需求等级。
     * Close story grade.
     *
     * @param  string $type
     * @param  int    $gradeID
     * @access public
     * @return void
     */
    public function closeGrade($type = 'story', $gradeID = 0)
    {
        if($gradeID) $this->dao->update(TABLE_STORYGRADE)->set('status')->eq('disable')->where('grade')->eq($gradeID)->andWhere('type')->eq($type)->exec();
        return $this->sendSuccess(array('load' => true));
    }
}
