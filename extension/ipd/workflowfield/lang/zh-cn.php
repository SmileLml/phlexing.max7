<?php
$lang->workflowfield->common         = '工作流字段';
$lang->workflowfield->browse         = '浏览字段';
$lang->workflowfield->create         = '添加字段';
$lang->workflowfield->edit           = '编辑字段';
$lang->workflowfield->quote          = '使用其他流程字段';
$lang->workflowfield->delete         = '删除字段';
$lang->workflowfield->import         = '导入字段';
$lang->workflowfield->showImport     = '导入确认';
$lang->workflowfield->exportTemplate = '下载模板';
$lang->workflowfield->sort           = '字段排序';
$lang->workflowfield->setValue       = '显示值设置';
$lang->workflowfield->setExport      = '导出设置';
$lang->workflowfield->setSearch      = '搜索设置';
$lang->workflowfield->settings       = '字段及属性设置';

$lang->workflowfield->id           = '编号';
$lang->workflowfield->module       = '所属流程';
$lang->workflowfield->field        = '代号';
$lang->workflowfield->type         = '数据类型';
$lang->workflowfield->length       = '长度';
$lang->workflowfield->name         = '名称';
$lang->workflowfield->control      = '控件';
$lang->workflowfield->group        = '来源流程';
$lang->workflowfield->expression   = '计算方式';
$lang->workflowfield->options      = '选项';
$lang->workflowfield->defaultValue = '默认值';
$lang->workflowfield->rules        = '验证规则';
$lang->workflowfield->tipInfo      = '提示信息';
$lang->workflowfield->canSetValue  = '启用显示值设置功能';
$lang->workflowfield->canExport    = '启用导出功能';
$lang->workflowfield->extendExport = '扩展导出功能';
$lang->workflowfield->canSearch    = '启用搜索功能';
$lang->workflowfield->extendSearch = '扩展搜索功能';
$lang->workflowfield->isKeyValue   = '键值';
$lang->workflowfield->order        = '顺序';
$lang->workflowfield->buildin      = '内置';
$lang->workflowfield->desc         = '描述';
$lang->workflowfield->createdBy    = '由谁创建';
$lang->workflowfield->createdDate  = '创建时间';
$lang->workflowfield->editedBy     = '由谁编辑';
$lang->workflowfield->editedDate   = '编辑时间';

$lang->workflowfield->position         = '位于';
$lang->workflowfield->datasource       = '数据源';
$lang->workflowfield->sql              = 'SQL';
$lang->workflowfield->vars             = '变量';
$lang->workflowfield->addVar           = '添加变量';
$lang->workflowfield->varName          = '变量名称';
$lang->workflowfield->showName         = '显示名称';
$lang->workflowfield->requestType      = '输入方式';
$lang->workflowfield->status           = '状态';
$lang->workflowfield->subStatus        = '子状态';
$lang->workflowfield->key              = '键';
$lang->workflowfield->value            = '值';
$lang->workflowfield->defaultSubStatus = '默认值';
$lang->workflowfield->fieldName        = '字段名称';
$lang->workflowfield->tableParent      = '主表编号';
$lang->workflowfield->template         = '字段模板';
$lang->workflowfield->detail           = '『%s』详细信息';
$lang->workflowfield->use              = '使用字段';
$lang->workflowfield->remove           = '移除字段';

$lang->workflowfield->integerDigits = '整数位数';
$lang->workflowfield->decimalDigits = '小数位数';

$lang->workflowfield->typeGroup['integer']  = '整数';
$lang->workflowfield->typeGroup['decimal']  = '小数';
$lang->workflowfield->typeGroup['date']     = '日期';
$lang->workflowfield->typeGroup['time']     = '时间';
$lang->workflowfield->typeGroup['varchar']  = '单行文本';
$lang->workflowfield->typeGroup['text']     = '多行文本';

$lang->workflowfield->controlTypeList['label']        = '标签';
$lang->workflowfield->controlTypeList['input']        = '单行文本';
$lang->workflowfield->controlTypeList['textarea']     = '多行文本';
$lang->workflowfield->controlTypeList['select']       = '单选下拉';
$lang->workflowfield->controlTypeList['multi-select'] = '多选下拉';
$lang->workflowfield->controlTypeList['radio']        = '单选按钮';
$lang->workflowfield->controlTypeList['checkbox']     = '复选框';
$lang->workflowfield->controlTypeList['richtext']     = '富文本';
$lang->workflowfield->controlTypeList['date']         = '日期';
$lang->workflowfield->controlTypeList['datetime']     = '时间';
$lang->workflowfield->controlTypeList['decimal']      = '小数';
$lang->workflowfield->controlTypeList['integer']      = '整数';
$lang->workflowfield->controlTypeList['formula']      = '公式';
$lang->workflowfield->controlTypeList['file']         = '附件';

