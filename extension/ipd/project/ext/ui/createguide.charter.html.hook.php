<?php
namespace zin;

$charterID = data('charterID');
if(!empty($charterID))
{
    query('.kanban')->remove();

    $programID = data('programID');
    $productID = data('productID');
    $branchID  = data('branchID');
    $createLink = createLink("project", "create", "model=%s&programID=$programID&copyProjectID=0&extra=productID=$productID,branchID=$branchID,charter=$charterID");
    query('.model-block .model-item')->each(function($node) use($createLink)
    {
        $model = $node->prop('data-model');
        $node->setProp('data-url', sprintf($createLink, $model));
    });

    global $config;
    if($config->edition != 'ipd')
    {
        query('.more-model')->addClass('hidden');
        query('.model-block')->each(function($node){$node->setProp('class', 'resetFlex');});
    }
    else
    {
        query('.more-model')->removeClass('hidden');
    }
}
