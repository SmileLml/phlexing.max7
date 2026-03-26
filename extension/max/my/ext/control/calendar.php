<?php
helper::importControl('my');
class myMy extends my
{
    public function calendar()
    {
        if(common::hasPriv('todo', 'calendar')) $this->locate($this->createLink('todo', 'calendar'));
        return parent::calendar();
    }
}
