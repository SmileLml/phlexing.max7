<?php
$lang->workflowdatasource->common   = '工作流数据源';
$lang->workflowdatasource->browse   = '浏览数据源';
$lang->workflowdatasource->create   = '添加数据源';
$lang->workflowdatasource->edit     = '编辑数据源';
$lang->workflowdatasource->view     = '数据源详情';
$lang->workflowdatasource->delete   = '删除数据源';
$lang->workflowdatasource->category = '维护分类';

$lang->workflowdatasource->id          = '编号';
$lang->workflowdatasource->type        = '类别';
$lang->workflowdatasource->name        = '名称';
$lang->workflowdatasource->code        = '代号';
$lang->workflowdatasource->datasource  = '数据源';
$lang->workflowdatasource->buildin     = '内置';
$lang->workflowdatasource->createdBy   = '由谁创建';
$lang->workflowdatasource->createdDate = '创建时间';
$lang->workflowdatasource->editedBy    = '由谁编辑';
$lang->workflowdatasource->editedDate  = '编辑时间';

$lang->workflowdatasource->key         = '键';
$lang->workflowdatasource->value       = '值';
$lang->workflowdatasource->app         = '所属应用';
$lang->workflowdatasource->module      = '所属模块';
$lang->workflowdatasource->method      = '方法';
$lang->workflowdatasource->desc        = '描述';
$lang->workflowdatasource->param       = '参数';
$lang->workflowdatasource->paramType   = '类型';
$lang->workflowdatasource->paramValue  = '值';
$lang->workflowdatasource->sql         = 'SQL语句';

$lang->workflowdatasource->apps['sys'] = '通用';

$lang->workflowdatasource->options['user']        = '系统用户';
$lang->workflowdatasource->options['dept']        = '系统部门';
$lang->workflowdatasource->options['deptManager'] = '部门经理';
$lang->workflowdatasource->options['actor']       = '操作人';
$lang->workflowdatasource->options['today']       = '操作日期';
$lang->workflowdatasource->options['now']         = '操作时间';
$lang->workflowdatasource->options['form']        = '表单数据';
$lang->workflowdatasource->options['record']      = '当前数据';
$lang->workflowdatasource->options['custom']      = '自定义';

$lang->workflowdatasource->typeList['option']   = '自定义选项';
$lang->workflowdatasource->typeList['sql']      = '自定义SQL';
$lang->workflowdatasource->typeList['category'] = '自定义分类';
$lang->workflowdatasource->typeList['system']   = '系统函数';
$lang->workflowdatasource->typeList['lang']     = '系统语言';

$lang->workflowdatasource->langList['orderStatus']      = '订单状态';
$lang->workflowdatasource->langList['orderReason']      = '订单关闭原因';
$lang->workflowdatasource->langList['contractType']     = '合同类型';
$lang->workflowdatasource->langList['contractDelivery'] = '合同交付状态';
$lang->workflowdatasource->langList['contractReturn']   = '合同回款状态';
$lang->workflowdatasource->langList['contractPay']      = '合同付款状态';
$lang->workflowdatasource->langList['contractStatus']   = '合同状态';
$lang->workflowdatasource->langList['customerType']     = '客户类型';
$lang->workflowdatasource->langList['customerRelation'] = '客户关系';
$lang->workflowdatasource->langList['customerSource']   = '客户来源';
$lang->workflowdatasource->langList['customerStatus']   = '客户状态';
$lang->workflowdatasource->langList['providerStatus']   = '供应商状态';
$lang->workflowdatasource->langList['contactStatus']    = '联系人状态';
$lang->workflowdatasource->langList['leadsStatus']      = '名单状态';
$lang->workflowdatasource->langList['productType']      = '产品类型';
$lang->workflowdatasource->langList['productModel']     = '产品规格';
$lang->workflowdatasource->langList['productUnit']      = '产品单位';
$lang->workflowdatasource->langList['productStatus']    = '产品状态';
$lang->workflowdatasource->langList['invoiceKind']      = '发票类别(销项/进项)';
$lang->workflowdatasource->langList['invoiceType']      = '发票类型(普票/专票)';
$lang->workflowdatasource->langList['invoiceSaleType']  = '发票销售类型(产品类/服务类)';
$lang->workflowdatasource->langList['invoiceSendway']   = '发票发送方式';
$lang->workflowdatasource->langList['invoiceStatus']    = '发票状态';
$lang->workflowdatasource->langList['feedbackPri']      = '售后优先级';
$lang->workflowdatasource->langList['feedbackType']     = '售后类型';
$lang->workflowdatasource->langList['feedbackReason']   = '售后关闭原因';
$lang->workflowdatasource->langList['feedbackStatus']   = '售后状态';
$lang->workflowdatasource->langList['public']           = '是否公开';
$lang->workflowdatasource->langList['deleted']          = '是否删除';
$lang->workflowdatasource->langList['gender']           = '性别';
$lang->workflowdatasource->langList['role']             = '角色';
$lang->workflowdatasource->langList['express']          = '快递';
$lang->workflowdatasource->langList['bank']             = '银行';

$lang->workflowdatasource->placeholder = new stdclass();
$lang->workflowdatasource->placeholder->optionCode = '可以使用字母或数字';
$lang->workflowdatasource->placeholder->sql        = '直接写入一条SQL查询语句，只能进行查询操作，禁止其他SQL操作。查询结果是键值对。查询语句的第一个字段作为键，第二个字段作为值，其它字段会被忽略。如果只有一个字段，这个字段同时作为键和值。';

$lang->workflowdatasource->error = new stdclass();;
$lang->workflowdatasource->error->emptyOptions        = '请输入选项的『键』和『值』。';
$lang->workflowdatasource->error->buildinCannotDelete = '内置数据源不允许删除。';
