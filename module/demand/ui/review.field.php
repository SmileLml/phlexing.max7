<?php
namespace zin;

global $lang, $app;

$fields = defineFieldList('demand.review');

$fields->field('reviewedDate')
    ->control('date')
    ->value(helper::today())
    ->width('1/3');

$fields->field('result')
    ->control('picker')
    ->items($lang->demand->resultList)
    ->required(true)
    ->width('1/3');

$fields->field('assignedTo')
    ->id('assignedToBox')
    ->control('picker')
    ->items(data('assignToList'))
    ->width('1/3');

$fields->field('pri')
    ->id('priBox')
    ->width('1/3')
    ->control('priPicker')
    ->items($lang->demand->priList)
    ->value(data('demand.pri'));

$fields->field('closedReason')
    ->label($lang->demand->rejectReason)
    ->id('closedReasonBox')
    ->control('picker')
    ->required(true)
    ->hidden(true)
    ->items($lang->demand->reasonList)
    ->width('1/3')
    ->value('');

$fields->field('duplicateDemand')
    ->id('duplicateDemandBox')
    ->hidden(true)
    ->width('1/3');

$fields->field('comment')
    ->control('editor')
    ->rows(6);

$fields->field('status')->value('reviewing')->hidden(true);
