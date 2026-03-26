<?php
$lang->action->objectTypes['feedback'] = 'Phản hồi';

$lang->action->label->feedback = 'Feedback|feedback|' . ($config->vision == 'lite' ? 'view' : 'adminView') . '|id=%s';

$lang->action->desc->asked        = '$date, doubted by <strong>$actor</strong>.' . "\n";
$lang->action->desc->replied      = '$date, trả lời bởi <strong>$actor</strong>.' . "\n";
$lang->action->desc->tobug        = '$date, converted tới Bug by <strong>$actor</strong>. Its ID was <strong>$extra</strong>.' . "\n";
$lang->action->desc->tostory      = '$date, converted to ' . $lang->SRCommon . ' by <strong>$actor</strong>. Its ID was <strong>$extra</strong>.' . "\n";
$lang->action->desc->touserstory  = '$date, converted to ' . $lang->URCommon . ' by <strong>$actor</strong>. Its ID was <strong>$extra</strong>.' . "\n";
$lang->action->desc->totask       = '$date, converted to Task by <strong>$actor</strong>. Its ID was <strong>$extra</strong>.' . "\n";
$lang->action->desc->fromfeedback = '$date, được tạo bởi <strong>$actor</strong> từ <strong>feedback</strong>, the ID cái là <strong>$extra</strong>.' . "\n";
$lang->action->desc->totodo       = '$date, converted to todo from <strong>$actor</strong>. Its ID was <strong>$extra</strong>.' . "\n";

$lang->action->label->asked        = 'doubted';
$lang->action->label->replied      = 'replied';
$lang->action->label->tobug        = 'convert to bug';
$lang->action->label->tostory      = 'convert to ' . $lang->SRCommon;
$lang->action->label->touserstory  = 'convert to ' . $lang->URCommon;
$lang->action->label->totask       = 'convert to nhiệm vụ';
$lang->action->label->totodo       = 'convert to todo';
$lang->action->label->fromfeedback = 'from feedback';
$lang->action->label->processed    = 'Processed';
