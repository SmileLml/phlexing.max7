<?php
namespace zin;

$context = \zin\context();
extract($context->data);

global $lang;

if($project->hasProduct && $project->charter && $project->linkType == 'roadmap')
{
    $relatedProducts = null;

    foreach($products as $productID => $product)
    {
        $productDom = null;
        $branches   = array();
        foreach($product->branches as $branchID)
        {
            $branchName = isset($branchGroups[$productID][$branchID]) ? '/' . $branchGroups[$productID][$branchID] : '';
            $branches[] = div
            (
                setClass('flex clip w-full items-center'),
                icon('product mr-2'),
                a
                (
                    setClass('flex'),
                    set::title($product->name . $branchName),
                    hasPriv('product', 'browse') ? set::href(createLink('product', 'browse', "productID={$productID}&branch={$branchID}")) : null,
                    span(setClass('flex-1'), setStyle('width', '0'), $product->name . $branchName)
                )
            );
        }

        $productDom = h::td(div(setClass('flex flex-wrap'), $branches));

        $roadmapNode = array();
        $roadmapDom  = null;
        $roadmapIdList = explode(',', $product->roadmaps);
        $roadmapIdList = array_unique(array_filter($roadmapIdList));
        foreach($roadmapIdList as $roadmapID)
        {
            if(!isset($roadmaps[$productID][$roadmapID])) continue;

            $roadmap = $roadmaps[$productID][$roadmapID];

            $class = 'clip';
            if(count($roadmapIdList) <= 2) $class .= ' flex flex-1 w-0 items-center';
            if(count($roadmapIdList) > 2)  $class .= ' flex-none w-1/3';

            if(count($roadmapNode) > 2)      $class .= ' mt-2';
            if(count($roadmapNode) % 3 != 0) $class .= ' pl-6';
            $roadmapNode[] = div
                (
                    setClass($class),
                    icon('productplan mr-2 '),
                    a(
                        set::title($roadmap->name),
                        hasPriv('roadmap', 'view') ? set::href(createLink('roadmap', 'view', "roadmapID={$roadmap->id}")) : null,
                        span($roadmap->name)
                    )
                );
        }

        $roadmapDom[]      = h::td(div(setClass('flex flex-wrap'), $roadmapNode));
        $relatedProducts[] = h::tr(setClass('border-r'), $productDom, $roadmapDom);
    }

    query('.productsBox')->find('tbody')->replaceWith($relatedProducts);

    $thRoadmap = h::th
    (
        setClass('th-plan'),
        span
        (
            setClass('flex'),
            img(set('src', 'static/svg/productplan.svg'), setClass('mr-2')),
            $lang->project->manageRoadmap
        )
    );
    query('.productsBox')->find('.th-plan')->replaceWith($thRoadmap);
}

if($project->charter)
{
    $charterDom = div
    (
        icon('seal mr-2 text-primary'),
        a
        (
            set::title($charter->name),
            hasPriv('charter', 'view') ? set::href(createLink('charter', 'view', "id={$project->charter}")) : null,
            span($charter->name)
        )
    );
    query('.program')->after($project->charter ? div(setClass('flex mt-4 charter'), div(setClass('clip charterBox'), $charterDom)) : null);
    query('.program')->addClass($project->parent ? '' : 'hidden');
}
