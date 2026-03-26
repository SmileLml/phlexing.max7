<?php
namespace zin;

global $lang, $app;

$fromFeedback = data('from') == 'feedback';

$fields = defineFieldList('demand.create');

$fields->field('pool')
    ->control('inputGroup')
    ->hidden(!$fromFeedback && !data('demand.id'))
    ->required($fromFeedback)
    ->itemBegin('pool')->control('picker')->items(data('demandpools'))->value(data('poolID'))->itemEnd();

$fields->field('product')
    ->control('inputGroup')
    ->checkbox(array('text' => $lang->demand->undetermined, 'name' => 'undetermined', 'checked' => false))
    ->itemBegin('undeterminedProduct')->control('input')->className('hidden')->readonly(true)->itemEnd()
    ->itemBegin('product[]')->control('picker')->multiple()->menu(array('checkbox' => true))->items(data('products'))->itemEnd();

$fields->field('assignedTo')
    ->control('inputGroup')
    ->itemBegin('assignedTo')->control('picker')->items(data('users'))->itemEnd();

$fields->field('source')
    ->foldable()
    ->control('picker')
    ->items($lang->demand->sourceList);

$fields->field('sourceNote')
    ->foldable();

$fields->field('duration')
    ->control('picker')
    ->items($lang->demand->durationList);

$fields->field('BSA')
    ->control('picker')
    ->labelHint($lang->demand->bsaTip)
    ->items($lang->demand->bsaList);

$fields->field('feedbackedBy')
    ->control('input')
    ->foldable();

$fields->field('email')
    ->control('input')
    ->foldable();

$fields->field('parent')
    ->control('picker')
    ->items(data('parents'))
    ->value(0);

$fields->field('reviewer')
    ->control('inputGroup')
    ->required(true)
    ->id('reviewerBox')
    ->wrapAfter()
    ->itemBegin('reviewer[]')->control('picker')->multiple()->menu(array('checkbox' => true))->items(data('reviewers'))->itemEnd();
$fields->field('needNotReview')->control('hidden')->value(0);

$fields->field('title')
    ->control('colorInput', array('colorValue' => data('demand.color')));

$fields->field('category')
    ->width('1/4')
    ->control('picker', array('required' => true))
    ->items($lang->demand->categoryList);

$fields->field('pri')
    ->width('1/4')
    ->control('priPicker', array('required' => true))
    ->items($lang->demand->priList)
    ->value(3);

$fields->field('spec')
    ->width('full')
    ->control('editor');

$fields->field('verify')
    ->width('full')
    ->control('editor');

$fields->field('files')
    ->width('full')
    ->control('fileSelector');

$fields->field('mailto')
    ->control('mailto');

$fields->field('keywords')
    ->control('input');

if($fromFeedback)
{
    $fields->field('feedback')
        ->control('hidden')
        ->value(data('fromID'));
}

$fields->field('status')->control('hidden')->value('active');
