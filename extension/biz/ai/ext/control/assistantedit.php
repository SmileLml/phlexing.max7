<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Edit an assistant.
     * @param  int  $assistantId
     * @access public
     * @return void
     */
    public function assistantEdit($assistantId)
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

            $assistant->id = $assistantId;

            $exists = $this->ai->checkAssistantDuplicate($assistant->name, $assistant->modelId);
            if($exists && $exists->id != $assistantId) return $this->send(array('result' => 'fail', 'message' => array('name' => $this->lang->ai->assistant->duplicateTip)));

            $assistant->icon = "$assistant->iconName-$assistant->iconTheme";
            unset($assistant->iconName, $assistant->iconTheme);

            if(empty($assistant->publish))
            {
                $this->ai->updateAssistant($assistant);
            }
            else
            {
                unset($assistant->publish);
                $assistant->enabled = '1';
                $assistant->id = $this->ai->updateAssistant($assistant);
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

        $assistant = $this->ai->getAssistantById($assistantId);

        list($iconName, $iconTheme) = explode('-', $assistant->icon);

        $this->view->models    = $models;
        $this->view->assistant = $assistant;
        $this->view->iconName  = $iconName;
        $this->view->iconTheme = $iconTheme;
        $this->view->title     = $this->lang->ai->assistant->edit;
        $this->display();
    }
}
