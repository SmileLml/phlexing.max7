<?php
helper::importControl('user');
class myuser extends user
{
    /**
     * @param int $deptID
     * @param string $type
     */
    public function batchCreate($deptID = 0, $type = 'inside')
    {
        $this->userZen->checkUserLimitForBatch();
        return parent::batchCreate($deptID, $type);
    }
}
