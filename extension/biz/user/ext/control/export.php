<?php
class myUser extends user
{
    /**
     * Get data to export
     *
     * @param  string $browseType
     * @param  string $param
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function export($browseType, $param, $type, $orderBy)
    {
        if($_POST)
        {
            $this->loadModel('file');
            $this->user->setListValue();
            $userLang   = $this->lang->user;
            $userConfig = $this->config->user;
            $userPairs  = $this->user->getPairs('noletter|noclosed');

            /* Create field lists. */
            $fields = $this->post->exportFields ? $this->post->exportFields : explode(',', $userConfig->list->exportFields);
            foreach($fields as $key => $fieldName)
            {
                $fieldName = trim($fieldName);
                $fields[$fieldName] = isset($userLang->$fieldName) ? $userLang->$fieldName : $fieldName;
                unset($fields[$key]);
            }

            /* Get users. */
            $users = $this->dao->select('*')->from(TABLE_USER)->where($this->session->userQueryCondition)
                ->beginIF($this->post->exportType == 'selected')->andWhere('id')->in($this->cookie->checkedItem)->fi()
                ->orderBy($orderBy)->fetchAll('id', false);

            /* Get role, products and executions. */
            $roleList   = $this->lang->user->roleList;
            $genderList = $this->lang->user->genderList;
            $typeList   = $this->lang->user->typeList;

            $depts = $this->loadModel('dept')->getOptionMenu();
            foreach($depts as $id => $dept) $depts[$id] = "$dept(#$id)";

            foreach($users as $user)
            {
                if(isset($userLang->roleList[$user->role])) $user->role = $userLang->roleList[$user->role];
                if(isset($userLang->genderList[$user->gender])) $user->gender = $userLang->genderList[$user->gender];
                if(isset($userLang->typeList[$user->type])) $user->type = $userLang->typeList[$user->type];
                if(isset($depts[$user->dept])) $user->dept = $depts[$user->dept];
                if(isset($userPairs[$user->superior])) $user->superior = $userPairs[$user->superior];
            }

            $this->post->set('fields', $fields);
            $this->post->set('rows', $users);
            $this->post->set('kind', 'user');
            $this->fetch('file', 'export2' . $this->post->fileType, $_POST);
        }

        $fileName = $this->lang->user->common;

        $this->view->fileName        = $fileName;
        $this->view->allExportFields = $this->config->user->list->exportFields;
        $this->view->customExport    = true;
        $this->display();
    }
}
