<?php
if(!defined('TABLE_ATTEND'))     define('TABLE_ATTEND',     '`' . $config->db->prefix . 'attend`');
if(!defined('TABLE_ATTENDSTAT')) define('TABLE_ATTENDSTAT', '`' . $config->db->prefix . 'attendstat`');
if(!defined('TABLE_HOLIDAY'))    define('TABLE_HOLIDAY',    '`' . $config->db->prefix . 'holiday`');
if(!defined('TABLE_LEAVE'))      define('TABLE_LEAVE',      '`' . $config->db->prefix . 'leave`');
if(!defined('TABLE_OVERTIME'))   define('TABLE_OVERTIME',   '`' . $config->db->prefix . 'overtime`');
if(!defined('TABLE_MAKEUP'))     define('TABLE_MAKEUP',     '`' . $config->db->prefix . 'overtime`');
if(!defined('TABLE_LIEU'))       define('TABLE_LIEU',       '`' . $config->db->prefix . 'lieu`');
if(!defined('TABLE_TRIP'))       define('TABLE_TRIP',       '`' . $config->db->prefix . 'trip`');

$config->objectTables['attend']     = TABLE_ATTEND;
$config->objectTables['attendstat'] = TABLE_ATTENDSTAT;
$config->objectTables['holiday']    = TABLE_HOLIDAY;
$config->objectTables['leave']      = TABLE_LEAVE;
$config->objectTables['overtime']   = TABLE_OVERTIME;
$config->objectTables['makeup']     = TABLE_MAKEUP;
$config->objectTables['lieu']       = TABLE_LIEU;
$config->objectTables['trip']       = TABLE_TRIP;
