<?php
$lang->marketresearch->create        = '发起调研';
$lang->marketresearch->name          = '调研名称';
$lang->marketresearch->market        = '目标市场';
$lang->marketresearch->PM            = '负责人';
$lang->marketresearch->dateRange     = '计划起止日期';
$lang->marketresearch->desc          = '调研描述';
$lang->marketresearch->acl           = '访问控制';
$lang->marketresearch->begin         = '计划开始';
$lang->marketresearch->to            = '至';
$lang->marketresearch->end           = '计划完成';
$lang->marketresearch->longTime      = '长期';
$lang->marketresearch->status        = '状态';
$lang->marketresearch->realBegan     = '实际开始';
$lang->marketresearch->realEnd       = '实际结束';
$lang->marketresearch->progress      = '进度';
$lang->marketresearch->openedBy      = '创建者';
$lang->marketresearch->mine          = '我参与的';
$lang->marketresearch->view          = '调研概况';
$lang->marketresearch->edit          = '编辑调研';
$lang->marketresearch->activate      = '激活调研';
$lang->marketresearch->start         = '开始调研';
$lang->marketresearch->close         = '结束调研';
$lang->marketresearch->teamAction    = '团队列表';
$lang->marketresearch->report        = '报告';
$lang->marketresearch->delete        = '删除调研';
$lang->marketresearch->all           = '全部调研列表';
$lang->marketresearch->browse        = '调研列表';
$lang->marketresearch->team          = '团队成员';
$lang->marketresearch->manageMembers = '团队管理';
$lang->marketresearch->unlinkMember  = '移除成员';
$lang->marketresearch->reports       = '调研报告';
$lang->marketresearch->task          = '调研任务列表';
$lang->marketresearch->createStage   = '设置阶段';
$lang->marketresearch->batchStage    = '细分阶段';
$lang->marketresearch->editStage     = '编辑阶段';
$lang->marketresearch->startStage    = '开始阶段';
$lang->marketresearch->closeStage    = '关闭阶段';
$lang->marketresearch->closedBy      = '由谁结束';
$lang->marketresearch->closedDate    = '结束日期';
$lang->marketresearch->activateStage = '激活阶段';
$lang->marketresearch->deleteStage   = '删除阶段';
$lang->marketresearch->common        = '调研';
$lang->marketresearch->whitelist     = '调研白名单';
$lang->marketresearch->execution     = '所属阶段';

$lang->researchstage = new stdclass();
$lang->researchstage->common = '调研阶段';

$lang->marketresearch->marketNotEmpty      = '『目标市场』不能为空';
$lang->marketresearch->readjustTime        = '重新调整调研的起止时间';
$lang->marketresearch->cannotGe            = '%s『%s』不能大于 %s『%s』';
$lang->marketresearch->closedReason        = '结束原因';
$lang->marketresearch->confirmDelete       = '您确定删除调研\"%s\"吗？删除后与该调研相关的任务、报告将被隐藏！';
$lang->marketresearch->stageConfirmDelete  = '您确定要删除该阶段吗？删除后，阶段下的任务将被隐藏。';
$lang->marketresearch->noMembers           = '暂时没有团队成员。';
$lang->marketresearch->confirmUnlinkMember = '您确定从该调研中移除该用户吗？';

$lang->marketresearch->createTask = '建任务';
$lang->marketresearch->planDate   = '计划日期';

$lang->marketresearch->endList[31]  = '一个月';
$lang->marketresearch->endList[93]  = '三个月';
$lang->marketresearch->endList[186] = '半年';
$lang->marketresearch->endList[365] = '一年';
$lang->marketresearch->endList[999] = '长期';

$lang->marketresearch->aclList['private'] = "私有 (只有调研负责人、团队成员可访问)";
$lang->marketresearch->aclList['open']    = "公开 (有调研视图权限即可访问)";

$lang->marketresearch->shortAclList['open']    = '公开';
$lang->marketresearch->shortAclList['private'] = '私有';

$lang->marketresearch->statusList = array();
$lang->marketresearch->statusList['wait']   = '未开始';
$lang->marketresearch->statusList['doing']  = '进行中';
$lang->marketresearch->statusList['closed'] = '已结束';

$lang->marketresearch->reasonList = array();
$lang->marketresearch->reasonList['finished']  = '完成';
$lang->marketresearch->reasonList['cancelled'] = '取消';

$lang->marketresearch->featureBar = array();
$lang->marketresearch->featureBar['all']['all']    = '全部';
$lang->marketresearch->featureBar['all']['wait']   = '未开始';
$lang->marketresearch->featureBar['all']['doing']  = '进行中';
$lang->marketresearch->featureBar['all']['closed'] = '已结束';

$lang->marketresearch->featureBar['browse'] = $lang->marketresearch->featureBar['all'];

$lang->marketresearch->featureBar['task']['all']          = '全部';
$lang->marketresearch->featureBar['task']['unclosed']     = '未关闭';
$lang->marketresearch->featureBar['task']['assignedtome'] = '指派给我';
$lang->marketresearch->featureBar['task']['myinvolved']   = '由我参与';
$lang->marketresearch->featureBar['task']['assignedbyme'] = '由我指派';
$lang->marketresearch->featureBar['task']['status']       = $lang->more;

$lang->marketresearch->featureBar['team']['all'] = '团队成员';

$lang->marketresearch->moreSelects['task']['status']['wait']         = '未开始';
$lang->marketresearch->moreSelects['task']['status']['doing']        = '进行中';
$lang->marketresearch->moreSelects['task']['status']['undone']       = '未完成';
$lang->marketresearch->moreSelects['task']['status']['finishedbyme'] = '我完成';
$lang->marketresearch->moreSelects['task']['status']['done']         = '已完成';
$lang->marketresearch->moreSelects['task']['status']['closed']       = '已关闭';
$lang->marketresearch->moreSelects['task']['status']['cancel']       = '已取消';
$lang->marketresearch->moreSelects['task']['status']['delayed']      = '已延期';

$lang->marketresearch->stageAcl['open'] = "继承{$lang->marketresearch->common}访问权限（能访问当前{$lang->marketresearch->common}，即可访问）";

$lang->marketresearch->summary    = "本页共 <strong>%s</strong> 个顶级阶段，<strong>%s</strong> 个子阶段，<strong>%s</strong> 个任务。";
$lang->marketresearch->allSummary = "本页共 <strong>{0}</strong> 个顶级阶段，<strong>{1}</strong> 个子阶段，<strong>{2}</strong> 个任务，未关闭任务<strong>{3}</strong> 个，未开始任务<strong>{4}</strong> 个，进行中任务<strong>{5}</strong> 个。";
