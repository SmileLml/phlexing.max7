<?php
$lang->chart->create    = '创建图表';
$lang->chart->edit      = '编辑图表';
$lang->chart->browse    = '浏览图表';
$lang->chart->delete    = '删除图表';
$lang->chart->design    = '设计图表';
$lang->chart->order     = '排序';
$lang->chart->save      = '保存';
$lang->chart->cancel    = '取消';
$lang->chart->publish   = '发布';
$lang->chart->draft     = '存为草稿';
$lang->chart->run       = '执行查询';
$lang->chart->add       = '添加';
$lang->chart->toDesign  = '进入设计';
$lang->chart->toPreview = '退出设计';

$lang->chart->browseAction = '进入图表设计';

$lang->chart->id          = 'ID';
$lang->chart->name        = '名称';
$lang->chart->dataset     = '数据集';
$lang->chart->dataview    = '数据';
$lang->chart->type        = '类型';
$lang->chart->config      = '参数配置';
$lang->chart->desc        = '描述';
$lang->chart->xaxis       = 'X轴';
$lang->chart->yaxis       = 'Y轴';
$lang->chart->rotateXaxis = 'x轴';
$lang->chart->rotateYaxis = 'y轴';
$lang->chart->metric      = '指标';
$lang->chart->column      = '列';
$lang->chart->columnField = '字段';
$lang->chart->operator    = '条件';
$lang->chart->orderby     = '排序';
$lang->chart->valOrAgg    = '值/汇总';
$lang->chart->value       = '值';
$lang->chart->display     = '显示名称';
$lang->chart->filterValue = '筛选值';
$lang->chart->must        = '请选择';
$lang->chart->split       = '分列显示';
$lang->chart->draftIcon   = '草稿';
$lang->chart->molecule    = '分子';
$lang->chart->denominator = '分母';
$lang->chart->goal        = '对';
$lang->chart->operate     = '进行';
$lang->chart->condition   = '条件';
$lang->chart->eq          = '=';
$lang->chart->and         = '且';

$lang->chart->chooseField     = '选择字段';
$lang->chart->chooseCalc      = '选择计算方式';
$lang->chart->chooseCondition = '选择条件';

$lang->chart->denominatorTip['tip']   = '水球图展示的占比是由分子/分母x100%得出的，可以在分子配置需要比较的部分内容，分母会根据配置自动生成';
$lang->chart->denominatorTip['empty'] = '根据分子配置的内容自动生成';
$lang->chart->denominatorTip['full']  = '%s字段的%s';

$lang->chart->rotateLabel    = '旋转';
$lang->chart->rotateLabelTip = '勾选后，该轴的刻度标签会旋转角度，防止标签之间重叠';

$lang->chart->calcList['']      = '';
$lang->chart->calcList['sum']   = '求和';
$lang->chart->calcList['count'] = '计数';

$lang->chart->errorList = new stdclass();
$lang->chart->errorList->cantequal = '%s 取值不能与 %s 相同, 请重新设计';

$lang->chart->pie = new stdclass();
$lang->chart->pie->group  = '标签';
$lang->chart->pie->metric = '数据';
$lang->chart->pie->stat   = '统计方式';

$lang->chart->cluBarX = new stdclass();
$lang->chart->cluBarX->xaxis = 'X轴';
$lang->chart->cluBarX->yaxis = 'Y轴';
$lang->chart->cluBarX->stat  = '统计方式';

/* Cluster bar X graphs and cluster bar Y graphs are really just a switch between the x and y axes, so use a little ingenuity in the cluster bar Y language terms so that the methods can be reused.*/
/* 簇状柱形图和簇状条形图其实只是x轴和y轴换了换，所以在簇状条形图语言项上使用了点小聪明，这样方法就可以复用了。*/
$lang->chart->cluBarY = new stdclass();
$lang->chart->cluBarY->yaxis = 'X轴';
$lang->chart->cluBarY->xaxis = 'Y轴';
$lang->chart->cluBarY->stat  = '统计方式';

$lang->chart->radar = new stdclass();
$lang->chart->radar->xaxis = '维度';
$lang->chart->radar->yaxis = '极坐标轴';

$lang->chart->line = $lang->chart->cluBarX;

$lang->chart->stackedBar  = $lang->chart->cluBarX;
$lang->chart->stackedBarY = $lang->chart->cluBarY;

$lang->chart->browseGroup = '维护分组';
$lang->chart->allGroup    = '所有分组';
$lang->chart->noGroup     = '暂时没有分组';
$lang->chart->groupName   = '分组名称';
$lang->chart->manageGroup = '维护分组';
$lang->chart->dragAndSort = '拖放排序';
$lang->chart->editGroup   = '编辑分组';
$lang->chart->deleteGroup = '删除分组';
$lang->chart->childTitle  = '子分组';
$lang->chart->export      = '导出图表';
$lang->chart->nextStep    = '下一步';
$lang->chart->add         = '添加';

$lang->chart->filter          = '筛选器';
$lang->chart->resultFilter    = '结果筛选器';
$lang->chart->noName          = '未命名';
$lang->chart->filterName      = '名称';
$lang->chart->showAs          = '显示为';
$lang->chart->default         = '默认值';
$lang->chart->legendBasicInfo = '基础信息';
$lang->chart->legendConfig    = '全局设置';
$lang->chart->baseSetting     = '基础设置';

