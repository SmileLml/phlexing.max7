<?php
namespace zin;

global $app, $lang;
$app->control->loadModel('charter');

query('formBatchPanel')->each(function($node) use ($lang)
{
    $items = $node->prop('items');

    $newItems = array();
    foreach($items as $key => $item)
    {
        $newItems[$key] = $item;
        if($key == 'level') $newItems['type'] = array('name' => 'type', 'label' => $lang->charter->type, 'control' => array('control' => 'radioList', 'items' => $lang->charter->typeList), 'width' => '80px');
    }
    $node->setProp('items', $newItems);
});

query('.form-actions')->before(html("<div class='p-2'>{$lang->custom->charter->tips->type}</div>"));
