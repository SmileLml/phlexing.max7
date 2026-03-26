<?php
$lang->try          = ' 试用';
$lang->maxName      = '旗舰版';
$lang->expireDate   = "到期时间：%s";
$lang->forever      = "永久授权";
$lang->unlimited    = "不限人数";
$lang->licensedUser = "授权人数：%s";
$lang->liteName     = '迅捷VIP版';

$lang->chart        = new stdclass();
$lang->dashboard    = new stdclass();
$lang->dataview     = new stdclass();
$lang->metriclib    = new stdclass();
$lang->relateObject = new stdclass();
$lang->relation     = new stdclass();
$lang->approvalflow = new stdclass();

$lang->navGroup->approvalflow = 'workflow';

$lang->approvalflow->common = '审批流';
$lang->chart->common        = '图表';
$lang->dashboard->common    = '仪表盘';
$lang->dataview->common     = '数据表';
$lang->metriclib->common    = '度量库';
$lang->relateObject->common = '关联对象';
$lang->relation->common     = '关联关系';

$lang->searchObjects['feedback']   = '反馈';
$lang->searchObjects['ticket']     = '工单';
$lang->searchObjects['practice']   = '实践';
$lang->searchObjects['service']    = '服务';
$lang->searchObjects['deploy']     = '上线';
$lang->searchObjects['deploystep'] = '上线步骤';

$lang->mainNav->menuOrder[47] = 'feedback';
$lang->mainNav->menuOrder[52] = 'workflow';
$lang->mainNav->menuOrder[53] = 'traincourse';
$lang->mainNav->menuOrder[62] = 'oa';

$lang->dividerMenu = ',aiapp,feedback,traincourse,system,';

$lang->noticeBizLimited = "<div style='float:left;color:red' id='bizUserLimited'>已经超出企业版授权人数限制。请联系：4006-8899-23，或者删除用户。</div>";

$lang->oa->webMenu = new stdclass();
$lang->oa->webMenu->attend   = array('link' => '考勤|attend|personal|', 'subModule' => 'attend');
$lang->oa->webMenu->leave    = array('link' => '请假|leave|personal|', 'alias' => 'browse', 'subModule' => 'leave');
$lang->oa->webMenu->makeup   = array('link' => '补班|makeup|personal|', 'alias' => 'create,edit,view,browse', 'subModule' => 'makeup');
$lang->oa->webMenu->overtime = array('link' => '加班|overtime|personal|', 'subModule' => 'overtime');
$lang->oa->webMenu->lieu     = array('link' => '调休|lieu|personal|', 'subModule' => 'lieu');

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
$lang->feedback->webMenu->unclosed   = array('link' => '未关闭|feedback|admin|browseType=unclosed', 'subModule' => 'tree');
$lang->feedback->webMenu->all        = array('link' => '全部|feedback|admin|browseType=all');
$lang->feedback->webMenu->public     = array('link' => '公开|feedback|admin|browseType=public');
$lang->feedback->webMenu->tostory    = array('link' => "转需求|feedback|admin|browseType=tostory");
$lang->feedback->webMenu->totask     = array('link' => '转任务|feedback|admin|browseType=totask');
$lang->feedback->webMenu->tobug      = array('link' => '转Bug|feedback|admin|browseType=tobug');
$lang->feedback->webMenu->totodo     = array('link' => '转待办|feedback|admin|browseType=totodo');
$lang->feedback->webMenu->assigntome = array('link' => '指派给我|feedback|admin|browseType=assigntome');

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
$lang->charter->common = '立项';

$lang->program->homeMenu->charter = array('link' => "{$lang->charter->common}|charter|browse|", 'subModule' => 'charter', 'divider' => true);

$lang->navGroup->charter        = 'program';
$lang->searchObjects['charter'] = '立项';

$lang->deployment = new stdclass();
$lang->deployment->common = '部署';

$lang->deploy = new stdclass();
$lang->deploy->common = '上线申请';

$lang->createObjects['charter'] = '立项';

$createIconList = array();
foreach($lang->createIcons as $objectName => $iconName)
{
    $createIconList[$objectName] = $iconName;

    if($objectName == 'program') $createIconList['charter'] = 'seal';
}
$lang->createIcons = $createIconList;
$lang->projectFlow = '项目流程';

include dirname(dirname(__FILE__)) . DS . 'menu.php';
