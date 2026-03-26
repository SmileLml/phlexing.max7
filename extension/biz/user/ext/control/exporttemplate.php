<?php
class myUser extends user
{
    public function exportTemplate()
    {
        $this->loadModel('transfer');
        if($_POST)
        {
            $this->loadModel('company');
            $this->config->user->dtable = new stdclass();
            $this->config->user->dtable->fieldList = $this->config->company->user->dtable->fieldList;
            $this->user->setListValue();
            $this->fetch('transfer', 'exportTemplate', 'module=user');
        }

        $this->display();
    }
}
