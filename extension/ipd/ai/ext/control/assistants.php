<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * List assistants.
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @access public
     * @return void
     */
    public function assistants($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);


        $assistants = $this->ai->getAssistants($pager, $orderBy);
        $models = $this->ai->getLanguageModels('', false);
        $assistants = array_map(function ($assistant) use ($models)
        {
            $model = current(array_filter($models, function ($model) use ($assistant)
            {
                return $model->id == $assistant->modelId;
            }));

            $assistant->modelId = $model->name;

            if(empty($assistant->modelId))
            {
                $assistant->modelId = $this->lang->ai->models->typeList[$model->type];
            }

            if(empty($assistant->publishedDate) || $assistant->publishedDate == '0000-00-00 00:00:00')
            {
                $assistant->publishedDate = '-';
            }
            return $assistant;
        }, $assistants);

        $hasModalAvailable = $this->ai->hasModelsAvailable();

        $this->view->hasModalAvailable = $hasModalAvailable;
        $this->view->assistants        = $assistants;
        $this->view->orderBy           = $orderBy;
        $this->view->pager             = $pager;
        $this->view->title             = $this->lang->ai->assistant->title;
        $this->display();
    }
}
