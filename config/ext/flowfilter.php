<?php
$filter->flow = new stdclass();
$filter->flow->default = new stdclass();
$filter->flow->default->cookie['preProductID'] = 'int';
$filter->flow->ajaxgetmore = new stdclass();
$filter->flow->ajaxgetmore->get['search'] = 'reg::any';
$filter->flow->ajaxgetmore->get['limit']  = 'int';

$config->searchLimit = 0;
$config->flowLimit   = 0;
