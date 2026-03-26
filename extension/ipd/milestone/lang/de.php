<?php
$lang->milestone->common      = $lang->projectCommon . 'Milestone Report';
$lang->milestone->index       = 'Milestone Home';
$lang->milestone->title       = 'Milestone Report';
$lang->milestone->name        = 'Milestone Name';
$lang->milestone->stage       = 'Milestone Stage';
$lang->milestone->save        = 'Save';

$lang->milestone->indexAction = 'Milestone Report';

$lang->milestone->startedWeeks  = 'Started Weeks';
$lang->milestone->finishedWeeks = 'Finished Weeks';
$lang->milestone->offset        = 'Milestone Deviation';

$lang->milestone->processCommon = $lang->projectCommon . 'Current Status';
$lang->milestone->process       = $lang->projectCommon . 'Schedule';
$lang->milestone->executionCost = $lang->projectCommon . 'Cost';
$lang->milestone->toNow         = 'So far';
$lang->milestone->targetRange   = 'Target Range';
$lang->milestone->ge            = 'ge';
$lang->milestone->le            = 'le';
$lang->milestone->analysis      = 'Analysis';
$lang->milestone->PV            = 'The work to be done(PV)';
$lang->milestone->EV            = 'Actual work done(EV)';
$lang->milestone->AC            = 'Actual cost(AC)';
$lang->milestone->SPI           = $lang->projectCommon . 'schedule performance(SPI)';
$lang->milestone->CPI           = $lang->projectCommon . 'cost performance(CPI)';
$lang->milestone->SV            = 'Rate of progress deviation(SV%)';
$lang->milestone->CV            = 'Cost deviation rate(CV%)';

$lang->milestone->workHours   = 'Workload (man-hour)';
$lang->milestone->allStage    = 'Project Stage';
$lang->milestone->devHours    = 'Development Workload';
$lang->milestone->toHours     = 'Rework Workload';
$lang->milestone->reviewHours = 'Review Hours';
$lang->milestone->qaHours     = 'Test Workload';
$lang->milestone->rowSummary  = 'Workload Subtotal';
$lang->milestone->rowPercent  = 'Percentage Distribution(%)';
$lang->milestone->colPercent  = 'Percentage of workload(%)';
$lang->milestone->colSummary  = 'Total';
$lang->milestone->qatoDev     = 'Test Development ratio';

$lang->milestone->executionRisk     = '5.' . $lang->projectCommon . 'Risks (top five highest priority risks)';
$lang->milestone->riskCountermove = 'Risk Countermove';
$lang->milestone->riskDescriptio  = 'Risk Description';
$lang->milestone->riskPossibility = 'Risk Possibility';
$lang->milestone->riskSeriousness = 'Risk Seriousness';
$lang->milestone->riskFactor      = 'Risk Factor';
$lang->milestone->riskMeasures    = 'Risk Measures';
$lang->milestone->riskAccumulate  = 'Risk Accumulate';

$lang->milestone->otherIssue       = 'Other Issue';
$lang->milestone->issueSolutions   = 'Issue Solutions';
$lang->milestone->issueDescription = 'Issue Description';
$lang->milestone->issuePropose     = 'Issue Propose';

$lang->milestone->demandStatus     = '4.Customer Demand Analysis';
$lang->milestone->storyUnit        = 'Unit:Item';
$lang->milestone->engineeringStage = 'Engineering Stage';
$lang->milestone->rateChange       = 'Rate of change in demand';
$lang->milestone->originalStory    = 'Quantity of original demand';
$lang->milestone->modifyNumber     = 'Total post-change requirements';
$lang->milestone->changeStory      = 'Number of changed requirements';

$lang->milestone->paogressForecast   = $lang->projectCommon . 'Forecast';
$lang->milestone->duration           = 'Construction period(day)';
$lang->milestone->cost               = 'Cost(hours)';
$lang->milestone->forecastResults    = 'Forecast Results';
$lang->milestone->plannedValue       = 'Planned Value';
$lang->milestone->predictedValue     = 'Predicted Value';
$lang->milestone->predictedValueDesc = 'Rentenformel (Germany)：If the execution' . $lang->projectCommon .'performance(SPI)=0,So the predicted value=0,Otherwise,Predicted value=Planned value divided by Project schedule performance(SPI)';
$lang->milestone->periodDeviation    = 'Period Deviation';
$lang->milestone->costDeviation      = 'Cost Deviation';
$lang->milestone->nextStage          = 'Next Stage';
$lang->milestone->overallProject     = 'Overall' . $lang->projectCommon;
$lang->milestone->corrective         = 'Corrective Measure';
$lang->milestone->timeOverrun        = 'The total construction time will exceed:%s day.';
$lang->milestone->costOverrun        = 'The total cost will exceed:%s unit';
$lang->milestone->saveOtherProblem   = 'Save Other Problem';

$lang->milestone->chart = new stdclass();
$lang->milestone->chart->title    = 'Trend Chart of execution'. $lang->projectCommon . 'to date';
$lang->milestone->chart->time     = 'No';
$lang->milestone->chart->week     = 'week';
$lang->milestone->chart->month    = 'month';
$lang->milestone->chart->workhour = 'Development Workload Analysis Chart';

$lang->milestone->otherproblem      = '6.Other Problem';
$lang->milestone->problemandsuggest = 'Problem and Suggest';
$lang->milestone->suggest           = 'Suggest';
$lang->milestone->needhelp          = 'Does it require high-level support?';
$lang->milestone->prodescr          = 'Problem Description';

