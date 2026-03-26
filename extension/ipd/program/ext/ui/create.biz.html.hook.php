<?php
namespace zin;

$loadUrl = createLink('program', 'create', "parentProgramID={parent}&charterID={charter}" . (empty($originExtra) ? '' : "&extra=$originExtra"));

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
