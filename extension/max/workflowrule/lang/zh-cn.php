<?php
$lang->workflowrule->common = '工作流验证规则';
$lang->workflowrule->browse = '浏览规则';
$lang->workflowrule->create = '添加规则';
$lang->workflowrule->edit   = '编辑规则';
$lang->workflowrule->view   = '规则详情';
$lang->workflowrule->delete = '删除规则';

$lang->workflowrule->id          = '编号';
$lang->workflowrule->type        = '类别';
$lang->workflowrule->name        = '名称';
$lang->workflowrule->rule        = '规则';
$lang->workflowrule->createdBy   = '由谁创建';
$lang->workflowrule->createdDate = '创建时间';
$lang->workflowrule->editedBy    = '由谁编辑';
$lang->workflowrule->editedDate  = '编辑时间';

$lang->workflowrule->typeList['system'] = '系统函数';
$lang->workflowrule->typeList['regex']  = '正则表达式';
$lang->workflowrule->typeList['func']   = '函数';

$lang->workflowrule->default = new stdclass();
$lang->workflowrule->default->rules['notempty'] = '必填';
$lang->workflowrule->default->rules['date']     = '日期';
$lang->workflowrule->default->rules['email']    = 'email';
$lang->workflowrule->default->rules['float']    = '数字';
$lang->workflowrule->default->rules['phone']    = '电话';
$lang->workflowrule->default->rules['ip']       = 'IP';

$lang->workflowrule->error = new stdclass();
$lang->workflowrule->error->wrongRegex = '正则表达式有错！错误：';

$lang->workflowrule->placeholder = new stdclass();
$lang->workflowrule->placeholder->regex = '正则表达式需要包含定界符';
