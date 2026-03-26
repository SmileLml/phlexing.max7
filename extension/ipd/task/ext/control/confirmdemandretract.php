<?php
helper::importControl('task');
class myTask extends task
{
    /**
     * @param int|string $extra
     * @param int $objectID
     * @param string $objectType
     */
    public function confirmDemandRetract($objectID = 0, $objectType = '', $extra = '')
    {
        echo $this->fetch('story', 'confirmDemandRetract', "objectID=$objectID&objectType=$objectType&extra=$extra");
    }
}
