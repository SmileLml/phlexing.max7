<?php
$lang->chart->create    = 'Create chart';
$lang->chart->edit      = 'Edit chart';
$lang->chart->browse    = 'Browse design chart';
$lang->chart->delete    = 'Delete chart';
$lang->chart->design    = 'Design chart';
$lang->chart->order     = 'Order';
$lang->chart->save      = 'Save';
$lang->chart->cancel    = 'Cancel';
$lang->chart->run       = 'RUN QUERY';
$lang->chart->publish   = 'Release';
$lang->chart->draft     = 'Save as draft';
$lang->chart->add       = 'Add';
$lang->chart->toPreview = 'Exit Design';

$lang->chart->browseAction = 'Design in Chart';

$lang->chart->id          = 'ID';
$lang->chart->name        = 'Name';
$lang->chart->dataset     = 'Dataset';
$lang->chart->dataview    = 'Data';
$lang->chart->type        = 'Type';
$lang->chart->config      = 'Config parameter';
$lang->chart->desc        = 'Description';
$lang->chart->xaxis       = 'Xaxis';
$lang->chart->yaxis       = 'Yaxis';
$lang->chart->rotateXaxis = 'x axis';
$lang->chart->rotateYaxis = 'y axis';
$lang->chart->metric      = 'Metric';
$lang->chart->column      = 'Column';
$lang->chart->columnField = 'Column field';
$lang->chart->operator    = 'Operator';
$lang->chart->orderby     = 'Order By';
$lang->chart->valOrAgg    = 'Value/Aggregate';
$lang->chart->value       = 'Value';
$lang->chart->agg         = 'Aggregate';
$lang->chart->display     = 'Display Name';
$lang->chart->filterValue = 'Filter value';
$lang->chart->must        = 'Please select';
$lang->chart->split       = 'Split';
$lang->chart->draftIcon   = 'draft';
$lang->chart->molecule    = 'Molecule';
$lang->chart->denominator = 'Denominator';
$lang->chart->goal        = 'Field';
$lang->chart->operate     = 'Do';
$lang->chart->condition   = 'Condition';
$lang->chart->eq          = '=';
$lang->chart->and         = 'And';

$lang->chart->chooseField     = 'Choose Field';
$lang->chart->chooseCalc      = 'Choose Calculation';
$lang->chart->chooseCondition = 'Choose Condition';

$lang->chart->denominatorTip['tip']   = 'The proportion shown in the water balloon chart is obtained from the molecule/denominator x 100%, and the parts that need to be compared can be configured in the numerator, and the denominator will be automatically generated according to the configuration';
$lang->chart->denominatorTip['empty'] = 'Automatically generated according to the content of the molecular configuration';
$lang->chart->denominatorTip['full']  = '%s of %s';

$lang->chart->rotateLabel    = 'Rotate';
$lang->chart->rotateLabelTip = 'When checked, the scale label of the axis rotates the Angle to prevent overlap between labels';

$lang->chart->calcList['']      = '';
$lang->chart->calcList['sum']   = 'Sum';
$lang->chart->calcList['count'] = 'Count';

$lang->chart->errorList = new stdclass();
$lang->chart->errorList->cantequal = '%s can not be equal to the %s, please redesign';

$lang->chart->pie = new stdclass();
$lang->chart->pie->group  = 'Tag';
$lang->chart->pie->metric = 'Data';
$lang->chart->pie->stat   = 'Aggregate type';

$lang->chart->cluBarX = new stdclass();
$lang->chart->cluBarX->xaxis = 'Xaxis';
$lang->chart->cluBarX->yaxis = 'Yaxis';
$lang->chart->cluBarX->stat  = 'Aggregate type';

/* Cluster bar X graphs and cluster bar Y graphs are really just a switch between the x and y axes, so use a little ingenuity in the cluster bar Y language terms so that the methods can be reused.*/
/* 簇状柱形图和簇状条形图其实只是x轴和y轴换了换，所以在簇状条形图语言项上使用了点小聪明，这样方法就可以复用了。*/
$lang->chart->cluBarY = new stdclass();
$lang->chart->cluBarY->xaxis = 'Xaxis';
$lang->chart->cluBarY->yaxis = 'Yaxis';
$lang->chart->cluBarY->stat  = 'Aggregate type';

$lang->chart->radar = new stdclass();
$lang->chart->radar->xaxis = 'Dimension';
$lang->chart->radar->yaxis = 'Polar axis';

$lang->chart->line = $lang->chart->cluBarX;

$lang->chart->stackedBar  = $lang->chart->cluBarX;
$lang->chart->stackedBarY = $lang->chart->cluBarY;

$lang->chart->browseGroup = 'Manage Group';
$lang->chart->allGroup    = 'All Group';
$lang->chart->noGroup     = 'No Group';
$lang->chart->groupName   = 'Group Name';
$lang->chart->manageGroup = 'Manage Group';
$lang->chart->dragAndSort = 'Drag to order';
$lang->chart->editGroup   = 'Edit Group';
$lang->chart->deleteGroup = 'Delete Group';
$lang->chart->childTitle  = 'Child Group';
$lang->chart->export      = 'Export Chart';
$lang->chart->nextStep    = 'Next Step';
$lang->chart->add         = 'Add';

