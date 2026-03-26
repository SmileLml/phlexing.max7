<?php
namespace zin;

global $lang, $app;

$context = \zin\context();
extract($context->data);

$source      = isset($source) ? $source : '';
$reportPairs = isset($reportPairs) ? $reportPairs : array();
$loadUrl     = isset($loadUrl) ? $loadUrl : '';

query('formGridPanel')->each(function($node) use($lang, $source, $reportPairs, $loadUrl)
{
    $fields = $node->prop('fields');

    if($source == 'researchreport')
    {
        $fields->field('sourceNote')
            ->label($lang->story->researchreport)
            ->width('1/2')
            ->control('picker')
            ->items($reportPairs);
    }

    $fields->autoLoad('source', 'sourceNote');

    $node->setProp('fields', $fields);
    $node->setProp('loadUrl', $loadUrl);
});
