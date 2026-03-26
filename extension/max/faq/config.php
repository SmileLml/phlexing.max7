<?php
$config->faq->create = new stdclass();
$config->faq->create->requiredFields = 'product,question';

$config->faq->edit = new stdclass();
$config->faq->edit->requiredFields = 'product,question';

$config->faq->editor = new stdclass();
$config->faq->editor->create = array('id' => 'answer', 'tools' => 'simpleTools');
$config->faq->editor->edit   = array('id' => 'answer', 'tools' => 'simpleTools');
