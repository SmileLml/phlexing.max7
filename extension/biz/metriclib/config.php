<?php
$config->metriclib = new stdclass();

$config->metriclib->defaultMetricCount = 10;

$config->metriclib->parentScope = array();
$config->metriclib->parentScope['product']   = 'program';
$config->metriclib->parentScope['project']   = 'program';
$config->metriclib->parentScope['execution'] = 'project';
$config->metriclib->parentScope['user']      = 'dept';

$config->metriclib->pageRangeList = array();

for($i = 5; $i <= 50; $i += 5) $config->metriclib->pageRangeList[] = $i;
$config->metriclib->pageRangeList[] = 100;

$config->metriclib->periodCodeList = array();
$config->metriclib->periodCodeList['nodate'] = 'summary';
$config->metriclib->periodCodeList['year']   = 'annual';
$config->metriclib->periodCodeList['month']  = 'monthly';
$config->metriclib->periodCodeList['week']   = 'weekly';
$config->metriclib->periodCodeList['day']    = 'daily';

$config->metriclib->defaultMetric = array();
$config->metriclib->defaultMetric['system'] = array();
$config->metriclib->defaultMetric['system']['nodate'] = array();
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_product';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_project';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_productplan';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_execution';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_release';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_story';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_task';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_bug';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_case';
$config->metriclib->defaultMetric['system']['nodate'][] = 'count_of_user';

$config->metriclib->defaultMetric['system']['year'] = array();
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_product';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_project';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_productplan';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_execution';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_release';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_story';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_task';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_bug';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_case';
$config->metriclib->defaultMetric['system']['year'][] = 'count_of_annual_created_user';

$config->metriclib->defaultMetric['system']['month'] = array();
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_project';
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_execution';
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_release';
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_story';
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_task';
$config->metriclib->defaultMetric['system']['month'][] = 'count_of_monthly_created_bug';

$config->metriclib->defaultMetric['system']['week'] = array();
$config->metriclib->defaultMetric['system']['week'][] = 'count_of_weekly_created_release';
$config->metriclib->defaultMetric['system']['week'][] = 'count_of_weekly_finished_story';
$config->metriclib->defaultMetric['system']['week'][] = 'scale_of_weekly_finished_story';

$config->metriclib->defaultMetric['system']['day'] = array();
$config->metriclib->defaultMetric['system']['day'][] = 'count_of_daily_created_story';
$config->metriclib->defaultMetric['system']['day'][] = 'count_of_daily_finished_task';
$config->metriclib->defaultMetric['system']['day'][] = 'count_of_daily_closed_bug';
$config->metriclib->defaultMetric['system']['day'][] = 'count_of_daily_run_case';
$config->metriclib->defaultMetric['system']['day'][] = 'hour_of_daily_effort';
$config->metriclib->defaultMetric['system']['day'][] = 'day_of_daily_effort';

$config->metriclib->defaultMetric['product'] = array();
$config->metriclib->defaultMetric['product']['nodate'] = array();
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_productplan_in_product';
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_release_in_product';
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_story_in_product';
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_requirement_in_product';
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_bug_in_product';
$config->metriclib->defaultMetric['product']['nodate'][] = 'count_of_case_in_product';

$config->metriclib->defaultMetric['product']['year'] = array();
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_productplan_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_release_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_story_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_requirement_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_bug_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_case_in_product';
$config->metriclib->defaultMetric['product']['year'][] = 'count_of_annual_created_feedback_in_product';

$config->metriclib->defaultMetric['product']['month'] = array();
$config->metriclib->defaultMetric['product']['month'][] = 'count_of_monthly_created_release_in_product';
$config->metriclib->defaultMetric['product']['month'][] = 'count_of_monthly_created_story_in_product';
$config->metriclib->defaultMetric['product']['month'][] = 'count_of_monthly_created_bug_in_product';

$config->metriclib->defaultMetric['product']['week'] = array();

$config->metriclib->defaultMetric['product']['day'] = array();
$config->metriclib->defaultMetric['product']['day'][] = 'count_of_daily_created_bug_in_product';
$config->metriclib->defaultMetric['product']['day'][] = 'count_of_daily_closed_bug_in_product';
$config->metriclib->defaultMetric['product']['day'][] = 'count_of_daily_resolved_bug_in_product';

$config->metriclib->defaultMetric['project'] = array();
$config->metriclib->defaultMetric['project']['nodate'] = array();
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_execution_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_story_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_task_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_bug_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_user_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'consume_of_all_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_opened_risk_in_project';
$config->metriclib->defaultMetric['project']['nodate'][] = 'count_of_opened_issue_in_project';

$config->metriclib->defaultMetric['project']['year'] = array();
$config->metriclib->defaultMetric['project']['year'][] = 'count_annual_closed_execution_in_project';
$config->metriclib->defaultMetric['project']['year'][] = 'count_of_annual_finished_story_in_project';

$config->metriclib->defaultMetric['project']['month'] = array();
$config->metriclib->defaultMetric['project']['week'] = array();
$config->metriclib->defaultMetric['project']['day'] = array();

$config->metriclib->defaultMetric['execution'] = array();
$config->metriclib->defaultMetric['execution']['nodate'] = array();
$config->metriclib->defaultMetric['execution']['nodate'][] = 'count_of_story_in_execution';
$config->metriclib->defaultMetric['execution']['nodate'][] = 'count_of_task_in_execution';
$config->metriclib->defaultMetric['execution']['nodate'][] = 'consume_of_task_in_execution';
$config->metriclib->defaultMetric['execution']['nodate'][] = 'left_of_task_in_execution';
$config->metriclib->defaultMetric['execution']['nodate'][] = 'progress_of_task_in_execution';

$config->metriclib->defaultMetric['execution']['year']  = array();
$config->metriclib->defaultMetric['execution']['month'] = array();
$config->metriclib->defaultMetric['execution']['week']  = array();

$config->metriclib->defaultMetric['execution']['day'] = array();
$config->metriclib->defaultMetric['execution']['day'][] = 'count_of_daily_finished_task_in_execution';

$config->metriclib->defaultMetric['user'] = array();
$config->metriclib->defaultMetric['user']['nodate'] = array();
$config->metriclib->defaultMetric['user']['nodate'][] = 'count_of_pending_story_in_user';
$config->metriclib->defaultMetric['user']['nodate'][] = 'count_of_assigned_task_in_user';
$config->metriclib->defaultMetric['user']['nodate'][] = 'count_of_assigned_bug_in_user';
$config->metriclib->defaultMetric['user']['nodate'][] = 'count_of_assigned_case_in_user';
$config->metriclib->defaultMetric['user']['nodate'][] = 'count_of_assigned_feedback_in_user';

$config->metriclib->defaultMetric['user']['year'] = array();
$config->metriclib->defaultMetric['user']['month'] = array();
$config->metriclib->defaultMetric['user']['week'] = array();

$config->metriclib->defaultMetric['user']['day'] = array();
$config->metriclib->defaultMetric['user']['day'][] = 'count_of_daily_review_story_in_user';
$config->metriclib->defaultMetric['user']['day'][] = 'count_of_daily_finished_task_in_user';
$config->metriclib->defaultMetric['user']['day'][] = 'count_of_daily_fixed_bug_in_user';
$config->metriclib->defaultMetric['user']['day'][] = 'count_of_daily_review_feedback_in_user';
