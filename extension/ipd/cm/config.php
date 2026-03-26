<?php
$config->cm = new stdclass();

$config->cm->create = new stdclass();
$config->cm->create->requiredFields = 'title,from,version';

$config->cm->edit = new stdclass();
$config->cm->edit->requiredFields = 'title,from,version';