$lang->workflowfield->optionTypeList['sql']        = '自定义SQL';
$lang->workflowfield->optionTypeList['category']   = '分类设置';
$lang->workflowfield->optionTypeList['prevModule'] = '前置流程';

$lang->workflowfield->positionList['before'] = '之前';
$lang->workflowfield->positionList['after']  = '之后';

$lang->workflowfield->exportList[1] = '可以导出';
$lang->workflowfield->exportList[0] = '不能导出';

$lang->workflowfield->searchList[1] = '可以检索';
$lang->workflowfield->searchList[0] = '不能检索';

$lang->workflowfield->keyValueList['key']   = '键';
$lang->workflowfield->keyValueList['value'] = '值';

$lang->workflowfield->buildinList['0'] = '否';
$lang->workflowfield->buildinList['1'] = '是';

$lang->workflowfield->all['program']   = '所有项目集';
$lang->workflowfield->all['product']   = '所有产品';
$lang->workflowfield->all['project']   = '所有项目';
$lang->workflowfield->all['execution'] = '所有执行';

$lang->workflowfield->default = new stdclass();
$lang->workflowfield->default->fields['id']           = '编号';
$lang->workflowfield->default->fields['parent']       = '父流程ID';
$lang->workflowfield->default->fields['program']      = '所属项目集';
$lang->workflowfield->default->fields['product']      = '所属产品';
$lang->workflowfield->default->fields['project']      = '所属项目';
$lang->workflowfield->default->fields['execution']    = '所属执行';
$lang->workflowfield->default->fields['assignedTo']   = '指派给';
$lang->workflowfield->default->fields['status']       = '状态';
$lang->workflowfield->default->fields['createdBy']    = '由谁创建';
$lang->workflowfield->default->fields['createdDate']  = '创建日期';
$lang->workflowfield->default->fields['editedBy']     = '由谁编辑';
$lang->workflowfield->default->fields['editedDate']   = '编辑日期';
$lang->workflowfield->default->fields['assignedBy']   = '由谁指派';
$lang->workflowfield->default->fields['assignedDate'] = '指派日期';
$lang->workflowfield->default->fields['mailto']       = '抄送给';
$lang->workflowfield->default->fields['deleted']      = '是否删除';

$lang->workflowfield->default->options = new stdclass();
$lang->workflowfield->default->options->deleted = array();
$lang->workflowfield->default->options->deleted['0'] = '未删除';
$lang->workflowfield->default->options->deleted['1'] = '已删除';

$lang->workflowfield->approval = new stdclass();
$lang->workflowfield->approval->fields['reviewers']     = '审批人';
$lang->workflowfield->approval->fields['reviewOpinion'] = '审批意见';
$lang->workflowfield->approval->fields['reviewResult']  = '审批结果';
$lang->workflowfield->approval->fields['reviewStatus']  = '审批状态';
$lang->workflowfield->approval->fields['approval']      = '审批流编号';

$lang->workflowfield->approval->options = new stdclass();
$lang->workflowfield->approval->options->reviewResult = array();
$lang->workflowfield->approval->options->reviewResult['pass']   = '通过';
$lang->workflowfield->approval->options->reviewResult['reject'] = '不通过';

$lang->workflowfield->approval->options->reviewStatus = array();
$lang->workflowfield->approval->options->reviewStatus['wait']      = '待审批';
$lang->workflowfield->approval->options->reviewStatus['doing']     = '审批中';
$lang->workflowfield->approval->options->reviewStatus['pass']      = '通过';
$lang->workflowfield->approval->options->reviewStatus['reject']    = '不通过';
$lang->workflowfield->approval->options->reviewStatus['reverting'] = '回退中';

