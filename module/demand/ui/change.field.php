<?php
namespace zin;

global $lang, $app;

$fields = defineFieldList('demand.change');

$fields->field('reviewBox')
    ->required(true)
    ->label($lang->demand->reviewer)
    ->control('inputGroup')
    ->itemBegin('reviewer')
    ->control('picker')
    ->multiple(true)
    ->disabled(data('needReview'))
    ->items(data('reviewers'))
    ->value(data('needReview') ? '' : data('reviewer'))->itemEnd()
    ->itemBegin('needNotReview')
    ->control('checkbox')
    ->hidden($app->control->demand->checkForceReview())
    ->rootClass('center w-32')
    ->checked(data('needReview'))
    ->text($lang->demand->needNotReview)->itemEnd();

$fields->field('title')->control('colorInput', array('colorValue' => data('demand.color')))->value(data('demand.title'));

$fields->field('spec')->control('editor')->value(data('demand.spec'));
$fields->field('verify')->control('editor')->value(data('demand.verify'));
$fields->field('comment')->control('editor');

$fields->field('files')->label($lang->files)->width('full')->control('fileSelector', array('multiple' => false, 'defaultFiles' => array_values(data('demand.files'))))->value('');

$fields->field('status')->control('hidden')->value(data('demand.status'));
$fields->field('lastEditedDate')->control('hidden')->value(data('demand.lastEditedDate'));
