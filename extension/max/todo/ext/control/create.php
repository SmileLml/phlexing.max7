<?php
helper::importControl('todo');
class myTodo extends todo
{
    public function create($date = 'today', $userID = '', $from = 'todo', $param = '')
    {
        if($userID == '') $userID = $this->app->user->id;
        $user = $this->loadModel('user')->getById($userID, 'id');

        if($from == 'feedback' or $this->post->type == 'feedback')
        {
            $feedbackID = $param ? $param : 0;
            $feedback   = $this->loadModel('feedback')->getByID($feedbackID);
            if(!empty($_POST))
            {
                if($this->post->type != 'custom')
                {
                    $this->config->todo->create->requiredFields = '';
                    $this->config->todo->create->form['name']['required'] = false;
                }
                $form     = form::data($this->config->todo->create->form);
                $form     = $this->todoZen->addCycleYearConfig($form);
                $todoData = $this->todoZen->beforeCreate($form);

                $todo = $this->todoZen->prepareCreateData($todoData);

                if(dao::isError()) die(js::error(dao::getError()));

                $todoID = $this->todo->create($todo);
                if($todoID === false) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

                if(dao::isError()) die(js::error(dao::getError()));

                $actionType = 'opened';
                if($from == 'feedback') $actionType = 'FromFeedback';
                $this->loadModel('action')->create('todo', $todoID, $actionType, '', $param);

                $date = str_replace('-', '', $this->post->date);
                if($date == '')
                {
                    $date = 'future';
                }
                elseif($date == date('Ymd'))
                {
                    $date = 'today';
                }

                if(!empty($_POST['objectID'])) return $this->send(array('result' => 'success'));

                if($this->app->getViewType() == 'xhtml') die(js::locate($this->createLink('todo', 'view', "todoID=$todoID"), 'parent'));

                return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
            }

            $actions = $this->loadModel('action')->getList('feedback', $feedbackID);
            foreach($actions as $action)
            {
                if($action->action == 'reviewed' and $action->comment)
                {
                    $feedback->desc .= $feedback->desc ? '<br/>' . $this->lang->feedback->reviewOpinion . '：' . $action->comment : $this->lang->feedback->reviewOpinion . '：' . $action->comment;
                }
            }

            /* Set Menu. */
            $this->lang->feedback->menu->browse['subModule'] = 'todo';

            $this->view->feedback = $feedback;
        }

        parent::create($date, $userID, $from);
    }
}
