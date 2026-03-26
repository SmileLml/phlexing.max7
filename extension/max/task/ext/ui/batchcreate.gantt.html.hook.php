<?php
namespace zin;

global $lang;

jsVar('splitTaskRelation', data('splitTaskRelation'));
jsVar('unlinkRelationTip', $lang->task->unlinkRelationTip->split);
jsVar('unlinkLang', $lang->task->unlink);

query('formBatchPanel')->each(function($node)
{
    $node->setProp('ajax', array('beforeSubmit' => jsRaw('clickSubmit')));
});
