<?php
$lang->workflowaction->common            = '工作流动作';
$lang->workflowaction->browse            = '浏览动作';
$lang->workflowaction->create            = '添加动作';
$lang->workflowaction->edit              = '编辑动作';
$lang->workflowaction->view              = '动作详情';
$lang->workflowaction->delete            = '删除动作';
$lang->workflowaction->sort              = '动作排序';
$lang->workflowaction->setVerification   = '数据校验';
$lang->workflowaction->resetVerification = '全部清除';
$lang->workflowaction->setNotice         = '设置提醒';
$lang->workflowaction->setJS             = 'JS';
$lang->workflowaction->setCSS            = 'CSS';
$lang->workflowaction->settings          = '动作及属性设置';

$lang->workflowaction->id            = '编号';
$lang->workflowaction->module        = '所属流程';
$lang->workflowaction->action        = '动作代号';
$lang->workflowaction->name          = '动作名称';
$lang->workflowaction->type          = '操作数据方式';
$lang->workflowaction->batchMode     = '批量操作模式';
$lang->workflowaction->extensionType = '扩展方式';
$lang->workflowaction->open          = '打开方式';
$lang->workflowaction->position      = '显示位置';
$lang->workflowaction->show          = '显示方式';
$lang->workflowaction->order         = '显示顺序';
$lang->workflowaction->buildin       = '内置';
$lang->workflowaction->conditions    = '触发条件';
$lang->workflowaction->verifications = '数据校验';
$lang->workflowaction->hooks         = '扩展动作';
$lang->workflowaction->toList        = '抄送';
$lang->workflowaction->desc          = '描述';
$lang->workflowaction->status        = '状态';
$lang->workflowaction->createdBy     = '由谁创建';
$lang->workflowaction->createdDate   = '创建时间';
$lang->workflowaction->editdeBy      = '由谁编辑';
$lang->workflowaction->editdeDate    = '编辑时间';
$lang->workflowaction->actionBy      = '由谁%s';
$lang->workflowaction->actionDate    = '%s日期';

$lang->workflowaction->actionWidth = 165;

$lang->workflowaction->layout      = '界面';
$lang->workflowaction->setLayout   = '去设计';
$lang->workflowaction->condition   = '触发条件';
$lang->workflowaction->linkage     = '界面联动';
$lang->workflowaction->hook        = '扩展动作';
$lang->workflowaction->previewData = "<div class='example-text-holder'></div>";

$lang->workflowaction->statusList['enable']  = '启用';
$lang->workflowaction->statusList['disable'] = '停用';

$lang->workflowaction->typeList['single'] = '操作单条数据';
$lang->workflowaction->typeList['batch']  = '批量操作多条数据';

$lang->workflowaction->batchModeList['same']      = '执行相同操作';
$lang->workflowaction->batchModeList['different'] = '执行不同操作';

$lang->workflowaction->extensionTypeList['none']     = '不扩展';
$lang->workflowaction->extensionTypeList['extend']   = '扩展';

$lang->workflowaction->openList['normal'] = '普通页面';
$lang->workflowaction->openList['modal']  = '弹窗页面';
$lang->workflowaction->openList['none']   = '无页面';

$lang->workflowaction->positionList['browse']        = '列表页';
$lang->workflowaction->positionList['view']          = '详情页';
$lang->workflowaction->positionList['browseandview'] = '列表页和详情页';

$lang->workflowaction->showList['dropdownlist'] = '显示在下拉菜单中';
$lang->workflowaction->showList['direct']       = '直接显示在页面上';

$lang->workflowaction->buildinList['0'] = '否';
$lang->workflowaction->buildinList['1'] = '是';

$lang->workflowaction->default = new stdclass();
$lang->workflowaction->default->actions['browse']         = '浏览列表';
$lang->workflowaction->default->actions['create']         = '新建';
$lang->workflowaction->default->actions['batchcreate']    = '批量新建';
$lang->workflowaction->default->actions['batchedit']      = '批量编辑';
$lang->workflowaction->default->actions['batchassign']    = '批量指派';
$lang->workflowaction->default->actions['edit']           = '编辑';
$lang->workflowaction->default->actions['assign']         = '指派';
$lang->workflowaction->default->actions['view']           = '详情';
$lang->workflowaction->default->actions['delete']         = '删除';
$lang->workflowaction->default->actions['link']           = '关联数据';
$lang->workflowaction->default->actions['unlink']         = '移除数据';
$lang->workflowaction->default->actions['export']         = '导出数据';
$lang->workflowaction->default->actions['exporttemplate'] = '下载模板';
$lang->workflowaction->default->actions['import']         = '导入数据';
$lang->workflowaction->default->actions['showimport']     = '导入确认';
$lang->workflowaction->default->actions['report']         = '报表';

$lang->workflowaction->approval = new stdclass();
$lang->workflowaction->approval->actions['approvalsubmit'] = '发起';
$lang->workflowaction->approval->actions['approvalcancel'] = '撤回';
$lang->workflowaction->approval->actions['approvalreview'] = '审批';

$lang->workflowaction->tips = new stdclass();
$lang->workflowaction->tips->buildin     = '内置动作不支持预览。';
$lang->workflowaction->tips->emptyLayout = '当前动作未设计页面，无法预览。';
$lang->workflowaction->tips->noLayout    = '当前动作不支持预览。';
$lang->workflowaction->tips->position    = '列表页会显示在列表操作列，详情页会显示在底部的操作按钮组。';
$lang->workflowaction->tips->show        = '如果动作操作比较多可以把相对不常用的放到下拉菜单，反之直接显示在列表页面的操作列';

$lang->workflowaction->placeholder = new stdclass();
$lang->workflowaction->placeholder->code = '只能包含英文字母';

$lang->workflowaction->error = new stdclass();
$lang->workflowaction->error->emptyName   = '请双击控件设置动作名称';
$lang->workflowaction->error->emptyCode   = '请双击控件设置动作代号';
$lang->workflowaction->error->existCode   = '动作代号已经有 %s 这条记录了。';
$lang->workflowaction->error->wrongCode   = '<strong> %s </strong>只能包含英文字母。';
$lang->workflowaction->error->builtinCode = '不能使用内置动作代号 %s。';
$lang->workflowaction->error->conflict    = '<strong> %s </strong>与系统语言冲突。';

/* Verification */
$lang->workflowverification = new stdclass();
$lang->workflowverification->common   = '数据校验';
$lang->workflowverification->type     = '校验类型';
$lang->workflowverification->result   = '校验结果';
$lang->workflowverification->field    = '字段';
$lang->workflowverification->sql      = 'SQL';
$lang->workflowverification->varName  = '变量名';
$lang->workflowverification->varValue = '变量值';
$lang->workflowverification->message  = '提示信息';

$lang->workflowverification->typeList['no']   = '不进行校验';
$lang->workflowverification->typeList['data'] = '以数据作为校验方式';
$lang->workflowverification->typeList['sql']  = '以SQL语句作为校验方式';

$lang->workflowverification->resultList['empty']    = '查询结果为空或0时通过校验';
$lang->workflowverification->resultList['notempty'] = '查询结果不为空和0时通过校验';

$lang->workflowverification->logicalOperatorList['and'] = '并且';
$lang->workflowverification->logicalOperatorList['or']  = '或者';

$lang->workflowverification->placeholder = new stdclass();
$lang->workflowverification->placeholder->sql     = '直接写入一条SQL查询语句，只能进行查询操作。';
$lang->workflowverification->placeholder->message = '校验不通过时显示的提示信息';
