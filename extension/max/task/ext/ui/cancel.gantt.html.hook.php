<?php
namespace zin;

global $lang;

jsVar('taskID', data('task.id'));
jsVar('cardPosition', data('cardPosition'));
jsVar('from', data('from'));
jsVar('taskRelation', data('taskRelation'));
jsVar('unlinkRelationTip', $lang->task->unlinkRelationTip->cancel);
jsVar('unlinkLang', $lang->task->unlink);

query('formPanel')->each(function($node)
{
    $node->setProp('id', 'cancelForm');
    $node->setProp('ajax', array('beforeSubmit' => jsRaw('clickSubmit')));
});
