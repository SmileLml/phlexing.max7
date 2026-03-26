<?php
$config->baseline->editor = new stdclass();
$config->baseline->editor->createtemplate = array('id' => 'content', 'tools' => 'fullTools');
$config->baseline->editor->edittemplate   = array('id' => 'content', 'tools' => 'fullTools');
$config->baseline->editor->editbook       = array('id' => 'content', 'tools' => 'fullTools');

$config->baseline->markdown = new stdclass();
$config->baseline->markdown->createtemplate = array('id' => 'contentMarkdown', 'tools' => 'withchange');
$config->baseline->markdown->edittemplate   = array('id' => 'contentMarkdown', 'tools' => 'withchange');

$config->baseline->createtemplate = new stdclass();
$config->baseline->createtemplate->requiredFields = 'title,templateType';

$config->baseline->edittemplate = new stdclass();
$config->baseline->edittemplate->requiredFields = 'title,templateType';
