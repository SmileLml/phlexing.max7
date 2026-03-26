<?php
namespace zin;
query('dtable')->each(function($node)
{
    global $lang;

    $node->setProp('checkable', false);
    $node->setProp('emptyTip', $lang->my->noTask);
});
