<?php
$lang->try          = ' Trial';
$lang->maxName      = ' Max';
$lang->expireDate   = "Expire on %s";
$lang->forever      = "Permanent";
$lang->unlimited    = "Unlimited";
$lang->licensedUser = "User Licensed: %s";
$lang->liteName     = 'lite VIP';

$lang->chart        = new stdclass();
$lang->dashboard    = new stdclass();
$lang->dataview     = new stdclass();
$lang->metriclib    = new stdclass();
$lang->relateObject = new stdclass();
$lang->relation     = new stdclass();
$lang->approvalflow = new stdclass();

$lang->navGroup->approvalflow = 'workflow';

$lang->approvalflow->common = 'Approval Flow';
$lang->chart->common        = 'Chart';
$lang->dashboard->common    = 'Dashboard';
$lang->dataview->common     = 'Data Table';
$lang->metriclib->common    = 'Metric Library';
$lang->relateObject->common = 'Related Object';
$lang->relation->common     = 'Relation';

$lang->searchObjects['feedback']   = 'Feedback';
$lang->searchObjects['ticket']     = 'Ticket';
$lang->searchObjects['practice']   = 'Practice';
$lang->searchObjects['service']    = 'Service';
$lang->searchObjects['deploy']     = 'Deploy';
$lang->searchObjects['deploystep'] = 'Deploy Step';

$lang->mainNav->menuOrder[47] = 'feedback';
$lang->mainNav->menuOrder[52] = 'workflow';
$lang->mainNav->menuOrder[53] = 'traincourse';
$lang->mainNav->menuOrder[62] = 'oa';

$lang->dividerMenu = ',aiapp,feedback,traincourse,system,';

$lang->noticeBizLimited = "<div style='float:left;color:red' id='bizUserLimited'>已经超出企业版授权人数限制。请联系：4006-8899-23，或者删除用户。</div>";

$lang->oa->webMenu = new stdclass();
$lang->oa->webMenu->attend   = array('link' => 'Attend|attend|personal|', 'subModule' => 'attend');
$lang->oa->webMenu->leave    = array('link' => 'Leave|leave|personal|', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->webMenu->makeup   = array('link' => 'Makeup|makeup|personal|', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->webMenu->overtime = array('link' => 'Overtime|overtime|personal|', 'subModule' => 'overtime');
$lang->oa->webMenu->lieu     = array('link' => 'Lieu|lieu|personal|', 'subModule' => 'lieu');

$lang->oa->webMenuOrder[5]  = 'attend';
$lang->oa->webMenuOrder[10] = 'leave';
$lang->oa->webMenuOrder[15] = 'makeup';
$lang->oa->webMenuOrder[20] = 'overtime';
$lang->oa->webMenuOrder[25] = 'lieu';

$lang->attend->webMenu    = $lang->oa->webMenu;
$lang->leave->webMenu     = $lang->oa->webMenu;
$lang->makeup->webMenu    = $lang->oa->webMenu;
$lang->overtime->webMenu  = $lang->oa->webMenu;
$lang->lieu->webMenu      = $lang->oa->webMenu;

$lang->attend->webMenuOrder   = $lang->oa->webMenuOrder;
$lang->leave->webMenuOrder    = $lang->oa->webMenuOrder;
$lang->makeup->webMenuOrder   = $lang->oa->webMenuOrder;
$lang->overtime->webMenuOrder = $lang->oa->webMenuOrder;
$lang->lieu->webMenuOrder     = $lang->oa->webMenuOrder;

$lang->feedback->webMenu = new stdclass();
$lang->feedback->webMenu->unclosed   = array('link' => 'Unclosed|feedback|admin|browseType=unclosed', 'subModule' => 'tree');
$lang->feedback->webMenu->all        = array('link' => 'All|feedback|admin|browseType=all');
$lang->feedback->webMenu->public     = array('link' => 'Public|feedback|admin|browseType=public');
$lang->feedback->webMenu->tostory    = array('link' => "To Story|feedback|admin|browseType=tostory");
$lang->feedback->webMenu->totask     = array('link' => 'To Task|feedback|admin|browseType=totask');
$lang->feedback->webMenu->tobug      = array('link' => 'To Bug|feedback|admin|browseType=tobug');
$lang->feedback->webMenu->totodo     = array('link' => 'To Todo|feedback|admin|browseType=totodo');
$lang->feedback->webMenu->assigntome = array('link' => 'AssignedToMe|feedback|admin|browseType=assigntome');

$lang->feedback->webMenuOrder[5]  = 'unclosed';
$lang->feedback->webMenuOrder[10] = 'all';
$lang->feedback->webMenuOrder[15] = 'public';
$lang->feedback->webMenuOrder[20] = 'tostory';
$lang->feedback->webMenuOrder[25] = 'totask';
$lang->feedback->webMenuOrder[30] = 'tobug';
$lang->feedback->webMenuOrder[35] = 'totodo';
$lang->feedback->webMenuOrder[40] = 'assigntome';

$lang->bi->menu->chart     = array('link' => "{$lang->chart->common}|chart|preview");
$lang->bi->menu->dataview  = array('link' => "{$lang->dataview->common}|dataview|browse", 'subModule' => 'dataview');
$lang->bi->menu->dimension = array('link' => "{$lang->dimension->common}|dimension|browse");
$lang->bi->menu->metriclib = array('link' => "{$lang->metriclib->common}|metriclib|browse");

$lang->bi->menuOrder[25] = 'metriclib';
$lang->bi->menuOrder[30] = 'dataview';
$lang->bi->menuOrder[35] = 'dimension';

$lang->bi->dividerMenu = ',dataview,metric,';

$lang->navGroup->chart     = 'bi';
$lang->navGroup->dataview  = 'bi';
$lang->navGroup->dimension = 'bi';
$lang->navGroup->metriclib = 'bi';

if(!helper::hasFeature('OA')) unset($lang->mainNav->oa, $lang->mainNav->menuOrder[62]);
if(!helper::hasFeature('traincourse'))
{
    unset($lang->mainNav->traincourse, $lang->mainNav->menuOrder[53]);
    $lang->dividerMenu = str_replace(',traincourse,', ',doc,', $lang->dividerMenu);
}

/* Charter */
$lang->charter = new stdclass();
$lang->charter->common = 'Charter';

$lang->program->homeMenu->charter = array('link' => "{$lang->charter->common}|charter|browse|", 'subModule' => 'charter', 'divider' => true);

$lang->navGroup->charter        = 'program';
$lang->searchObjects['charter'] = 'Charter';

$lang->deployment = new stdclass();
$lang->deployment->common = 'Deployment';

$lang->deploy = new stdclass();
$lang->deploy->common = 'Deploy';

$lang->createObjects['charter'] = 'Charter';

$createIconList = array();
foreach($lang->createIcons as $objectName => $iconName)
{
    $createIconList[$objectName] = $iconName;

    if($objectName == 'program') $createIconList['charter'] = 'seal';
}
$lang->createIcons = $createIconList;
$lang->projectFlow = 'Project Flow';

include dirname(dirname(__FILE__)) . DS . 'menu.php';
