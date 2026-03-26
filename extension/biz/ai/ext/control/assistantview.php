<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * View assistant details.
     * @param  int  $assistantId
     * @access public
     * @return void
     */
    public function assistantView($assistantId)
    {
        $assistant = $this->ai->getAssistantById($assistantId);
        $model     = $this->ai->getLanguageModel($assistant->modelId);

        if(empty($model->name))
        {
            $model->name = $this->lang->ai->models->typeList[$model->type];
        }

        list($iconName, $iconTheme) = explode('-', $assistant->icon);

        $this->view->iconName  = $iconName;
        $this->view->iconTheme = $iconTheme;
        $this->view->assistant = $assistant;
        $this->view->model     = $model;
        $this->view->title     = $this->lang->ai->assistant->view;
        $this->display();
    }
}
