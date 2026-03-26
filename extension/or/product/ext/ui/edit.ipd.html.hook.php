<?php
namespace zin;

$PMTValue = data('fields.PMT.default');
query('formGridPanel')->each(function($node) use($PMTValue)
{
    $fields = $node->prop('fields');

    $fields->field('name')->width('full');
    $fields->field('QD')->hidden();
    $fields->field('RD')->hidden();
    $fields->field('feedback')->hidden();
    $fields->field('ticket')->hidden();
    $fields->field('PMT')->control(array('control' => 'remotepicker', 'params' => 'noclosed|nodeleted|pofirst'))->multiple()->value($PMTValue);

    $fields->moveAfter('type', 'PO');
    $fields->moveAfter('PMT', 'PO');

    $node->setProp('fields', $fields);
});
