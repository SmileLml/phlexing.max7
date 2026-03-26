<?php
$lang->ipdName = 'IPD';

$lang->ipd       = new stdclass();
$lang->ipd->menu = new stdclass();
$lang->ipd->menu = unserialize(serialize($lang->waterfall->menu));
unset($lang->ipd->menu->review['subMenu']->issue);

$lang->ipd->menuOrder   = $lang->waterfall->menuOrder;
$lang->ipd->dividerMenu = $lang->waterfall->dividerMenu;
$lang->ipd->menu->settings['subModule'] = 'stakeholder,tree';

$lang->visionList = array();
$lang->visionList['or']   = 'OR & MM Interface';
$lang->visionList['rnd']  = 'IPD Interface';
$lang->visionList['lite'] = 'Operation Management Interface';

$lang->product->menu->roadmap   = array('link' => "Release Roadmap|product|roadmap|productID=%s", 'exclude' => 'roadmap-browse,roadmap-view');
$lang->product->menu->orroadmap = array('link' => "Product RoadMap|roadmap|browse|productID=%s", 'subModule' => 'roadmap');

$lang->product->menuOrder[5]  = 'dashboard';
$lang->product->menuOrder[8]  = 'epic';
$lang->product->menuOrder[10] = 'requirement';
$lang->product->menuOrder[15] = 'story';
$lang->product->menuOrder[20] = 'orroadmap';
$lang->product->menuOrder[25] = 'plan';
$lang->product->menuOrder[30] = 'track';
$lang->product->menuOrder[35] = 'project';
$lang->product->menuOrder[40] = 'release';
$lang->product->menuOrder[45] = 'roadmap';
$lang->product->menuOrder[50] = 'doc';
$lang->product->menuOrder[55] = 'dynamic';
$lang->product->menuOrder[60] = 'settings';
$lang->product->menuOrder[65] = 'create';

$lang->product->dividerMenu = ',epic,track,doc,settings,';

$lang->navGroup->roadmap = 'product';

unset($lang->ipd->menu->cm);
unset($lang->ipd->menu->other['dropMenu']->pssp);
unset($lang->ipd->menu->other['dropMenu']->auditplan);
unset($lang->ipd->menu->other['dropMenu']->research);

$lang->demand = new stdclass();
$lang->demand->common = 'Demand';
