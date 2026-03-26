<?php
helper::importControl('custom');
class myCustom extends custom
{
    public function storyGrade()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $this->custom->setStoryGrade('story', $_POST);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->sendSuccess(array('load' => true));
        }

        $this->view->title       = $this->lang->custom->story->fields['storyGrade'];
        $this->view->storyGrades = $this->loadModel('story')->getGradeList('story');
        $this->view->module      = 'story';
        $this->display();
    }
}
