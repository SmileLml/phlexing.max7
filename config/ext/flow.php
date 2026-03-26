<?php
$config->vision         = '';
$config->flowLimit      = 100;       // 页面加载时载入数据的最大数量。The maximum number of data that are loaded when the page loads.
$config->openedApproval = true;      // 是否需要启用工作流审批功能。禅道企业版是关闭的。

$config->flow = new stdclass();

if(!defined('TABLE_WORKFLOW'))               define('TABLE_WORKFLOW',               '`' . $config->db->prefix . 'workflow`');
if(!defined('TABLE_WORKFLOWACTION'))         define('TABLE_WORKFLOWACTION',         '`' . $config->db->prefix . 'workflowaction`');
if(!defined('TABLE_WORKFLOWDATASOURCE'))     define('TABLE_WORKFLOWDATASOURCE',     '`' . $config->db->prefix . 'workflowdatasource`');
if(!defined('TABLE_WORKFLOWFIELD'))          define('TABLE_WORKFLOWFIELD',          '`' . $config->db->prefix . 'workflowfield`');
if(!defined('TABLE_WORKFLOWLABEL'))          define('TABLE_WORKFLOWLABEL',          '`' . $config->db->prefix . 'workflowlabel`');
if(!defined('TABLE_WORKFLOWLAYOUT'))         define('TABLE_WORKFLOWLAYOUT',         '`' . $config->db->prefix . 'workflowlayout`');
if(!defined('TABLE_WORKFLOWLINKDATA'))       define('TABLE_WORKFLOWLINKDATA',       '`' . $config->db->prefix . 'workflowlinkdata`');
if(!defined('TABLE_WORKFLOWRELATION'))       define('TABLE_WORKFLOWRELATION',       '`' . $config->db->prefix . 'workflowrelation`');
if(!defined('TABLE_WORKFLOWRELATIONLAYOUT')) define('TABLE_WORKFLOWRELATIONLAYOUT', '`' . $config->db->prefix . 'workflowrelationlayout`');
if(!defined('TABLE_WORKFLOWRULE'))           define('TABLE_WORKFLOWRULE',           '`' . $config->db->prefix . 'workflowrule`');
if(!defined('TABLE_WORKFLOWSQL'))            define('TABLE_WORKFLOWSQL',            '`' . $config->db->prefix . 'workflowsql`');
if(!defined('TABLE_WORKFLOWVERSION'))        define('TABLE_WORKFLOWVERSION',        '`' . $config->db->prefix . 'workflowversion`');
if(!defined('TABLE_WORKFLOWREPORT'))         define('TABLE_WORKFLOWREPORT',         '`' . $config->db->prefix . 'workflowreport`');
if(!defined('TABLE_WORKFLOWUI'))             define('TABLE_WORKFLOWUI',             '`' . $config->db->prefix . 'workflowui`');

$config->objectTables['workflow']               = TABLE_WORKFLOW;
$config->objectTables['workflowaction']         = TABLE_WORKFLOWACTION;
$config->objectTables['workflowdatasource']     = TABLE_WORKFLOWDATASOURCE;
$config->objectTables['workflowfield']          = TABLE_WORKFLOWFIELD;
$config->objectTables['workflowlabel']          = TABLE_WORKFLOWLABEL;
$config->objectTables['workflowlayout']         = TABLE_WORKFLOWLAYOUT;
$config->objectTables['workflowlinkdata']       = TABLE_WORKFLOWLINKDATA;
$config->objectTables['workflowrelation']       = TABLE_WORKFLOWRELATION;
$config->objectTables['workflowrelationlayout'] = TABLE_WORKFLOWRELATIONLAYOUT;
$config->objectTables['workflowrule']           = TABLE_WORKFLOWRULE;
$config->objectTables['workflowsql']            = TABLE_WORKFLOWSQL;
$config->objectTables['workflowversion']        = TABLE_WORKFLOWVERSION;
$config->objectTables['workflowreport']         = TABLE_WORKFLOWREPORT;

$filter->workflow = new stdclass();
$filter->workflow->browseflow = new stdclass();
$filter->workflow->browseflow->cookie['flowViewType'] = 'code';
