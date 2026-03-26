<?php
namespace zin;
if(data('feedbackID'))
{
    query('formGridPanel')->each(function($node)
    {
        $fields = $node->prop('fields');

        $fields->field('feedback')->hidden()->value(data('feedbackID'));
        $fields->remove('after');

        $node->setProp('fields', $fields);
    });
}
