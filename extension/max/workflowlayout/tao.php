<?php
/**
 * The tao file of workflowlayout module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     workflowlayout
 * @link        https://www.zentao.net
 */

class workflowlayoutTao extends workflowlayoutModel
{
    /**
     * Build UI conditions.
     *
     * @param  object $ui
     * @access public
     * @return array
     */
    public function buildUIConditions($ui)
    {
        $conditions = array();
        foreach($ui->field as $key => $field)
        {
            $param = $ui->param[$key];
            if(empty($field)) continue;
            if(empty($param)) continue;

            $condition = new stdclass();
            $condition->field    = $field;
            $condition->operator = $ui->operator[$key];
            $condition->param    = $param;

            $conditions[] = $condition;
        }
        unset($ui->field, $ui->operator, $ui->param);
        return $conditions;
    }

    /**
     * 将条件按照字段为分组
     * Grouped conditions by field.
     *
     * @param  array  $conditions
     * @access public
     * @return array
     */
    public function groupConditionsByField($conditions)
    {
        $groups = array();
        foreach($conditions as $key => $condition) $groups[$condition->field][] = $condition;

        return $groups;
    }

    /**
     * 检查两个条件是否有重复。
     * Check if two conditions are duplicate.
     *
     * @param  object  $checkCondition
     * @param  array   $conditions
     * @access public
     * @return int|bool
     */
    public function checkUICondition($checkCondition, $conditions)
    {
        foreach($conditions as $condition)
        {
            if($checkCondition->param == $condition->param) $compare = '=';
            if($checkCondition->param >  $condition->param) $compare = '>';
            if($checkCondition->param <  $condition->param) $compare = '<';
            $result = $this->config->workflowlayout->uniqueRelation[$checkCondition->operator][$condition->operator][$compare];
            if($result > 0) return $result;
        }
        return false;
    }

    /**
     * 检查对象是否匹配触发条件
     * Check if the object matches the trigger conditions.
     *
     * @param  object $data
     * @param  array  $conditionGroup
     * @access public
     * @return bool
     */
    public function isMatchConditions($data, $conditionGroup)
    {
        $this->loadModel('workflowhook');
        foreach($conditionGroup as $field => $conditions)
        {
            if(!isset($data->$field)) return false;

            foreach($conditions as $condition)
            {
                $param = $this->workflowhook->getParamRealValue($condition->param);
                if(!$this->compareValue($data->$field, $param, $condition->operator)) return false;
            }
        }
        return true;
    }

    /**
     * 比较两个值并返回结果。
     * Compare two values and return the result.
     *
     * @param  mixed   $value1
     * @param  mixed   $value2
     * @param  string  $operator
     * @access public
     * @return bool
     */
    public function compareValue($value1, $value2, $operator)
    {
        if($operator == 'equal')    return $value1 == $value2;
        if($operator == 'notequal') return $value1 != $value2;
        if($operator == 'gt')       return $value1 >  $value2;
        if($operator == 'ge')       return $value1 >= $value2;
        if($operator == 'lt')       return $value1 <  $value2;
        if($operator == 'le')       return $value1 <= $value2;
        return false;
    }

    /**
     * 获取所有已经分组的条件
     * Get all grouped conditions.
     *
     * @param  string $module
     * @param  string $action
     * @access public
     * @return array
     */
    public function getAllGroupedConditions($module, $action)
    {
        $uiList = $this->getUIList($module, $action);
        if(empty($uiList)) return array();

        $allConditions = array();
        foreach($uiList as $ui) $allConditions[$ui->id] = $this->groupConditionsByField(json_decode($ui->conditions));
        return $allConditions;
    }
}
