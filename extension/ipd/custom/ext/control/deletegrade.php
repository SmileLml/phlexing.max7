<?php
helper::importControl('custom');
class myCustom extends custom
{
    /**
     * 删除需求等级。
     * Delete story grade.
     *
     * @param  string $type
     * @param  int    $gradeID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function deleteGrade($type = 'story', $gradeID = 0, $confirm = 'no')
    {
        $story = $this->dao->select('id')->from(TABLE_STORY)->where('grade')->eq($gradeID)->andWhere('type')->eq($type)->limit(1)->fetch('id');
        if($story) return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.alert('{$this->lang->custom->notice->gradeNotEmpty}');"));

        if($confirm == 'no')
        {
            $formUrl = $this->createLink('custom', 'deleteGrade', "type=$type&gradeID=$gradeID&confirm=yes");
            return $this->send(array('result' => 'fail', 'callback' => "zui.Modal.confirm({message: '{$this->lang->custom->notice->deleteGrade}'}).then((res) => {if(res) $.ajaxSubmit({url: '{$formUrl}'});});"));
        }

        if($gradeID)
        {
            $grades = $this->dao->select('*')->from(TABLE_STORYGRADE)->where('grade')->gt($gradeID)->andWhere('type')->eq($type)->orderBy('grade_asc')->fetchAll();
            $this->dao->delete()->from(TABLE_STORYGRADE)->where('grade')->eq($gradeID)->andWhere('type')->eq($type)->exec();
            foreach($grades as $grade) $this->dao->update(TABLE_STORYGRADE)->set('grade')->eq($grade->grade - 1)->where('grade')->eq($grade->grade)->andWhere('type')->eq($type)->exec();
        }
        return $this->sendSuccess(array('load' => true));
    }
}
