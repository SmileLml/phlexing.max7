<?php
$lang->pivot->testcase          = '用例统计表';
$lang->pivot->casesrun          = '用例执行统计表';
$lang->pivot->build             = '版本统计表';
$lang->pivot->workSummary       = '任务完成汇总表';
$lang->pivot->bugSummary        = 'Bug解决汇总表';
$lang->pivot->roadmap           = $lang->productCommon . '路线图表';
$lang->pivot->storyLinkedBug    = $lang->SRCommon . '关联Bug汇总表';
$lang->pivot->workAssignSummary = '任务指派汇总表';
$lang->pivot->bugAssignSummary  = 'Bug指派汇总表';
$lang->pivot->productInvest     = $lang->productCommon . '投入表';

$lang->pivot->taskFinishedDate  = '任务完成时间';
$lang->pivot->taskConsumed      = '任务总消耗';
$lang->pivot->projectConsumed   = $lang->projectCommon . '总消耗';
$lang->pivot->executionConsumed = '执行总消耗';
$lang->pivot->userConsumed      = '用户总消耗';
$lang->pivot->bugResolvedDate   = 'Bug解决日期';
$lang->pivot->bugAssignedDate   = 'Bug指派日期';
$lang->pivot->taskAssignedDate  = '任务指派时间';
$lang->pivot->projects          = $lang->projectCommon . '数';
$lang->pivot->storyConsumed     = $lang->SRCommon . '消耗工时';
$lang->pivot->taskConsumed      = '任务消耗工时';
$lang->pivot->bugConsumed       = 'Bug消耗工时';
$lang->pivot->caseConsumed      = '用例消耗工时';
$lang->pivot->totalConsumed     = '总消耗工时';

if(helper::hasFeature('product_roadmap')) $lang->pivotList->product->lists[20] = $lang->productCommon . '路线图表|pivot|roadmap';
$lang->pivotList->product->lists[25] = $lang->productCommon . '投入表|pivot|productInvest';
$lang->pivotList->test->lists[20]    = '用例统计表|pivot|testcase';
$lang->pivotList->test->lists[25]    = '用例执行统计表|pivot|casesrun';
$lang->pivotList->test->lists[30]    = '版本统计表|pivot|build';
$lang->pivotList->test->lists[35]    = $lang->SRCommon . '关联Bug汇总表|pivot|storylinkedbug';
$lang->pivotList->staff->lists[20]   = '任务完成汇总表|pivot|worksummary';
$lang->pivotList->staff->lists[25]   = '任务指派汇总表|pivot|workAssignSummary';
$lang->pivotList->staff->lists[30]   = 'Bug解决汇总表|pivot|bugsummary';
$lang->pivotList->staff->lists[35]   = 'Bug指派汇总表|pivot|bugAssignSummary';

$lang->pivot->product    = $lang->productCommon . '名称';
$lang->pivot->moduleName = '模块名称';
$lang->pivot->buildTitle = '测试版本';
$lang->pivot->severity   = '严重程度';
$lang->pivot->bugType    = 'Bug类型';
$lang->pivot->bugStatus  = 'Bug状态';
$lang->pivot->delay      = '延期';
$lang->pivot->day        = '天';
$lang->pivot->plan       = '计划';
$lang->pivot->future     = '待定';

$lang->pivot->case           = new stdclass();
$lang->pivot->case->total    = '总用例数';
$lang->pivot->case->run      = '总执行数';
$lang->pivot->case->passRate = '用例通过率';
$lang->pivot->case->name     = '名称';

$lang->pivot->bugTypeList['codeerror']   = '代码';
$lang->pivot->bugTypeList['interface']   = '界面';
$lang->pivot->bugTypeList['config']      = '配置';
$lang->pivot->bugTypeList['install']     = '安装';
$lang->pivot->bugTypeList['security']    = '安全';
$lang->pivot->bugTypeList['performance'] = '性能';
$lang->pivot->bugTypeList['standard']    = '标准';
$lang->pivot->bugTypeList['automation']  = '脚本';
$lang->pivot->bugTypeList['others']      = '其他';

$lang->pivot->bug         = new stdclass();
$lang->pivot->bug->total  = '总计';
$lang->pivot->bug->title  = 'Bug标题';
$lang->pivot->bug->story  = $lang->SRCommon;
$lang->pivot->bug->status = '状态';

$lang->pivot->caseRunDesc = '分别按照用例的执行次数和结果进行统计';
