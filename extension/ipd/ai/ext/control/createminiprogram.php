<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Create a mini program.
     *
     * @access public
     * @return void
     */
    public function createMiniProgram()
    {
        if(!empty($_POST))
        {
            $toNext = $_POST['toNext'];
            unset($_POST['toNext']);
            $requiredFields = array('category' => $this->lang->ai->miniPrograms->category, 'model' => $this->lang->prompt->model, 'name' => $this->lang->prompt->name, 'desc' => $this->lang->ai->miniPrograms->desc);
            $errors = $this->ai->verifyRequiredFields($requiredFields);
            if($errors !== false) return $this->sendError($errors);

            $isDuplicated = $this->ai->checkDuplicatedAppName($_POST['name']);
            if($isDuplicated) return $this->sendError(array('name' => $this->lang->ai->miniPrograms->field->duplicatedNameTip));

            $appID = $this->ai->createMiniProgram();
            if($appID === false) return $this->sendError(array('message' => $this->lang->fail));
            if($toNext === '1')  return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'locate' => $this->createLink('ai', 'configuredMiniProgram', "appID=$appID")));
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'locate' => $this->createLink('ai', 'editMiniProgram', "appID=$appID")));
        }

        $models = $this->ai->getLanguageModels();
        $models = array_reduce($models, function ($carry, $model)
        {
            $carry[$model->id] = $model->name;
            return $carry;
        }, array('default' => $this->lang->ai->models->default));

        $this->view->models       = $models;
        $this->view->iconName     = 'writinghand';
        $this->view->iconTheme    = 7;
        $this->view->categoryList = $this->ai->getCustomCategories();
        $this->view->title        = $this->lang->ai->miniPrograms->common;
        $this->display();
    }
}
