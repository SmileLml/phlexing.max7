<?php
namespace zin;

query('formGridPanel')->each(function($node)
{
    $fields = $node->prop('fields');

    $fields->field('name')->width('full');
    $fields->field('QD')->hidden();
    $fields->field('RD')->hidden();
    $fields->field('feedback')->hidden();
    $fields->field('ticket')->hidden();
    $fields->field('reviewer')->width('full');
    $fields->field('PMT')->control(array('control' => 'remotepicker', 'params' => 'noclosed|nodeleted|pofirst'))->multiple();

    $fields->moveAfter('type', 'workflowGroup');
    $fields->moveAfter('PMT', 'PO');

    $node->setProp('fields', $fields);
});


query('#form-product-create')->find('.form-actions')->before(formHidden('status', 'wait'));
