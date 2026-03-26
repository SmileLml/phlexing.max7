<?php
$config->reviewcl = new stdclass();

$config->reviewcl->create = new stdclass();
$config->reviewcl->edit   = new stdclass();
$config->reviewcl->create->requiredFields = 'title';
$config->reviewcl->edit->requiredFields   = 'title';
