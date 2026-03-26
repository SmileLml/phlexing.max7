<?php
namespace zin;

global $lang;

if(common::hasPriv('design', 'submit'))
{
    $productID = data('productID');
    $type      = data('type');
    query('#actionBar .btn-group')->before(
        btn(
            setClass('secondary'),
            set::icon('plus'),
            set::url(createLink('design', 'submit', "productID={$productID}&type={$type}")),
            setData('toggle', 'modal'),
            $lang->design->submit
        ));
}
