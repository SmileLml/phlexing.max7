<?php
$lang->group->package->artifactrepo       = '制品库';
$lang->group->package->browseArtifactrepo = '浏览制品库';
$lang->group->package->manageArtifactrepo = '创建维护制品库';
$lang->group->package->deleteArtifactrepo = '删除制品库';

if(helper::hasFeature('devops') || (defined('IN_UPGRADE') && IN_UPGRADE))
{
    $lang->resource->repo->review        = 'reviewAction';
    $lang->resource->repo->addBug        = 'addBug';
    $lang->resource->repo->editBug       = 'editBug';
    $lang->resource->repo->deleteBug     = 'deleteBug';
    $lang->resource->repo->addComment    = 'addComment';
    $lang->resource->repo->editComment   = 'editComment';
    $lang->resource->repo->deleteComment = 'deleteComment';

    $lang->resource->artifactrepo = new stdclass();
    $lang->resource->artifactrepo->browse                  = 'browse';
    $lang->resource->artifactrepo->ajaxGetArtifactRepos    = 'ajaxGetArtifactRepos';
    $lang->resource->artifactrepo->create                  = 'create';
    $lang->resource->artifactrepo->edit                    = 'edit';
    $lang->resource->artifactrepo->ajaxUpdateArtifactRepos = 'ajaxUpdateArtifactRepos';
    $lang->resource->artifactrepo->delete                  = 'delete';
}
