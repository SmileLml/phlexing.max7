<?php
namespace zin;
$message = $this->task->checkDepend($task->id, 'begin');
if($message)
{
    modalHeader(); 
    div
    (
        setClass('inline-flex items-center alert with-icon w-full py-4'),
        icon('exclamation-sign text-gray icon-2x pl-2 text-warning'),
        span($message)
    );
    render();
}
else
{
    $oldDir = getcwd();
    chdir($app->getModuleRoot() . 'task/ui/');
    include './start.html.php';
    chdir($oldDir);
}
