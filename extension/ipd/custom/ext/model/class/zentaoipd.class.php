<?php
class zentaoipdCustom extends customModel
{
    /**
     * 设置需求等级。
     * Set story grade.
     *
     * @param  string $module
     * @param mixed[] $data
     * @access public
     * @return bool
     */
    public function setStoryGrade($module, $data)
    {
        $this->app->loadLang('story');
        $names = array();
        foreach($data['grade'] as $key => $value)
        {
            $name = $data['gradeName'][$key];
            if(!$name)
            {
                dao::$errors['grade'] = sprintf($this->lang->error->notempty, $this->lang->story->gradeName);
                return false;
            }

            if(in_array($name, $names))
            {
                dao::$errors['grade'] = sprintf($this->lang->error->repeat, $this->lang->story->gradeName, $name);
                return false;
            }

            $names[] = $name;
        }

        $oldGrades = $this->dao->select('*')->from(TABLE_STORYGRADE)->where('type')->eq($module)->fetchAll('grade');
        $this->dao->delete()->from(TABLE_STORYGRADE)->where('type')->eq($module)->exec();
        foreach($data['grade'] as $key => $value)
        {
            $name = $data['gradeName'][$key];

            $grade = new stdclass();
            $grade->grade  = $value;
            $grade->name   = $name;
            $grade->type   = $module;
            $grade->status = isset($oldGrades[$value]) ? $oldGrades[$value]->status : 'enable';

            $this->dao->insert(TABLE_STORYGRADE)->data($grade)->exec();

            /* New grade. */
            if(!in_array($value, array_keys($oldGrades)))
            {
                $showGradesValue = $module . (string)$value;
                $this->dao->update(TABLE_CONFIG)->set("`value` = concat(`value`, ',', '$showGradesValue')")
                     ->where('`key`')->eq('showGrades')
                     ->beginIF($module == 'epic')->andWhere('module')->eq('epic')->fi()
                     ->beginIF($module == 'requirement')->andWhere('module')->in('epic,requirement')->fi()
                     ->exec();
            }
        }

        return !dao::isError();
    }
}
