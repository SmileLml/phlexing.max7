<?php
public function getList($type = 'trip', $year = '', $month = '', $account = '', $dept = '', $orderBy = 'id_desc')
{
    $date     = '';
    $length   = 0;
    $position = 0;
    if($year)
    {
        $position = 1;
        $length   = 4;
        $date     = $year;
        if($month)
        {
            $length = 7;
            $date   = "$year-$month";
        }
    }
    elseif($month)
    {
        $date     = $month;
        $position = 6;
        $length   = 2;
    }

    return $this->dao->select('t1.*, t2.realname, t2.dept')
        ->from(TABLE_TRIP)->alias('t1')
        ->leftJoin(TABLE_USER)->alias('t2')->on("t1.createdBy=t2.account")
        ->where(1)
        ->beginIF($type != '')->andWhere('t1.type')->eq($type)->fi()
        ->beginIf($date)
        ->andWhere("SUBSTRING(t1.`begin`, $position, $length)", true)->eq($date)
        ->orWhere("SUBSTRING(t1.`end`, $position, $length)")->eq($date)
        ->markRight(1)
        ->fi()
        ->beginIF($account != '')->andWhere('t1.createdBy')->eq($account)->fi()
        ->beginIF($dept != '')->andWhere('t2.dept')->in($dept)->fi()
        ->orderBy("t2.dept,t1.{$orderBy}")
        ->fetchAll();
}
