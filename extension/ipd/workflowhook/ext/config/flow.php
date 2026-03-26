<?php
$config->workflowhook->tables['action']     = TABLE_ACTION;
$config->workflowhook->tables['attend']     = TABLE_ATTEND;
$config->workflowhook->tables['branch']     = TABLE_BRANCH;
$config->workflowhook->tables['deploy']     = TABLE_DEPLOY;
$config->workflowhook->tables['dept']       = TABLE_DEPT;
$config->workflowhook->tables['effort']     = TABLE_EFFORT;
$config->workflowhook->tables['faq']        = TABLE_FAQ;
$config->workflowhook->tables['holiday']    = TABLE_HOLIDAY;
$config->workflowhook->tables['host']       = TABLE_HOST;
$config->workflowhook->tables['leave']      = TABLE_LEAVE;
$config->workflowhook->tables['lieu']       = TABLE_LIEU;
$config->workflowhook->tables['makeup']     = TABLE_OVERTIME;
$config->workflowhook->tables['overtime']   = TABLE_OVERTIME;
$config->workflowhook->tables['score']      = TABLE_SCORE;
$config->workflowhook->tables['serverroom'] = TABLE_SERVERROOM;
$config->workflowhook->tables['service']    = TABLE_SERVICE;
$config->workflowhook->tables['testreport'] = TABLE_TESTREPORT;
$config->workflowhook->tables['todo']       = TABLE_TODO;
$config->workflowhook->tables['trip']       = TABLE_TRIP;
$config->workflowhook->tables['user']       = TABLE_USER;
$config->workflowhook->tables['storyspec']  = TABLE_STORYSPEC;
$config->workflowhook->tables['file']       = TABLE_FILE;

$config->workflowhook->skipFields['action']  = array('read', 'efforted');
$config->workflowhook->skipFields['dept']    = array('path', 'position', 'function');
$config->workflowhook->skipFields['host']    = array('assetID');
$config->workflowhook->skipFields['service'] = array('hosts', 'path');
$config->workflowhook->skipFields['todo']    = array('config');
$config->workflowhook->skipFields['user']    = array('avatar', 'birthday', 'fails', 'locked', 'scoreLevel', 'clientStatus', 'clientLang');

if(!isset($config->workflowhook->apps)) $config->workflowhook->apps = array();
