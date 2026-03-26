<?php
$config->ticket->listFields     = "module,product,type,openedBuild,assignedTo,mailto,pri";
$config->ticket->sysListFields  = "module,product,type,openedBuild,assignedTo,mailto,pri";
$config->ticket->templateFields = 'product,module,title,pri,type,desc,openedBuild,assignedTo,deadline,customer,contact,notifyEmail,mailto,keywords';
$config->ticket->cascade        = array('module' => 'product', 'openedBuild' => 'product');
$config->ticket->exportFields   = 'id, product, module, title, status, pri, type, desc, openedBuild, customer, contact, notifyEmail, deadline, assignedTo, mailto, keywords, startedBy, startedDate, feedback, openedBy, openedDate, activatedBy, activatedDate, activatedCount, resolvedBy, resolvedDate, resolution, closedBy, closedDate, closedReason, editedBy, editedDate';
