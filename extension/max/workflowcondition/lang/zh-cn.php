<?php
$lang->workflowcondition->common = '工作流触发条件';
$lang->workflowcondition->browse = '浏览触发条件';
$lang->workflowcondition->create = '添加触发条件';
$lang->workflowcondition->edit   = '编辑触发条件';
$lang->workflowcondition->delete = '删除触发条件';

$lang->workflowcondition->condition = '触发条件';
$lang->workflowcondition->type      = '条件类型';
$lang->workflowcondition->result    = '条件结果';
$lang->workflowcondition->field     = '字段';
$lang->workflowcondition->sql       = 'SQL';
$lang->workflowcondition->varName   = '变量名';
$lang->workflowcondition->varValue  = '变量值';

$lang->workflowcondition->know = '知道了';
$lang->workflowcondition->tips = '设置显示当前动作的条件，例如：当采购金额＞1000时，显示“审核”动作。';

$lang->workflowcondition->typeList['data'] = '以数据作为校验方式';
$lang->workflowcondition->typeList['sql']  = '以SQL语句作为校验方式';

$lang->workflowcondition->resultList['empty']    = '查询结果为空或0时通过校验';
$lang->workflowcondition->resultList['notempty'] = '查询结果不为空和0时通过校验';

$lang->workflowcondition->logicalOperatorList['and'] = '并且';
$lang->workflowcondition->logicalOperatorList['or']  = '或者';

$lang->workflowcondition->options['currentUser'] = '当前用户';
$lang->workflowcondition->options['currentDept'] = '当前部门';
$lang->workflowcondition->options['deptManager'] = '部门经理';
$lang->workflowcondition->options['actor']       = '操作人';
$lang->workflowcondition->options['today']       = '操作日期';
$lang->workflowcondition->options['now']         = '操作时间';

$lang->workflowcondition->operatorList['equal']      = '=';
$lang->workflowcondition->operatorList['notequal']   = '!=';
$lang->workflowcondition->operatorList['gt']         = '>';
$lang->workflowcondition->operatorList['ge']         = '>=';
$lang->workflowcondition->operatorList['lt']         = '<';
$lang->workflowcondition->operatorList['le']         = '<=';
$lang->workflowcondition->operatorList['include']    = '包含';
$lang->workflowcondition->operatorList['notinclude'] = '不包含';

$lang->workflowcondition->placeholder = new stdclass();
$lang->workflowcondition->placeholder->sql = '直接写入一条SQL查询语句，只能进行查询操作。';
