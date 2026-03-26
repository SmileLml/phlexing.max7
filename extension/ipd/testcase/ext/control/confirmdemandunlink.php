<?php
helper::importControl('testcase');
class myTestcase extends testcase
{
    /**
     * @param int|string $extra
     * @param int $objectID
     * @param string $objectType
     */
    public function confirmDemandUnlink($objectID = 0, $objectType = '', $extra = '')
    {
        echo $this->fetch('story', 'confirmDemandUnlink', "objectID=$objectID&objectType=$objectType&extra=$extra");
    }
}
