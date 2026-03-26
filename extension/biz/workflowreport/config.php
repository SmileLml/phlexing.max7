<?php
$config->workflowreport->require = new stdclass();
$config->workflowreport->require->create = 'dimension, name';
$config->workflowreport->require->edit   = 'dimension, name';

/* Fields to be exclude when querying report dimension fields. */
$config->workflowreport->excludeFields = array('id', 'parent', 'mailto', 'deleted');
