<?php
class task extends control
{
    /**
     * @param int $executionID
     */
    public function browse($executionID = 0)
    {
        $this->locate($this->createLink('execution', 'task', "executionID=$executionID"));
    }
}
