<?php
$lang->group->package->artifactrepo       = '製品庫';
$lang->group->package->browseArtifactrepo = '瀏覽製品庫';
$lang->group->package->manageArtifactrepo = '創建維護製品庫';
$lang->group->package->deleteArtifactrepo = '刪除製品庫';

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
