<?php
/**
 * The tao file of metriclib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easysoft.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
class metriclibTao extends metriclibModel
{
    /**
     * 根据范围和时间周期获取度量项编码。
     * Fetch metric pairs.
     *
     * @param  string      $scope
     * @param  string      $period
     * @param  array|null  $codeList
     * @param  string|bool $builtin
     * @param  int|string  $limit
     * @param  string      $nameKey
     * @access protected
     * @return array
     */
    protected function fetchMetricPairs($scope, $period, $codeList = array(), $builtin = 'all', $limit = 10, $nameKey = 'name')
    {
        $this->app->loadLang('metric');
        $avaliableObjects = array_keys($this->lang->metric->objectList);
        $metrics = $this->dao->select("code,name,IF(alias IS NULL or alias = '', name, alias) as alias, unit")->from(TABLE_METRIC)
            ->where('deleted')->eq('0')
            ->andWhere('scope')->eq($scope)
            ->andWhere('object')->in($avaliableObjects)
            ->andWhere('dateType')->eq($period)
            ->andWhere('stage')->eq('released')
            ->beginIF(!empty(array_filter((array)$codeList)))->andWhere('code')->in($codeList)->fi()
            ->beginIF($builtin != 'all')->andWhere('builtin')->eq((int)$builtin)->fi()
            ->beginIF($this->config->edition == 'open')->andWhere('object')->notIN('feedback,issue,risk')->fi()
            ->beginIF($this->config->edition == 'biz')->andWhere('object')->notIN('issue,risk')->fi()
            ->orderBy('id_desc')
            ->beginIF(is_numeric($limit))->limit($limit)->fi()
            ->fetchAll();

        $metricPairs = array();
        $unitList = $this->lang->metric->unitList;
        foreach($metrics as $metric)
        {
            $key   = $metric->code;
            $unit  = zget($unitList, $metric->unit, $metric->unit);
            if(!empty($unit)) $unit = " ($unit) ";
            $value = $nameKey == 'aliasUnit' ? $metric->alias . $unit : $metric->$nameKey;
            $metricPairs[$key] = $value;
        }

        if(empty($codeList)) return $metricPairs;

        uksort($metricPairs, function($a, $b) use ($codeList)
        {
            $aIndex = array_search($a, $codeList);
            $bIndex = array_search($b, $codeList);
            return $aIndex - $bIndex;
        });

        return $metricPairs;
    }

    /**
     * 根据度量项编码请求度量记录。
     * Fetch metric record.
     *
     * @param  array  $codes
     * @param  string $scope
     * @param  string $period
     * @param  array  $parentIdList
     * @param  array  $objectList
     * @param  string $dateBegin
     * @param  string $dateEnd
     * @param  object $pager
     * @access protected
     * @return array
     * @param mixed[] $dateList
     */
    protected function fetchMetricRecordByScopeAndDate($codes, $scope, $period, $parentIdList = array(), $objectList = array(), $dateList = array(), $dateBegin = '', $dateEnd = '', $pager = null)
    {
        if(empty($codes)) return array();

        $parentIdList = array_filter($parentIdList);
        $objectList   = array_filter($objectList);

        $hasScopeFilter = !empty($parentIdList) || !empty($objectList);
        $hasDateFilter  = !empty($dateBegin) && !empty($dateEnd);

        if(empty($objectList) && !empty($parentIdList))
        {
            $objectPairs = $this->fetchObjectPairsByParent($scope, $parentIdList);
            $objectList  = array_keys($objectPairs);
        }

        $scopeList  = array();
        $scopeOrder = 'desc';
        if($scope != 'system')
        {
            $scopePairs = $this->loadModel('metric')->getPairsByScope($scope);

            if($scope == 'user')
            {
                ksort($scopePairs);
                $scopeOrder = "asc";
            }
            else
            {
                krsort($scopePairs);
                $scopeOrder = "desc";
            }

            $scopeList   = array_keys($scopePairs);
            if($hasScopeFilter) $scopeList = array_intersect($scopeList, $objectList);
        }

        $begin     = null;
        $end       = null;
        $weekRange = null;
        if($hasDateFilter)
        {
            $begin = $this->parseDateFilter($dateBegin, $period, 'begin');
            $end   = $this->parseDateFilter($dateEnd, $period, 'end');
            if($period == 'week') $weekRange = $this->getRangeOfYearWeek($begin, $end);
        }

        $filterMonthField = "CONCAT(year, '-', month, '-', '01')";
        $monthField = "CONCAT(year, '-', month)";
        $weekField  = "CONCAT(year, '-', week)";
        $dayField   = "CONCAT(year, '-', month, '-', day)";

        $records = array();
        foreach($codes as $code)
        {
            if($pager) $originalPageID = $pager->pageID;

            $stmt = $this->dao->select("`$scope`, `year`, `month`, `week`, `day`, `date`, `metricCode`, `value`")
                ->from(TABLE_METRICLIB)
                ->where('metricCode')->eq($code)
                ->beginIF($scope != 'system')->andWhere("`$scope`")->in($scopeList)->fi()

                ->beginIF($hasDateFilter && $period == 'year')->andWhere('year')->ge($begin)->andWhere('year')->le($end)->fi()
                ->beginIF($hasDateFilter && $period == 'month')->andWhere($filterMonthField)->ge($begin)->andWhere($filterMonthField)->le($end)->fi()
                ->beginIF($hasDateFilter && $period == 'day')->andWhere($dayField)->ge($begin)->andWhere($dayField)->le($end)->fi()
                ->beginIF($hasDateFilter && $period == 'nodate')->andWhere('date')->ge($begin)->andWhere('date')->le($end)->fi()
                ->beginIF($hasDateFilter && $period == 'week')->andWhere($weekField)->in($weekRange)->fi()

                ->beginIF(!empty($dateList) && $period == 'year')->andWhere('year')->in($dateList)->fi()
                ->beginIF(!empty($dateList) && $period == 'month')->andWhere($monthField)->in($dateList)->fi()
                ->beginIF(!empty($dateList) && $period == 'week')->andWhere($weekField)->in($dateList)->fi()
                ->beginIF(!empty($dateList) && $period == 'day')->andWhere($dayField)->in($dateList)->fi()
                ->beginIF(!empty($dateList) && $period == 'nodate')->andWhere('DATE(date)')->in($dateList)->fi()

                ->beginIF($period == 'nodate')->orderBy("`date` desc, `$scope` $scopeOrder")->fi()
                ->beginIF($period == 'year')->orderBy("`year` desc, `$scope` $scopeOrder")->fi()
                ->beginIF($period == 'month')->orderBy("`year` desc, `month` desc, `$scope` $scopeOrder")->fi()
                ->beginIF($period == 'week')->orderBy("`year` desc, `week` desc, `$scope` $scopeOrder")->fi()
                ->beginIF($period == 'day')->orderBy("`year` desc, `month` desc, `day` desc, `$scope` $scopeOrder")->fi()
                ->page($pager);

            if($pager && $originalPageID != $pager->pageID) $pager->pageID = $originalPageID;

            $codeRecords = $stmt->fetchAll();
            $records = array_merge($records, $codeRecords);
        }

        return $records;
    }

    /**
     * 获取最新的度量记录。
     * Fetch latest records.
     *
     * @param  string $scope
     * @param  object $pager
     * @access protected
     * @return array
     * @param mixed[] $codes
     */
    protected function fetchLatestMetricRecords($scope, $codes, $pager)
    {
        $codes = array_filter($codes);
        $stmt = $this->dao->select("t2.`code`,t2.name,IF(t2.alias IS NULL or t2.alias = '', t2.name, t2.alias) as alias,t1.`value`,t1.`date`")
            ->from(TABLE_METRICLIB)->alias('t1')
            ->leftJoin(TABLE_METRIC)->alias('t2')->on('t1.metricCode = t2.code')
            ->where('t2.scope')->eq($scope)
            ->andWhere('DATE(t2.lastCalcTime) = DATE(t1.date)')
            ->beginIF(!empty($codes))->andWhere('t2.`code`')->in($codes)->fi()
            ->orderBy('t1.id desc')
            ->page($pager);

        return $stmt->fetchAll();
    }

    /**
     * Fetch object pairs by parent scope.
     *
     * @param  string $scope
     * @param  string $parentIdList
     * @access protected
     * @return array
     */
    protected function fetchObjectPairsByParent($scope, $parentIdList)
    {
        $id   = $scope == 'user' ? 'account'  : 'id';
        $name = $scope == 'user' ? 'realname' : 'name';

        $table       = $this->config->objectTables[$scope];
        $parentScope = $this->config->metriclib->parentScope[$scope];
        $field       = $scope == 'project' ? 'parent' : $parentScope;

        return $this->dao->select("$id, $name")->from($table)
           ->beginIF(!empty($parentIdList))->where("`$field`")->in($parentIdList)->fi()
           ->beginIF($scope == 'project')->andWhere('type')->eq('project')->fi()
           ->fetchPairs();
    }

    /**
     * Parse date filter according period.
     *
     * @param  string $date
     * @param  string $period
     * @param  string $filterType begin|end
     * @access protected
     * @return string
     */
    protected function parseDateFilter($date, $period, $filterType)
    {
        $timeStamp = strtotime($date);
        if($period == 'year')  return date('Y', $timeStamp);
        if($period == 'month') return date('Y-m-01', $timeStamp);
        if($period == 'week')  return date('Y-W', $timeStamp);
        if($period == 'nodate')
        {
            if($filterType == 'begin') return date('Y-m-d 00:00:00', $timeStamp);
            if($filterType == 'end')   return date('Y-m-d 23:59:59', $timeStamp);
        }
        return $date;
    }

    /**
     * Parse date according period.
     *
     * @param  string $date
     * @param  string $period
     * @access protected
     * @return string
     */
    protected function parseDate($date, $period)
    {
        $timeStamp = strtotime($date);

        if($period == 'year')   return date('Y', $timeStamp);
        if($period == 'month')  return date('Y-m', $timeStamp);
        if($period == 'week')   return date('Y-W', $timeStamp);
        return date('Y-m-d', $timeStamp);
    }

    /**
     * Get range list of year-week string.
     *
     * @param  string $begin
     * @param  string $end
     * @access protected
     * @return array
     */
    protected function getRangeOfYearWeek($begin, $end)
    {
        list($startYear, $startWeek) = explode('-', $begin);
        $startYear = intval($startYear);
        $startWeek = intval($startWeek);

        list($endYear, $endWeek) = explode('-', $end);
        $endYear = intval($endYear);
        $endWeek = intval($endWeek);

        $range = array();
        for($year = $startYear; $year <= $endYear; $year++)
        {
            $firstWeek = ($year === $startYear) ? $startWeek : 1;
            $lastWeek  = ($year === $endYear)   ? $endWeek   : 52;

            for($week = $firstWeek; $week <= $lastWeek; $week++)
            {
                $weekString = sprintf('%04d-%02d', $year, $week);
                $range[] = $weekString;
            }
        }

        return $range;
    }
}
