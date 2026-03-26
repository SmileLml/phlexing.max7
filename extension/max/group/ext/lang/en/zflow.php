<?php
if(empty($_SESSION['user']->feedback) and empty($_COOKIE['feedbackView']))
{
    $lang->resource->story->browse = 'browse';
    $lang->resource->task->browse  = 'browse';
    $lang->resource->build->browse = 'browse';

    if(!isset($lang->resource->workflow)) $lang->resource->workflow = new stdclass();
    $lang->resource->workflow->browse     = 'browseAction';
    $lang->resource->workflow->release    = 'releaseAction';
    $lang->resource->workflow->deactivate = 'deactivateAction';
    $lang->resource->workflow->activate   = 'activateAction';
    $lang->resource->workflow->setJS      = 'setJSAction';
    $lang->resource->workflow->setCSS     = 'setCSSAction';

    if(!isset($lang->resource->workflowfield)) $lang->resource->workflowfield = new stdclass();
    $lang->resource->workflowfield->browse = 'browseAction';

    if(!isset($lang->resource->workflowaction)) $lang->resource->workflowaction = new stdclass();
    $lang->resource->workflowaction->browse = 'browseAction';
    $lang->resource->workflowaction->setJS  = 'setJSAction';
    $lang->resource->workflowaction->setCSS = 'setCSSAction';
}
