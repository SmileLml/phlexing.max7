<?php
namespace zin;

global $app;
$taskID        = data('task.id');
$startMessage  = $app->control->task->checkDepend($taskID, 'begin');
$finishMessage = $app->control->task->checkDepend($taskID, 'end');
jsVar('startMessage',  $startMessage);
jsVar('finishMessage', $finishMessage);

pageJS(<<<JAVASCRIPT
$(function()
{
    if(startMessage.length > 0)
    {
        var \$startA = $('.center .float-toolbar .icon-play').closest('a');
        \$startA.attr('data-toggle', 'tooltip');
        \$startA.attr('data-type', 'warning');
        \$startA.attr('data-title', startMessage);
        \$startA.removeAttr('href');
    }
    if(finishMessage.length > 0)
    {
        var \$finishMessageA = $('.center .float-toolbar .icon-checked').closest('a');
        \$finishMessageA.attr('data-toggle', 'tooltip');
        \$finishMessageA.attr('data-type', 'warning');
        \$finishMessageA.attr('data-title', finishMessage);
        \$finishMessageA.removeAttr('href');
    }
});
JAVASCRIPT
);
