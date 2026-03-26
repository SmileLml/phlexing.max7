<?php
/**
 * 生成创建项目阶段视图数据。
 * Build create view data.
 *
 * @param  object $viewData
 * @access protected
 * @return void
 */
public function buildCreateView($viewData)
{
    return $this->loadExtension('zentaomax')->buildCreateView($viewData);
}

/**
 * 生成编辑阶段数据。
 * Build edit view data.
 *
 * @param  object $plan
 * @access public
 * @return void
 */
public function buildEditView($plan)
{
    return $this->loadExtension('zentaomax')->buildEditView($plan);
}
