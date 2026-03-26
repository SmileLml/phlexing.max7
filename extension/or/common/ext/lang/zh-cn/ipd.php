<?php
$lang->storyCommon = $lang->SRCommon;

$lang->demandpool = new stdclass();
$lang->demandpool->common = '需求池';

$lang->demand = new stdclass();
$lang->demand->common = '需求池需求';

$lang->charter = new stdclass();
$lang->charter->common = '立项';

$lang->market = new stdclass();
$lang->market->common = '市场';

$lang->marketreport = new stdclass();
$lang->marketreport->common = '报告';

$lang->marketresearch = new stdclass();
$lang->marketresearch->common = '调研';

$lang->researchtask = new stdclass();
$lang->researchtask->common = '调研任务';

$lang->navIcons['demandpool'] = "<i class='icon icon-bars'></i>";
$lang->navIcons['market']     = "<i class='icon icon-market'></i>";
$lang->navIcons['charter']    = "<i class='icon icon-seal'></i>";

$lang->navIconNames['demandpool'] = 'bars';
$lang->navIconNames['market']     = 'market';
$lang->navIconNames['charter']    = 'seal';

/* Main Navigation. */
$lang->mainNav             = new stdclass();
$lang->mainNav->my         = "{$lang->navIcons['my']} {$lang->my->shortCommon}|my|index|";
$lang->mainNav->demandpool = "{$lang->navIcons['demandpool']} {$lang->demandpool->common}|demandpool|browse|";
$lang->mainNav->market     = "{$lang->navIcons['market']} {$lang->market->common}|marketreport|all|";
$lang->mainNav->product    = "{$lang->navIcons['product']} {$lang->productCommon}|product|all|";
$lang->mainNav->charter    = "{$lang->navIcons['charter']} {$lang->charter->common}|charter|browse|";
$lang->mainNav->feedback   = "{$lang->navIcons['feedback']} {$lang->feedback->common}|feedback|admin|";
$lang->mainNav->workflow   = "{$lang->navIcons['workflow']} {$lang->workflow->common}|workflow|browseFlow|";
$lang->mainNav->doc        = "{$lang->navIcons['doc']} {$lang->doc->common}|doc|mySpace|";
$lang->mainNav->admin      = "{$lang->navIcons['admin']} {$lang->admin->common}|admin|index|";

$lang->dividerMenu = ',feedback,doc,admin,';

$lang->mainNav->menuOrder[5]  = 'my';
$lang->mainNav->menuOrder[10] = 'demandpool';
$lang->mainNav->menuOrder[15] = 'market';
$lang->mainNav->menuOrder[20] = 'product';
$lang->mainNav->menuOrder[25] = 'charter';
$lang->mainNav->menuOrder[30] = 'feedback';
$lang->mainNav->menuOrder[35] = 'workflow';
$lang->mainNav->menuOrder[40] = 'doc';
$lang->mainNav->menuOrder[45] = 'admin';

$lang->navGroup->demandpool     = 'demandpool';
$lang->navGroup->demand         = 'demandpool';
$lang->navGroup->roadmap        = 'product';
$lang->navGroup->charter        = 'charter';
$lang->navGroup->market         = 'market';
$lang->navGroup->marketreport   = 'market';
$lang->navGroup->marketresearch = 'market';
$lang->navGroup->researchtask   = 'market';

$lang->demandpool->menu = new stdclass();
$lang->demandpool->menu->demand = array('link' => "需求|demand|browse|poolID=%s", 'alias' => 'managetree,tostory');
$lang->demandpool->menu->track  = array('link' => "矩阵|demandpool|track|poolID=%s");
$lang->demandpool->menu->view   = array('link' => "概况|demandpool|view|poolID=%s", 'alias' => 'edit');

$lang->demandpool->menuOrder[5]  = 'demand';
$lang->demandpool->menuOrder[10] = 'track';
$lang->demandpool->menuOrder[15] = 'view';

$lang->product->menu              = new stdclass();
if($config->enableER) $lang->product->menu->epic = array('link' => "{$lang->ERCommon}|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=epic", 'subModule' => 'story');
$lang->product->menu->requirement = array('link' => "{$lang->URCommon}|product|browse|productID=%s&branch=&browseType=unclosed&param=0&storyType=requirement", 'alias' => 'batchedit', 'subModule' => 'story');
$lang->product->menu->roadmap     = array('link' => "路标|roadmap|browse|productID=%s");
$lang->product->menu->settings    = array('link' => "{$lang->settings}|product|view|productID=%s", 'subModule' => 'tree,branch', 'alias' => 'edit,whitelist,addwhitelist');

$lang->product->menu->settings['subMenu'] = new stdclass();
$lang->product->menu->settings['subMenu']->view      = array('link' => "{$lang->overview}|product|view|productID=%s", 'alias' => 'edit');
$lang->product->menu->settings['subMenu']->module    = array('link' => "{$lang->module}|tree|browse|product=%s&view=story", 'subModule' => 'tree');
$lang->product->menu->settings['subMenu']->branch    = array('link' => "@branch@|branch|manage|product=%s", 'subModule' => 'branch');
$lang->product->menu->settings['subMenu']->whitelist = array('link' => "{$lang->whitelist}|product|whitelist|product=%s", 'subModule' => 'personnel');