$lang->chart->filter          = 'Filter';
$lang->chart->resultFilter    = 'Result Filter';
$lang->chart->noName          = 'Unnamed';
$lang->chart->filterName      = 'Name';
$lang->chart->showAs          = 'Show as';
$lang->chart->default         = 'Default';
$lang->chart->legendBasicInfo = 'Basic info';
$lang->chart->legendConfig    = 'Global Setting';
$lang->chart->baseSetting     = 'Base Setting';

$lang->chart->dateGroup = array();
$lang->chart->dateGroup['year']  = 'Year';
$lang->chart->dateGroup['month'] = 'Month';
$lang->chart->dateGroup['week']  = 'Week';
$lang->chart->dateGroup['date']  = 'Day';

$lang->chart->count       = 'Count';
$lang->chart->project     = $lang->projectCommon;
$lang->chart->customer    = 'Customer';
$lang->chart->build       = 'Build';
$lang->chart->cusBuild    = 'Customer Build';
$lang->chart->period      = 'Period';
$lang->chart->purpose     = 'Purpose';
$lang->chart->stage       = 'Stage';
$lang->chart->users       = 'Users';
$lang->chart->testtasks   = 'Testtasks';
$lang->chart->comment     = 'Comment';
$lang->chart->major       = 'Major';
$lang->chart->conclusion  = 'Conclusion';
$lang->chart->result      = 'Result';
$lang->chart->caseCount   = 'Cases';
$lang->chart->runCount    = 'Run';
$lang->chart->runRate     = 'Run Rate';
$lang->chart->manpower    = 'Manpower';
$lang->chart->bugs        = 'Bugs';
$lang->chart->day         = 'Day';
$lang->chart->reportDate  = 'Report Date';
$lang->chart->hours       = 'Hours';
$lang->chart->pastDays    = 'Past Days';

$lang->chart->saveAsDataview = 'Save as Custom Table';

$lang->chart->confirmDelete = 'Do you want to delete this chart?';
$lang->chart->nameEmpty     = '『Name』should not be blank';
$lang->chart->fieldEmpty    = "Field can't empty";

$lang->chart->noChartTip    = 'Once configured, the chart can be displayed';
$lang->chart->noFilterTip   = 'No filter';
$lang->chart->dataError     = '"%s" is not valid';
$lang->chart->beginGtEnd    = 'Begin time should not be >= end time.';
$lang->chart->resetSettings = 'The configuration of the query data has been modified, requiring redesign of the chart and configuration filter, whether to continue.';
$lang->chart->clearSettings = 'The configuration of the query data has been modified, whether to clear the chart and filter configuration and save.';
$lang->chart->draftSave     = 'Released content is edited, will be overwritten, whether to continue?';

$lang->chart->confirm = new stdclass();
$lang->chart->confirm->design  = 'This chart is referenced by a published screen. Do you want to continue?';
$lang->chart->confirm->publish = 'This chart is referenced by a published screen and will be displayed as a modified chart after publication. Do you want to continue?';
$lang->chart->confirm->draft   = 'This chart is referenced by a published screen and will be displayed as and prompts on the large screen after being saved as a draft. Do you want to continue?';
$lang->chart->confirm->delete  = 'This chart is referenced by a published screen and will be displayed as and prompts on the large screen after deletion. Do you want to continue?';

$lang->chart->orderList = array();
$lang->chart->orderList['asc']  = 'ASC';
$lang->chart->orderList['desc'] = 'DESC';

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
$lang->chart->dateList['day']   = 'DAY';
$lang->chart->dateList['week']  = 'WEEK';
$lang->chart->dateList['month'] = 'MONTH';
$lang->chart->dateList['year']  = 'YEAR';

$lang->chart->designStepNav = array();
$lang->chart->designStepNav['1'] = 'Query Data';
$lang->chart->designStepNav['2'] = 'Design Chart';
$lang->chart->designStepNav['3'] = 'Set Filter';
$lang->chart->designStepNav['4'] = 'Release';

$lang->chart->nextButton = array();
$lang->chart->nextButton['1'] = 'To Design';
$lang->chart->nextButton['2'] = 'To Configurate';
$lang->chart->nextButton['3'] = 'To Release';
$lang->chart->nextButton['4'] = 'Release';

$lang->chart->displayList = array();
$lang->chart->displayList['value']   = 'Display value';
$lang->chart->displayList['percent'] = 'Percentage';

$lang->chart->typeOptions = array();
$lang->chart->typeOptions['user']      = 'User';
$lang->chart->typeOptions['product']   = 'Product';
$lang->chart->typeOptions['project']   = 'Project';
$lang->chart->typeOptions['execution'] = 'Execution';
$lang->chart->typeOptions['dept']      = 'Dept';

$lang->chart->browseTypeList = $lang->chart->typeList;
$lang->chart->browseTypeList['card']      = 'Card';
$lang->chart->browseTypeList['bar']       = 'Clustered Bar Y';
$lang->chart->browseTypeList['piecircle'] = 'Pie Circle';
$lang->chart->browseTypeList['waterpolo'] = 'Water Polo';
