<?php
if(!defined('TABLE_FEEDBACK')) define('TABLE_FEEDBACK', '`' . $config->db->prefix . 'feedback`');
if(!defined('TABLE_FEEDBACKVIEW')) define('TABLE_FEEDBACKVIEW', '`' . $config->db->prefix . 'feedbackview`');
if(!defined('TABLE_FAQ')) define('TABLE_FAQ', '`' . $config->db->prefix . 'faq`');

$config->objectTables['feedback'] = TABLE_FEEDBACK;
$config->objectTables['faq']      = TABLE_FAQ;

$filter->default->cookie['feedbackView']   = 'equal::1';
$filter->default->cookie['feedbackModule'] = 'int';

$filter->feedback = new stdclass();
$filter->feedback->export = new stdclass();
$filter->feedback->export->cookie['checkedItem']  = 'reg::checked';

$filter->feedback->batchcreate = new stdclass();
$filter->feedback->batchcreate->cookie['preProductID'] = 'int';

$config->logonMethods[] = 'feedback.index';
