<?php
helper::importControl('custom');
class myCustom extends custom
{
    public function requirementGrade()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $this->custom->setStoryGrade('requirement', $_POST);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title       = $this->lang->custom->requirement->fields['requirementGrade'];
        $this->view->storyGrades = $this->loadModel('story')->getGradeList('requirement');
        $this->view->module      = 'requirement';
        $this->display('custom', 'storygrade');
    }
}
