<?php
namespace zin;

global $app, $lang;

$story = data('story');
query('#category')->closest('tr')->after(
    h::tr
    (
        h::th(setClass('py-1.5 pr-2 font-normal nowrap text-right'), $lang->story->duration),
        h::td(setClass('py-1.5 pl-2 w-full'), picker(set::name('duration'), set::items($lang->demand->durationList), set::value($story->duration)))
    ),
    h::tr
    (
        h::th(setClass('py-1.5 pr-2 font-normal nowrap text-right'), $lang->story->BSA),
        h::td(setClass('py-1.5 pl-2 w-full'), picker(set::name('BSA'), set::items($lang->demand->bsaList), set::value($story->BSA)))
    )
);

$roadmaps    = $app->control->loadModel('roadmap')->getPairs($story->product, $story->branch, 'noclosed', 0, $story->roadmap);
$roadmapNode = h::tr
(
    h::th(setClass('py-1.5 pr-2 font-normal nowrap text-right'), $lang->story->roadmap),
    h::td
    (
        inputGroup
        (
            setClass('py-1.5 pl-2'),
            span(setID('roadmapIdBox'), setClass('w-full'), picker(set::name('roadmap'), set::items($roadmaps), set::value($story->roadmap), set::disabled(!in_array($story->stage, array('wait', 'inroadmap', 'incharter'))))),
            empty($roadmaps) && in_array($story->stage, array('wait', 'inroadmap', 'incharter')) ? btn(set::url(createLink('roadmap', 'create', "productID={$story->product}", '', true)), setData(array('toggle' => 'modal', 'type' => 'iframe', 'size' => 'lg')), icon('plus')) : null,
            empty($roadmaps) && in_array($story->stage, array('wait', 'inroadmap', 'incharter')) ? btn(set('onclick', "loadProductRoadmaps({$story->product}, {$story->branch})"), setClass('refresh'), icon('refresh')) : null
        )
    ),
    !in_array($story->stage, array('wait', 'inroadmap', 'incharter')) ? formHidden('roadmap', $story->roadmap) : null
);

query('#grade')->closest('tr')->after($roadmapNode);
query('#product')->prop('disabled', !in_array($story->stage, array('wait', 'inroadmap', 'incharter')));
query('#branch')->prop('disabled', !in_array($story->stage, array('wait', 'inroadmap', 'incharter')));
