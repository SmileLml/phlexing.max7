<?php
helper::importControl('execution');
class myExecution extends execution
{
    /**
     * Batch edit.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function batchEdit($executionID = 0)
    {
        if(isset($_POST['names']))
        {
            $this->app->loadLang('programplan');
            $executionGroup = $this->dao->select('id,project,`order`,begin,end')->from(TABLE_PROJECT)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('stage,sprint,kanban')
                ->andWhere('grade')->eq(1)
                ->orderBy('order_asc')
                ->fetchGroup('project', 'id');

            $executions = $this->execution->getList();

            $begins = $_POST['begins'];
            $ends   = $_POST['ends'];

            $preDate  = '';
            $nextDate = '';

            foreach($begins as $executionID => $value)
            {
                $execution = isset($executions[$executionID]) ? $executions[$executionID] : array();
                if(!empty($execution->parallel)) continue;
                $groups = $executionGroup[$execution->project];

                $preExecution  = $this->getPreviousKeyValue($groups, $executionID);
                $nextExecution = $this->getNextKeyValue($groups, $executionID);

                $preDate = $preExecution ? $preExecution->end : '';
                $preDate = isset($ends[$preExecution->id]) ? $ends[$preExecution->id] : $preDate;

                $nextDate = $nextExecution ? $nextExecution->begin : '';
                $nextDate = isset($begins[$nextExecution->id]) ? $begins[$nextExecution->id] : $nextDate;

                if($preDate == '') continue;
                if($value < $preDate) dao::$errors["begins$executionID"][] = $this->lang->programplan->error->outOfDate . ": $preDate";
                if($nextDate and $end[$executionID] > $nextDate) dao::$errors["ends$executionID"][] = $this->lang->programplan->error->lessOfDate . ": $nextDate";
            }
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        return parent::batchEdit($executionID);
    }

    /**
     * 获取关联数组中指定键值的前一个键值。
     * getPreviousKeyValue .
     *
     * @param  array      $array
     * @param  string|int $key
     * @access public
     * @return array|object
     */
    public function getPreviousKeyValue($array, $key)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        if ($index !== false && $index > 0) {
            $previousKey = $keys[$index - 1];
            return $array[$previousKey];
        }
        return null;
    }

    /**
     * 获取关联数组中指定键值的后一个键值
     * getNextKeyValue.
     *
     * @param  array       $array
     * @param  string|int  $key
     * @access public
     * @return array|object
     */
    public function getNextKeyValue($array, $key)
    {
        $keys = array_keys($array);
        $index = array_search($key, $keys);
        $totalKeys = count($keys);
        if ($index !== false && $index < $totalKeys - 1) {
            $nextKey = $keys[$index + 1];
            return $array[$nextKey];
        }
        return null;
    }
}
