<?php
$lang->navGroup->artifactrepo = 'devops';

$lang->devops->artifactrepo = '制品库';

$lang->devops->homeMenu->artifactrepo = array('link' => "{$lang->devops->artifactrepo}|artifactrepo|browse", 'alias' => 'create');

$lang->devops->menuOrder[50] = 'artifactrepo';

if(helper::hasFeature('devops'))
{
    $lang->devops->menu->review = array('link' => '问题|repo|review|repoID=%s', 'subModule' => 'bug');
    $lang->devops->menuOrder[65] = 'review';

    $lang->execution->menu->devops['subMenu']->review = '问题|repo|review|repoID=0&browseType=all&executionID=%s';
    $lang->execution->menu->devops['menuOrder'][60] = 'review';

    $lang->scrum->menu->devops['subMenu']->review = '问题|repo|review|repoID=0&browseType=all&executionID=%s';
    $lang->scrum->menu->devops['menuOrder'][60] = 'review';

    $lang->waterfall->menu->devops['subMenu']->review = '问题|repo|review|repoID=0&browseType=all&executionID=%s';
    $lang->waterfall->menu->devops['menuOrder'][60] = 'review';
}
