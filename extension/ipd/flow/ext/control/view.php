<?php
class myflow extends flow
{
    public function view($dataID, $linkType = '', $mode = 'browse')
    {
        $module = $this->app->rawModule;
        $this->view->actionFormLink = $this->createLink('action', 'comment', "objectType={$module}&objectID=$dataID");
        $this->lang->hyphen = $this->lang->flow->colon;
        parent::view($dataID, $linkType, $mode);
    }
}
