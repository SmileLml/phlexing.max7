<?php
$lang->execution->createRelation      = '添加任务关系';
$lang->execution->editRelation        = '维护任务关系';
$lang->execution->batchEditRelation   = '批量维护任务关系';
$lang->execution->maintainRelation    = '维护任务关系';
$lang->execution->deleteRelation      = '删除任务关系';
$lang->execution->batchDeleteRelation = '批量删除任务关系';
$lang->execution->viewRelation        = '浏览任务关系';
$lang->execution->maintain            = '维护';

$lang->execution->featureBar['relation']['all'] = '全部';

$lang->execution->relation = new stdClass();
$lang->execution->relation->typeList = array();
$lang->execution->relation->typeList['FS'] = '完成开始（FS）';
$lang->execution->relation->typeList['FF'] = '完成完成（FF）';
$lang->execution->relation->typeList['SF'] = '开始完成（SF）';
$lang->execution->relation->typeList['SS'] = '开始开始（SS）';

$lang->execution->relation->typeHintList = array();
$lang->execution->relation->typeHintList['FS'] = '前置任务计划完成不能晚于后置任务计划开始';
$lang->execution->relation->typeHintList['FF'] = '前置任务计划完成不能晚于后置任务计划完成';
$lang->execution->relation->typeHintList['SF'] = '前置任务计划开始不能晚于后置任务计划完成';
$lang->execution->relation->typeHintList['SS'] = '前置任务计划开始不能晚于后置任务计划开始';

$lang->execution->ganttchart   = '甘特图';
$lang->execution->ganttSetting = '显示设置';
$lang->execution->ganttEdit    = '甘特图编辑';

$lang->execution->gantt->common    = '甘特图';
$lang->execution->gantt->id        = '编号';
$lang->execution->gantt->pretask   = '前置任务';
$lang->execution->gantt->condition = '条件动作';
$lang->execution->gantt->task      = '后置任务';
$lang->execution->gantt->action    = '动作';
$lang->execution->gantt->type      = '关系类型';

$lang->execution->gantt->createRelationOfTasks    = '创建任务关系';
$lang->execution->gantt->newCreateRelationOfTasks = '新增任务关系';
$lang->execution->gantt->editRelationOfTasks      = '维护任务关系';
$lang->execution->gantt->relationOfTasks          = '查看任务关系';
$lang->execution->gantt->relation                 = '任务关系';
$lang->execution->gantt->showCriticalPath         = '显示关键路径';
$lang->execution->gantt->hideCriticalPath         = '隐藏关键路径';
$lang->execution->gantt->fullScreen               = '全屏';

$lang->execution->gantt->zooming['day']   = '天';
$lang->execution->gantt->zooming['week']  = '周';
$lang->execution->gantt->zooming['month'] = '月';

$lang->execution->gantt->assignTo  = '指派给';
$lang->execution->gantt->duration  = '可用工日';
$lang->execution->gantt->comp      = '进度';
$lang->execution->gantt->startDate = '开始日期';
$lang->execution->gantt->endDate   = '结束日期';
$lang->execution->gantt->days      = ' 天';
$lang->execution->gantt->format    = '查看格式';

$lang->execution->gantt->preTaskStatus['']      = '';
$lang->execution->gantt->preTaskStatus['end']   = '完成后';
$lang->execution->gantt->preTaskStatus['begin'] = '开始后';

$lang->execution->gantt->taskActions[''] = '';
$lang->execution->gantt->taskActions['begin'] = '才能开始';
$lang->execution->gantt->taskActions['end']   = '才能完成';

$lang->execution->gantt->browseType['type']       = '按任务类型分组';
$lang->execution->gantt->browseType['module']     = '按模块分组';
$lang->execution->gantt->browseType['assignedTo'] = '按指派给分组';
$lang->execution->gantt->browseType['story']      = "按{$lang->SRCommon}分组";

$lang->execution->gantt->confirmDelete      = '确认要删除此任务关系吗？';
$lang->execution->gantt->confirmBatchDelete = '您确认要批量删除这些任务关系吗？';
$lang->execution->gantt->tmpNotWrite        = '不可写';

$lang->execution->gantt->showList[0] = '不显示';
$lang->execution->gantt->showList[1] = '显示';

$lang->execution->gantt->warning                 = new stdclass();
$lang->execution->gantt->warning->noEditSame     = "前置任务与后置任务不能相同！";
$lang->execution->gantt->warning->noEditRepeat   = "编号%s与任务关系【%s】重复！";
$lang->execution->gantt->warning->noEditContrary = "所选任务关系与已有任务关系之间有矛盾！";
$lang->execution->gantt->warning->noRepeat       = "编号%s任务关系已存在！";
$lang->execution->gantt->warning->noContrary     = "编号%s与任务关系【%s】之间有矛盾！";
$lang->execution->gantt->warning->noNewSame      = "新增的编号%s前后任务不能相同！";
$lang->execution->gantt->warning->noNewRepeat    = "新增的编号%s与新增的编号%s任务关系之间重复！";
$lang->execution->gantt->warning->noNewContrary  = "新增的编号%s与新增的编号%s任务关系之间有矛盾！";
$lang->execution->gantt->warning->noCreateLink   = "所选任务之间的任务关系已存在，无法重复创建！";
$lang->execution->gantt->warning->hasConflict    = "前后置任务所在的路径上存在依赖关系冲突，请重新建立任务依赖关系。";

$lang->execution->error = new stdClass();
$lang->execution->error->wrongGanttRelation       = '只能为任务创建依赖关系。';
$lang->execution->error->wrongGanttRelationSource = '您选择的第一个对象不是任务。';
$lang->execution->error->wrongGanttRelationTarget = '您选择的第二个对象不是任务。';
$lang->execution->error->parentTaskRelation       = '为了简化任务关系复杂度，父级任务不再支持任务关系。';
$lang->execution->error->preTaskIsParent          = '前置任务为父级任务，不支持建立任务关系。';
$lang->execution->error->afterTaskIsParent        = '后置任务为父级任务，不支持建立任务关系。';
$lang->execution->error->closedLoop               = '该任务关系与已有任务关系之间有矛盾。';
$lang->execution->error->multiplePreTask          = '后置任务不允许有多个前置任务。';
$lang->execution->error->wrongTaskStatus          = '已完成、已取消和已关闭的任务不能建立任务关系。';
$lang->execution->error->wrongKanbanTasks         = '看板下的任务不能建立任务关系。';

$lang->execution->ganttCustom['id']         = '编号ID';
$lang->execution->ganttCustom['branch']     = '分支';
$lang->execution->ganttCustom['assignedTo'] = '指派给';
$lang->execution->ganttCustom['progress']   = '进度';
$lang->execution->ganttCustom['begin']      = '计划开始';
$lang->execution->ganttCustom['realBegan']  = '实际开始';
$lang->execution->ganttCustom['deadline']   = '计划完成';
$lang->execution->ganttCustom['realEnd']    = '实际完成';
$lang->execution->ganttCustom['duration']   = '可用工日';
$lang->execution->ganttCustom['estimate']   = '预计工时';
$lang->execution->ganttCustom['consumed']   = '消耗工时';
$lang->execution->ganttCustom['left']       = '剩余工时';
$lang->execution->ganttCustom['delay']      = '是否延期';
$lang->execution->ganttCustom['delayDays']  = '延期天数';
$lang->execution->ganttCustom['openedBy']   = '创建者';
$lang->execution->ganttCustom['finishedBy'] = '完成者';
