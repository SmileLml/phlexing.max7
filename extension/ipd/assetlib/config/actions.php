<?php
global $lang, $app;
$app->loadLang('story');
$config->assetlib->story = new stdclass();

$config->assetlib->story->actionList = array();
$config->assetlib->story->actionList['editStory']['icon']     = 'edit';
$config->assetlib->story->actionList['editStory']['text']     = $lang->story->edit;
$config->assetlib->story->actionList['editStory']['hint']     = $lang->story->edit;
$config->assetlib->story->actionList['editStory']['url']      = array('module' => 'assetlib', 'method' => 'editStory', 'params' => 'storyID={id}');
$config->assetlib->story->actionList['editStory']['data-app'] = $app->tab;

$config->assetlib->story->actionList['approveStory']['icon']        = 'glasses';
$config->assetlib->story->actionList['approveStory']['text']        = $lang->assetlib->approveStory;
$config->assetlib->story->actionList['approveStory']['hint']        = $lang->assetlib->approveStory;
$config->assetlib->story->actionList['approveStory']['url']         = array('module' => 'assetlib', 'method' => 'approveStory', 'params' => 'storyID={id}', 'onlybody' => true);
$config->assetlib->story->actionList['approveStory']['data-toggle'] = 'modal';
$config->assetlib->story->actionList['approveStory']['data-type']   = 'iframe';
$config->assetlib->story->actionList['approveStory']['data-app']    = $app->tab;

$config->assetlib->story->actionList['removeStory']['icon']         = 'unlink';
$config->assetlib->story->actionList['removeStory']['text']         = $lang->assetlib->removeStory;
$config->assetlib->story->actionList['removeStory']['hint']         = $lang->assetlib->removeStory;
$config->assetlib->story->actionList['removeStory']['url']          = array('module' => 'assetlib', 'method' => 'removeStory', 'params' => 'storyID={id}');
$config->assetlib->story->actionList['removeStory']['className']    = 'ajax-submit';
$config->assetlib->story->actionList['removeStory']['data-confirm'] = $lang->assetlib->confirmDeleteStory;
$config->assetlib->story->actionList['removeStory']['data-app']     = $app->tab;
