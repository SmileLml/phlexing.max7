<?php
helper::importControl('my');
class myMy extends my
{
    public function changePassword()
    {
        if(!empty($_POST) and $this->app->getViewType() == 'mhtml')
        {
            $this->user->updatePassword($this->app->user->id);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('my', 'index'), 'parent'));
        }
        return parent::changePassword();
    }
}
