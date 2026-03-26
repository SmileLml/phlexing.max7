<?php
namespace zin;

$project = data('project');

if($project->isTpl)
{
    query('#products')->remove();
}
