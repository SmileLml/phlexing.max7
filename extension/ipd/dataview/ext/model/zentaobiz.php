<?php
public function getByID($dataviewID)
{
    return $this->loadExtension('zentaobiz')->getByID($dataviewID);
}

public function getByView($view)
{
    return $this->loadExtension('zentaobiz')->getByView($view);
}

public function getDataviewState($dataviewID = 0)
{
    return $this->loadExtension('zentaobiz')->getDataviewState($dataviewID);
}

public function getTableName($table)
{
    return $this->loadExtension('zentaobiz')->getTableName($table);
}

public function stripVars($sql)
{
    return $this->loadExtension('zentaobiz')->stripVars($sql);
}

public function getVars($sql)
{
    return $this->loadExtension('zentaobiz')->getVars($sql);
}

public function getTableInfo($key)
{
    return $this->loadExtension('zentaobiz')->getTableInfo($key);
}

public function getDatasetInfo($id)
{
    return $this->loadExtension('zentaobiz')->getDatasetInfo($id);
}

public function getTableData($table, $type, $limit = 25, $pager = null)
{
    return $this->loadExtension('zentaobiz')->getTableData($table, $type, $limit, $pager);
}

public function printCell($data, $field, $info)
{
    return $this->loadExtension('zentaobiz')->printCell($data, $field, $info);
}

public function getFilters($dataviews)
{
    return $this->loadExtension('zentaobiz')->getFilters($dataviews);
}

public function getSysOptions($sysOptions)
{
    return $this->loadExtension('zentaobiz')->getSysOptions($sysOptions);
}

public function getList($type)
{
    return $this->loadExtension('zentaobiz')->getList($type);
}

public function create()
{
    return $this->loadExtension('zentaobiz')->create();
}

public function querySave($viewID, $state)
{
    return $this->loadExtension('zentaobiz')->querySave($viewID, $state);
}

public function createViewInDB($viewID, $viewName, $sql, $oldView = null)
{
    return $this->loadExtension('zentaobiz')->createViewInDB($viewID, $viewName, $sql, $oldView);
}

public function deleteViewInDB($viewName)
{
    return $this->loadExtension('zentaobiz')->deleteViewInDB($viewName);
}

public function existView($viewName)
{
    return $this->loadExtension('zentaobiz')->existView($viewName);
}

public function update($dataViewID)
{
    return $this->loadExtension('zentaobiz')->update($dataViewID);
}

public function checkUsed($dataview)
{
    return $this->loadExtension('zentaobiz')->checkUsed($dataview);
}

public function getVarFilters($vars)
{
    return $this->loadExtension('zentaobiz')->getVarFilters($vars);
}

public function getFields($table)
{
    return $this->loadExtension('zentaobiz')->getFields($table);
}

public function getOriginTreeMenu($selectedTable = '')
{
    return $this->loadExtension('zentaobiz')->getOriginTreeMenu($selectedTable);
}

public function buildThirdMenu($secondName, $tables, $selectedTable)
{
    return $this->loadExtension('zentaobiz')->buildThirdMenu($secondName, $tables, $selectedTable);
}

public function processTable($tables)
{
    return $this->loadExtension('zentaobiz')->processTable($tables);
}
