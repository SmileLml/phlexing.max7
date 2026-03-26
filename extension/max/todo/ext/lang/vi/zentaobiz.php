<?php
global $app;
if(!empty($app->user->feedback) or !empty($_COOKIE['feedbackView']))
{
    unset($lang->todo->typeList['bug']);
    unset($lang->todo->typeList['task']);
    unset($lang->todo->typeList['story']);
    unset($lang->todo->typeList['testtask']);
}
$lang->side->feedback = 'Phản hồi';
$lang->todo->feedback = 'Phản hồi';
$lang->todo->typeList['feedback'] = 'Phản hồi';
