<?php
helper::importControl('my');
class myMy extends my
{
    /**
     * The index page of whole zentao system.
     *
     * @param  string $open
     * @access public
     * @return void
     */
    public function index()
    {
        if(!empty($this->app->user->feedback) or $this->cookie->feedbackView) $this->locate($this->createLink('todo', 'calendar'));
        return parent::index();
    }
}
