<?php
class build extends control
{
    public function browse()
    {
        $this->locate($this->createLink('project', 'build'));
    }
}
