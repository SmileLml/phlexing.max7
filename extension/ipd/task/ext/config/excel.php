<?php
$config->task->listFields     = "module,assignedTo,mode,story,pri,type";
$config->task->sysListFields  = "module,story";
$config->task->templateFields = "module,story,assignedTo,mode,level,name,desc,type,pri,estimate,estStarted,deadline";

$config->task->exportFields = '
    id, project, execution, module, story, fromBug,
    name, desc, parent,
    type, pri, estStarted, realStarted, deadline, status,estimate, consumed, left,
    mailto, progress, mode,
    openedBy, openedDate, assignedTo, assignedDate,
    finishedBy, finishedDate, canceledBy, canceledDate,
    closedBy, closedDate, closedReason,
    lastEditedBy, lastEditedDate,files
    ';

$config->task->cascade = array('story' => 'module');
