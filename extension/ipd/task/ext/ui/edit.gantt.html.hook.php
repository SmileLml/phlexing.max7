<?php
namespace zin;

global $lang;

jsVar('task', data('task'));
jsVar('unlinkSelfRelationTip', $lang->task->unlinkRelationTip->cancel);
jsVar('unlinkParentRelationTip', $lang->task->unlinkRelationTip->parent);
jsVar('unlinkLang', $lang->task->unlink);
