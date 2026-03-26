<?php

/**
 * The model file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      qixinzhi <qixinzhi@easycorp.ltd>
 * @package     metriclib
 * @link        http://www.zentao.net
 */
class metriclibModel extends model
{
    /**
     * 根据范围和时间周期获取度量项编码。
     * Get metric list.
     *
     * @param  string      $scope
     * @param  string      $period
     * @param  array|null  $codeList
     * @param  string|bool $builtin
     * @param  int|string  $limit
     * @access public
     * @return array
     */
    public function getMetricPairs($scope, $period, $codeList = array(), $builtin = 'all', $limit = 10)
    {
        return $this->metriclibTao->fetchMetricPairs($scope, $period, $codeList, $builtin, $limit);
    }

    /**
     * 获取度量库名称。
     * Get metric library name.
     *
     * @param  string $scope
     * @param  string $period
     * @access public
     * @return string
     */
    public function getMetricLibName($scope, $period)
    {
        $metriclibLang = $this->lang->metriclib;
        $scopeText     = $metriclibLang->scopeList[$scope];
        $periodText    = $metriclibLang->periodTextList[$period];

        return sprintf($metriclibLang->libraryName, $scopeText, $periodText);
    }

    /**
     * 初始化表格。
     * Init table.
     *
     * @param  string $scope
     * @param  string $period
     * @param  array  $filters
     * @param  array  $defaultMetricPairs
     * @param  object $pager
     * @access public
     * @return array
     */
    public function initTable($scope, $period, $filters = array(), $pager = null)
    {
        $metricList = array_filter($filters['metric']);

        $builtinMetrics = $this->metriclibTao->fetchMetricPairs($scope, $period, $metricList, 1, 'unlimited', 'aliasUnit');
        $customMetrics  = $this->metriclibTao->fetchMetricPairs($scope, $period, $metricList, 0, 'unlimited', 'aliasUnit');
        $allMetrics     = array_merge($builtinMetrics, $customMetrics);

        $orderMetrics = array();
        foreach($metricList as $metricCode) $orderMetrics[$metricCode] = $allMetrics[$metricCode];

        $headers = $this->getTableHeader($orderMetrics, $scope, $period);
        $data    = $this->getTableData($builtinMetrics, $customMetrics, $scope, $period, $filters, $pager);

        return array($headers, $data);
    }

    /**
     * 初始化最新数据表格。
     * Init latest table.
     *
     * @param  string $scope
     * @param  object $pager
     * @access public
     * @return array
     * @param mixed[] $codes
     */
    public function initLatestTable($scope, $codes, $pager = null)
    {
        $headers = array();
        $headers[] = array('name' => 'alias', 'title' => '');
        $headers[] = array('name' => 'value', 'title' => '');
        $data = $this->metriclibTao->fetchLatestMetricRecords($scope, $codes, $pager);

        return array($headers, $data);
    }

    /**
     * 获取度量记录的最新日期。
     * Get the latest date of metric records.
     *
     * @param  array $records
     * @access public
     * @return string
     */
    public function getLatestDate($records)
    {
        if(empty($records)) return false;
        return $records[0]->date;
    }

    /**
     * 获取表头。
     * Get table header.
     *
     * @param  array  $metrics
     * @param  string $scope
     * @param  string $period
     * @access public
     * @return array
     */
    public function getTableHeader($metrics, $scope, $period)
    {
        $headers = array();
        if($scope != 'system')
        {
            $scopeHeader = $this->lang->metriclib->headerList->scope[$scope];
            $headers[] = array('name' => $scope, 'title' => $scopeHeader, 'type' => 'desc', 'fixed' => 'left');
        }

        $periodHeader = $this->lang->metriclib->headerList->period[$period];
        $headers[] = array('name' => $period, 'title' => $periodHeader, 'width' => 120, 'fixed' => 'left');

        foreach($metrics as $code => $name)
        {
            $headers[] = array('name' => $code, 'title' => $name, 'type' => 'text', 'width' => 88, 'align' => 'center', 'className' => 'metric-header');
        }

        return $headers;
    }

