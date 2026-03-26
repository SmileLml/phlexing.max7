<?php
$lang->milestone->common      =  $lang->projectCommon . '里程碑报告';
$lang->milestone->index       = '报告首页';
$lang->milestone->title       = '里程碑报告';
$lang->milestone->name        = '里程碑名称';
$lang->milestone->stage       = '里程碑阶段';
$lang->milestone->save        = '保存';

$lang->milestone->indexAction = '里程碑报告';

$lang->milestone->startedWeeks  = '开始周数';
$lang->milestone->finishedWeeks = '结束周数';
$lang->milestone->offset        = '里程碑工期偏差';

$lang->milestone->processCommon =  $lang->projectCommon . '当前进展状况';
$lang->milestone->process       =  $lang->projectCommon . '进度';
$lang->milestone->executionCost =  $lang->projectCommon . '成本';
$lang->milestone->toNow         = '到目前为止';
$lang->milestone->targetRange   = '目标控制范围';
$lang->milestone->ge            = '大于等于';
$lang->milestone->le            = '小于等于';
$lang->milestone->analysis      = '分析结果';
$lang->milestone->PV            = '计划完成的工作(PV)';
$lang->milestone->EV            = '实际完成的工作(EV)';
$lang->milestone->AC            = '实际花费的成本(AC)';
$lang->milestone->SPI           =  $lang->projectCommon . '进度绩效(SPI)';
$lang->milestone->CPI           =  $lang->projectCommon . '成本绩效(CPI)';
$lang->milestone->SV            = '进度偏差率(SV%)';
$lang->milestone->CV            = '成本偏差率(CV%)';

$lang->milestone->workHours   = '工作量（人时）';
$lang->milestone->allStage    = '工程阶段';
$lang->milestone->devHours    = '研发工作量';
$lang->milestone->toHours     = '返工工作量';
$lang->milestone->reviewHours = '评审工作量';
$lang->milestone->qaHours     = '测试工作量';
$lang->milestone->rowSummary  = '工作量小计';
$lang->milestone->rowPercent  = '分布百分比(%)';
$lang->milestone->colPercent  = '占工作量百分比(%)';
$lang->milestone->colSummary  = '总和';
$lang->milestone->qatoDev     = '测试研发比';

$lang->milestone->executionRisk   = '5.' . $lang->projectCommon . '风险(优先最高的前五项风险)';
$lang->milestone->riskCountermove = '风险对策';
$lang->milestone->riskDescriptio  = '风险描述';
$lang->milestone->riskPossibility = '可能性';
$lang->milestone->riskSeriousness = '严重性';
$lang->milestone->riskFactor      = '风险系数';
$lang->milestone->riskMeasures    = '风险对策';
$lang->milestone->riskAccumulate  = '累积的高风险';

$lang->milestone->otherIssue       = '其它问题';
$lang->milestone->issueSolutions   = '问题及解决建议';
$lang->milestone->issueDescription = '问题描述';
$lang->milestone->issuePropose     = '解决建议';

$lang->milestone->demandStatus     = '4.用户需求状况分析';
$lang->milestone->storyUnit        = '单位:Item';
$lang->milestone->engineeringStage = '工程阶段';
$lang->milestone->rateChange       = '需求变化率';
$lang->milestone->originalStory    = '原始需求数量';
$lang->milestone->modifyNumber     = '变更后需求总数';
$lang->milestone->changeStory      = '变更的需求数';

$lang->milestone->paogressForecast   =  $lang->projectCommon . '进展预测';
$lang->milestone->duration           = '工期(天)';
$lang->milestone->cost               = '成本(人时)';
$lang->milestone->forecastResults    = '预测结果分析';
$lang->milestone->plannedValue       = '计划值';
$lang->milestone->predictedValue     = '预测值';
$lang->milestone->predictedValueDesc = '计算公式：如果' . $lang->projectCommon . '进度绩效(SPI)=0，那么预测值=0，否则，预测值=计划值 除以 ' . $lang->projectCommon . '进度绩效(SPI)';
$lang->milestone->periodDeviation    = '工期偏差';
$lang->milestone->costDeviation      = '成本偏差';
$lang->milestone->nextStage          = '下一里程碑阶段';
$lang->milestone->overallProject     =  $lang->projectCommon . '总体';
$lang->milestone->corrective         = '纠偏措施';
$lang->milestone->timeOverrun        = '总工期将超出:%s天。';
$lang->milestone->costOverrun        = '总成本将超出:%s个单位。';
$lang->milestone->saveOtherProblem   = '保存其他问题';

$lang->milestone->chart = new stdclass();
$lang->milestone->chart->title    = '到目前为止' . $lang->projectCommon . '进展趋势图';
$lang->milestone->chart->time     = '第';
$lang->milestone->chart->week     = '周';
$lang->milestone->chart->month    = '月';
$lang->milestone->chart->workhour = '研发工作量分析图';

$lang->milestone->otherproblem      = '6.其它问题';
$lang->milestone->problemandsuggest = '问题及解决建议';
$lang->milestone->suggest           = '解决建议';
$lang->milestone->needhelp          = '是否需要高层支持？';
$lang->milestone->prodescr          = '问题描述';

