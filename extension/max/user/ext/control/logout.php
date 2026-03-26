<?php
helper::importControl('user');
class myuser extends user
{
    /**
     * @param string $referer
     */
    public function logout($referer = '')
    {
        $this->app->loadModuleConfig('attend');
        /* Save sign out info. */
        $this->loadModel('attend')->signOut();
        return parent::logout($referer);
    }
}
