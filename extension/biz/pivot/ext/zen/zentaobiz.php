<?php
public function initAction($pivots)
{
    return $this->loadExtension('zentaobiz')->initAction($pivots);
}

public function isPublishAction($post)
{
    return $this->loadExtension('zentaobiz')->isPublishAction($post);
}

public function isEnterDesignAction($post)
{
    return $this->loadExtension('zentaobiz')->isEnterDesignAction($post);
}

public function getDrillsByPivotID($pivotID, $status = 'published')
{
    return $this->loadExtension('zentaobiz')->getDrillsByPivotID($pivotID, $status);
}

public function getFiltersByPivotID($pivotID, $status = 'published')
{
    return $this->loadExtension('zentaobiz')->getFiltersByPivotID($pivotID, $status);
}

public function getDesignAction()
{
    return $this->loadExtension('zentaobiz')->getDesignAction();
}

public function getCacheFilePath($id)
{
    return $this->loadExtension('zentaobiz')->getCacheFilePath($id);
}

public function getCache($id)
{
    return $this->loadExtension('zentaobiz')->getCache($id);
}

public function setCache()
{
    return $this->loadExtension('zentaobiz')->setCache();
}

public function clearCache($id)
{
    return $this->loadExtension('zentaobiz')->clearCache($id);
}

public function setCacheRoot()
{
    return $this->loadExtension('zentaobiz')->setCacheRoot();
}

public function cacheExist($id)
{
    return $this->loadExtension('zentaobiz')->cacheExist($id);
}

public function initPivotState($pivot, $fromCache = true)
{
    return $this->loadExtension('zentaobiz')->initPivotState($pivot, $fromCache);
}

public function handleDesignAction($pivot)
{
    return $this->loadExtension('zentaobiz')->handleDesignAction($pivot);
}

public function handleQueryDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleQueryDesignAction();
}

public function handleQueryFilterDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleQueryFilterDesignAction();
}

public function handleAddQueryFilterDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleAddQueryFilterDesignAction();
}

public function handleSaveQueryFilterDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleSaveQueryFilterDesignAction();
}

public function handleTableDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleTableDesignAction();
}

public function handleSettingsDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleSettingsDesignAction();
}

public function handleAddColumnDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleAddColumnDesignAction();
}

public function handleChangeObjectDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleChangeObjectDesignAction();
}

public function handleAddDrillDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleAddDrillDesignAction();
}

public function handlePreviewResultDesignAction()
{
    return $this->loadExtension('zentaobiz')->handlePreviewResultDesignAction();
}

public function preparePivotFilters($sql = '', $filters = array(), $checkSql = true)
{
    return $this->loadExtension('zentaobiz')->preparePivotFilters($sql, $filters, $checkSql);
}

public function getFilterOptions($filter, $sql = '')
{
    return $this->loadExtension('zentaobiz')->getFilterOptions($filter, $sql);
}

public function handleSaveFieldsDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleSaveFieldsDesignAction();
}

public function handleAddFilterDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleAddFilterDesignAction();
}

public function handleChangeModeDesignAction()
{
    return $this->loadExtension('zentaobiz')->handleChangeModeDesignAction();
}
