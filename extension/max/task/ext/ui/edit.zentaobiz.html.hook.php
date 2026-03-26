<?php
namespace zin;
global $lang;

$task     = data('task');
$docField = array();
$docField[] = section
(
    set::title($lang->task->docs),
    doclist(set::data($task))
);
query('#files')->before($docField);