$lang->milestone->reviewHoursTip  = 'Review Hours = Reviewed Hours * Reviewer Count';
$lang->milestone->toHoursTip      = "Rework Workload counts the Reconsumed work hours after task is activated";
$lang->milestone->startedWeekTip  = 'Counts the number of weeks between the project actual begin date and the current milestone actual begin date';
$lang->milestone->finishedWeekTip = 'Counts the number of weeks between the project actual begin date and the current milestone actual end date';

$lang->milestone->quality = new stdclass();
$lang->milestone->quality->total         = 'Total';
$lang->milestone->quality->identify      = 'Defect identification phase';
$lang->milestone->quality->injection     = 'Defect injection phase';
$lang->milestone->quality->scale         = 'Scale';
$lang->milestone->quality->identifyRate  = 'Defect recognition rate';
$lang->milestone->quality->injectionRate = 'Defect injection rate';

$lang->milestone->options  = 'Options';

$lang->milestone->milestoneHelpNotice = <<<EOD
<h2>PV Planned Value</h2>
Calculation method:
<br />1) The phase of the task is the current milestone, and the estimated man hours are accumulated
<br />2) The tasks under all stages before the current milestone stage, and the estimated man hours are accumulated
<p>Statistical range:</p>
1) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />2) Exclude deleted tasks
<br />3) Exclude canceled tasks
<br />4) Exclude tasks in deleted execution
<h2>EV Earned Value</h2>
Calculation method:
<br />1) The phase of the task is the current milestone:
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is done, and the estimated work hours are accumulated
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is closed and the reason for closing is done, and the estimated work hours are accumulated
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is in doing, suspended, and the estimated work hours are accumulated × progress
<br />2) Tasks under all phases before the current milestone phase:
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is done, and the estimated work hours are accumulated
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is closed and the reason for closing is done, and the estimated work hours are accumulated
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The task status is in doing, suspended, and the estimated work hours are accumulated × progress
<p>Statistical range:</p>
1) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />2) Exclude deleted tasks
<br />3) Exclude canceled tasks
<br />4) Exclude tasks in deleted execution
<br />5) Tasks with consumed work not 0
<br />6) Progress= consumed man hours ÷(consumed man hours+remaining man hours)
<h2>AC Actual Cost</h2>
Calculation method:
<br />1) All consumed man hours in the current milestone
<br />2) Man hours consumed in all stages before the current milestone stage
<p>Statistical range:</p>
1) All consumed man hours include task, requirement, bug, use case, version, test sheet, problem, risk, document and review time
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Including deleted tasks, requirements, bugs, use cases, versions, test sheets, problems, risks, documents, and time consuming of reviews
<br />4) Including the time consumption of deleted tasks, requirements, bugs, use cases, versions, test sheets and documents in execution
<br />5) Including the time consumption of cancelled tasks, problems and risks
<h2>SV(%) Schedule Variance</h2>
Calculation method: SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) Cost Variance</h2>
Calculation method: CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI Schedule Performance Index</h2>
Calculation method: SPI = EV / PV
<h2>CPI Cost Performance Index</h2>
Calculation method: CPI = EV / AC
EOD;
$lang->milestone->soFarHelpNotice = <<<EOD
<h2>PV Planned Value</h2>
Calculation method:
<br />1）The expected deadline of the task is before today's end time, and the expected work is accumulated
<br />2）The estimated start date of a task is less than today's end time, and the end date is greater than today's end time. Accumulate (the estimated work hours of the task ÷ the task duration days) × The number of days from the expected start of the task to the end of this week
<p>Statistical range:</p>
1) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />2) Exclude deleted tasks
<br />3) Exclude canceled tasks
<br />4) Exclude tasks in deleted execution
<br />5）Today's end time is today's 23:59:59
<br />6）No expected start date is filled in the task. The expected start date defaults to the planned start date of the phase to which the task belongs
<br />7）No expected end date is filled in the task. By default, the expected end date is the planned completion date of the phase to which the task belongs
<br />8）Calculation formula only calculates working days
<h2>EV Earned Value</h2>
Calculation method:
<br />1) The task status is done, and the estimated work hours are accumulated
<br />2) The task status is closed and the reason for closing is done, and the estimated work hours are accumulated
<br />3) The task status is in doing, suspended, and the estimated work hours are accumulated × progress
<p>Statistical range:</p>
1) Before today's end time, today's end time is 23:59:59
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Exclude deleted tasks
<br />4) Exclude canceled tasks
<br />5) Exclude tasks in deleted execution
<br />6) Tasks with consumed work not 0
<br />7) Progress=consumed man hours ÷ (consumed man hours+remaining man hours)
<h2>AC Actual Cost</h2>
Calculation method:
<br />1) Accumulate all consumed man hours before today's end time
<p>Statistical range:</p>
1) All consumed man hours include task, requirement, bug, use case, version, test sheet, problem, risk, document and review time
<br />2) To avoid repeated calculation, only child tasks are included, not parent tasks
<br />3) Including deleted tasks, requirements, bugs, use cases, versions, test sheets, problems, risks, documents, and time consuming of reviews
<br />4) Including the time consumption of deleted tasks, requirements, bugs, use cases, versions, test sheets and documents in execution
<br />5) Including the time consumption of cancelled tasks, problems and risks
<br />6）Today's end time is today's 23:59:59
<h2>SV(%) Schedule Variance</h2>
Calculation method: SV(%) = -1 * (1 - (EV / PV))%
<h2>CV(%) Cost Variance</h2>
Calculation method: CV(%) = -1 * (1 - (EV / AC))%
<h2>SPI Schedule Performance Index</h2>
Calculation method: SPI = EV / PV
<h2>CPI Cost Performance Index</h2>
Calculation method: CPI = EV / AC
EOD;
