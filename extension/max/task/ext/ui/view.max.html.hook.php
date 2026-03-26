<?php
namespace zin;

global $lang;
$version  = data('version');
$task     = data('task');
$onItem   = $version ? $version : $task->version;
$taskSpec = data('taskSpec');

$versionItems = array();
for($i = $task->version; $i > 0; $i--)
{
    $versionItems[] = array('value' => $i, 'text' => "#{$i}", 'url' => createLink('task', 'view', "taskID={$taskID}&version={$i}"), 'active' => $onItem == $i);
}

if($version)
{
    query('#mainContent .entity-title-text')->html($taskSpec->name)->prop('title', $taskSpec->name);
    query('#mainContent .estStarted-text')->html($taskSpec->estStarted);
    query('#mainContent .deadline-text')->html($taskSpec->deadline);

    $today = helper::today();
    $delay = helper::diffDate($today, $taskSpec->deadline);
    if($delay > 0)
    {
        $delayDesc = sprintf($lang->task->delayWarning, $delay);
        query('#mainContent .deadline-text')->append
            (
                label
                (
                    setClass('danger-pale circle'),
                    $delayDesc
                )
            );
    }
}

query('#mainContent .entity-title-text')->after(
    dropdown
    (
        btn(setClass('ghost bg-gray-200 bg-opacity-50'), "#{$onItem}"),
        set::items($versionItems)
    )
);
