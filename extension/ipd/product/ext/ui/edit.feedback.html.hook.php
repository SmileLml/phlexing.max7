<?php
namespace zin;

query('formGridPanel')->each(function($node)
{
    $lang   = data('lang');
    $fields = $node->prop('fields');

    $fields->field('feedback')
        ->label($lang->product->FM)
        ->control('remotepicker')
        ->value(data('product.feedback'));

    $fields->field('ticket')
        ->label($lang->product->TM)
        ->control('remotepicker')
        ->value(data('product.ticket'));

    $fields->orders('name,code', 'type,status', 'reviewer,QD,RD,feedback,ticket,desc,acl');
    $fields->fullModeOrders('name,code', 'type,status', 'reviewer,QD,RD,feedback,ticket,desc,acl');

    $node->setProp('fields', $fields);
});
