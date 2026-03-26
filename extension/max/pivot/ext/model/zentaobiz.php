<?php
public function getList($dimensionID = 0, $groupID = 0, $orderBy = 'id_desc', $pager = null)
{
    return $this->loadExtension('zentaobiz')->getList($dimensionID, $groupID, $orderBy, $pager);
}

public function getPivotFieldList($pivotID)
{
    return $this->loadExtension('zentaobiz')->getPivotFieldList($pivotID);
}

public function create($dimensionID)
{
    return $this->loadExtension('zentaobiz')->create($dimensionID);
}

public function querySave($pivotID)
{
    return $this->loadExtension('zentaobiz')->querySave($pivotID);
}

public function edit($pivotID)
{
    return $this->loadExtension('zentaobiz')->edit($pivotID);
}

public function update($pivotID, $pivotState)
{
    return $this->loadExtension('zentaobiz')->update($pivotID, $pivotState);
}

public function processNameAndDesc($data)
{
    return $this->loadExtension('zentaobiz')->processNameAndDesc($data);
}

public function processGroup($pivots, $groups)
{
    return $this->loadExtension('zentaobiz')->processGroup($pivots, $groups);
}

public function getCommonColumn($fieldSettings, $langs)
{
    return $this->loadExtension('zentaobiz')->getCommonColumn($fieldSettings, $langs);
}

public function autoGenWhereSQL($objectTable, $sql)
{
    return $this->loadExtension('zentaobiz')->autoGenWhereSQL($objectTable, $sql);
}

public function autoGenReferSQL($objectTable)
{
    return $this->loadExtension('zentaobiz')->autoGenReferSQL($objectTable);
}

public function getDrillFieldList($drillObject)
{
    return $this->loadExtension('zentaobiz')->getDrillFieldList($drillObject);
}

public function getFieldList($pivotState, $modalIndex = null)
{
    return $this->loadExtension('zentaobiz')->getFieldList($pivotState, $modalIndex);
}

/**
 * @param mixed[] $conditions
 */
public function prepareConditionSql($conditions)
{
    return $this->loadExtension('zentaobiz')->prepareConditionSql($drill);
}

public function clearAutoDrills($drills)
{
    return $this->loadExtension('zentaobiz')->clearAutoDrills($drills);
}

public function autoGenDrillSettings($pivotState)
{
    return $this->loadExtension('zentaobiz')->autoGenDrillSettings($pivotState);
}

public function autoGenDrillConditions($object, $pivotState)
{
    return $this->loadExtension('zentaobiz')->autoGenDrillConditions($object, $pivotState);
}

public function updateDrills($pivotID, $drills, $status = 'published')
{
    return $this->loadExtension('zentaobiz')->updateDrills($pivotID, $drills, $status);
}

public function initDesignDrill($pivotID)
{
    return $this->loadExtension('zentaobiz')->initDesignDrill($pivotID);
}

public function initDesignFilters($pivotID)
{
    return $this->loadExtension('zentaobiz')->initDesignFilters($pivotID);
}

/**
 * @param int $pivotID
 * @param mixed[] $designingFilters
 */
public function updateDesignFilters($pivotID, $designingFilters)
{
    return $this->loadExtension('zentaobiz')->updateDesignFilters($pivotID, $designingFilters);
}

/**
 * @param string $table
 */
public function getTableDescList($table)
{
    return $this->loadExtension('zentaobiz')->getTableDescList($table);
}

public function filterPivotSpecialChars($pivotState)
{
    return $this->loadExtension('zentaobiz')->filterPivotSpecialChars($pivotState);
}
