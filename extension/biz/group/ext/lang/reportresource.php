<?php
$lang->resource->pivot->casesrun          = 'casesrun';
$lang->resource->pivot->storyLinkedBug    = 'storyLinkedBug';
$lang->resource->pivot->testcase          = 'testcase';
$lang->resource->pivot->build             = 'build';
$lang->resource->pivot->workSummary       = 'workSummary';
$lang->resource->pivot->roadmap           = 'roadmap';
$lang->resource->pivot->productInvest     = 'productInvest';
$lang->resource->pivot->bugSummary        = 'bugSummary';
$lang->resource->pivot->bugAssignSummary  = 'bugAssignSummary';
$lang->resource->pivot->workAssignSummary = 'workAssignSummary';

if(!helper::hasFeature('product_roadmap')) unset($lang->resource->pivot->roadmap);

$lang->pivot->methodOrder[11] = 'roadmap';
$lang->pivot->methodOrder[12] = 'productInvest';
$lang->pivot->methodOrder[26] = 'testcase';
$lang->pivot->methodOrder[27] = 'casesrun';
$lang->pivot->methodOrder[28] = 'build';
$lang->pivot->methodOrder[29] = 'storyLinkedBug';
$lang->pivot->methodOrder[31] = 'workSummary';
$lang->pivot->methodOrder[32] = 'workAssignSummary';
$lang->pivot->methodOrder[33] = 'bugSummary';
$lang->pivot->methodOrder[34] = 'bugAssignSummary';
