<?php
$config->traincourse = new stdclass();
$config->traincourse->editor = new stdclass();
$config->traincourse->editor->editchapter  = array('id' => 'desc', 'tools' => 'simpleTools');
$config->traincourse->editor->editcourse   = array('id' => 'desc', 'tools' => 'simpleTools');
$config->traincourse->editor->createcourse = array('id' => 'desc', 'tools' => 'simpleTools');

$config->traincourse->markdown = new stdclass();
$config->traincourse->markdown->edit = array('id' => 'contentMarkdown', 'tools' => 'withchange');

$config->traincourse->create = new stdclass();
$config->traincourse->create->requiredFields = 'name';

$config->traincourse->edit = new stdclass();
$config->traincourse->edit->requiredFields = 'name';

$config->traincourse->uploadPath = 'data' . DIRECTORY_SEPARATOR . 'course' . DIRECTORY_SEPARATOR;

$config->traincourse->api = 'https://api.zentao.net';

$config->traincourse->contentTypeList = array('video', 'chapter');

global $lang;
$config->traincourse->search['module']            = 'traincourse';
$config->traincourse->search['fields']['name']    = $lang->traincourse->name;
$config->traincourse->search['fields']['teacher'] = $lang->traincourse->teacher;
$config->traincourse->search['fields']['status']  = $lang->traincourse->status;

$config->traincourse->search['params']['name']    = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->traincourse->search['params']['teacher'] = array('operator' => 'include', 'control' => 'input', 'values' => '');
$config->traincourse->search['params']['status']  = array('operator' => '=', 'control' => 'select', 'values' => $lang->traincourse->progressList);

$config->practice = new stdclass();
$config->practice->updateInterval         = 86400 * 7;
$config->practice->practiceDataPath       = 'db' . DS . 'practice' . DS;
$config->practice->practiceStructData     = $config->practice->practiceDataPath . 'practicestruct';
$config->practice->practiceStructTreeData = $config->practice->practiceDataPath . 'practicetreestruct';

$config->practice->api = new stdclass();
$config->practice->api->getPractices = 'https://www.rongpm.com/rrpl.json';
$config->practice->api->getContent   = 'https://www.rongpm.com/rrpl/%s.md';
$config->practice->api->httpHeader   = array('Origin: ' . $_SERVER['SERVER_NAME'], "Authorization: {$config->global->sn}", 'Content-Type: application/json', 'X-Requested-With: XMLHttpRequest');

$config->practice->labelClassList = array();
$config->practice->labelClassList[] = 'label-item-primary';
$config->practice->labelClassList[] = 'label-item-secondary';
$config->practice->labelClassList[] = 'label-item-special';
