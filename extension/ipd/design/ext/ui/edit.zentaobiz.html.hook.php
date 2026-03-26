<?php
namespace zin;
global $lang;

$design   = data('design');
$docField = array();
$docField[] = formGroup
(
    set::label($lang->design->docs),
    doclist(set::data($design))
);
query('#files')->before($docField);
