<?php
helper::importControl('ai');
class myAI extends ai
{
    /**
     * Create a mini program.
     *
     * @param string $appID
     * @access public
     * @return void
     */
    public function editMiniProgram($appID)
    {
        if(!empty($_POST))
        {
            $toNext = $_POST['toNext'];
            unset($_POST['toNext']);
            $requiredFields = array('category' => $this->lang->ai->miniPrograms->category, 'model' => $this->lang->prompt->model, 'name' => $this->lang->prompt->name, 'desc' => $this->lang->ai->miniPrograms->desc);
            $errors = $this->ai->verifyRequiredFields($requiredFields);
            if($errors !== false) return $this->sendError($errors);

            $isDuplicated = $this->ai->checkDuplicatedAppName($_POST['name'], $appID);
            if($isDuplicated) return $this->sendError(array('name' => $this->lang->ai->miniPrograms->field->duplicatedNameTip));

            $result = $this->ai->editMiniProgram($appID);
            if($result === false) return $this->sendError(array('message' => $this->lang->fail));
            if($toNext === '1')   return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'locate' => $this->createLink('ai', 'configuredMiniProgram', "appID=$appID")));
            return $this->sendSuccess(array('message' => $this->lang->saveSuccess, 'locate' => $this->createLink('ai', 'editMiniProgram', "appID=$appID")));
        }

        $miniProgram = $this->ai->getMiniProgramByID($appID);
        if(empty($miniProgram)) return $this->sendError($this->lang->ai->noMiniProgram);

        $models = $this->ai->getLanguageModels();
        $models = array_reduce($models, function ($carry, $model)
        {
            $carry[$model->id] = $model->name;
            return $carry;
        }, array('default' => $this->lang->ai->models->default));

        $this->view->name     = $miniProgram->name;
        $this->view->desc     = $miniProgram->desc;
        $this->view->model    = $miniProgram->model;
        $this->view->models   = $models;
        $this->view->category = $miniProgram->category;
        list($this->view->iconName, $this->view->iconTheme) = explode('-', $miniProgram->icon);
        $this->view->categoryList = $this->ai->getCustomCategories();
        $this->view->title = $this->lang->ai->miniPrograms->common;
        $this->display('ai', 'createminiprogram');
    }
}
