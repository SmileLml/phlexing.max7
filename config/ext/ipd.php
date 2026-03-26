<?php
$config->ipdVersion     = '4.1';
$config->showStoryGrade = true;

$filter->charter = new stdclass();
$filter->charter->default = new stdclass();
$filter->charter->default->cookie['browseType'] = 'reg::word';

$filter->marketreport = new stdclass();
$filter->marketreport->browse = new stdclass();
$filter->marketreport->all    = new stdclass();
$filter->marketreport->browse->cookie['involvedReport'] = 'code';
$filter->marketreport->all->cookie['involvedReport']    = 'code';

$filter->marketresearch = new stdclass();
$filter->marketresearch->browse  = new stdclass();
$filter->marketresearch->all     = new stdclass();
$filter->marketresearch->reports = new stdclass();
$filter->marketresearch->browse->cookie['involvedResearch'] = 'code';
$filter->marketresearch->all->cookie['involvedResearch']    = 'code';
$filter->marketresearch->reports->cookie['involvedReport']  = 'code';

$filter->demand = new stdclass();

$filter->demand->browse = new stdclass();
$filter->demand->export = new stdclass();

$filter->demand->browse->cookie['requirementModule'] = 'int';
$filter->demand->export->cookie['checkedItem']       = 'reg::checked';

$config->logonMethods[] = 'demand.showimport';
$config->logonMethods[] = 'story.showimport';

if($config->edition == 'ipd') $config->featureGroup->product = array('roadmap', 'track', 'ER');

$config->hasDropmenuApps[]     = 'market';
$config->excludeDropmenuList[] = 'marketresearch-all';
