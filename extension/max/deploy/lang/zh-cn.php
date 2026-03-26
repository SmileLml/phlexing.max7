<?php
$lang->deploy->common           = '上线申请';
$lang->deploy->create           = '创建上线申请';
$lang->deploy->view             = '上线申请概况';
$lang->deploy->finish           = '完成';
$lang->deploy->finishAction     = '完成上线申请';
$lang->deploy->edit             = '编辑';
$lang->deploy->editAction       = '编辑上线申请';
$lang->deploy->delete           = '删除';
$lang->deploy->deleteAction     = '删除上线申请';
$lang->deploy->deleted          = '已删除';
$lang->deploy->activate         = '取消上线';
$lang->deploy->activateAction   = '取消上线申请';
$lang->deploy->browse           = '浏览上线申请';
$lang->deploy->browseAction     = '上线申请列表';
$lang->deploy->scope            = '上线范围';
$lang->deploy->manageScope      = '管理上线范围';
$lang->deploy->cases            = '用例';
$lang->deploy->notify           = '通知';
$lang->deploy->casesAction      = '上线用例';
$lang->deploy->linkCases        = '关联用例';
$lang->deploy->unlinkCase       = '移除用例';
$lang->deploy->steps            = '上线步骤';
$lang->deploy->manageStep       = '管理上线步骤';
$lang->deploy->finishStep       = '完成上线步骤';
$lang->deploy->activateStep     = '激活上线步骤';
$lang->deploy->assignTo         = '指派';
$lang->deploy->assignAction     = '指派上线步骤';
$lang->deploy->editStep         = '编辑上线步骤';
$lang->deploy->deleteStep       = '删除上线步骤';
$lang->deploy->viewStep         = '上线步骤详情';
$lang->deploy->batchUnlinkCases = '批量移除用例';
$lang->deploy->createdDate      = '创建时间';
$lang->deploy->createdBy        = '创建人';
$lang->deploy->publish          = '上线';
$lang->deploy->estimate         = '预计上线时间';

$lang->deploy->name       = '申请标题';
$lang->deploy->desc       = '描述';
$lang->deploy->members    = '上线人员';
$lang->deploy->hosts      = '主机';
$lang->deploy->service    = '服务';
$lang->deploy->product    = $lang->productCommon;
$lang->deploy->release    = '应用版本号';
$lang->deploy->package    = '包地址';
$lang->deploy->begin      = '开始时间';
$lang->deploy->end        = '结束时间';
$lang->deploy->status     = '状态';
$lang->deploy->owner      = '负责人';
$lang->deploy->stage      = '上线阶段';
$lang->deploy->ditto      = '同上';
$lang->deploy->manageAB   = '管理';
$lang->deploy->title      = '上线步骤标题';
$lang->deploy->content    = '上线步骤描述';
$lang->deploy->assignedTo = '指派给';
$lang->deploy->finishedBy = '由谁完成';
$lang->deploy->result     = '上线状态';
$lang->deploy->updateHost = '修改主机关系';
$lang->deploy->removeHost = '待移除主机';
$lang->deploy->addHost    = '新加主机';
$lang->deploy->hadHost    = '已有主机';
$lang->deploy->type       = '部署方式';
$lang->deploy->date       = '预计上线时间';
$lang->deploy->reviewedBy = '评审人';
$lang->deploy->reviewDate = '评审时间';
$lang->deploy->system     = '所属应用';

$lang->deploy->lblBeginEnd = '起止时间';
$lang->deploy->lblBasic    = '基本信息';
$lang->deploy->lblProduct  = '关联' . $lang->productCommon;
$lang->deploy->lblMonth    = '当前显示';
$lang->deploy->toggle      = '切换';

$lang->deploy->monthFormat = 'Y年m月';

$lang->deploy->statusList['wait']    = '待上线';
$lang->deploy->statusList['doing']   = '上线中';
$lang->deploy->statusList['success'] = '上线成功';
$lang->deploy->statusList['fail']    = '上线失败';

$lang->deploy->dateList['today']     = '今天';
$lang->deploy->dateList['tomorrow']  = '明天';
$lang->deploy->dateList['thisweek']  = '本周';
$lang->deploy->dateList['thismonth'] = '本月';
$lang->deploy->dateList['done']      = $lang->deploy->statusList['success'];

$lang->deploy->stageList['wait']    = '上线前';
$lang->deploy->stageList['doing']   = '上线中';
$lang->deploy->stageList['testing'] = '冒烟测试';
$lang->deploy->stageList['done']    = '上线后';

$lang->deploy->resultList['success'] = '成功';
$lang->deploy->resultList['fail']    = '失败';

$lang->deploy->confirmDelete     = '是否删除该上线申请';
$lang->deploy->confirmDeleteStep = '是否删除该上线上线步骤';
$lang->deploy->errorTime         = '结束时间必须大于开始时间！';
$lang->deploy->errorStatusWait   = '如果上线步骤状态是未完成，由谁完成必须为空';
$lang->deploy->errorStatusDone   = '如果上线步骤状态是已完成，由谁完成不能为空';
$lang->deploy->errorOffline      = '服务的上架机器和下架机器不能共存。';
$lang->deploy->resultNotEmpty    = '结果不能为空';
$lang->deploy->confirmPublish    = '是否发起上线?';

$lang->deploystep = new stdClass();
$lang->deploystep->status       = $lang->deploy->status;
$lang->deploystep->assignedTo   = $lang->deploy->assignedTo;
$lang->deploystep->finishedBy   = $lang->deploy->finishedBy;
$lang->deploystep->finishedDate = '完成日期';
$lang->deploystep->begin        = $lang->deploy->begin;
$lang->deploystep->end          = $lang->deploy->end;

$lang->datepicker->monthNames = array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月');

$lang->deploy->featureBar['browse']['all']         = '全部';
$lang->deploy->featureBar['browse']['wait']        = '待上线';
$lang->deploy->featureBar['browse']['success']     = '已上线';
$lang->deploy->featureBar['browse']['createdbyme'] = '由我创建';

$lang->deploy->typeList['manual'] = '手动上线';

$lang->deploy->notice = new stdclass();
$lang->deploy->notice->nameLength = '名称不能超过50个字符。';
$lang->deploy->notice->descLength = '描述不能超过255个字符。';
$lang->deploy->notice->styleName  = '名称只能由中文、字母、数字、-和_组成。';

$lang->deploy->stepStatusList['wait'] = '未完成';
$lang->deploy->stepStatusList['done'] = '已完成';
