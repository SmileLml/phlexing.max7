<?php
namespace zin;
global $lang, $app;

$users = $app->control->loadModel('user')->getPairs('noletter|noclosed');

query('formBatchPanel')->each(function($node) use($users)
{
    $items = $node->prop('items');
    $items['superior']['items'] = $users;
    $node->setProp('items', $items);
});
