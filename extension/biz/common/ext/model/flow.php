<?php
/**
 * Return a virtual license for local test.
 *
 * @static
 * @access public
 * @return object
 */
public function getLicense()
{
}

/**
 * 按照模块生成详情页的操作按钮。
 * Build operate actions menu.
 *
 * @param  object $data
 * @param  string $moduleName
 * @access public
 * @return array
 */
public function buildOperateMenu($data, $moduleName = '')
{
    return $this->loadExtension('flow')->buildOperateMenu($data, $moduleName);
}

public function mergePrimaryFlows()
{
    return $this->loadExtension('flow')->mergePrimaryFlows();
}

public function mergeFlowMenuLang()
{
    return $this->loadExtension('flow')->mergeFlowMenuLang();
}

public function setMenuForFlow($tab = '', $objectID = 0)
{
    return $this->loadExtension('flow')->setMenuForFlow($tab, $objectID);
}

public function loadCustomLang($rawModule = '', $rawMethod = '')
{
    return $this->loadExtension('flow')->loadCustomLang($rawModule, $rawMethod);
}
