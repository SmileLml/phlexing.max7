<?php
helper::importControl('tree');
class mytree extends tree
{
    /**
     * @param int $rootID
     * @param string $viewType
     * @param int $currentModuleID
     * @param string $branch
     * @param string $from
     */
    public function browse($rootID, $viewType, $currentModuleID = 0, $branch = 'all', $from = '')
    {
        if((!empty($this->app->user->feedback) or $this->cookie->feedbackView) and $viewType != 'doc') die();
        if($this->app->tab == 'feedback')
        {
            if($viewType == 'feedback') $this->lang->feedback->menu->browse['subModule'] = 'tree';
            if($viewType == 'ticket')   $this->lang->feedback->menu->ticket['subModule'] = 'tree';
        }

        return parent::browse($rootID, $viewType, $currentModuleID, $branch, $from);
    }
}
