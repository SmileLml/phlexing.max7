<?php
namespace zin;
global $app;

$docs    = array();
$docList = $app->control->loadModel('doc')->getMySpaceDocs('all', 'bykeyword');
foreach($docList as $doc) $docs[] = array('text' => $doc->title, 'value' => $doc->id);
query('formGridPanel')->each(function($node) use($docs)
{
    $fields = $node->prop('fields');

    $fields->field('docs')->width('full')->control(array('control' => 'picker', 'multiple' => true, 'maxItemsCount' => 50, 'menu' => array('checkbox' => true), 'toolbar' => true))->items($docs);
    $fields->defaultOrders = array_merge($fields->defaultOrders, array('docs,files'));
    $fields->ordersForFull = array_merge($fields->ordersForFull, array('docs,files'));

    $node->setProp('fields', $fields);
});