$lang->milestone->reviewHoursTip  = '评审工作量 = 评审时长 * 评审人数';
$lang->milestone->toHoursTip      = '返工工作量统计的是研发任务被激活之后，重新消耗的工时';
$lang->milestone->startedWeekTip  = '统计项目开始时间到当前里程碑开始时间的周数';
$lang->milestone->finishedWeekTip = '统计项目开始时间到当前里程碑结束时间的周数';

$lang->milestone->quality = new stdclass();
$lang->milestone->quality->total         = '合计';
$lang->milestone->quality->identify      = '缺陷识别阶段';
$lang->milestone->quality->injection     = '缺陷注入阶段';
$lang->milestone->quality->scale         = '规模';
$lang->milestone->quality->identifyRate  = '缺陷识别率';
$lang->milestone->quality->injectionRate = '缺陷注入率';

$lang->milestone->options  = '操作';

$lang->milestone->milestoneHelpNotice = <<<EOD
<h2>PV 计划完成的工作</h2>
计算方式：
<br />1）任务所属阶段是当前里程碑，累加预计工时
<br />2）当前里程碑阶段之前的所有阶段下的任务，累加预计工时
<p>统计范围：</p>
1）为避免重复计算，只包含子任务，不包括父任务
<br />2）不包括已删除的任务
<br />3）不包括已取消的任务
<br />4）不包括已删除执行中的任务
<h2>EV实际完成的工作</h2>
计算方式：
<br />1）任务所属阶段是当前里程碑：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为已完成，累加预计工时
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为已关闭且关闭原因为已完成，累加预计工时
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为进行中、已暂停，累加 预计工时×完成进度
<br />2）当前里程碑阶段之前的所有阶段下的任务：
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为已完成，累加预计工时
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为已关闭且关闭原因为已完成，累加预计工时
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;任务状态为进行中、已暂停，累加 预计工时×完成进度
<p>统计范围：</p>
1）为避免重复计算，只包含子任务，不包括父任务
<br />2）不包括已删除的任务
<br />3）不包括已取消的任务
<br />4）不包括已删除执行中的任务
<br />5）消耗工时不为0的任务
<br />6）完成进度=已消耗工时÷(已消耗工时+剩余工时)
<h2>AC 实际花费（消耗）的成本</h2>
计算方式：
<br />1）当前里程碑中所有消耗的工时
<br />2）当前里程碑阶段之前的所有阶段下消耗的工时
<p>统计范围：</p>
1）消耗的工时包括任务、需求、Bug、用例、版本、测试单、问题、风险、文档、评审的耗时
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）包括已删除的任务、需求、Bug、用例、版本、测试单、问题、风险、文档、评审的耗时
<br />4）包括已删除执行中任务、需求、Bug、用例、版本、测试单、文档的耗时
<br />5）包括取消的任务、问题、风险的耗时
<h2>SV(%)进度偏差率</h2>
计算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
计算方式：CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI 进度性能指标</h2>
计算方式：SPI = EV / PV
<h2>CPI成本性能指标</h2>
计算方式：CPI = EV / AC
EOD;
$lang->milestone->soFarHelpNotice = <<<EOD
<h2>PV计划完成的工作</h2>
计算方式：
<br />1）任务预计截止日期在今天结束时间之前，累加预计工时
<br />2）任务预计开始日期小于今天结束时间，截止日期大于今天结束时间，累加（任务的预计工时 ÷ 任务工期天数）×  任务预计开始到本周结束日期的天数
<p>统计范围：</p>
1）为避免重复计算，只包含子任务，不包括父任务
<br />2）不包括删除的任务
<br />3）不包括取消的任务
<br />4）不包括已删除执行中的任务
<br />5）今天结束时间是今天的23:59:59
<br />6）任务未填写预计开始日期，预计开始日期默认取任务所属阶段的计划开始日期
<br />7）任务未填写预计截止日期，预计截止日期默认取任务所属阶段的计划完成日期
<br />8）计算公式只计算工作日
<h2>EV实际完成的工作</h2>
计算方式：
<br />1）任务状态为已完成，累加预计工时
<br />2）任务状态为已关闭且关闭原因为已完成，累加预计工时
<br />3）任务状态为进行中、已暂停，累加 预计工时×完成进度
<p>统计范围：</p>
1）今天结束时间之前，今天结束时间是今天的23:59:59
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）不包括已删除的任务
<br />4）不包括已取消的任务
<br />5）不包括已删除执行中的任务
<br />6）消耗工时不为0的任务
<br />7）完成进度=已消耗工时÷(已消耗工时+剩余工时)
<h2>AC 实际花费（消耗）的成本</h2>
计算方式：
<br />1）累加今天结束时间之前所有消耗的工时
<p>统计范围：</p>
1）消耗的工时包括任务、需求、Bug、用例、版本、测试单、问题、风险、文档、评审的耗时
<br />2）为避免重复计算，只包含子任务，不包括父任务
<br />3）包括已删除的任务、需求、Bug、用例、版本、测试单、问题、风险、文档、评审的耗时
<br />4）包括已删除执行中任务、需求、Bug、用例、版本、测试单、文档的耗时
<br />5）包括取消的任务、问题、风险的耗时
<br />6）今天结束时间是今天的23:59:59
<h2>SV(%)进度偏差率</h2>
计算方式：SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) 成本偏差率</h2>
计算方式：CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI 进度性能指标</h2>
计算方式：SPI = EV / PV
<h2>CPI成本性能指标</h2>
计算方式：CPI = EV / AC
EOD;
