<?php
$config->todo->moduleList[] = 'issue';
$config->todo->moduleList[] = 'risk';
$config->todo->moduleList[] = 'opportunity';
$config->todo->moduleList[] = 'review';

$config->todo->getUserObjectsMethod['issue']       = 'ajaxGetUserIssues';
$config->todo->getUserObjectsMethod['risk']        = 'ajaxGetUserRisks';
$config->todo->getUserObjectsMethod['opportunity'] = 'ajaxGetUseropportunities';
$config->todo->getUserObjectsMethod['review']      = 'ajaxGetUserReviews';

$config->todo->objectList['issue']       = 'issues';
$config->todo->objectList['risk']        = 'risks';
$config->todo->objectList['opportunity'] = 'opportunities';
$config->todo->objectList['review']      = 'reviews';