/* Tips */
$lang->workflowfield->tips = new stdclass();
$lang->workflowfield->tips->keyValue           = '<strong>显示值</strong> 在其他流程调用本流程数据时生效。<br /><strong>显示值</strong> 可以有多个，多个值以空格拼接显示。<br />如果没有设置显示值，则按照 <strong>流程名称 + ID</strong> 的格式显示。';
$lang->workflowfield->tips->lengthNotice       = '该类型修改可能会造成数据丢失，请慎重使用！';
$lang->workflowfield->tips->emptyStatus        = '请先设置状态字段的选项值，再设置子状态的选项值。';
$lang->workflowfield->tips->emptySubStatus     = '<strong>%s</strong>的子状态的选项值不能为空。';
$lang->workflowfield->tips->emptyValue         = '您没有选择任何字段，无法启用显示值设置功能。确定保存此设置吗？';
$lang->workflowfield->tips->emptyExport        = '您没有为表 <strong>TABLE</strong> 选择任何字段，该表无法启用导出功能。确定保存此设置吗？';
$lang->workflowfield->tips->emptySearch        = '您没有选择任何字段，无法启用搜索功能。确定保存此设置吗？';
$lang->workflowfield->tips->number             = '最大值 MAX，最小值 MIN。';
$lang->workflowfield->tips->string             = '最多可以输入 LENGTH 个字符和标点符号。';
$lang->workflowfield->tips->syncQuoteFields    = '该字段已在『%s』中引用，修改后将同步修改。（选项、默认值、验证规则不同步）';
$lang->workflowfield->tips->syncQuoteNoOptions = "该字段已在『%s』中引用，修改后将同步修改。<span class='nosyncbox'>（默认值、验证规则不同步）</span>";
$lang->workflowfield->tips->noQuoteFields      = '其他流程没有可以引用的字段。';
$lang->workflowfield->tips->placeholder        = '如果设置了默认值，提示信息在前端将不会默认显示。';

/* Placeholder */
$lang->workflowfield->placeholder = new stdclass();
$lang->workflowfield->placeholder->code          = '只能包含英文字母';
$lang->workflowfield->placeholder->sql           = '直接写入一条SQL查询语句，只能进行查询操作，禁止其他SQL操作。查询结果是键值对。查询语句的第一个字段作为键，第二个字段作为值，其它字段会被忽略。如果只有一个字段，这个字段同时作为键和值。';
$lang->workflowfield->placeholder->defaultValue  = '多个值之间用空格或逗号隔开';
$lang->workflowfield->placeholder->optionCode    = '可以使用字母或数字';
$lang->workflowfield->placeholder->auto          = '系统自动生成';
$lang->workflowfield->placeholder->varcharLength = '默认长度255，最大长度1000，最小长度1';
$lang->workflowfield->placeholder->charLength    = '默认长度50，最大长度255，最小长度1';
$lang->workflowfield->placeholder->integerDigits = '默认位数10，最大位数12，最小位数1';
$lang->workflowfield->placeholder->decimalDigits = '默认位数2，最大位数5，最小位数1';

/* Alert */
$lang->workflowfield->alert = new stdclass();
$lang->workflowfield->alert->common                       = '警告';
$lang->workflowfield->alert->update                       = '系统检测到字段可能在以下功能中使用，无法自动更新，请检查并手动更新：';
$lang->workflowfield->alert->delete                       = '系统检测到字段可能在以下功能中使用，无法自动删除，请检查并手动删除：';
$lang->workflowfield->alert->separater                    = '，';
$lang->workflowfield->alert->confirmDelete                = '您确定要执行删除操作吗？';
$lang->workflowfield->alert->confirmDeleteHasQuote        = '删除后，其他流程中引用该字段的数据均被同步删除，操作不可逆，您确定要删除吗？';
$lang->workflowfield->alert->confirmDeleteInQuote         = '移除后，该工作流内使用此字段的配置均同步删除，操作不可逆，您确定要移除吗？';
$lang->workflowfield->alert->types['fieldExpression']     = '<strong>%s</strong>流程的<strong>%s字段的公式</strong>';
$lang->workflowfield->alert->types['conditionSql']        = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>触发条件的SQL</strong>';
$lang->workflowfield->alert->types['verificationSql']     = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>数据校验的SQL</strong>';
$lang->workflowfield->alert->types['verificationSqlVar']  = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>数据校验的SQL的%s变量</strong>';
$lang->workflowfield->alert->types['hookConditionSql']    = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的触发条件的SQL</strong>';
$lang->workflowfield->alert->types['hookConditionSqlVar'] = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的触发条件的SQL的%s变量</strong>';
$lang->workflowfield->alert->types['hookConditionField']  = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的触发条件的%s字段</strong>';
$lang->workflowfield->alert->types['hookField']           = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的%s字段</strong>';
$lang->workflowfield->alert->types['hookFieldFormula']    = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的%s字段的公式</strong>';
$lang->workflowfield->alert->types['hookWhere']           = '<strong>%s</strong>流程的<strong>%s</strong>动作的<strong>扩展动作的%s条件</strong>';