$lang->chart->dateGroup = array();
$lang->chart->dateGroup['year']  = '年';
$lang->chart->dateGroup['month'] = '月';
$lang->chart->dateGroup['week']  = '周';
$lang->chart->dateGroup['day']   = '日';

$lang->chart->count       = '个数';
$lang->chart->project     = $lang->projectCommon . '名称';
$lang->chart->customer    = '客户名称';
$lang->chart->build       = '软件版本';
$lang->chart->cusBuild    = '客户版本';
$lang->chart->period      = '测试周期';
$lang->chart->purpose     = '测试目的';
$lang->chart->stage       = '阶段';
$lang->chart->users       = '测试人数';
$lang->chart->testtasks   = '提交测试单';
$lang->chart->comment     = '备注';
$lang->chart->major       = '软件主测';
$lang->chart->conclusion  = '结论';
$lang->chart->result      = '基本测试结果';
$lang->chart->caseCount   = '用例总数';
$lang->chart->runCount    = '完成数';
$lang->chart->runRate     = '完成率';
$lang->chart->manpower    = '投入人数';
$lang->chart->bugs        = '提交Bug数';
$lang->chart->day         = '天';
$lang->chart->reportDate  = '报告日期';
$lang->chart->hours       = '耗时(小时)';
$lang->chart->pastDays    = '距今天数';

$lang->chart->saveAsDataview = '存为中间表';

$lang->chart->confirmDelete = '您确认要删除吗?';
$lang->chart->nameEmpty     = '『名称』不能为空';
$lang->chart->fieldEmpty    = '字段不能为空';

$lang->chart->noChartTip    = '配置后，即可显示图表';
$lang->chart->noFilterTip   = '暂时没有筛选器。';
$lang->chart->dataError     = '"%s" 填写的不是合法的值';
$lang->chart->beginGtEnd    = '开始时间不得大于结束时间。';
$lang->chart->resetSettings = '查询数据的配置已修改，是否清空图表和筛选器配置，重新设计。';
$lang->chart->clearSettings = '查询数据的配置已修改，是否清空图表和筛选器配置并保存。';
$lang->chart->draftSave     = '已发布的内容被编辑，将覆盖，是否继续?';

$lang->chart->confirm = new stdclass();
$lang->chart->confirm->design  = '该图表被已发布的大屏引用，是否继续？';
$lang->chart->confirm->publish = '该图表被已发布的大屏引用，发布后将在大屏上显示为修改后的图表，是否继续？';
$lang->chart->confirm->draft   = '该图表被已发布的大屏引用，存为草稿后将在大屏上显示为提示信息，是否继续？';
$lang->chart->confirm->delete  = '该图表被已发布的大屏引用，删除后将在大屏上显示为提示信息，是否继续？';

$lang->chart->orderList = array();
$lang->chart->orderList['asc']  = '正序';
$lang->chart->orderList['desc'] = '倒序';

$lang->chart->operatorList = array();
$lang->chart->operatorList['=']       = '=';
$lang->chart->operatorList['!=']      = '!=';
$lang->chart->operatorList['<']       = '<';
$lang->chart->operatorList['>']       = '>';
$lang->chart->operatorList['<=']      = '<=';
$lang->chart->operatorList['>=']      = '>=';
$lang->chart->operatorList['in']      = 'IN';
$lang->chart->operatorList['notin']   = 'NOT IN';
$lang->chart->operatorList['notnull'] = 'IS NOT NULL';
$lang->chart->operatorList['null']    = 'IS NULL';

$lang->chart->dateList = array();
$lang->chart->dateList['day']   = '天';
$lang->chart->dateList['week']  = '周';
$lang->chart->dateList['month'] = '月';
$lang->chart->dateList['year']  = '年';

$lang->chart->designStepNav = array();
$lang->chart->designStepNav['1'] = '查询数据';
$lang->chart->designStepNav['2'] = '设计图表';
$lang->chart->designStepNav['3'] = '配置筛选器';
$lang->chart->designStepNav['4'] = '准备发布';

$lang->chart->nextButton = array();
$lang->chart->nextButton['1'] = '去设计';
$lang->chart->nextButton['2'] = '去配置';
$lang->chart->nextButton['3'] = '去准备';
$lang->chart->nextButton['4'] = '发布';

$lang->chart->displayList = array();
$lang->chart->displayList['value']   = '显示值';
$lang->chart->displayList['percent'] = '百分比';

$lang->chart->typeOptions = array();
$lang->chart->typeOptions['user']      = '用户';
$lang->chart->typeOptions['product']   = '产品';
$lang->chart->typeOptions['project']   = '项目';
$lang->chart->typeOptions['execution'] = '执行';
$lang->chart->typeOptions['dept']      = '部门';

$lang->chart->browseTypeList = $lang->chart->typeList;
$lang->chart->browseTypeList['card']      = '指标卡片';
$lang->chart->browseTypeList['bar']       = '簇状条形图';
$lang->chart->browseTypeList['piecircle'] = '环形饼图';
$lang->chart->browseTypeList['waterpolo'] = '水球图';
