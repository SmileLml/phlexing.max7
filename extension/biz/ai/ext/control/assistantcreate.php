<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * create an assistant.
     *
     * @access public
     * @return int
     */
    public function assistantCreate()
    {
        if(strtolower($this->server->request_method) == 'post')
        {
            $assistant = fixer::input('post')->get();

            if(empty($assistant->name))
            {
                dao::$errors['name'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->assistant->name);
            }
            if(empty($assistant->modelId))
            {
                dao::$errors['modelId'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->models->common);
            }
            if(empty($assistant->systemMessage))
            {
                dao::$errors['systemMessage'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->assistant->systemMessage);
            }
            if(empty($assistant->greetings))
            {
                dao::$errors['greetings'][] = sprintf($this->lang->ai->validate->noEmpty, $this->lang->ai->assistant->greetings);
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $exists = $this->ai->checkAssistantDuplicate($assistant->name, $assistant->modelId);
            if($exists) return $this->send(array('result' => 'fail', 'message' => array('name' => $this->lang->ai->assistant->duplicateTip)));

            $assistant->icon = "$assistant->iconName-$assistant->iconTheme";
            unset($assistant->iconName, $assistant->iconTheme);

            if(empty($assistant->publish))
            {
                $this->ai->createAssistant($assistant);
            }
            else
            {
                unset($assistant->publish);
                $assistant->id = $this->ai->createAssistant($assistant, true);
            }

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            return $this->send(array('result' => 'success', 'locate' => helper::createLink('ai', 'assistants')));
        }

        $models = $this->ai->getLanguageModels('', true);
        $models = array_reduce($models, function($acc, $model)
        {
            $acc[$model->id] = empty($model->name) ? $this->lang->ai->models->typeList[$model->type] : $model->name;
            return $acc;
        }, array());

        $this->view->models    = $models;
        $this->view->iconName  = 'coding';
        $this->view->iconTheme = 1;
        $this->view->title     = $this->lang->ai->assistant->create;
        $this->display();
    }
}