/* Error */
$lang->workflowfield->error = new stdclass();
$lang->workflowfield->error->remainFields     = '<strong> %s </strong>为系统保留关键字，请更改字段代号。';
$lang->workflowfield->error->emptyOptions     = '请输入选项的<strong>键</strong>和<strong>值</strong>。';
$lang->workflowfield->error->wrongCode        = '<strong> %s </strong>只能包含英文字母。';
$lang->workflowfield->error->longCode         = '<strong> %s </strong>长度不能超过30。';
$lang->workflowfield->error->wrongSQL         = 'SQL语句有错！错误：';
$lang->workflowfield->error->notunique        = '必须添加唯一验证';
$lang->workflowfield->error->defaultValue     = '<strong> 默认值 </strong>的长度不应该超过%s。';
$lang->workflowfield->error->textDefaultValue = 'text类型字段不能设置默认值';
$lang->workflowfield->error->duplicatedCode   = '<strong>键名</strong> %s 重复，请修改。';
$lang->workflowfield->error->duplicatedName   = '<strong>值</strong> %s 重复，请修改。';
$lang->workflowfield->error->emptyDefault     = '请选择一个<strong>默认值</strong>。';
$lang->workflowfield->error->emptyCustomField = '您还没有添加字段，会影响您的正常使用，请先添加字段。';
$lang->workflowfield->error->length           = '<strong>%s</strong>应该大于等于<strong>%s</strong>，且小于等于<strong>%s</strong>。';
$lang->workflowfield->error->digits           = '<strong>%s</strong>应该大于等于<strong>%s</strong>。';
$lang->workflowfield->error->dateFormat       = '<strong> 日期 </strong>格式错误，必须为Y-m-d。';
$lang->workflowfield->error->timeFormat       = '<strong> 时间 </strong>格式错误，必须为Y-m-d H:i。';
$lang->workflowfield->error->intSize          = '<strong> 默认值 </strong>应该大于等于%s，且小于等于%s。';
$lang->workflowfield->error->float            = '<strong> 默认值 </strong>应该为数字，可以是小数。';
$lang->workflowfield->error->existDelay       = '<strong> %s </strong>已在系统中使用，请更改字段代号。';
$lang->workflowfield->error->unique           = '『%s』已被其他流程使用。如需使用此字段，请从【使用其他流程字段】中选择。';
$lang->workflowfield->error->usedKey          = '键：『%s』已被『%s』使用，对应的值是：%s。';
$lang->workflowfield->error->usedValue        = '值：『%s』已被『%s』使用，对应的键是：%s。';

/* Formula */
$lang->workflowfield->formula = new stdclass();
$lang->workflowfield->formula->common    = '公式';
$lang->workflowfield->formula->target    = '计算对象';
$lang->workflowfield->formula->operator  = '计算符号';
$lang->workflowfield->formula->numbers   = '数字';
$lang->workflowfield->formula->clearLast = '删除';
$lang->workflowfield->formula->clearAll  = '清空';
$lang->workflowfield->formula->set       = '设置';

$lang->workflowfield->formula->functions['sum']     = '%s_%s_合计值';
$lang->workflowfield->formula->functions['average'] = '%s_%s_平均值';
$lang->workflowfield->formula->functions['max']     = '%s_%s_最大值';
$lang->workflowfield->formula->functions['min']     = '%s_%s_最小值';
$lang->workflowfield->formula->functions['count']   = '%s_%s_计数';

$lang->workflowfield->formula->error = new stdclass();
$lang->workflowfield->formula->error->empty = '请为公式设置计算方式';
$lang->workflowfield->formula->error->error = '公式存在错误，请检查后再保存';

$lang->workflowfield->excel = new stdclass();
$lang->workflowfield->excel->tips       = "1、字段名称和代号为必填项。\r\n2、“选项”的输入格式为“键,值”，键/值之间用英文逗号分隔，多组键/值之间用换行分隔。";
$lang->workflowfield->excel->defaultTip = "\r\n3、默认值请填写选项的键。";