$lang->product->menuOrder = array();
if($config->enableER) $lang->product->menuOrder[5] = 'epic';
$lang->product->menuOrder[10] = 'requirement';
$lang->product->menuOrder[15] = 'roadmap';
$lang->product->menuOrder[20] = 'settings';

unset($lang->product->homeMenu->home);
unset($lang->product->homeMenu->kanban);

$lang->charter->menu            = new stdclass();
$lang->charter->menu->all       = array('link' => "全部|charter|browse|browseType=all");
$lang->charter->menu->wait      = array('link' => "待立项|charter|browse|browseType=wait");
$lang->charter->menu->reviewing = array('link' => "审批中|charter|browse|browseType=reviewing");
$lang->charter->menu->launched  = array('link' => "已立项|charter|browse|browseType=launched");
$lang->charter->menu->completed = array('link' => "已结项|charter|browse|browseType=completed");
$lang->charter->menu->canceled  = array('link' => "已取消|charter|browse|browseType=canceled");
$lang->charter->menu->closed    = array('link' => "已关闭|charter|browse|browseType=closed");
//$lang->charter->menu->settings = array('link' => "{$lang->settings}|charter|view|charterID=%s", 'subModule' => 'tree,branch', 'alias' => 'edit,whitelist,addwhitelist');

unset($lang->my->menu->project);
unset($lang->my->menu->execution);
unset($lang->my->menu->meeting);
unset($lang->doc->menu->product);
unset($lang->doc->menu->project);
unset($lang->doc->menu->api);
unset($lang->feedback->menu->ticket);
unset($lang->feedback->menu->faq);

$lang->my->menu->work       = array('link' => "{$lang->my->work}|my|work|mode=demand", 'subModule' => 'task');
$lang->my->menu->contribute = array('link' => "$lang->contribute|my|contribute|mode=demand");

$lang->my->menu->work['subMenu'] = new stdclass();
$lang->my->menu->work['subMenu']->demand      = "需求池需求|my|work|mode=demand";
$lang->my->menu->work['subMenu']->requirement = array('link' => "$lang->URCommon|my|work|mode=requirement", 'alias' => 'requirement');
$lang->my->menu->work['subMenu']->task        = "调研任务|my|work|mode=task";
$lang->my->menu->work['subMenu']->feedback    = "{$lang->feedback->common}|my|work|mode=feedback";

$lang->my->menu->contribute['subMenu'] = new stdclass();
$lang->my->menu->contribute['subMenu']->demand      = array('link' => "需求池需求|my|contribute|mode=demand", 'alias' => 'demand');
$lang->my->menu->contribute['subMenu']->requirement = array('link' => "$lang->URCommon|my|contribute|mode=requirement", 'alias' => 'requirement');
$lang->my->menu->contribute['subMenu']->task        = array('link' => "调研任务|my|contribute|mode=task", 'alias' => 'task');
$lang->my->menu->contribute['subMenu']->feedback    = array('link' => "{$lang->feedback->common}|my|contribute|mode=feedback&type=openedbyme", 'alias' => 'feedback');

unset($lang->createIcons['bug']);
unset($lang->createIcons['story']);
unset($lang->createIcons['task']);
unset($lang->createIcons['testcase']);
unset($lang->createIcons['program']);
unset($lang->createIcons['project']);
unset($lang->createIcons['execution']);
unset($lang->createIcons['kanbanspace']);
unset($lang->createIcons['kanban']);

unset($lang->workflow->menu->flowgroup);
unset($lang->workflow->menu->ruler);

$lang->searchObjects = array();
$lang->searchObjects['all']            = '全部';
$lang->searchObjects['story']          = '需求';
$lang->searchObjects['demandpool']     = '需求池';
$lang->searchObjects['demand']         = '需求池需求';
$lang->searchObjects['roadmap']        = '路标';
$lang->searchObjects['charter']        = '立项';
$lang->searchObjects['product']        = $lang->productCommon;
$lang->searchObjects['doc']            = '文档';
$lang->searchObjects['market']         = '市场';
$lang->searchObjects['marketreport']   = '报告';
$lang->searchObjects['marketresearch'] = '调研';
$lang->searchObjects['feedback']       = '反馈';
$lang->searchTips                      = '编号(ctrl+g)';

$lang->market->homeMenu           = new stdclass();
$lang->market->homeMenu->report   = array('link' => "报告|marketreport|all", 'subModule' => 'marketreport');
$lang->market->homeMenu->research = array('link' => "调研|marketresearch|all", 'subModule' => 'marketresearch', 'exclude' => 'marketresearch-reports');
$lang->market->homeMenu->market   = array('link' => "市场|market|browse|browseType=all");

$lang->market->menu           = new stdclass();
$lang->market->menu->report   = array('link' => "报告|marketreport|browse|marketID=%s", 'subModule' => 'marketreport');
$lang->market->menu->research = array('link' => "调研|marketresearch|browse|marketID=%s", 'subModule' => 'marketresearch,researchtask', 'exclude' => 'marketresearch-reports');
$lang->market->menu->view     = array('link' => "概况|market|view|marketID=%s", 'alias' => 'edit');
