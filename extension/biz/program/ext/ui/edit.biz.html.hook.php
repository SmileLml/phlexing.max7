<?php
namespace zin;

$loadUrl = createLink('program', 'edit', 'programID=' . data('program.id') . '&parentProgramID={parent}&extra=charter={charter}');

query('formGridPanel')->each(function($node) use($loadUrl)
{
    $fields = $node->prop('fields');

    $fields->field('charter')
        ->class('charterBox')
        ->control('picker')
        ->items(data('charters'))
        ->value(data('charter'))
        ->moveAfter('parent');

    $autoLoad = $node->prop('autoLoad');
    if(isset($autoLoad['parent'])) $autoLoad['parent'] = trim($autoLoad['parent'] . ',charter', ',');

    $node->setProp('fields', $fields);
    $node->setProp('autoLoad', $autoLoad);
    $node->setProp('loadUrl', $loadUrl);
});
