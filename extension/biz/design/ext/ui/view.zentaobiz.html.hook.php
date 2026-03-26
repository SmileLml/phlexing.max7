<?php
namespace zin;
global $lang;

$design = data('design');
$docContent[] = section
(
    setID('desc'),
    set::title($lang->design->docs),
    docList(set::data($design), set::mode('view'))
);
query('#desc')->after($docContent);
