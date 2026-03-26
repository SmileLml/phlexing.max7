<?php
namespace zin;
global $lang;

$story      = data('story');
$docField   = array();
$docField[] = section
(
    set::title($lang->story->docs),
    doclist(set::data($story))
);
query('#files')->before($docField);
