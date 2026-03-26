<?php
helper::importControl('message');
class myMessage extends message
{
    /**
     * @param string $windowBlur
     */
    public function ajaxGetMessage($windowBlur = 'false')
    {
        if(!empty($this->app->user->signed) and $this->app->user->mustSignOut == 'no') $this->loadModel('attend')->signOut();
        return parent::ajaxGetMessage($windowBlur);
    }
}
