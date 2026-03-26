<?php
helper::importControl('custom');
class myCustom extends custom
{
    /**
     * @param string $module
     * @param string $field
     * @param string $lang
     */
    public function set($module = 'story', $field = 'priList', $lang = '')
    {
        if(($this->config->edition == 'max' || $this->config->edition == 'ipd') && ($module == 'program' && $field == 'priList')) $field = 'unitList';

        if($module == 'feedback' && $field == 'review')
        {
            if(strtolower($this->server->request_method) == "post")
            {
                if($this->post->needReview)  $data = fixer::input('post')->join('forceNotReview', ',')->remove('forceReview')->get();
                if(!$this->post->needReview) $data = fixer::input('post')->join('forceReview', ',')->remove('forceNotReview')->get();

                $this->loadModel('setting')->setItems("system.feedback@{$this->config->vision}", $data);

                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('custom', 'set', "module=$module&field=$field&lang=" . str_replace('-', '_', isset($this->config->langs[$lang]) ? $lang : 'all'))));
            }

            $this->app->loadLang('feedback');
            $this->app->loadLang('product');
            $this->app->loadConfig('feedback');

            $feedbackManager = array('feedbackmanager' => $this->lang->product->FM);
            $users           = $this->loadModel('user')->getPairs('noclosed|nodeleted');

            $this->view->users          = array_merge($feedbackManager, $users);
            $this->view->needReview     = zget($this->config->feedback, 'needReview', 0);
            $this->view->forceReview    = zget($this->config->feedback, 'forceReview', '');
            $this->view->forceNotReview = zget($this->config->feedback, 'forceNotReview', '');
            $this->view->reviewer       = zget($this->config->feedback, 'reviewer', '');
        }

        parent::set($module, $field, $lang);
    }
}
