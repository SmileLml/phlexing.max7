<?php
helper::importControl('custom');
class myCustom extends custom
{
    public function epicGrade()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $this->custom->setStoryGrade('epic', $_POST);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title       = $this->lang->custom->epic->fields['epicGrade'];
        $this->view->storyGrades = $this->loadModel('story')->getGradeList('epic');
        $this->view->module      = 'epic';
        $this->display('custom', 'storygrade');
    }
}
