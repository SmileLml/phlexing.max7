<?php
namespace zin;

query('formGridPanel')->each(function($node) use($loadUrl)
{
    $fields = $node->prop('fields');
    $fields->field('source')->hidden()->value(data('bug.bugID'));
    $node->setProp('fields', $fields);
});
