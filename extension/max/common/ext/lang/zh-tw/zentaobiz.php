<?php
$lang->try          = ' 試用';
$lang->maxName      = '旗艦版';
$lang->expireDate   = "到期時間：%s";
$lang->forever      = "永久授權";
$lang->unlimited    = "不限人數";
$lang->licensedUser = "授權人數：%s";
$lang->liteName     = '迅捷VIP版';

$lang->chart        = new stdclass();
$lang->dashboard    = new stdclass();
$lang->dataview     = new stdclass();
$lang->metriclib    = new stdclass();
$lang->relateObject = new stdclass();
$lang->relation     = new stdclass();
$lang->approvalflow = new stdclass();

$lang->navGroup->approvalflow = 'workflow';

$lang->approvalflow->common = '審批流';
$lang->chart->common        = '圖表';
$lang->dashboard->common    = '儀表盤';
$lang->dataview->common     = '數據表';
$lang->metriclib->common    = '度量庫';
$lang->relateObject->common = '關聯對象';
$lang->relation->common     = '關聯關係';

$lang->searchObjects['feedback']   = '反饋';
$lang->searchObjects['ticket']     = '工單';
$lang->searchObjects['practice']   = '實踐';
$lang->searchObjects['service']    = '服務';
$lang->searchObjects['deploy']     = '上線';
$lang->searchObjects['deploystep'] = '上線步驟';

$lang->mainNav->menuOrder[47] = 'feedback';
$lang->mainNav->menuOrder[52] = 'workflow';
$lang->mainNav->menuOrder[53] = 'traincourse';
$lang->mainNav->menuOrder[62] = 'oa';

$lang->dividerMenu = ',aiapp,feedback,traincourse,system,';

$lang->noticeBizLimited = "<div style='float:left;color:red' id='bizUserLimited'>已經超出企業版授權人數限制。請聯繫：4006-8899-23，或者刪除用戶。</div>";

$lang->oa->webMenu = new stdclass();
$lang->oa->webMenu->attend   = array('link' => '考勤|attend|personal|', 'subModule' => 'attend');
$lang->oa->webMenu->leave    = array('link' => '請假|leave|personal|', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->webMenu->makeup   = array('link' => '補班|makeup|personal|', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->webMenu->overtime = array('link' => '加班|overtime|personal|', 'subModule' => 'overtime');
$lang->oa->webMenu->lieu     = array('link' => '調休|lieu|personal|', 'subModule' => 'lieu');

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
$lang->feedback->webMenu->unclosed   = array('link' => '未關閉|feedback|admin|browseType=unclosed', 'subModule' => 'tree');
$lang->feedback->webMenu->all        = array('link' => '全部|feedback|admin|browseType=all');
$lang->feedback->webMenu->public     = array('link' => '公開|feedback|admin|browseType=public');
$lang->feedback->webMenu->tostory    = array('link' => "轉需求|feedback|admin|browseType=tostory");
$lang->feedback->webMenu->totask     = array('link' => '轉任務|feedback|admin|browseType=totask');
$lang->feedback->webMenu->tobug      = array('link' => '轉Bug|feedback|admin|browseType=tobug');
$lang->feedback->webMenu->totodo     = array('link' => '轉待辦|feedback|admin|browseType=totodo');
$lang->feedback->webMenu->assigntome = array('link' => '指派給我|feedback|admin|browseType=assigntome');

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
$lang->charter->common = '立項';

$lang->program->homeMenu->charter = array('link' => "{$lang->charter->common}|charter|browse|", 'subModule' => 'charter', 'divider' => true);

$lang->navGroup->charter        = 'program';
$lang->searchObjects['charter'] = '立項';

$lang->deployment = new stdclass();
$lang->deployment->common = '部署';

$lang->deploy = new stdclass();
$lang->deploy->common = '上線申請';

$lang->createObjects['charter'] = '立項';

$createIconList = array();
foreach($lang->createIcons as $objectName => $iconName)
{
    $createIconList[$objectName] = $iconName;

    if($objectName == 'program') $createIconList['charter'] = 'seal';
}
$lang->createIcons = $createIconList;
$lang->projectFlow = '項目流程';

include dirname(dirname(__FILE__)) . DS . 'menu.php';
