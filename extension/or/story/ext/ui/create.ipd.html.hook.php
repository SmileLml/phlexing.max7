<?php
namespace zin;

global $app, $lang;

//$roadmaps     = $app->control->loadModel('roadmap')->getPairs(data('productID'), 0, 'noclosed');
$createFields = data('fields');
$feedback     = data('feedback');
$isBranchUR   = isset($createFields['branch']) && $type != 'story';
query('formGridPanel')->each(function($node) use($createFields, $feedback, $lang, $isBranchUR)
{
    $fields = $node->prop('fields');

    $fields->field('product')
        ->hidden(data('product.shadow'))
        ->required()
        ->control('inputGroup')
        ->items(false)
        ->itemBegin('product')->control('picker')->items($createFields['product']['options'])->value($createFields['product']['default'])->required(true)->itemEnd()
        ->item($isBranchUR ? field('branch')->control('picker')->boxClass('flex-none')->width('100px')->name('branch')->items($createFields['branch']['options'])->value($createFields['branch']['default'])->set(array('data-on' => 'change', 'data-call' => 'loadBranchRoadmaps')) : null);

    $fields->field('duration')
        ->required($createFields['duration']['required'])
        ->items($createFields['duration']['options'])
        ->value($createFields['duration']['default'])
        ->moveAfter('title');

    $fields->field('BSA')
        ->required($createFields['BSA']['required'])
        ->items($createFields['BSA']['options'])
        ->value($createFields['BSA']['default']);

    $fields->field('keywords')->value(!empty($feedback->keywords) ? $feedback->keywords : '');

    //$fields->field('roadmap')
    //    ->foldable()
    //    ->control('inputGroup')
    //    ->items(false)
    //    ->itemBegin('roadmap')->control('picker')->id('roadmapIdBox')->items($roadmaps)->value('')->itemEnd()
    //    ->item(empty($roadmaps) ? field()->control('btn')->icon('plus')->url(createLink('roadmap', 'create', 'productID=' . data('productID'), '', true))->set(array('data-toggle' => 'modal', 'data-size' => 'lg', 'data-type' => 'iframe'))->set('title', $lang->roadmap->create) : null)
    //    ->item(empty($roadmaps) ? field()->control('btn')->icon('refresh')->id("loadProductRoadmaps")->set('title', $lang->refresh)->set(array('data-on' => 'click', 'data-call' => 'loadProductRoadmaps', 'data-params' => data('productID'))) : null);

    if(!empty($fields->defaultOrders))
    {
        $orders = current($fields->defaultOrders);
        if(strpos($orders, ',reviewer,') !== false)
        {
            $orders = str_replace(',reviewer,', ',reviewer,duration,BSA,', $orders);
            $fields->defaultOrders[0] = $orders;
        }
    }
    if(!empty($fields->ordersForFull))
    {
        $orders = current($fields->ordersForFull);
        if(strpos($orders, ',reviewer,') !== false)
        {
            $orders = str_replace(',reviewer,', ',reviewer,duration,BSA,', $orders);
            $fields->ordersForFull[0] = $orders;
        }
    }

    //$fields->sort('files,roadmap');
    //$fields->fullModeOrders('module,roadmap,parent');

    $node->setProp('fields', $fields);
});
