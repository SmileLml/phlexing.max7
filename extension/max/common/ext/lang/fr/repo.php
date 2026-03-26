<?php
$lang->navGroup->artifactrepo = 'devops';

$lang->devops->artifactrepo = 'Artifact Repo';

$lang->devops->homeMenu->artifactrepo = array('link' => "{$lang->devops->artifactrepo}|artifactrepo|browse", 'alias' => 'create');

$lang->devops->menuOrder[30] = 'artifactrepo';

if(helper::hasFeature('devops'))
{
    $lang->devops->menu->review = array('link' => 'Issue|repo|review|repoID=%s', 'subModule' => 'bug');
    $lang->devops->menuOrder[60] = 'review';

    $lang->execution->menu->devops['subMenu']->review = "Issue|repo|review|repoID=0&browseType=all&executionID=%s";
    $lang->execution->menu->devops['menuOrder'][60] = 'review';

    $lang->scrum->menu->devops['subMenu']->review = "Issue|repo|review|repoID=0&browseType=all&executionID=%s";
    $lang->scrum->menu->devops['menuOrder'][60] = 'review';

    $lang->waterfall->menu->devops['subMenu']->review = "Issue|repo|review|repoID=0&browseType=all&executionID=%s";
    $lang->waterfall->menu->devops['menuOrder'][60] = 'review';
}
