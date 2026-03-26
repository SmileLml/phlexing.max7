<?php
/**
 * 关闭迭代。
 * Close execution.
 *
 * @param  int       $executionID
 * @param  object    $postData
 * @access public
 * @return int|false
 */
public function close($executionID, $postData)
{
    return $this->loadExtension('zentaomax')->close($executionID, $postData);
}

/**
 * 创建迭代。
 * Create a execution.
 *
 * @param  object    $execution
 * @param  array     $postMembers
 * @access public
 * @return int|false
 */
public function create($execution, $postMembers)
{
    return $this->loadExtension('zentaomax')->create($execution, $postMembers);
}

/**
 * 更新一个迭代。
 * Update a execution.
 *
 * @param  int    $executionID
 * @param  object $postData
 * @access public
 * @return array|false
 */
public function update($executionID, $postData)
{
    return $this->loadExtension('zentaomax')->update($executionID, $postData);
}

/**
 * 变更迭代交付物配置。
 * Change execution deliverable.
 *
 * @param  int    $executionID
 * @param  object $postData
 * @access public
 * @return object
 */
public function changeExecutionDeliverable($executionID, $postData)
{
    return $this->loadExtension('zentaomax')->changeExecutionDeliverable($executionID, $postData);
}

/**
 * 检查迭代是否有已上传的交付物。
 * Check if the execution has uploaded deliverable.
 *
 * @param  object  $execution
 * @access public
 * @return bool
 */
public function hasUploadedDeliverable($execution)
{
    return $this->loadExtension('zentaomax')->hasUploadedDeliverable($execution);
}