    /**
     * 获取表头高度。
     * Get height of table header.
     *
     * @param  array  $cols
     * @access public
     * @return int
     */
    public function getTableHeaderHeight($cols)
    {
        $maxLength = 0;
        foreach($cols as $col)
        {
            $length = mb_strlen($col['title']);
            if($length > $maxLength) $maxLength = $length;
        }

        $initHeight = 32;
        $increaseHeight = 24;

        // if maxLength is less than or equal 5, return the init height.
        if($maxLength <= 5) return $initHeight;

        $lines = floor($maxLength / 5);
        $lines = $lines == 0 ? 1 : $lines;
        return $initHeight + $increaseHeight * ($lines - 1);
    }

    /**
     * 获取表格数据。
     * Get table data.
     *
     * @param  array  $metrics
     * @param  string $scope
     * @param  string $period
     * @param  array  $filters
     * @param  object $pager
     * @access public
     * @return array
     * @param mixed[] $builtinMetrics
     * @param mixed[] $customMetrics
     */
    public function getTableData($builtinMetrics, $customMetrics,  $scope, $period, $filters, $pager = null)
    {
        $parentIdList = isset($filters['parent'])     ? $filters['parent']     : array();
        $objectList   = isset($filters['objectType']) ? $filters['objectType'] : array();
        $dateBegin    = isset($filters['dateBegin'])  ? $filters['dateBegin']  : '';
        $dateEnd      = isset($filters['dateEnd'])    ? $filters['dateEnd']    : '';

        $builtinCodes = array_keys($builtinMetrics);
        $builtinRecords = $this->fetchMetricRecordByScopeAndDate($builtinCodes, $scope, $period, $parentIdList, $objectList, array(), $dateBegin, $dateEnd, $pager);

        $metricInfoList = array();
        $metricHaveCalc = array();

        $metricList = $this->loadModel('metric')->getMetricsByCodeList(array_keys($builtinMetrics));
        foreach($metricList as $metricInfo) $metricInfoList[$metricInfo->code] = $metricInfo;
        foreach($metricList as $metricInfo) $metricHaveCalc[$metricInfo->code] = $metricInfo->lastCalcTime ? true : false;

        $groupedRecords = array();
        $scopePairs = $scope == 'system' ? array() : $this->loadModel('metric')->getPairsByScope($scope);
        foreach($builtinRecords as $record)
        {
            $key = $this->generateGroupKey($scope, $period, $record);

            if(!isset($groupedRecords[$key]))
            {
                $groupedRecords[$key] = array();
                $groupedRecords[$key] = $this->addScopeValue($groupedRecords[$key], $scope, $record, $scopePairs);
                $groupedRecords[$key] = $this->addDateValue($groupedRecords[$key], $period, $record);
                $groupedRecords[$key]['scope'] = $record->$scope;

                foreach($customMetrics as $code => $name)  $groupedRecords[$key][$code] = $this->lang->metriclib->null;
                foreach($builtinMetrics as $code => $name) $groupedRecords[$key][$code] = $metricHaveCalc[$code] && $this->isMetricCalc($metricInfoList[$code], $period, $groupedRecords[$key][$period]) ? 0 : $this->lang->metriclib->null;
            }

            $groupedRecords[$key][$record->metricCode] = $record->value;
        }

        $scopeObjectList = array_unique(array_column($groupedRecords, 'scope'));
        $dateList        = array_unique(array_column($groupedRecords, $period));

        $customRecords = $this->fetchMetricRecordByScopeAndDate(array_keys($customMetrics), $scope, $period, $parentIdList, $scopeObjectList, $dateList, $dateBegin, $dateEnd);
        foreach($customRecords as $record)
        {
            $customKey = $this->generateGroupKey($scope, $period, $record);
            if(array_key_exists($customKey, $groupedRecords)) $groupedRecords[$customKey][$record->metricCode] = $record->value;
        }

        return array_values($groupedRecords);
    }

    /**
     * 按照范围和日期类型生成分组键。
     * Generate group key by scope and date type.
     *
     * @param  string $scope
     * @param  string $period
     * @param  object $record
     * @access private
     * @return string
     */
    private function generateGroupKey($scope, $period, $record)
    {
        $key = '';
        if($scope != 'system') $key .= $record->$scope . '|';
        if($period == 'year')   $key .= $record->year;
        if($period == 'month')  $key .= "{$record->year}-{$record->month}";
        if($period == 'week')   $key .= "{$record->year}-{$record->week}";
        if($period == 'day')    $key .= "{$record->year}-{$record->month}-{$record->day}";
        if($period == 'nodate') $key .= substr($record->date, 0, 10);

        return $key;
    }

