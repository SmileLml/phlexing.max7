<?php
helper::importControl('misc');
class myMisc extends misc
{
    public function ping()
    {
        if(!empty($this->app->user->signed) and $this->app->user->mustSignOut == 'no') $this->loadModel('attend')->signOut();
        return parent::ping();
    }
}
