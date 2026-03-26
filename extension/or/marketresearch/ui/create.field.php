<?php
namespace zin;

global $lang;

$fields = defineFieldList('marketresearch.create', 'marketresearch');

$fields->field('name')->control('input');
$fields->field('PM')->control('picker')->items(data('users'));

$fields->field('market')
    ->required(true)
    ->checkbox(array('text' => $lang->market->create, 'name' => 'newMarket', 'checked' => data('newMarket')))
    ->control('inputGroup')
    ->itemBegin()
    ->control(array('control' => 'picker', 'required' => false, 'name' => 'market', 'value' => data('marketID')))
    ->items(data('marketList'))
    ->class(data('newMarket') ? 'hidden' : '')
    ->itemEnd()
    ->itemBegin()
    ->control(array('control' => 'input', 'name' => 'marketName', 'value' => ''))
    ->class(!data('newMarket') ? 'hidden' : '')
    ->itemEnd();

unset($lang->marketresearch->endList[999]);
$fields->field('begin')
    ->label($lang->marketresearch->planDate)
    ->checkbox(array('text' => $lang->marketresearch->longTime, 'name' => 'longTime', 'checked' => false))
    ->required()
    ->controlBegin('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->marketresearch->begin)
    ->beginValue(date('Y-m-d'))
    ->endName('end')
    ->endPlaceholder($lang->marketresearch->end)
    ->endValue('')
    ->endList($lang->marketresearch->endList)
    ->controlEnd()
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'));

$fields->field('desc')->width('full')->control('editor');

$fields->field('acl')
    ->width('full')
    ->control(array('control' => 'aclBox', 'aclItems' => $lang->marketresearch->aclList, 'aclValue' => 'private', 'whitelistLabel' => $lang->marketresearch->whitelist, 'userValue' => ''));
