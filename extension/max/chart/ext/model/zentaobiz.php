<?php
public function getChartFieldList($chartID)
{
    return $this->loadExtension('zentaobiz')->getChartFieldList($chartID);
}

public function create($dimensionID)
{
    return $this->loadExtension('zentaobiz')->create($dimensionID);
}

public function edit($chartID)
{
    return $this->loadExtension('zentaobiz')->edit($chartID);
}

public function update($chartID)
{
    return $this->loadExtension('zentaobiz')->update($chartID);
}

/**
 * @return object|false
 * @param int $chartID
 */
public function getByID($chartID)
{
    return $this->loadExtension('zentaobiz')->getByID($chartID);
}

public function getList($dimensionID = 0, $groupID = 0, $orderBy = 'id_desc', $pager = null)
{
    return $this->loadExtension('zentaobiz')->getList($dimensionID, $groupID, $orderBy, $pager);
}

public function getChartData($chart)
{
    return $this->loadExtension('zentaobiz')->getChartData($chart);
}

public function getTableData($chart)
{
    return $this->loadExtension('zentaobiz')->getTableData($chart);
}

public function getFields($info)
{
    return $this->loadExtension('zentaobiz')->getFields($info);
}

public function getTableInfo($key)
{
    return $this->loadExtension('zentaobiz')->getTableInfo($key);
}

public function getSchemaFromFields($key, $table, $fields)
{
    return $this->loadExtension('zentaobiz')->getSchemaFromFields($key, $table, $fields);
}

public function settings($type, $dataset)
{
    return $this->loadExtension('zentaobiz')->settings($type, $dataset);
}

public function getData($schema, $filter, $filterValues, $group, $order, $limit = 0)
{
    return $this->loadExtension('zentaobiz')->getData($schema, $filter, $filterValues, $group, $Order, $limit);
}

/**
 * @param mixed[] $fields
 * @param string $field
 */
public function getFieldOptionsFromFields($fields, $field)
{
    return $this->loadExtension('zentaobiz')->getFieldOptionsFromFields($fields, $field);
}

public function getFieldName($fields, $field)
{
    return $this->loadExtension('zentaobiz')->getFieldName($fields, $field);
}

public function getFieldType($fields, $field)
{
    return $this->loadExtension('zentaobiz')->getFieldType($fields, $field);
}

public function genTable($schema, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genTable($schema, $settings, $rows, $usres);
}

public function genTableData($fields, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genTableData($fields, $settings, $rows, $users);
}

private function getDateBegin($date, $type)
{
    return $this->loadExtension('zentaobiz')->getDateBegin($date, $type);
}

public function genLine($schema, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genLine($schema, $settings, $rows, $users);
}

public function genLineData($fields, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genLineData($fields, $settings, $rows, $users);
}

public function genBar($schema, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genLineBar($fields, $settings, $rows, $users);
}

public function genBarData($fields, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genBarData($fields, $settings, $rows, $users);
}

public function genPieData($fields, $settings, $rows, $users)
{
    return $this->loadExtension('zentaobiz')->genPieData($fields, $settings, $rows, $users);
}

public function genTestingReport($type, $filterValues)
{
    return $this->loadExtension('zentaobiz')->genTestingReport($type, $filterValues);
}

public function setFilters($filters)
{
    return $this->loadExtension('zentaobiz')->setFilters($filters);
}

public function getDatasets()
{
    return $this->loadExtension('zentaobiz')->getDatasets();
}

public function getSQLFieldOptions($sql, $fieldSetting = array())
{
    return $this->loadExtension('zentaobiz')->getSQLFieldOptions($sql, $fieldSetting);
}

public function getDenominatorTip($goalName, $calcName)
{
    return $this->loadExtension('zentaobiz')->getDenominatorTip($goalName, $calcName);
}
