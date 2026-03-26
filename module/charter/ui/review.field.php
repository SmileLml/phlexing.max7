<?php
namespace zin;

global $lang, $app;

unset($lang->charter->reviewResultList['failed']);
unset($lang->charter->reviewResultList['launched']);
$fields = defineFieldList('charter.review');

$fields->field('reviewResult')
    ->label($lang->charter->review)
    ->control(array('control' => 'radioList', 'inline' => true))
    ->items($lang->charter->reviewResultList)
    ->value('pass');

$fields->field('reviewOpinion')
    ->label($lang->charter->reviewOpinion)
    ->control('editor')
    ->value('');
