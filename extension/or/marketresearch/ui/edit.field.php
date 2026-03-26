<?php
namespace zin;

global $lang;

$fields = defineFieldList('marketresearch.edit', 'marketresearch');

$fields->field('name')->control('input')->value(data('research.name'));
$fields->field('PM')->control('picker')->items(data('users'))->value(data('research.PM'));

$fields->field('market')
    ->required(true)
    ->control(array('control' => 'picker', 'required' => false))
    ->items(data('marketList'))
    ->value(data('research.market'));

unset($lang->marketresearch->endList[999]);
$isLongTime = data('research.end') == LONG_TIME;
$fields->field('begin')
    ->label($lang->marketresearch->planDate)
    ->checkbox(array('text' => $lang->marketresearch->longTime, 'name' => 'longTime', 'checked' => $isLongTime))
    ->required()
    ->controlBegin('dateRangePicker')
    ->beginName('begin')
    ->beginPlaceholder($lang->marketresearch->begin)
    ->beginValue(data('research.begin'))
    ->endName('end')
    ->endPlaceholder($lang->marketresearch->end)
    ->endValue($isLongTime ? '' : data('research.end'))
    ->endList($lang->marketresearch->endList)
    ->endDisabled($isLongTime)
    ->controlEnd()
    ->tip(' ')
    ->tipProps(array('id' => 'dateTip'));

$fields->field('desc')->width('full')->control('editor')->value(data('research.desc'));

$fields->field('acl')
    ->width('full')
    ->control(array('control' => 'aclBox', 'aclItems' => $lang->marketresearch->aclList, 'aclValue' => data('research.acl'), 'whitelistLabel' => $lang->marketresearch->whitelist, 'userValue' => data('research.whitelist')));
