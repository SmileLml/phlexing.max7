<?php
class custom extends control
{
    public function feedback()
    {
        if(strtolower($this->server->request_method) == "post")
        {
            $review = fixer::input('post')->get();
            if($review->needReview) $data = fixer::input('post')->join('forceNotReview', ',')->remove('forceReview')->get();
            if(!$review->needReview) $data = fixer::input('post')->join('forceReview', ',')->remove('forceNotReview')->get();
            $this->loadModel('setting')->setItems("system.feedback@{$this->config->vision}", $data);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('custom', 'feedback')));
        }

        $this->app->loadLang('feedback');
        $this->app->loadConfig('feedback');
        $this->view->users = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->needReview      = zget($this->config->feedback, 'needReview', 0);
        $this->view->forceReview     = zget($this->config->feedback, 'forceReview', '');
        $this->view->forceNotReview  = zget($this->config->feedback, 'forceNotReview', '');
        $this->view->reviewer        = zget($this->config->feedback, 'reviewer', '');

        $this->view->title       = $this->lang->custom->common . $this->lang->hyphen . $this->lang->feedback->common;
        $this->view->position[]  = $this->lang->custom->common;
        $this->view->position[]  = $this->lang->feedback->common;
        $this->display();
    }
}
