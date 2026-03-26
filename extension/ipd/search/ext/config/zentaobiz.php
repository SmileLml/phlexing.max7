<?php
$config->search->fields->feedback = new stdclass();
$config->search->fields->feedback->id         = 'id';
$config->search->fields->feedback->title      = 'title';
$config->search->fields->feedback->content    = 'desc';
$config->search->fields->feedback->addedDate  = 'openedDate';
$config->search->fields->feedback->editedDate = 'editedDate';

$config->search->fields->ticket = new stdclass();
$config->search->fields->ticket->id         = 'id';
$config->search->fields->ticket->title      = 'title';
$config->search->fields->ticket->content    = 'desc';
$config->search->fields->ticket->addedDate  = 'openedDate';
$config->search->fields->ticket->editedDate = 'editedDate';

$config->search->fields->deploy = new stdclass();
$config->search->fields->deploy->id         = 'id';
$config->search->fields->deploy->title      = 'name';
$config->search->fields->deploy->content    = 'desc';
$config->search->fields->deploy->addedDate  = 'createdDate';
$config->search->fields->deploy->editedDate = 'createdDate';

$config->search->fields->deploystep = new stdclass();
$config->search->fields->deploystep->id         = 'id';
$config->search->fields->deploystep->title      = 'title';
$config->search->fields->deploystep->content    = 'content';
$config->search->fields->deploystep->addedDate  = 'createdDate';
$config->search->fields->deploystep->editedDate = 'createdDate';

$config->search->fields->practice = new stdclass();
$config->search->fields->practice->id           = 'id';
$config->search->fields->practice->title        = 'title';
$config->search->fields->practice->content      = 'code,labels,contributor,summary,content';
$config->search->fields->deploystep->addedDate  = 'date';
$config->search->fields->deploystep->editedDate = 'date';
