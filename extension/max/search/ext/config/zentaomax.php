<?php
$config->search->fields->issue = new stdclass();
$config->search->fields->issue->id         = 'id';
$config->search->fields->issue->title      = 'title';
$config->search->fields->issue->content    = 'desc';
$config->search->fields->issue->addedDate  = 'createdDate';
$config->search->fields->issue->editedDate = 'editedDate';

$config->search->fields->risk = new stdclass();
$config->search->fields->risk->id         = 'id';
$config->search->fields->risk->title      = 'name';
$config->search->fields->risk->content    = 'remedy,prevention';
$config->search->fields->risk->addedDate  = 'createdDate';
$config->search->fields->risk->editedDate = 'editedDate';

$config->search->fields->opportunity = new stdclass();
$config->search->fields->opportunity->id         = 'id';
$config->search->fields->opportunity->title      = 'name';
$config->search->fields->opportunity->content    = 'resolution,prevention';
$config->search->fields->opportunity->addedDate  = 'createdDate';
$config->search->fields->opportunity->editedDate = 'editedDate';

$config->search->fields->trainplan = new stdclass();
$config->search->fields->trainplan->id         = 'id';
$config->search->fields->trainplan->title      = 'name';
$config->search->fields->trainplan->content    = 'place,lecturer,summary';
$config->search->fields->trainplan->addedDate  = 'createdDate';
$config->search->fields->trainplan->editedDate = 'editedDate';
