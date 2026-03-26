<?php
/**
 * Edit a mini program.
 *
 * @param string $appID
 * @access public
 * @return bool
 */
public function editMiniProgram($appID)
{
    $data = fixer::input('post')->get();
    $data->editedBy   = $this->app->user->account;
    $data->editedDate = helper::now();
    $data->published  = '0';
    if(!empty($data->iconName) && !empty($data->iconTheme))
    {
        $data->icon = $data->iconName . '-' . $data->iconTheme;
        unset($data->iconName);
        unset($data->iconTheme);
    }

    $data->model = !isset($data->model) || $data->model == 'default' ? 0 : $data->model;

    $old = $this->getMiniProgramByID($appID);
    if($old->builtIn === '1') return false;

    $this->dao->update(TABLE_AI_MINIPROGRAM)
        ->data($data)
        ->where('id')->eq($appID)
        ->exec();
    if(dao::isError()) return false;

    $data->id = $appID;
    $changes = common::createChanges($old, $data);
    $actionID = $this->loadModel('action')->create('miniProgram', $appID, 'edited');
    if(!empty($changes)) $this->action->logHistory($actionID, $changes);
    return true;
}

/**
 * Create a mini program php data file.
 *
 * @param string $appID
 * @return string
 */
public function createZtAppPhp($appID)
{
    $miniProgram = $this->getMiniProgramByID($appID);
    $fields = $this->getMiniProgramFields($appID);
    unset($miniProgram->id);
    unset($miniProgram->category);
    unset($miniProgram->createdBy);
    unset($miniProgram->createdDate);
    unset($miniProgram->editedBy);
    unset($miniProgram->editedDate);
    unset($miniProgram->model);
    unset($miniProgram->publishedDate);
    $miniProgram->published = '0';
    $miniProgram->fields = array();

    foreach($fields as $field)
    {
        unset($field->id);
        unset($field->appID);
        $miniProgram->fields[] = $field;
    }

    $appJson = json_encode($miniProgram);
    $content = <<<APP
<?php

\$ztApp = '$appJson';
APP;
    $file = $this->app->getAppRoot() . "tmp/{$miniProgram->name}.ztapp.php";
    file_put_contents($file, $content);
    return $file;
}

/**
 * Create mini program zip.
 *
 * @param string $file
 * @return string
 */
public function createZtAppZip($file)
{
    $this->app->loadClass('pclzip', true);
    $zipPath = substr($file, 0, -3) . 'zip';
    $zip = new pclzip($zipPath);
    $zip->create($file, PCLZIP_OPT_REMOVE_ALL_PATH);
    return $zipPath;
}

/**
 * Change mini program `deleted` value.
 *
 * @param string $appID
 * @param string $deleted
 * @access public
 * @return bool
 */
public function deleteMiniProgram($appID, $deleted = '1')
{
    $program = $this->getMiniProgramByID($appID);
    if($program->builtIn === '1') return 'Deletion of built-in program is not supported.';
    $this->dao->update(TABLE_AI_MINIPROGRAM)
        ->set('deleted')->eq($deleted)
        ->where('id')->eq($appID)
        ->exec();

    $this->loadModel('action')->create('miniProgram', $appID, 'deleted');
    return !dao::isError();
}
