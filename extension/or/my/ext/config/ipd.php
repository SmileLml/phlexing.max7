<?php
global $app;
$app->loadLang('researchtask');
$app->loadLang('marketreport');
$app->loadLang('marketresearch');
$app->loadLang('demand');

$config->my->task->dtable->fieldList['name']['title']      = $lang->researchtask->name;
$config->my->task->dtable->fieldList['project']['title']   = $lang->marketreport->research;
$config->my->task->dtable->fieldList['execution']['title'] = $lang->marketresearch->execution;

$config->my->task->dtable->fieldList['name']['link'] = array('url' => array('module' => 'researchtask', 'method' => 'view', 'params' => 'taskID={id}'), 'data-app' => 'market');
unset($config->my->task->dtable->fieldList['name']['data-toggle'], $config->my->task->dtable->fieldList['name']['data-size']);
$config->my->task->dtable->fieldList['project']['link'] = array('module' => 'marketresearch', 'method' => 'task', 'params' => 'projectID={project}');
unset($config->my->task->dtable->fieldList['execution']['link']);

$config->my->task->actionList['start']['url']        = array('module' => 'researchtask', 'method' => 'start', 'params' => 'taskID={id}');
$config->my->task->actionList['finish']['url']       = array('module' => 'researchtask', 'method' => 'finish', 'params' => 'taskID={id}');
$config->my->task->actionList['close']['url']        = array('module' => 'researchtask', 'method' => 'close', 'params' => 'taskID={id}');
$config->my->task->actionList['record']['url']       = array('module' => 'researchtask', 'method' => 'recordWorkhour', 'params' => 'taskID={id}');
$config->my->task->actionList['edit']['url']         = array('module' => 'researchtask', 'method' => 'edit', 'params' => 'taskID={id}');
$config->my->task->actionList['batchCreate']['url']  = array('module' => 'researchtask', 'method' => 'batchCreate', 'params' => 'executionID={execution}&taskID={id}');

$config->my->task->dtable->fieldList['actions']['list'] = $config->my->task->actionList;

$config->my->demand = new stdclass();
$config->my->demand->actionList = array();
$config->my->demand->actionList['change']['icon']        = 'alter';
$config->my->demand->actionList['change']['text']        = $lang->demand->change;
$config->my->demand->actionList['change']['hint']        = $lang->demand->change;
$config->my->demand->actionList['change']['url']         = array('module' => 'demand', 'method' => 'change', 'params' => 'demandID={id}');
$config->my->demand->actionList['change']['data-toggle'] = 'modal';

$config->my->demand->actionList['submitReview']['icon']        = 'confirm';
$config->my->demand->actionList['submitReview']['text']        = $lang->demand->submitReview;
$config->my->demand->actionList['submitReview']['hint']        = $lang->demand->submitReview;
$config->my->demand->actionList['submitReview']['url']         = array('module' => 'demand', 'method' => 'submitReview', 'params' => 'demandID={id}');
$config->my->demand->actionList['submitReview']['data-toggle'] = 'modal';

$config->my->demand->actionList['review']['icon']        = 'search';
$config->my->demand->actionList['review']['text']        = $lang->demand->review;
$config->my->demand->actionList['review']['hint']        = $lang->demand->review;
$config->my->demand->actionList['review']['url']         = array('module' => 'demand', 'method' => 'review', 'params' => 'demandID={id}');
$config->my->demand->actionList['review']['data-toggle'] = 'modal';

$config->my->demand->actionList['recall']['icon']      = 'undo';
$config->my->demand->actionList['recall']['text']      = $lang->demand->recall;
$config->my->demand->actionList['recall']['hint']      = $lang->demand->recall;
$config->my->demand->actionList['recall']['url']       = array('module' => 'demand', 'method' => 'recall', 'params' => 'demandID={id}');
$config->my->demand->actionList['recall']['className'] = 'ajax-submit';

$config->my->demand->actionList['distribute']['icon']        = 'sitemap';
$config->my->demand->actionList['distribute']['text']        = $lang->demand->distribute;
$config->my->demand->actionList['distribute']['hint']        = $lang->demand->distributeHint;
$config->my->demand->actionList['distribute']['url']         = array('module' => 'demand', 'method' => 'distribute', 'params' => 'demandID={id}');
$config->my->demand->actionList['distribute']['data-toggle'] = 'modal';
$config->my->demand->actionList['distribute']['data-size']   = 'lg';

$config->my->demand->actionList['edit']['icon']        = 'edit';
$config->my->demand->actionList['edit']['text']        = $lang->demand->edit;
$config->my->demand->actionList['edit']['hint']        = $lang->demand->edit;
$config->my->demand->actionList['edit']['url']         = array('module' => 'demand', 'method' => 'edit', 'params' => 'demandID={id}');
$config->my->demand->actionList['edit']['data-toggle'] = 'modal';
$config->my->demand->actionList['edit']['data-size']   = 'lg';

