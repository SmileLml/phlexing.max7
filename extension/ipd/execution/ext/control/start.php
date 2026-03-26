<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * 开始一个执行。
     * Start the execution.
     *
     * @param  int    $executionID
     * @param  string $from
     * @access public
     * @return void
     */
    public function start($executionID, $from = 'execution')
    {
        if($_POST)
        {
            $result = $this->execution->checkStageStatus($executionID, 'start');
            if($result['disabled']) return $this->send(array('result' => 'fail', 'message' => $result['message'])); 
        }
        return parent::start($executionID, $from);
    }
}
