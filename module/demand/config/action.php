<?php
global $lang;

$config->demand->actionList = array();
$config->demand->actionList['change']['icon']  = 'alter';
$config->demand->actionList['change']['text']  = $lang->demand->change;
$config->demand->actionList['change']['hint']  = $lang->demand->change;
$config->demand->actionList['change']['url']   = array('module' => 'demand', 'method' => 'change', 'params' => 'demandID={id}');
$config->demand->actionList['change']['class'] = 'demand-change-btn';

$config->demand->actionList['submitReview']['icon']        = 'confirm';
$config->demand->actionList['submitReview']['text']        = $lang->demand->submitReview;
$config->demand->actionList['submitReview']['hint']        = $lang->demand->submitReview;
$config->demand->actionList['submitReview']['url']         = array('module' => 'demand', 'method' => 'submitReview', 'params' => 'demandID={id}');
$config->demand->actionList['submitReview']['data-toggle'] = 'modal';

$config->demand->actionList['review']['icon']  = 'search';
$config->demand->actionList['review']['text']  = $lang->demand->review;
$config->demand->actionList['review']['hint']  = $lang->demand->review;
$config->demand->actionList['review']['url']   = array('module' => 'demand', 'method' => 'review', 'params' => 'demandID={id}');
$config->demand->actionList['review']['class'] = 'demand-review-btn';

$config->demand->actionList['recall']['icon']      = 'undo';
$config->demand->actionList['recall']['text']      = $lang->demand->recall;
$config->demand->actionList['recall']['hint']      = $lang->demand->recall;
$config->demand->actionList['recall']['url']       = array('module' => 'demand', 'method' => 'recall', 'params' => 'demandID={id}');
$config->demand->actionList['recall']['className'] = 'ajax-submit';

$config->demand->actionList['distribute']['icon']        = 'sitemap';
$config->demand->actionList['distribute']['text']        = $lang->demand->distribute;
$config->demand->actionList['distribute']['hint']        = $lang->demand->distributeHint;
$config->demand->actionList['distribute']['url']         = array('module' => 'demand', 'method' => 'distribute', 'params' => 'demandID={id}');
$config->demand->actionList['distribute']['data-toggle'] = 'modal';
$config->demand->actionList['distribute']['data-size']   = 'lg';

$config->demand->actionList['edit']['icon'] = 'edit';
$config->demand->actionList['edit']['text'] = $lang->demand->edit;
$config->demand->actionList['edit']['hint'] = $lang->demand->edit;
$config->demand->actionList['edit']['url']  = array('module' => 'demand', 'method' => 'edit', 'params' => 'demandID={id}');

$config->demand->actionList['batchCreate']['icon'] = 'split';
$config->demand->actionList['batchCreate']['text'] = $lang->demand->subdivide;
$config->demand->actionList['batchCreate']['hint'] = $lang->demand->subdivideHint;
$config->demand->actionList['batchCreate']['url']  = array('module' => 'demand', 'method' => 'batchCreate', 'params' => 'poolID={pool}&demandID={id}');

$config->demand->actionList['close']['icon']        = 'off';
$config->demand->actionList['close']['text']        = $lang->demand->close;
$config->demand->actionList['close']['hint']        = $lang->demand->close;
$config->demand->actionList['close']['url']         = array('module' => 'demand', 'method' => 'close', 'params' => 'demandID={id}');
$config->demand->actionList['close']['data-toggle'] = 'modal';

$config->demand->actionList['activate']['icon']        = 'magic';
$config->demand->actionList['activate']['text']        = $lang->demand->activate;
$config->demand->actionList['activate']['hint']        = $lang->demand->activate;
$config->demand->actionList['activate']['url']         = array('module' => 'demand', 'method' => 'activate', 'params' => 'demandID={id}');
$config->demand->actionList['activate']['data-toggle'] = 'modal';

$config->demand->actionList['copy']['icon'] = 'copy';
$config->demand->actionList['copy']['text'] = $lang->demand->copy;
$config->demand->actionList['copy']['hint'] = $lang->demand->copy;
$config->demand->actionList['copy']['url']  = array('module' => 'demand', 'method' => 'create', 'params' => 'poolID={pool}&demandID={id}');

$config->demand->actionList['delete']['icon']         = 'trash';
$config->demand->actionList['delete']['text']         = $lang->demand->delete;
$config->demand->actionList['delete']['hint']         = $lang->demand->delete;
$config->demand->actionList['delete']['url']          = array('module' => 'demand', 'method' => 'delete', 'params' => 'demandID={id}');
$config->demand->actionList['delete']['className']    = 'ajax-submit';
$config->demand->actionList['delete']['data-confirm'] = array('message' => $lang->demand->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->demand->actionList['delete']['notInModal']   = true;

$config->demand->actionList['processDemandChange']['icon'] = 'ok';
$config->demand->actionList['processDemandChange']['text'] = $lang->demand->processDemandChange;
$config->demand->actionList['processDemandChange']['hint'] = $lang->demand->processDemandChange;
$config->demand->actionList['processDemandChange']['url']  = array('module' => 'demand', 'method' => 'processDemandChange', 'params' => 'demandID={id}');

$config->demand->actions = new stdclass();
$config->demand->actions->view = array();
$config->demand->actions->view['mainActions']   = array('change', 'submitReview', 'review', 'recall', 'distribute', 'batchCreate', 'close', 'activate');
$config->demand->actions->view['suffixActions'] = array('edit', 'copy', 'delete');
