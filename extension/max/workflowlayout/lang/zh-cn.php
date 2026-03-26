<?php
$lang->workflowlayout->common  = '工作流界面';
$lang->workflowlayout->admin   = '维护界面';

$lang->workflowlayout->id           = '编号';
$lang->workflowlayout->module       = '所属流程';
$lang->workflowlayout->action       = '所属动作';
$lang->workflowlayout->field        = '字段';
$lang->workflowlayout->order        = '顺序';
$lang->workflowlayout->width        = '宽度';
$lang->workflowlayout->position     = '位置';
$lang->workflowlayout->alignment    = '对齐';
$lang->workflowlayout->readonly     = '只读';
$lang->workflowlayout->mobileShow   = '移动端';
$lang->workflowlayout->summary      = '统计信息';
$lang->workflowlayout->defaultValue = '默认值';
$lang->workflowlayout->layoutRules  = '验证规则';
$lang->workflowlayout->blockName    = '区块名称';
$lang->workflowlayout->tabName      = '标签名称';

$lang->workflowlayout->show     = '显示';
$lang->workflowlayout->hide     = '隐藏';
$lang->workflowlayout->require  = '必选';
$lang->workflowlayout->custom   = '自定义';
$lang->workflowlayout->block    = '区块设计';
$lang->workflowlayout->showName = '显示名称';
$lang->workflowlayout->addBlock = '添加区块';
$lang->workflowlayout->addTab   = '添加标签';
$lang->workflowlayout->addUI    = '建界面';
$lang->workflowlayout->editUI   = '编辑界面';
$lang->workflowlayout->deleteUI = '删除界面';
$lang->workflowlayout->design   = '设计';

$lang->workflowlayout->ui = new stdclass();
$lang->workflowlayout->ui->name      = '界面名称';
$lang->workflowlayout->ui->other     = '其他界面条件';
$lang->workflowlayout->ui->condition = '触发条件';

$lang->workflowlayout->positionList['browse']['left']   = '居左';
$lang->workflowlayout->positionList['browse']['center'] = '居中';
$lang->workflowlayout->positionList['browse']['right']  = '居右';

$lang->workflowlayout->positionList['view']['basic'] = '基本信息';
$lang->workflowlayout->positionList['view']['info']  = '详细信息';

$lang->workflowlayout->positionList['edit']['basic'] = '居右';
$lang->workflowlayout->positionList['edit']['info']  = '居左';

$lang->workflowlayout->mobileList[1] = '显示';
$lang->workflowlayout->mobileList[0] = '不显示';

$lang->workflowlayout->summaryList['sum']     = '合计值';
$lang->workflowlayout->summaryList['average'] = '平均值';
$lang->workflowlayout->summaryList['max']     = '最大值';
$lang->workflowlayout->summaryList['min']     = '最小值';

$lang->workflowlayout->default = new stdclass();
$lang->workflowlayout->default->user['currentUser'] = '当前用户';
$lang->workflowlayout->default->user['deptManager'] = '部门经理';
$lang->workflowlayout->default->dept['currentDept'] = '当前部门';
$lang->workflowlayout->default->time['currentTime'] = '当前时间';

$lang->workflowlayout->tips = new stdclass();
$lang->workflowlayout->tips->position      = '基本信息是右边侧栏位置，详细信息是左侧主栏位置';
$lang->workflowlayout->tips->confirmDelete = '您确定要删除该界面吗？删除后不可恢复';
$lang->workflowlayout->tips->duplicateUI   = '『%s』界面触发条件的『%s』字段';

$lang->workflowlayout->error = new stdclass();
$lang->workflowlayout->error->mobileShow          = '移动端列表页面最多显示5个字段';
$lang->workflowlayout->error->emptyCustomFields   = "您还没有添加字段，请前往 <a href='%s' class='alert-link'>主表设计</a> 添加字段。";
$lang->workflowlayout->error->emptyLayout         = "您还没有为动作 <strong>%s</strong> 配置界面，会影响您的正常使用，请先配置界面。<br>如果以上动作无需配置界面，请切换到<strong>高级编辑器</strong>，将动作的<strong>打开方式</strong>改为“无页面”，或者将动作的<strong>状态</strong>改为“停用”。";
$lang->workflowlayout->error->emptyConditions     = "新界面不能没有触发条件。";
$lang->workflowlayout->error->duplicateConditions = "当前的触发条件和%s存在重复的可能性。";
$lang->workflowlayout->error->contradiction       = "当前的触发条件设置有矛盾，请重新检查。";
