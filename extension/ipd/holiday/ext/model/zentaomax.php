<?php
/**
 * @param string $begin
 * @param string $end
 */
public function getWorkingDays($begin = '', $end = '')
{
    $records = $this->dao->select('*')->from(TABLE_HOLIDAY)
        ->where('type')->eq('working')
        ->andWhere('begin')->le($end)
        ->andWhere('end')->ge($begin)
        ->fetchAll('id');

    $workingDays = array();
    foreach($records as $record)
    {
        $dates = $this->getDaysBetween($record->begin, $record->end);
        $workingDays = array_merge($workingDays, $dates);
    }
    return $workingDays;
}

/**
 * @param string $begin
 * @param string $end
 */
public function getActualWorkingDays($begin, $end)
{
    if(empty($begin) or empty($end) or $begin == '0000-00-00' or $end == '0000-00-00') return array();

    $this->app->loadConfig('execution');

    $actualDays = array();
    $currentDay = $begin;

    $holidays    = $this->getHolidays($begin, $end);
    $workingDays = $this->getWorkingDays($begin, $end);
    $weekend     = isset($this->config->execution->weekend) ? $this->config->execution->weekend : 2;

    /* When the start date and end date are the same. */
    if($begin == $end)
    {
        if(in_array($begin, $workingDays))
        {
            $actualDays[] = $begin;
            return $actualDays;
        }

        if(in_array($begin, $holidays)) return $actualDays;

        $w = date('w', strtotime($begin));
        if($weekend == 2)
        {
            if($w == 0 or $w == 6) return $actualDays;
        }
        else
        {
            if($w == 0) return $actualDays;
        }

        $actualDays[] = $begin;
        return $actualDays;
    }

    for($i = 0; $currentDay < $end; $i ++)
    {
        $currentDay = date('Y-m-d', strtotime("$begin + $i days"));
        $w          = date('w', strtotime($currentDay));

        if(in_array($currentDay, $workingDays))
        {
            $actualDays[] = $currentDay;
            continue;
        }

        if(in_array($currentDay, $holidays)) continue;
        if($weekend == 2)
        {
            if($w == 0 or $w == 6) continue;
        }
        else
        {
            if($w == 0) continue;
        }
        $actualDays[] = $currentDay;
    }

    return $actualDays;
}

public function delete($id, $null = null)
{
    $holidayInformation = $this->dao->select('begin,end')->from(TABLE_HOLIDAY)->where('id')->eq($id)->fetch();

    $result = parent::delete($id, $null = null);
    if($result)
    {
        /* Update project. */
        $this->updateProgramPlanDuration($holidayInformation->begin, $holidayInformation->end);
        $this->updateProjectRealDuration($holidayInformation->begin, $holidayInformation->end);

        /* Update task. */
        $this->updateTaskPlanDuration($holidayInformation->begin, $holidayInformation->end);
        $this->updateTaskRealDuration($holidayInformation->begin, $holidayInformation->end);
    }

    return $result;
}
