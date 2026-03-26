<?php
namespace zin;
global $lang;

$productID = data('productID');

query('form .form-actions')->append(
    a
    (
        setClass('btn'),
        $lang->testcase->selectStory,
        setData('toggle', 'modal'),
        setData('id', 'storyForm'),
        setData('size', 'lg'),
        setData('url', inlink('ajaxSelectStory', "productID={$productID}"))
    )
);
?>
<?php include $app->getModuleRoot() . 'transfer/ui/exporttemplate.html.php';?>