$config->my->demand->actionList['batchCreate']['icon']        = 'split';
$config->my->demand->actionList['batchCreate']['text']        = $lang->demand->subdivide;
$config->my->demand->actionList['batchCreate']['hint']        = $lang->demand->subdivideHint;
$config->my->demand->actionList['batchCreate']['url']         = array('module' => 'demand', 'method' => 'batchCreate', 'params' => 'poolID={pool}&demandID={id}&confirm=no');
$config->my->demand->actionList['batchCreate']['data-toggle'] = 'modal';
$config->my->demand->actionList['batchCreate']['data-size']   = 'lg';

$config->my->demand->actionList['close']['icon']        = 'off';
$config->my->demand->actionList['close']['text']        = $lang->demand->close;
$config->my->demand->actionList['close']['hint']        = $lang->demand->close;
$config->my->demand->actionList['close']['url']         = array('module' => 'demand', 'method' => 'close', 'params' => 'demandID={id}');
$config->my->demand->actionList['close']['data-toggle'] = 'modal';
$config->my->demand->actionList['close']['data-size']   = 'lg';

$config->my->demand->actionList['processDemandChange']['icon']     = 'ok';
$config->my->demand->actionList['processDemandChange']['text']     = $lang->demand->processDemandChange;
$config->my->demand->actionList['processDemandChange']['hint']     = $lang->demand->processDemandChange;
$config->my->demand->actionList['processDemandChange']['url']      = array('module' => 'demand', 'method' => 'processDemandChange', 'params' => 'demandID={id}');
$config->my->demand->actionList['processDemandChange']['data-app'] = 'my';

$config->my->demand->dtable = new stdclass();
$config->my->demand->dtable->fieldList['id']['name']     = 'id';
$config->my->demand->dtable->fieldList['id']['title']    = $lang->idAB;
$config->my->demand->dtable->fieldList['id']['type']     = 'id';
$config->my->demand->dtable->fieldList['id']['sortType'] = true;

$config->my->demand->dtable->fieldList['title']['name']         = 'title';
$config->my->demand->dtable->fieldList['title']['title']        = $lang->demand->demandTitle;
$config->my->demand->dtable->fieldList['title']['type']         = 'title';
$config->my->demand->dtable->fieldList['title']['nestedToggle'] = true;
$config->my->demand->dtable->fieldList['title']['link']         = array('module' => 'demand', 'method' => 'view', 'params' => 'id={id}');
$config->my->demand->dtable->fieldList['title']['fixed']        = 'left';
$config->my->demand->dtable->fieldList['title']['sortType']     = true;

$config->my->demand->dtable->fieldList['pri']['name']     = 'pri';
$config->my->demand->dtable->fieldList['pri']['title']    = $lang->priAB;
$config->my->demand->dtable->fieldList['pri']['type']     = 'pri';
$config->my->demand->dtable->fieldList['pri']['sortType'] = true;

$config->my->demand->dtable->fieldList['status']['name']      = 'status';
$config->my->demand->dtable->fieldList['status']['title']     = $lang->statusAB;
$config->my->demand->dtable->fieldList['status']['type']      = 'status';
$config->my->demand->dtable->fieldList['status']['statusMap'] = $lang->demand->statusList;
$config->my->demand->dtable->fieldList['status']['sortType']  = true;

$config->my->demand->dtable->fieldList['pool']['name']     = 'poolName';
$config->my->demand->dtable->fieldList['pool']['title']    = $lang->demand->pool;
$config->my->demand->dtable->fieldList['pool']['type']     = 'text';
$config->my->demand->dtable->fieldList['pool']['sortType'] = true;

$config->my->demand->dtable->fieldList['category']['name']     = 'category';
$config->my->demand->dtable->fieldList['category']['title']    = $lang->demand->category;
$config->my->demand->dtable->fieldList['category']['map']      = $lang->demand->categoryList;
$config->my->demand->dtable->fieldList['category']['type']     = 'text';
$config->my->demand->dtable->fieldList['category']['sortType'] = true;

$config->my->demand->dtable->fieldList['duration']['name']     = 'duration';
$config->my->demand->dtable->fieldList['duration']['title']    = $lang->demand->duration;
$config->my->demand->dtable->fieldList['duration']['map']      = $lang->demand->durationList;
$config->my->demand->dtable->fieldList['duration']['type']     = 'text';
$config->my->demand->dtable->fieldList['duration']['sortType'] = true;

$config->my->demand->dtable->fieldList['BSA']['name']     = 'BSA';
$config->my->demand->dtable->fieldList['BSA']['title']    = $lang->demand->BSA;
$config->my->demand->dtable->fieldList['BSA']['map']      = $lang->demand->bsaList;
$config->my->demand->dtable->fieldList['BSA']['type']     = 'text';
$config->my->demand->dtable->fieldList['BSA']['sortType'] = true;

$config->my->demand->dtable->fieldList['actions']['name']     = 'actions';
$config->my->demand->dtable->fieldList['actions']['title']    = $lang->actions;
$config->my->demand->dtable->fieldList['actions']['type']     = 'actions';
$config->my->demand->dtable->fieldList['actions']['sortType'] = false;
$config->my->demand->dtable->fieldList['actions']['list']     = $config->my->demand->actionList;
$config->my->demand->dtable->fieldList['actions']['menu']     = array(array('processDemandChange'), array('change', 'submitReview|review', 'recall', 'distribute', 'edit', 'batchCreate', 'close'));
