<?php
$config->objectTables['effort'] = TABLE_EFFORT;

$filter->effort = new stdclass();
$filter->effort->export = new stdclass();
$filter->effort->export->cookie['checkedItem'] = 'reg::checked';

$filter->effort->createforobject = new stdclass();
$filter->effort->createforobject->cookie['taskEffortFold'] = 'reg::checked';
