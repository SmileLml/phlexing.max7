<?php
if(!isset($config->word)) $config->word = new stdclass();
$config->word->story       = new stdclass();
$config->word->epic        = new stdclass();
$config->word->requirement = new stdclass();

$config->word->story->exportFields[] = 'id';
$config->word->story->exportFields[] = 'spec';
$config->word->story->exportFields[] = 'verify';
$config->word->story->exportFields[] = 'pri';
$config->word->story->exportFields[] = 'estimate';
$config->word->story->exportFields[] = 'stage';
$config->word->story->exportFields[] = 'status';
$config->word->story->exportFields[] = 'files';

$config->word->story->titleField       = 'title';
$config->word->epic->titleField        = 'title';
$config->word->requirement->titleField = 'title';

$config->word->story->style['title']  = 'title';
$config->word->story->style['spec']   = 'showImage';
$config->word->story->style['verify'] = 'showImage';

$config->word->tableName = new stdclass();
$config->word->tableName->story       = TABLE_STORY;
$config->word->tableName->epic        = TABLE_STORY;
$config->word->tableName->requirement = TABLE_STORY;

$config->word->header = new stdclass();
$config->word->header->story       = array('name' => 'product',   'tableName' => TABLE_PRODUCT);
$config->word->header->epic        = array('name' => 'product',   'tableName' => TABLE_PRODUCT);
$config->word->header->requirement = array('name' => 'product',   'tableName' => TABLE_PRODUCT);
$config->word->header->task        = array('name' => 'execution', 'tableName' => TABLE_EXECUTION);

$config->word->size = new stdclass();
$config->word->size->titles[1] = 20;
$config->word->size->titles[2] = 16;
$config->word->size->titles[3] = 12;
$config->word->size->titles[4] = 8;

$config->word->imageExtension = array('png', 'jpg', 'gif', 'jpeg');

global $app;
$config->word->filePath = $app->getBasePath() . 'www/';

$config->word->sectPrAbeam   = '<w:sectPr><w:pgSz w:w="16838" w:h="11906" /><w:pgMar w:top="1800" w:right="1440" w:bottom="1800" w:left="1440" w:header="851" w:footer="992" w:gutter="0"/><w:cols w:space="720" w:num="1"/><w:docGrid w:type="lines" w:linePitch="312"/></w:sectPr>';
$config->word->sectProVertical = '<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1440" w:right="1800" w:bottom="1440" w:left="1800" w:header="851" w:footer="992" w:gutter="0"/><w:cols w:space="720" w:num="1"/><w:docGrid w:type="lines" w:linePitch="312"/></w:sectPr>';