    /**
     * 添加范围值。
     * Add scope value.
     *
     * @param  array  $group
     * @param  string $scope
     * @param  object $record
     * @param  array  $scopePairs
     * @access private
     * @return array
     */
    private function addScopeValue($group, $scope, $record, $scopePairs)
    {
        if($scope == 'system') return $group;

        $scopeValue = isset($scopePairs[$record->$scope]) ? $scopePairs[$record->$scope] : $record->$scope;
        $group[$scope] = $scopeValue;

        return $group;
    }

    /**
     * 添加日期值。
     * Add date value.
     *
     * @param  array  $group
     * @param  string $period
     * @param  object $record
     * @access private
     * @return array
     */
    private function addDateValue($group, $period, $record)
    {
        extract((array)$record);
        $this->app->loadLang('metric');

        switch($period)
        {
            case 'year':
                $group['year'] = $year;
                break;
            case 'month':
                $group['month'] = $year . '-' . $month;
                break;
            case 'week':
                $group['week'] = sprintf($this->lang->metric->weekCell, $year, $week);
                break;
            case 'day':
                $group['day'] = $year . '-' . $month . '-' . $day;
                break;
            case 'nodate':
                $group['nodate'] = substr($date, 0, 10);
                break;
        }

        return $group;
    }

    /**
     * Get default metric pairs for metric filter.
     *
     * @param  string $scope
     * @param  string $period
     * @access public
     * @return array
     */
    public function getDefaultMetricPairs($scope, $period)
    {
        $customMetricPairs = $this->getMetricPairs($scope, $period, null, false, 10);
        $builtinDefaultMetrics = $this->config->metriclib->defaultMetric[$scope][$period];

        $builtinMetricCount = $this->config->metriclib->defaultMetricCount - count($customMetricPairs);
        $builtinMetricList  = array_slice($builtinDefaultMetrics, 0, $builtinMetricCount);
        $defaultMetricPairs = array_merge(array_keys($customMetricPairs), $builtinMetricList);

        return $defaultMetricPairs;
    }

    /**
     * Get default filter of begin date and end date.
     *
     * @param  string $period
     * @access public
     * @return array
     */
    public function getDefaultDateFilter($period)
    {
        $dateEnd = date('Y-m-d');

        if($period == 'year')   $dateBegin = date('Y-m-d', strtotime('-2 years', strtotime($dateEnd)));
        if($period == 'month')  $dateBegin = date('Y-m-d', strtotime('-5 months', strtotime($dateEnd)));
        if($period == 'week')   $dateBegin = date('Y-m-d', strtotime('-3 weeks', strtotime($dateEnd)));
        if($period == 'day')    $dateBegin = $dateEnd;
        if($period == 'nodate') $dateBegin = date('Y-m-d', strtotime('-6 days', strtotime($dateEnd)));

        return array('dateBegin' => $dateBegin, 'dateEnd' => $dateEnd);
    }

    /**
     * Get options by parent scope.
     *
     * @param  string $scope
     * @param  string $parentIdList
     * @access public
     * @return array
     */
    public function getOptionsByParent($scope, $parentIdList = '')
    {
        if(empty($parentIdList))
        {
            $objectPairs = $this->loadModel('metric')->getPairsByScope($scope);
        }
        else
        {
            $objectPairs = $this->metriclibTao->fetchObjectPairsByParent($scope, $parentIdList);
        }

        $options = array();
        foreach($objectPairs as $id => $object) $options[] = array('text' => $object, 'value' => $id, 'keys' => $object);
        return $options;
    }

    /**
     * Determine whether a metric has been calculated.
     *
     * @param  object $metricInfo
     * @param  string $period
     * @param  string $periodValue
     * @access public
     * @return bool
     */
    public function isMetricCalc($metricInfo, $period, $periodValue)
    {
        if(empty($metricInfo->implementedDate)) return true;

        $parsedDate = $this->metriclibTao->parseDate($metricInfo->implementedDate, $period);
        return $periodValue >= $parsedDate;
    }
}
