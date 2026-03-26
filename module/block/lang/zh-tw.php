<?php
/**
 * The zh-tw file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
global $config;
$lang->block->id         = '編號';
$lang->block->params     = '參數';
$lang->block->name       = '區塊名稱';
$lang->block->style      = '外觀';
$lang->block->grid       = '位置';
$lang->block->color      = '顏色';
$lang->block->reset      = '恢復預設';
$lang->block->story      = '需求';
$lang->block->investment = '投入';
$lang->block->estimate   = '預計工時';
$lang->block->last       = '近期';
$lang->block->width      = '長度';

$lang->block->account = '所屬用戶';
$lang->block->title   = '區塊名稱';
$lang->block->module  = '所屬模組';
$lang->block->code    = '區塊';
$lang->block->order   = '排序';
$lang->block->height  = '高度';
$lang->block->role    = '角色';

$lang->block->lblModule       = '模組';
$lang->block->lblBlock        = '區塊';
$lang->block->lblNum          = '條數';
$lang->block->lblHtml         = 'HTML內容';
$lang->block->html            = 'HTML';
$lang->block->dynamic         = '最新動態';
$lang->block->zentaoDynamic   = '禪道動態';
$lang->block->assignToMe      = '待處理';
$lang->block->wait            = '未開始';
$lang->block->doing           = '進行中';
$lang->block->done            = '已完成';
$lang->block->lblFlowchart    = '流程圖';
$lang->block->lblTesttask     = '查看測試詳情';
$lang->block->contribute      = '我的貢獻';
$lang->block->finish          = '已完成';
$lang->block->guide           = '使用幫助';
$lang->block->teamAchievement = '團隊成就';
$lang->block->learnMore       = '瞭解更多';
$lang->block->prevPage        = '上一頁';
$lang->block->nextPage        = '下一頁';
$lang->block->experience      = '開始體驗';

$lang->block->leftToday           = '今天剩餘工作總計';
$lang->block->myTask              = '我的任務';
$lang->block->myStory             = "我的{$lang->SRCommon}";
$lang->block->myBug               = '我的BUG';
$lang->block->myExecution         = '未關閉的' . $lang->executionCommon;
$lang->block->myProduct           = '未關閉的' . $lang->productCommon;
$lang->block->delay               = '延期';
$lang->block->delayed             = '已延期';
$lang->block->noData              = '當前統計類型下暫無數據';
$lang->block->emptyTip            = '暫無數據';
$lang->block->createdTodos        = '創建的待辦數';
$lang->block->createdRequirements = '創建的' . $lang->URCommon . '數';
$lang->block->createdStories      = '創建的' . $lang->SRCommon . '數';
$lang->block->finishedTasks       = '完成的任務數';
$lang->block->createdBugs         = '提交的Bug數';
$lang->block->resolvedBugs        = '解決的Bug數';
$lang->block->createdCases        = '創建的用例數';
$lang->block->createdRisks        = '創建的風險數';
$lang->block->resolvedRisks       = '解決的風險數';
$lang->block->createdIssues       = '創建的問題數';
$lang->block->resolvedIssues      = '解決的問題數';
$lang->block->createdDocs         = '創建的文檔數';
$lang->block->allExecutions       = '所有' . $lang->executionCommon;
$lang->block->doingExecution      = '進行中的' . $lang->executionCommon;
$lang->block->finishExecution     = '累積' . $lang->executionCommon;
$lang->block->estimatedHours      = '預計';
$lang->block->consumedHours       = '已消耗';
$lang->block->time                = '第';
$lang->block->week                = '周';
$lang->block->month               = '月';
$lang->block->selectProduct       = "選擇{$lang->productCommon}";
$lang->block->blockTitle          = '%1$s的%2$s';
$lang->block->remain              = '剩餘工時';
$lang->block->allStories          = '總需求';

$lang->block->createBlock        = '添加區塊';
$lang->block->editBlock          = '編輯區塊';
$lang->block->ordersSaved        = '排序已保存';
$lang->block->confirmRemoveBlock = '確定隱藏區塊嗎？';
$lang->block->noticeNewBlock     = '10.0版本以後各個視圖主頁提供了全新的視圖，您要啟用新的視圖佈局嗎？';
$lang->block->confirmReset       = '是否恢復預設佈局？';
$lang->block->closeForever       = '永久關閉';
$lang->block->confirmClose       = '確定永久關閉該區塊嗎？關閉後所有人都將無法使用該區塊，可以在[後台-功能配置-地盤-區塊]中打開。';
$lang->block->remove             = '移除';
$lang->block->refresh            = '刷新';
$lang->block->nbsp               = '';
$lang->block->hidden             = '隱藏';
$lang->block->dynamicInfo        = "<span class='timeline-tag'>%s</span> <span class='timeline-text'>%s<span class='label-action'>%s</span>%s<a href='%s' title='%s'>%s</a></span>";
$lang->block->noLinkDynamic      = "<span class='timeline-tag'>%s</span> <span class='timeline-text' title='%s'>%s<span class='label-action'>%s</span>%s<span class='label-name'>%s</span></span>";
$lang->block->cannotPlaceInLeft  = '此區塊無法放置在左側。';
$lang->block->cannotPlaceInRight = '此區塊無法放置在右側。';
$lang->block->tutorial           = '進入使用教程';
$lang->block->filterProject      = "{$lang->projectCommon}篩選";

$lang->block->productName   = $lang->productCommon . '名稱';
$lang->block->totalStory    = '總' . $lang->SRCommon;
$lang->block->totalBug      = '總Bug';
$lang->block->totalRelease  = '發佈次數';
$lang->block->totalTask     = '總' . $lang->task->common;
$lang->block->projectMember = '團隊成員';
$lang->block->totalMember   = '共 %s 人';

$lang->block->totalInvestment = '已投入';
$lang->block->totalPeople     = '總人數';
$lang->block->spent           = '已花費';
$lang->block->budget          = '預算';
$lang->block->left            = '剩餘';

$lang->block->summary = new stdclass();
$lang->block->summary->welcome    = '禪道已陪伴您%s： %s今日期待優秀的您來處理！';
$lang->block->summary->yesterday  = '<strong>昨日</strong>';
$lang->block->summary->noWork     = '您暫未處理任務和Bug，';
$lang->block->summary->finishTask = '完成了<a href="' .  helper::createLink('my', 'contribute', 'mode=task&type=finishedBy') . '" class="text-success">%s</a>個任務';
$lang->block->summary->fixBug     = '解決了<a href="' . helper::createLink('my', 'contribute', 'mode=bug&type=resolvedBy') . '" class="text-success">%s</a>個Bug';

$lang->block->dashboard['default'] = '儀表盤';
$lang->block->dashboard['my']      = '地盤';

$lang->block->titleList['flowchart']      = '流程圖';
$lang->block->titleList['guide']          = '使用幫助';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}統計";
$lang->block->titleList['recentproject']  = "我近期參與的{$lang->projectCommon}";
$lang->block->titleList['assigntome']     = '待處理';
$lang->block->titleList['project']        = "{$lang->projectCommon}列表";
$lang->block->titleList['dynamic']        = '最新動態';
$lang->block->titleList['list']           = '我的待辦';
$lang->block->titleList['scrumoverview']  = "{$lang->projectCommon}總覽";
$lang->block->titleList['scrumtest']      = '測試單列表';
$lang->block->titleList['scrumlist']      = '迭代列表';
$lang->block->titleList['sprint']         = '迭代總覽';
$lang->block->titleList['projectdynamic'] = '最新動態';
$lang->block->titleList['bug']            = '指派給我的Bug';
$lang->block->titleList['case']           = '指派給我的用例';
$lang->block->titleList['testtask']       = '測試單列表';
$lang->block->titleList['statistic']      = "{$lang->projectCommon}統計";

$lang->block->default['scrumproject'][] = array('title' => "{$lang->projectCommon}總覽",   'module' => 'scrumproject', 'code' => 'scrumoverview',  'width' => '2');
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon}列表", 'module' => 'scrumproject', 'code' => 'scrumlist',      'width' => '2', 'params' => array('type' => 'undone', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => '待測測試單列表',               'module' => 'scrumproject', 'code' => 'scrumtest',      'width' => '2', 'params' => array('type' => 'wait', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['scrumproject'][] = array('title' => "{$lang->executionCommon}總覽", 'module' => 'scrumproject', 'code' => 'sprint',         'width' => '1');
$lang->block->default['scrumproject'][] = array('title' => '最新動態',                     'module' => 'scrumproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['kanbanproject']    = $lang->block->default['scrumproject'];
unset($lang->block->default['kanbanproject'][2]);
$lang->block->default['agileplusproject'] = $lang->block->default['scrumproject'];

$lang->block->default['waterfallproject'][] = array('title' => "{$lang->projectCommon}計劃", 'module' => 'waterfallproject', 'code' => 'waterfallgantt', 'width' => '2');
$lang->block->default['waterfallproject'][] = array('title' => '最新動態',                   'module' => 'waterfallproject', 'code' => 'projectdynamic', 'width' => '1');

$lang->block->default['waterfallplusproject'] = $lang->block->default['waterfallproject'];
$lang->block->default['ipdproject']           = $lang->block->default['waterfallproject'];

$lang->block->default['product'][] = array('title' => "{$lang->productCommon}總覽",             'module' => 'product', 'code' => 'overview',         'width' => '3');
$lang->block->default['product'][] = array('title' => "未關閉的{$lang->productCommon}統計",     'module' => 'product', 'code' => 'statistic',        'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "未關閉{$lang->productCommon}的Bug統計",  'module' => 'product', 'code' => 'bugstatistic',     'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}月度推進分析",     'module' => 'product', 'code' => 'monthlyprogress',  'width' => '2');
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}年度工作量統計",   'module' => 'product', 'code' => 'annualworkload',   'width' => '2');
$lang->block->default['product'][] = array('title' => "未關閉的{$lang->productCommon}列表",     'module' => 'product', 'code' => 'list',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['product'][] = array('title' => "未關閉{$lang->productCommon}的發佈列表", 'module' => 'product', 'code' => 'release',          'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "未關閉{$lang->productCommon}的計劃列表", 'module' => 'product', 'code' => 'plan',             'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['product'][] = array('title' => "{$lang->productCommon}發佈統計",         'module' => 'product', 'code' => 'releasestatistic', 'width' => '1');
$lang->block->default['product'][] = array('title' => "指派給我的{$lang->SRCommon}",            'module' => 'product', 'code' => 'story',            'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));

$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}統計",               'module' => 'singleproduct', 'code' => 'singlestatistic',        'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}的Bug統計",          'module' => 'singleproduct', 'code' => 'singlebugstatistic',     'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}路線圖",             'module' => 'singleproduct', 'code' => 'roadmap',                'width' => '2');
$lang->block->default['singleproduct'][] = array('title' => "指派給我的{$lang->SRCommon}",              'module' => 'singleproduct', 'code' => 'singlestory',            'width' => '2', 'params' => array('type' => 'assignedTo', 'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}計劃列表",           'module' => 'singleproduct', 'code' => 'singleplan',             'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}發佈統計",           'module' => 'singleproduct', 'code' => 'singlerelease',          'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['singleproduct'][] = array('title' => "最新動態",                                 'module' => 'singleproduct', 'code' => 'singledynamic',          'width' => '1');
$lang->block->default['singleproduct'][] = array('title' => "{$lang->productCommon}月度推進分析",       'module' => 'singleproduct', 'code' => 'singlemonthlyprogress',  'width' => '1');

$lang->block->default['qa'][] = array('title' => '測試統計',           'module' => 'qa', 'code' => 'statistic', 'width' => '2', 'params' => array('type' => 'noclosed',   'count' => '20'));
$lang->block->default['qa'][] = array('title' => '待測測試單列表',     'module' => 'qa', 'code' => 'testtask',  'width' => '2', 'params' => array('type' => 'wait',       'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => '指派給我的Bug列表',  'module' => 'qa', 'code' => 'bug',       'width' => '1', 'params' => array('type' => 'assignedTo', 'count' => '15', 'orderBy' => 'id_desc'));
$lang->block->default['qa'][] = array('title' => '指派給我的用例列表', 'module' => 'qa', 'code' => 'case',      'width' => '1', 'params' => array('type' => 'assigntome', 'count' => '15', 'orderBy' => 'id_desc'));

$lang->block->default['full']['my'][] = array('title' => '歡迎總覽',                               'module' => 'welcome',         'code' => 'welcome',         'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "使用幫助",                               'module' => 'guide',           'code' => 'guide',           'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "我的待處理",                             'module' => 'assigntome',      'code' => 'assigntome',      'width' => '2', 'params' => array('todoCount' => '20',  'taskCount' => '20', 'bugCount' => '20', 'riskCount' => '20', 'issueCount' => '20', 'storyCount' => '20', 'reviewCount' => '20', 'meetingCount' => '20', 'feedbackCount' => '20'));
$lang->block->default['full']['my'][] = array('title' => "我近期參與的{$lang->projectCommon}",     'module' => 'project',         'code' => 'recentproject',   'width' => '2');
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->projectCommon}列表",     'module' => 'project',         'code' => 'project',         'width' => '2', 'params' => array('type' => 'undone',   'count' => '20', 'orderBy' => 'id_desc'));
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->execution->common}統計", 'module' => 'execution',       'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "未完成的{$lang->projectCommon}統計",     'module' => 'project',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'undone',   'count' => '20'));
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "未關閉的{$lang->productCommon}統計",     'module' => 'product',         'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "未關閉{$lang->productCommon}的測試統計", 'module' => 'qa',              'code' => 'statistic',       'width' => '2', 'params' => array('type' => 'noclosed', 'count' => '20'));
$lang->block->default['full']['my'][] = array('title' => "禪道動態",                               'module' => 'zentaodynamic',   'code' => 'zentaodynamic',   'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "最新動態",                               'module' => 'dynamic',         'code' => 'dynamic',         'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "團隊成就",                               'module' => 'teamachievement', 'code' => 'teamachievement', 'width' => '1');
if($config->vision != 'lite') $lang->block->default['full']['my'][] = array('title' => "{$lang->productCommon}總覽",             'module' => 'product',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->projectCommon}總覽",             'module' => 'project',         'code' => 'overview',        'width' => '1');
$lang->block->default['full']['my'][] = array('title' => "{$lang->execution->common}總覽",         'module' => 'execution',       'code' => 'overview',        'width' => '1');

$lang->block->default['doc'][] = array('title' => '文檔統計',                   'module' => 'doc', 'code' => 'docstatistic',    'width' => '2');
$lang->block->default['doc'][] = array('title' => '我收藏的文檔',               'module' => 'doc', 'code' => 'docmycollection', 'width' => '2');
$lang->block->default['doc'][] = array('title' => '我創建的文檔',               'module' => 'doc', 'code' => 'docmycreated',    'width' => '2');
$lang->block->default['doc'][] = array('title' => '最近更新的文檔',             'module' => 'doc', 'code' => 'docrecentupdate', 'width' => '2');
if($config->vision == 'rnd') $lang->block->default['doc'][] = array('title' => "{$lang->productCommon}文檔", 'module' => 'doc', 'code' => 'productdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => "{$lang->projectCommon}文檔", 'module' => 'doc', 'code' => 'projectdoc',      'width' => '2', 'params' => array('count' => '20'));
$lang->block->default['doc'][] = array('title' => '文檔動態',                   'module' => 'doc', 'code' => 'docdynamic',      'width' => '1');
$lang->block->default['doc'][] = array('title' => '瀏覽排行榜',                 'module' => 'doc', 'code' => 'docviewlist',     'width' => '1');
$lang->block->default['doc'][] = array('title' => '收藏排行榜',                 'module' => 'doc', 'code' => 'doccollectlist',  'width' => '1');

$lang->block->count   = '數量';
$lang->block->type    = '類型';
$lang->block->orderBy = '排序';

$lang->block->availableBlocks['todo']        = '待辦';
$lang->block->availableBlocks['task']        = '任務';
$lang->block->availableBlocks['bug']         = 'Bug';
$lang->block->availableBlocks['case']        = '用例';
$lang->block->availableBlocks['story']       = "{$lang->SRCommon}";
$lang->block->availableBlocks['requirement'] = "{$lang->URCommon}";
$lang->block->availableBlocks['product']     = $lang->productCommon . '列表';
$lang->block->availableBlocks['execution']   = $lang->execution->common . '列表';
$lang->block->availableBlocks['plan']        = "計劃列表";
$lang->block->availableBlocks['release']     = '發佈列表';
$lang->block->availableBlocks['build']       = '構建列表';
$lang->block->availableBlocks['testcase']    = '用例';
$lang->block->availableBlocks['testtask']    = '測試單';
$lang->block->availableBlocks['risk']        = '風險';
$lang->block->availableBlocks['issue']       = '問題';
$lang->block->availableBlocks['meeting']     = '會議';
$lang->block->availableBlocks['feedback']    = '反饋';
$lang->block->availableBlocks['ticket']      = '工單';
$lang->block->availableBlocks['demand']      = '需求池需求';

$lang->block->modules['project'] = new stdclass();
$lang->block->modules['project']->availableBlocks['overview']      = "{$lang->projectCommon}總覽";
$lang->block->modules['project']->availableBlocks['recentproject'] = "我近期參與的{$lang->projectCommon}";
$lang->block->modules['project']->availableBlocks['statistic']     = "{$lang->projectCommon}統計";
$lang->block->modules['project']->availableBlocks['project']       = "{$lang->projectCommon}列表";

$lang->block->modules['scrumproject'] = new stdclass();
$lang->block->modules['scrumproject']->availableBlocks['scrumoverview']  = "{$lang->projectCommon}總覽";
$lang->block->modules['scrumproject']->availableBlocks['scrumlist']      = $lang->executionCommon . '列表';
$lang->block->modules['scrumproject']->availableBlocks['sprint']         = $lang->executionCommon . '總覽';
$lang->block->modules['scrumproject']->availableBlocks['scrumtest']      = '測試單列表';
$lang->block->modules['scrumproject']->availableBlocks['projectdynamic'] = '最新動態';

$lang->block->modules['waterfallproject'] = new stdclass();
$lang->block->modules['waterfallproject']->availableBlocks['waterfallgantt'] = "{$lang->projectCommon}計劃";
$lang->block->modules['waterfallproject']->availableBlocks['projectdynamic'] = '最新動態';

$lang->block->modules['agileplusproject']     = $lang->block->modules['scrumproject'];
$lang->block->modules['waterfallplusproject'] = $lang->block->modules['waterfallproject'];
$lang->block->modules['ipdproject']           = $lang->block->modules['waterfallproject'];

$lang->block->modules['product'] = new stdclass();
$lang->block->modules['product']->availableBlocks['overview']         = "{$lang->productCommon}總覽";
$lang->block->modules['product']->availableBlocks['statistic']        = "{$lang->productCommon}統計";
$lang->block->modules['product']->availableBlocks['releasestatistic'] = "{$lang->productCommon}發佈統計";
$lang->block->modules['product']->availableBlocks['bugstatistic']     = "{$lang->productCommon}Bug統計";
$lang->block->modules['product']->availableBlocks['annualworkload']   = "{$lang->productCommon}年度工作量統計";
$lang->block->modules['product']->availableBlocks['monthlyprogress']  = "{$lang->productCommon}月度推進分析";
$lang->block->modules['product']->availableBlocks['list']             = "{$lang->productCommon}列表";
$lang->block->modules['product']->availableBlocks['plan']             = "{$lang->productCommon}的計劃列表";
$lang->block->modules['product']->availableBlocks['release']          = "{$lang->productCommon}的發佈列表";
$lang->block->modules['product']->availableBlocks['story']            = "{$lang->SRCommon}列表";

$lang->block->modules['singleproduct'] = new stdclass();
$lang->block->modules['singleproduct']->availableBlocks['singlestatistic']       = "{$lang->productCommon}統計";
$lang->block->modules['singleproduct']->availableBlocks['singlebugstatistic']    = "{$lang->productCommon}Bug統計";
$lang->block->modules['singleproduct']->availableBlocks['roadmap']               = "{$lang->productCommon}路線圖";
$lang->block->modules['singleproduct']->availableBlocks['singlestory']           = "{$lang->SRCommon}列表";
$lang->block->modules['singleproduct']->availableBlocks['singleplan']            = "{$lang->productCommon}計劃列表";
$lang->block->modules['singleproduct']->availableBlocks['singlerelease']         = "{$lang->productCommon}發佈列表";
$lang->block->modules['singleproduct']->availableBlocks['singledynamic']         = '最新動態';
$lang->block->modules['singleproduct']->availableBlocks['singlemonthlyprogress'] = "{$lang->productCommon}月度推進分析";

$lang->block->modules['execution'] = new stdclass();
$lang->block->modules['execution']->availableBlocks['statistic'] = $lang->execution->common . '統計';
$lang->block->modules['execution']->availableBlocks['overview']  = $lang->execution->common . '總覽';
$lang->block->modules['execution']->availableBlocks['list']      = $lang->execution->common . '列表';
$lang->block->modules['execution']->availableBlocks['task']      = '任務列表';
$lang->block->modules['execution']->availableBlocks['build']     = '構建列表';

$lang->block->modules['qa'] = new stdclass();
$lang->block->modules['qa']->availableBlocks['statistic'] = "{$lang->productCommon}的測試統計";
$lang->block->modules['qa']->availableBlocks['bug']       = 'Bug列表';
$lang->block->modules['qa']->availableBlocks['case']      = '用例列表';
$lang->block->modules['qa']->availableBlocks['testtask']  = '測試單列表';

$lang->block->modules['todo'] = new stdclass();
$lang->block->modules['todo']->availableBlocks['list'] = '待辦列表';

$lang->block->modules['doc'] = new stdclass();
$lang->block->modules['doc']->availableBlocks['docstatistic']    = '文檔統計';
$lang->block->modules['doc']->availableBlocks['docdynamic']      = '文檔動態';
$lang->block->modules['doc']->availableBlocks['docmycollection'] = '我收藏的文檔';
$lang->block->modules['doc']->availableBlocks['docmycreated']    = '我創建的文檔';
$lang->block->modules['doc']->availableBlocks['docrecentupdate'] = '最近更新';
$lang->block->modules['doc']->availableBlocks['docviewlist']     = '瀏覽排行榜';
if($config->vision == 'rnd') $lang->block->modules['doc']->availableBlocks['productdoc'] = $lang->productCommon . '文檔';
$lang->block->modules['doc']->availableBlocks['doccollectlist']  = '收藏排行榜';
$lang->block->modules['doc']->availableBlocks['projectdoc']      = $lang->projectCommon . '文檔';

$lang->block->orderByList = new stdclass();
$lang->block->orderByList->product = array();
$lang->block->orderByList->product['id_asc']      = 'ID 遞增';
$lang->block->orderByList->product['id_desc']     = 'ID 遞減';
$lang->block->orderByList->product['status_asc']  = '狀態正序';
$lang->block->orderByList->product['status_desc'] = '狀態倒序';

$lang->block->orderByList->project = array();
$lang->block->orderByList->project['id_asc']      = 'ID 遞增';
$lang->block->orderByList->project['id_desc']     = 'ID 遞減';
$lang->block->orderByList->project['status_asc']  = '狀態正序';
$lang->block->orderByList->project['status_desc'] = '狀態倒序';

$lang->block->orderByList->execution = array();
$lang->block->orderByList->execution['id_asc']      = 'ID 遞增';
$lang->block->orderByList->execution['id_desc']     = 'ID 遞減';
$lang->block->orderByList->execution['status_asc']  = '狀態正序';
$lang->block->orderByList->execution['status_desc'] = '狀態倒序';

$lang->block->orderByList->task = array();
$lang->block->orderByList->task['id_asc']        = 'ID 遞增';
$lang->block->orderByList->task['id_desc']       = 'ID 遞減';
$lang->block->orderByList->task['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->task['pri_desc']      = '優先順序遞減';
$lang->block->orderByList->task['estimate_asc']  = '預計時間遞增';
$lang->block->orderByList->task['estimate_desc'] = '預計時間遞減';
$lang->block->orderByList->task['status_asc']    = '狀態正序';
$lang->block->orderByList->task['status_desc']   = '狀態倒序';
$lang->block->orderByList->task['deadline_asc']  = '截止日期遞增';
$lang->block->orderByList->task['deadline_desc'] = '截止日期遞減';

$lang->block->orderByList->bug = array();
$lang->block->orderByList->bug['id_asc']        = 'ID 遞增';
$lang->block->orderByList->bug['id_desc']       = 'ID 遞減';
$lang->block->orderByList->bug['pri_asc']       = '優先順序遞增';
$lang->block->orderByList->bug['pri_desc']      = '優先順序遞減';
$lang->block->orderByList->bug['severity_asc']  = '級別遞增';
$lang->block->orderByList->bug['severity_desc'] = '級別遞減';

$lang->block->orderByList->case = array();
$lang->block->orderByList->case['id_asc']   = 'ID 遞增';
$lang->block->orderByList->case['id_desc']  = 'ID 遞減';
$lang->block->orderByList->case['pri_asc']  = '優先順序遞增';
$lang->block->orderByList->case['pri_desc'] = '優先順序遞減';

$lang->block->orderByList->story = array();
$lang->block->orderByList->story['id_asc']      = 'ID 遞增';
$lang->block->orderByList->story['id_desc']     = 'ID 遞減';
$lang->block->orderByList->story['pri_asc']     = '優先順序遞增';
$lang->block->orderByList->story['pri_desc']    = '優先順序遞減';
$lang->block->orderByList->story['status_asc']  = '狀態正序';
$lang->block->orderByList->story['status_desc'] = '狀態倒序';
$lang->block->orderByList->story['stage_asc']   = '階段正序';
$lang->block->orderByList->story['stage_desc']  = '階段倒序';

$lang->block->todoCount     = '待辦數';
$lang->block->taskCount     = '任務數';
$lang->block->bugCount      = 'Bug數';
$lang->block->riskCount     = '風險數';
$lang->block->issueCount    = '問題數';
$lang->block->storyCount    = $lang->SRCommon . '數';
$lang->block->reviewCount   = '審批數';
$lang->block->meetingCount  = '會議數';
$lang->block->feedbackCount = '反饋數';
$lang->block->ticketCount   = '工單數';

$lang->block->typeList = new stdclass();
$lang->block->typeList->task['assignedTo'] = '指派給我';
$lang->block->typeList->task['openedBy']   = '由我創建';
$lang->block->typeList->task['finishedBy'] = '由我完成';
$lang->block->typeList->task['closedBy']   = '由我關閉';
$lang->block->typeList->task['canceledBy'] = '由我取消';

$lang->block->typeList->bug['assignedTo'] = '指派給我';
$lang->block->typeList->bug['openedBy']   = '由我創建';
$lang->block->typeList->bug['resolvedBy'] = '由我解決';
$lang->block->typeList->bug['closedBy']   = '由我關閉';

$lang->block->typeList->case['assigntome'] = '指派給我';
$lang->block->typeList->case['openedbyme'] = '由我創建';

$lang->block->typeList->story['assignedTo'] = '指派給我';
$lang->block->typeList->story['reviewBy']   = '待我評審';
$lang->block->typeList->story['openedBy']   = '由我創建';
$lang->block->typeList->story['reviewedBy'] = '我評審過';
$lang->block->typeList->story['closedBy']   = '由我關閉';

$lang->block->typeList->product['noclosed'] = '未關閉';
$lang->block->typeList->product['closed']   = '已關閉';
$lang->block->typeList->product['all']      = '全部';
$lang->block->typeList->product['involved'] = '我參與';

$lang->block->typeList->project['undone']   = '未完成';
$lang->block->typeList->project['doing']    = '進行中';
$lang->block->typeList->project['all']      = '全部';
$lang->block->typeList->project['involved'] = '我參與的';

$lang->block->typeList->projectAll['all']       = '全部';
$lang->block->typeList->projectAll['undone']    = '未完成';
$lang->block->typeList->projectAll['wait']      = '未開始';
$lang->block->typeList->projectAll['doing']     = '進行中';
$lang->block->typeList->projectAll['suspended'] = '已掛起';
$lang->block->typeList->projectAll['closed']    = '已關閉';

$lang->block->typeList->execution['undone']   = '未完成';
$lang->block->typeList->execution['doing']    = '進行中';
$lang->block->typeList->execution['all']      = '所有';
$lang->block->typeList->execution['involved'] = '我參與';

$lang->block->typeList->scrum['undone']   = '未完成';
$lang->block->typeList->scrum['doing']    = '進行中';
$lang->block->typeList->scrum['all']      = '全部';
$lang->block->typeList->scrum['involved'] = '我參與';

$lang->block->typeList->testtask['wait']    = '待測';
$lang->block->typeList->testtask['doing']   = '測試中';
$lang->block->typeList->testtask['blocked'] = '阻塞';
$lang->block->typeList->testtask['done']    = '已測';
$lang->block->typeList->testtask['all']     = '全部';

$lang->block->typeList->risk['all']      = '全部';
$lang->block->typeList->risk['active']   = '開放';
$lang->block->typeList->risk['assignTo'] = '指派給我';
$lang->block->typeList->risk['assignBy'] = '由我指派';
$lang->block->typeList->risk['closed']   = '已關閉';
$lang->block->typeList->risk['hangup']   = '已掛起';
$lang->block->typeList->risk['canceled'] = '已取消';

$lang->block->typeList->issue['all']      = '全部';
$lang->block->typeList->issue['open']     = '開放';
$lang->block->typeList->issue['assignto'] = '指派給我';
$lang->block->typeList->issue['assignby'] = '由我指派';
$lang->block->typeList->issue['closed']   = '已關閉';
$lang->block->typeList->issue['resolved'] = '已解決';
$lang->block->typeList->issue['canceled'] = '已取消';

$lang->block->welcomeList['06:00'] = '%s，早上好';
$lang->block->welcomeList['11:30'] = '%s，中午好';
$lang->block->welcomeList['13:30'] = '%s，下午好';
$lang->block->welcomeList['19:00'] = '%s，晚上好';

$lang->block->gridOptions[8] = '左側';
$lang->block->gridOptions[4] = '右側';

$lang->block->widthOptions['1'] = '短區塊';
$lang->block->widthOptions['2'] = '長區塊';
$lang->block->widthOptions['3'] = '超長區塊';

$lang->block->flowchart            = array();
$lang->block->flowchart['admin']   = array('管理員', '維護部門', '添加用戶', '維護權限');
if($config->systemMode == 'ALM') $lang->block->flowchart['program'] = array('項目集負責人', '創建項目集', "關聯{$lang->productCommon}", "創建{$lang->projectCommon}", "制定預算和規劃", '添加干係人');
$lang->block->flowchart['product'] = array($lang->productCommon . '經理', '創建' . $lang->productCommon, '維護模組', "維護計劃", "維護需求", '創建發佈');
$lang->block->flowchart['project'] = array('項目經理', "創建{$lang->projectCommon}、" . $lang->execution->common, '維護團隊', "關聯需求", '分解任務', '跟蹤進度');
$lang->block->flowchart['dev']     = array('研發人員', '領取任務和Bug', '設計實現方案', '更新狀態', '完成任務和Bug', '提交代碼');
$lang->block->flowchart['tester']  = array('測試人員', '撰寫用例', '執行用例', '提交Bug', '驗證Bug', '關閉Bug');

$lang->block->zentaoapp = new stdclass();
$lang->block->zentaoapp->common               = '禪道移動端';
$lang->block->zentaoapp->thisYearInvestment   = '今年投入';
$lang->block->zentaoapp->sinceTotalInvestment = '從使用至今，總投入';
$lang->block->zentaoapp->myStory              = '我的需求';
$lang->block->zentaoapp->allStorySum          = '需求總數';
$lang->block->zentaoapp->storyCompleteRate    = '需求完成率';
$lang->block->zentaoapp->latestExecution      = '近期執行';
$lang->block->zentaoapp->involvedExecution    = '我參與的執行';
$lang->block->zentaoapp->mangedProduct        = "負責{$lang->productCommon}";
$lang->block->zentaoapp->involvedProject      = "參與{$lang->projectCommon}";
$lang->block->zentaoapp->customIndexCard      = '定製首頁卡片';
$lang->block->zentaoapp->createStory          = '提需求';
$lang->block->zentaoapp->createEffort         = '記日誌';
$lang->block->zentaoapp->createDoc            = '建文檔';
$lang->block->zentaoapp->createTodo           = '建待辦';
$lang->block->zentaoapp->workbench            = '工作台';
$lang->block->zentaoapp->notSupportKanban     = '移動端暫不支持研發看板模式';
$lang->block->zentaoapp->notSupportVersion    = '移動端暫不支持該禪道版本';
$lang->block->zentaoapp->incompatibleVersion  = '當前禪道版本較低，請升級至最新版本後再試';
$lang->block->zentaoapp->canNotGetVersion     = '獲取禪道版本失敗，請確認網址是否正確';
$lang->block->zentaoapp->desc                 = "禪道移動端為您提供移動辦公的環境，方便隨時管理個人待辦事務，跟進{$lang->projectCommon}進度，增強了{$lang->projectCommon}管理的靈活性和敏捷性。";
$lang->block->zentaoapp->downloadTip          = '掃瞄二維碼下載';

$lang->block->zentaoclient = new stdClass();
$lang->block->zentaoclient->common = '禪道客戶端';
$lang->block->zentaoclient->desc   = '您可以使用禪道桌面客戶端直接使用禪道，無需頻繁切換瀏覽器。除此之外，客戶端還提供了聊天，信息通知，機器人，內嵌禪道小程序等功能，團隊協作更方便。';

$lang->block->zentaoclient->edition = new stdclass();
$lang->block->zentaoclient->edition->win64   = 'Windows版';
$lang->block->zentaoclient->edition->linux64 = 'Linux版';
$lang->block->zentaoclient->edition->mac64   = 'Mac版';

$lang->block->guideTabs['flowchart']      = '流程圖';
if($config->systemMode != 'PLM') $lang->block->guideTabs['systemMode']     = '運行模式';
$lang->block->guideTabs['visionSwitch']   = '界面切換';
$lang->block->guideTabs['themeSwitch']    = '主題切換';
$lang->block->guideTabs['preference']     = '個性化設置';
$lang->block->guideTabs['downloadClient'] = '客戶端下載';
$lang->block->guideTabs['downloadMobile'] = '移動端下載';

$lang->block->themes['default']    = '禪道藍';
$lang->block->themes['blue']       = '青春藍';
$lang->block->themes['green']      = '葉蘭綠';
$lang->block->themes['red']        = '赤誠紅';
$lang->block->themes['purple']     = '萱萱紫';

$lang->block->visionTitle            = '禪道使用界面分為【研發綜合界面】和【運營管理界面】。';
$lang->block->visions['rnd']         = new stdclass();
$lang->block->visions['rnd']->key    = 'rnd';
$lang->block->visions['rnd']->title  = '研發綜合界面';
$lang->block->visions['rnd']->text   = "集項目集、{$lang->productCommon}、{$lang->projectCommon}、執行、測試等多維度管理於一體，提供全過程{$lang->projectCommon}管理解決方案。";
$lang->block->visions['lite']        = new stdclass();
$lang->block->visions['lite']->key   = 'lite';
$lang->block->visions['lite']->title = '運營管理界面';
$lang->block->visions['lite']->text  = "專為非研發團隊打造，主要以直觀、可視化的看板{$lang->projectCommon}管理模型為主。";

$lang->block->customModes['light'] = '輕量管理模式';
$lang->block->customModes['ALM']   = '全生命周期管理模式';

$lang->block->honorary = array();
$lang->block->honorary['bug']    = '消滅BUG能力者';
$lang->block->honorary['task']   = '勤勞小蜜蜂';
$lang->block->honorary['review'] = '模範評審官';

$lang->block->welcome = new stdclass();
$lang->block->welcome->common     = '歡迎總覽';
$lang->block->welcome->reviewByMe = '待我評審';
$lang->block->welcome->assignToMe = '指派給我';

$lang->block->welcome->reviewList = array();
$lang->block->welcome->reviewList['story']      = $lang->SRCommon . '數';
$lang->block->welcome->reviewList['reviewByMe'] = '待我評審數';

$lang->block->welcome->assignList = array();
$lang->block->welcome->assignList['task'] = '任務數';
if($config->vision != 'or') $lang->block->welcome->assignList['bug']   = 'BUG數';
if($config->vision != 'or') $lang->block->welcome->assignList['story'] = "{$lang->SRCommon}數";
$lang->block->welcome->assignList['testcase'] = '用例數';
if($config->URAndSR && $config->vision != 'or')  $lang->block->welcome->assignList['requirement'] = "{$lang->URCommon}數";
if($config->enableER && $config->vision != 'or') $lang->block->welcome->assignList['epic']        = "{$lang->ERCommon}數";

$lang->block->customModeTip = new stdClass();
$lang->block->customModeTip->common = '禪道運行模式分為【輕量級管理模式】和【全生命周期管理模式】。';
$lang->block->customModeTip->ALM    = '適用於中大型團隊的管理模式，概念更加完整、嚴謹，功能更豐富。';
$lang->block->customModeTip->light  = "適用於小型研發團隊的管理模式，提供{$lang->projectCommon}管理的核心功能。";

$lang->block->productstatistic = new stdclass();
$lang->block->productstatistic->effectiveStory  = '有效需求';
$lang->block->productstatistic->delivered       = '已交付';
$lang->block->productstatistic->unclosed        = '未關閉';
$lang->block->productstatistic->storyStatistics = '需求統計';
$lang->block->productstatistic->monthDone       = '本月完成 <span class="text-success font-bold">%s</span>';
$lang->block->productstatistic->monthOpened     = '本月新增 <span class="text-primary font-bold">%s</span>';
$lang->block->productstatistic->opened          = '新增';
$lang->block->productstatistic->done            = '完成';
$lang->block->productstatistic->news            = '產品最新推進';
$lang->block->productstatistic->newPlan         = '最新計劃';
$lang->block->productstatistic->newExecution    = '最新執行';
$lang->block->productstatistic->newRelease      = '最新發佈';
$lang->block->productstatistic->deliveryRate    = '需求交付率';

$lang->block->projectoverview = new stdclass();
$lang->block->projectoverview->totalProject  = '項目總量';
$lang->block->projectoverview->thisYear      = '今年完成';
$lang->block->projectoverview->lastThreeYear = '近三年完成的項目數量分佈';

$lang->block->projectstatistic = new stdclass();
$lang->block->projectstatistic->story            = '需求';
$lang->block->projectstatistic->cost             = '投入';
$lang->block->projectstatistic->task             = '任務';
$lang->block->projectstatistic->bug              = 'Bug';
$lang->block->projectstatistic->storyPoints      = '總規模';
$lang->block->projectstatistic->done             = '已完成';
$lang->block->projectstatistic->undone           = '未關閉';
$lang->block->projectstatistic->costs            = '已投入';
$lang->block->projectstatistic->consumed         = '消耗工時';
$lang->block->projectstatistic->remainder        = '預計剩餘';
$lang->block->projectstatistic->tasks            = '總數量';
$lang->block->projectstatistic->wait             = '未開始';
$lang->block->projectstatistic->doing            = '進行中';
$lang->block->projectstatistic->bugs             = '總數量';
$lang->block->projectstatistic->closed           = '已關閉';
$lang->block->projectstatistic->activated        = '激活';
$lang->block->projectstatistic->unit             = '個';
$lang->block->projectstatistic->SP               = 'SP';
$lang->block->projectstatistic->personDay        = '人天';
$lang->block->projectstatistic->day              = '天';
$lang->block->projectstatistic->hour             = 'h';
$lang->block->projectstatistic->leftDaysPre      = '距項目結束還剩';
$lang->block->projectstatistic->delayDaysPre     = '項目已延期';
$lang->block->projectstatistic->existRisks       = '存在風險';
$lang->block->projectstatistic->existIssues      = '存在問題';
$lang->block->projectstatistic->lastestExecution = '最新執行';
$lang->block->projectstatistic->projectClosed    = "{$lang->projectCommon}已關閉";
$lang->block->projectstatistic->longTimeProject  = "長期{$lang->projectCommon}";
$lang->block->projectstatistic->totalProgress    = '總進度';
$lang->block->projectstatistic->totalProgressTip = "<strong>項目總進度</strong>=按項目統計的任務消耗工時數 /（按項目統計的任務消耗工時數+按項目統計的任務剩餘工時數）<br/>
<strong>按項目統計的任務消耗工時數</strong>：項目中任務的消耗工時數求和，過濾已刪除的任務，過濾父任務，過濾已刪除執行的任務。<br/>
<strong>按項目統計的任務剩餘工時數</strong>：項目中任務的剩餘工時數求和，過濾已刪除的任務，過濾父任務，過濾已刪除執行的任務。";
$lang->block->projectstatistic->currentCost      = '當前成本';
$lang->block->projectstatistic->sv               = '進度偏差率(SV)';
$lang->block->projectstatistic->pv               = '計劃完成(PV)';
$lang->block->projectstatistic->ev               = '實際完成(EV)';
$lang->block->projectstatistic->cv               = '成本偏差率(CV)';
$lang->block->projectstatistic->ac               = '實際花費(AC)';

$lang->block->qastatistic = new stdclass();
$lang->block->qastatistic->fixBugRate        = 'Bug修復率';
$lang->block->qastatistic->closedBugRate     = 'Bug關閉率';
$lang->block->qastatistic->totalBug          = 'Bug總數';
$lang->block->qastatistic->bugStatistics     = 'Bug統計';
$lang->block->qastatistic->addYesterday      = '昨日新增';
$lang->block->qastatistic->addToday          = '今日新增';
$lang->block->qastatistic->resolvedYesterday = '昨日解決';
$lang->block->qastatistic->resolvedToday     = '今日解決';
$lang->block->qastatistic->closedYesterday   = '昨日關閉';
$lang->block->qastatistic->closedToday       = '今日關閉';
$lang->block->qastatistic->unclosedTesttasks = '未關閉的測試單';
$lang->block->qastatistic->bugStatusStat     = '月度Bug變化情況';

$lang->block->bugstatistic = new stdclass();
$lang->block->bugstatistic->effective = '有效Bug';
$lang->block->bugstatistic->fixed     = '已修復';
$lang->block->bugstatistic->activated = '激活的';

$lang->block->executionstatistic = new stdclass();
$lang->block->executionstatistic->allProject        = '全部項目';
$lang->block->executionstatistic->progress          = '執行進度';
$lang->block->executionstatistic->totalEstimate     = '預計工時';
$lang->block->executionstatistic->totalConsumed     = '消耗工時';
$lang->block->executionstatistic->totalLeft         = '剩餘工時';
$lang->block->executionstatistic->burn              = $lang->execution->common . '燃盡圖';
$lang->block->executionstatistic->cfd               = $lang->execution->common . '任務累積流圖';
$lang->block->executionstatistic->story             = '需求';
$lang->block->executionstatistic->doneStory         = '已完成';
$lang->block->executionstatistic->totalStory        = '需求總數';
$lang->block->executionstatistic->task              = '任務';
$lang->block->executionstatistic->totalTask         = '任務總數';
$lang->block->executionstatistic->undoneTask        = '未完成';
$lang->block->executionstatistic->yesterdayDoneTask = '昨日完成';

$lang->block->executionoverview = new stdclass();
$lang->block->executionoverview->totalExecution = "{$lang->execution->common}總量";
$lang->block->executionoverview->thisYear       = '今年完成';
$lang->block->executionoverview->statusCount    = "未關閉{$lang->execution->common}狀態分佈";

$lang->block->productoverview = new stdclass();
$lang->block->productoverview->overview                = '總覽數據';
$lang->block->productoverview->yearFinished            = '產品年度推進統計';
$lang->block->productoverview->productLineCount        = '產品綫總量';
$lang->block->productoverview->productCount            = '產品總量';
$lang->block->productoverview->releaseCount            = '今年發佈';
$lang->block->productoverview->milestoneCount          = '發佈里程碑';
$lang->block->productoverview->unfinishedPlanCount     = '未完成計劃數';
$lang->block->productoverview->unclosedStoryCount      = '未關閉需求數';
$lang->block->productoverview->activeBugCount          = '激活 Bug 數';
$lang->block->productoverview->finishedReleaseCount    = '已完成發佈數';
$lang->block->productoverview->finishedStoryCount      = '已完成需求數';
$lang->block->productoverview->finishedStoryPoint      = '已完成需求規模';
$lang->block->productoverview->thisWeek                = '本週';

$lang->block->productlist = new stdclass();
$lang->block->productlist->unclosedFeedback  = '未關閉反饋';
$lang->block->productlist->activatedStory    = '激活需求';
$lang->block->productlist->storyCompleteRate = '需求完成率';
$lang->block->productlist->activatedBug      = '激活Bug';

$lang->block->sprint = new stdclass();
$lang->block->sprint->totalExecution = "{$lang->executionCommon}總量";
$lang->block->sprint->thisYear       = '今年完成';
$lang->block->sprint->statusCount    = "{$lang->executionCommon}狀態分佈";

$lang->block->zentaodynamic = new stdclass();
$lang->block->zentaodynamic->zentaosalon  = '禪道·中國行';
$lang->block->zentaodynamic->publicclass  = '禪道公開課';
$lang->block->zentaodynamic->release      = '最新發佈';
$lang->block->zentaodynamic->registration = '立即報名';
$lang->block->zentaodynamic->reservation  = '立即預約';

$lang->block->monthlyprogress = new stdclass();
$lang->block->monthlyprogress->doneStoryEstimateTrendChart = '完成需求規模趨勢圖';
$lang->block->monthlyprogress->storyTrendChart             = '需求新增和完成趨勢圖';
$lang->block->monthlyprogress->bugTrendChart               = 'Bug新增和解決趨勢圖';

$lang->block->annualworkload = new stdclass();
$lang->block->annualworkload->doneStoryEstimate = '完成需求規模';
$lang->block->annualworkload->doneStoryCount    = '完成需求數';
$lang->block->annualworkload->resolvedBugCount  = '修復Bug數';

$lang->block->releasestatistic = new stdclass();
$lang->block->releasestatistic->monthly = '月度發佈次數趨勢圖';
$lang->block->releasestatistic->annual  = "年度發佈榜（%s年）";

$lang->block->teamachievement = new stdclass();
$lang->block->teamachievement->finishedTasks  = '完成任務數量';
$lang->block->teamachievement->createdStories = '創建需求數量';
$lang->block->teamachievement->closedBugs     = '關閉的Bug數';
$lang->block->teamachievement->runCases       = '執行的用例數';
$lang->block->teamachievement->consumedHours  = '消耗工時';
$lang->block->teamachievement->totalWorkload  = '累計工作量';
$lang->block->teamachievement->vs             = '較昨日';
$lang->block->teamachievement->accrued        = '累計';

$lang->block->estimate = new stdclass();
$lang->block->estimate->costs    = '人工';
$lang->block->estimate->workhour = '工時';
$lang->block->estimate->people   = '人';
$lang->block->estimate->expect   = '預計';
$lang->block->estimate->consumed = '已消耗';
$lang->block->estimate->surplus  = '剩餘';
$lang->block->estimate->hour     = 'H';

$lang->block->moduleList['product']         = $lang->productCommon;
$lang->block->moduleList['project']         = $lang->projectCommon;
$lang->block->moduleList['execution']       = $lang->execution->common;
$lang->block->moduleList['qa']              = $lang->qa->common;
$lang->block->moduleList['welcome']         = $lang->block->welcome->common;
$lang->block->moduleList['guide']           = $lang->block->guide;
$lang->block->moduleList['zentaodynamic']   = $lang->block->zentaoDynamic;
$lang->block->moduleList['teamachievement'] = $lang->block->teamAchievement;
$lang->block->moduleList['assigntome']      = $lang->block->assignToMe;
$lang->block->moduleList['dynamic']         = $lang->block->dynamic;
$lang->block->moduleList['html']            = $lang->block->html;

$lang->block->tooltips = array();
$lang->block->tooltips['deliveryRate']      = "按{$lang->productCommon}統計的{$lang->SRCommon}完成率=按{$lang->productCommon}統計的已交付{$lang->SRCommon}數 / 按{$lang->productCommon}統計的有效{$lang->SRCommon}數 * 100%";
$lang->block->tooltips['resolvedRate']      = "按{$lang->productCommon}統計的Bug修復率 = 按{$lang->productCommon}統計的修復Bug數 / 按{$lang->productCommon}統計的有效Bug數";
$lang->block->tooltips['effectiveStory']    = "按{$lang->productCommon}統計的{$lang->SRCommon}總數：{$lang->productCommon}中{$lang->SRCommon}的個數求和，過濾已刪除的{$lang->SRCommon}，過濾已刪除的{$lang->productCommon}。";
$lang->block->tooltips['deliveredStory']    = "按{$lang->productCommon}統計的已交付{$lang->SRCommon}數：{$lang->productCommon}中{$lang->SRCommon}個數求和，所處階段為已發佈或關閉原因為已完成，過濾已刪除的{$lang->SRCommon}，過濾已刪除的{$lang->productCommon}。";
$lang->block->tooltips['costs']             = "已投入 = 已消耗工時 / 後台配置的每日可用工時";
$lang->block->tooltips['sv']                = "進度偏差率 = (EV - PV) / PV * 100% ";
$lang->block->tooltips['ev']                = "任務狀態為已完成，累加預計工時。<br/>任務狀態為已關閉且關閉原因為已完成，累加預計工時。<br/>任務狀態為進行中、已暫停，累加（任務預計工時*任務進度）。<br/>";
$lang->block->tooltips['pv']                = "任務截至日期小於等於本週結束日期，累加預計工時。<br/>任務預計開始日期小於或等於本週結束日期，預計截至日期大於本週結束日期，累加預計工時=(任務的預計工時÷任務工期天數)x 任務預計開始到本週結束日期的天數。<br/>";
$lang->block->tooltips['cv']                = "成本偏差率 = (EV - AC) / AC * 100%";
$lang->block->tooltips['ac']                = "瀑布{$lang->projectCommon}中本週結束之前所有日誌記錄的工時之和，過濾已刪除的{$lang->projectCommon}。";
$lang->block->tooltips['executionProgress'] = "<strong>{$lang->execution->common}進度</strong>=按{$lang->execution->common}統計的任務消耗工時數 /（按{$lang->execution->common}統計的任務消耗工時數+按{$lang->execution->common}統計的任務剩餘工時數）<br/>
<strong>按{$lang->execution->common}統計的任務消耗工時數</strong>：{$lang->execution->common}中任務的消耗工時數求和，過濾已刪除的任務，過濾父任務，過濾已刪除的{$lang->execution->common}，過濾已刪除的{$lang->projectCommon}。<br/>
<strong>按{$lang->execution->common}統計的任務剩餘工時數</strong>：{$lang->execution->common}中任務的剩餘工時數求和，過濾已刪除的任務，過濾父任務，過濾已刪除的{$lang->execution->common}，過濾已刪除的{$lang->projectCommon}。";
$lang->block->tooltips['metricTime']        = '統計數據將整點更新，最新更新時間為 %s。';
