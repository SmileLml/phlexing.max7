<?php
namespace zin;

query('formGridPanel')->each(function($node)
{
    $lang   = data('lang');
    $config = data('config');
    $fields = $node->prop('fields');

    $fields->field('feedback')
        ->label($lang->product->FM)
        ->control('remotepicker')
        ->value(data('fields.RD.default'))
        ->foldable();

    $fields->field('ticket')
        ->label($lang->product->TM)
        ->control('remotepicker')
        ->items(data('fields.RD.options'))
        ->value(data('fields.RD.default'))
        ->foldable();

    $fields->field('workflowGroup')
        ->label($lang->product->workflowGroup)
        ->control('picker')
        ->required(true)
        ->items(data('fields.workflowGroup.options'))
        ->value(data('fields.workflowGroup.default'));

    if(!empty($config->setCode))
    {
        $fields->field('workflowGroup')->width('1/4');
    }

    $fields->moveAfter('feedback,ticket', 'RD');
    $fields->moveAfter('workflowGroup', 'type');
    $fields->fullModeOrders('RD,feedback,ticket,desc');

    $node->setProp('fields', $fields);
});
