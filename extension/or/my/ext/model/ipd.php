<?php
/**
 * 构建调研任务搜索表单。
 * Build task search form.
 *
 * @param  int    $queryID
 * @param  string $actionURL
 * @access public
 * @return void
 */
public function buildTaskSearchForm($queryID, $actionURL)
{
    $this->loadExtension('ipd')->buildTaskSearchForm($queryID, $actionURL);
}

/**
 * 构建用户需求搜索表单。
 * Build Requirement search form.
 *
 * @param  int    $queryID
 * @param  string $actionURL
 * @access public
 * @return void
 */
public function buildRequirementSearchForm($queryID, $actionURL)
{
    $this->loadExtension('ipd')->buildRequirementSearchForm($queryID, $actionURL);
    parent::buildRequirementSearchForm($queryID, $actionURL);
}

/**
 * 构建需求池需求搜索表单
 * Build demand search form.
 *
 * @param  int    $queryID
 * @param  string $actionURL
 * @access public
 * @return void
 */
public function buildDemandSearchForm($queryID, $actionURL)
{
    $this->loadExtension('ipd')->buildDemandSearchForm($queryID, $actionURL);
}

/**
 * 根据查询条件搜索需求池需求
 * Get demands by search.
 *
 * @param  string $account
 * @param  int    $queryID
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getDemandsBySearch($account, $queryID = 0, $orderBy = 'id_desc', $pager = null)
{
    return $this->loadExtension('ipd')->getDemandsBySearch($account, $queryID, $orderBy, $pager);
}

/**
 * 获取由我指派的对象。
 * Get assigned by me objects.
 *
 * @param  string $account
 * @param object|null $pager
 * @param  string $orderBy
 * @access public
 * @return array
 */
public function getDemandAssignedByMe($account, $pager = null, $orderBy = 'id_desc')
{
    return $this->loadExtension('ipd')->getDemandAssignedByMe($account, $pager, $orderBy);
}
