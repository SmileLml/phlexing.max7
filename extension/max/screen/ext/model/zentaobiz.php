<?php
/**
 * @param int $dimensionID
 */
public function getList($dimensionID)
{
    return $this->loadExtension('zentaobiz')->getList($dimensionID);
}

public function create($dimensionID)
{
    return $this->loadExtension('zentaobiz')->create($dimensionID);
}

public function update($screenID)
{
    return $this->loadExtension('zentaobiz')->update($screenID);
}

public function publish($screenID)
{
    return $this->loadExtension('zentaobiz')->publish($screenID);
}

public function getTreeData($dimensionID)
{
    return $this->loadExtension('zentaobiz')->getTreeData($dimensionID);
}

public function getDimensionPairs()
{
    return $this->loadExtension('zentaobiz')->getDimensionPairs();
}

public function getTreeSelectOptions()
{
    return $this->loadExtension('zentaobiz')->getTreeSelectOptions();
}
public function checkIFChartInUse($chartID, $type = 'chart')
{
        return $this->loadExtension('zentaobiz')->checkIFChartInUse($chartID, $type);
}

public function saveThumbnail($screenID)
{
        return $this->loadExtension('zentaobiz')->saveThumbnail($screenID);
}
