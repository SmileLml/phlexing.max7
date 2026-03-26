<?php
class task extends control
{
    public function recordEstimate($taskID, $from = '', $orderBy = '')
    {
        $this->locate($this->createLink('effort', 'createForObject', "objectType=task&objectID=$taskID&from=$from&orderBy=$orderBy"));
    }
}
