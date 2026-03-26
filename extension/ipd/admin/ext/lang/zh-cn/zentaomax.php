<?php
$lang->admin->menuList->feature['subMenu']['measure']     = array('link' => "度量|measurement|settips|", 'subModule' => 'sqlbuilder,measurement,report', 'links' => array('measurement|settips|', 'measurement|browse|', 'sqlbuilder|browsesqlview|', 'measurement|template|'));
$lang->admin->menuList->feature['subMenu']['meetingroom'] = array('link' => "会议室|meetingroom|browse|", 'subModule' => 'meetingroom');

$lang->admin->menuList->feature['menuOrder']['45'] = 'measure';
$lang->admin->menuList->feature['menuOrder']['55'] = 'meetingroom';

$lang->admin->menuList->model['tabMenu']['common']['build']['divider'] = true;
if(helper::hasFeature('issue'))       $lang->admin->menuList->model['tabMenu']['common']['issue']       = array('link' => "{$lang->issue->common}|custom|set|module=issue&fieldList=typeList", 'exclude' => 'custom');
if(helper::hasFeature('risk'))        $lang->admin->menuList->model['tabMenu']['common']['risk']        = array('link' => "{$lang->risk->common}|custom|set|module=risk&fieldList=sourceList", 'exclude' => 'custom');
if(helper::hasFeature('opportunity')) $lang->admin->menuList->model['tabMenu']['common']['opportunity'] = array('link' => "{$lang->opportunity->common}|custom|set|module=opportunity&fieldList=sourceList", 'exclude' => 'custom');
if(helper::hasFeature('auditplan'))   $lang->admin->menuList->model['tabMenu']['common']['nc']          = array('link' => "QA|custom|set|module=nc&fieldList=typeList", 'exclude' => 'custom');
$lang->admin->menuList->model['tabMenu']['common']['estimate'] = array('link' => '估算|custom|estimate|');
$lang->admin->menuList->model['tabMenu']['common']['subject']  = array('link' => '支出科目|subject|browse|', 'subModule' => 'subject');
$lang->admin->menuList->model['tabMenu']['common']['baseline'] = array('link' => '评审对象|baseline|templatetype|', 'exclude' => 'custom');

if(helper::hasFeature('issue'))       $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['15'] = 'issue';
if(helper::hasFeature('risk'))        $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['20'] = 'risk';
if(helper::hasFeature('opportunity')) $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['25'] = 'opportunity';
if(helper::hasFeature('auditplan'))   $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['30'] = 'nc';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['50'] = 'estimate';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['55'] = 'subject';
$lang->admin->menuList->model['tabMenu']['menuOrder']['common']['60'] = 'baseline';

if($config->vision != 'lite' and helper::hasFeature('waterfall') and isset($lang->admin->menuList->model['subMenu']['waterfall'])) $lang->admin->menuList->model['subMenu']['waterfall']['subModule'] .= ',design,auditcl,process,reviewcl,cmcl,activity,zoutput,classify,baseline,reviewsetting,';
$lang->admin->menuList->model['tabMenu']['waterfall']['design']   = array('link' => "{$lang->design->common}|design|settype|", 'subModule' => 'design');
$lang->admin->menuList->model['tabMenu']['waterfall']['auditcl']  = array('link' => "{$lang->auditcl->common}|auditcl|browse|processID=0&browseType=waterfall", 'subModule' => 'auditcl');
$lang->admin->menuList->model['tabMenu']['waterfall']['process']  = array('link' => "{$lang->process->common}|classify|browse|browseType=waterfall", 'subModule' => 'process,activity,zoutput,classify,', 'links' => array('activity|browse|model=waterfall', 'zoutput|browse|model=scrum', 'classify|browse|', 'process|browse|'));
$lang->admin->menuList->model['tabMenu']['waterfall']['reviewcl'] = array('link' => "{$lang->reviewcl->common}|reviewcl|browse|type=waterfall&category=PP|", 'subModule' => ',reviewcl,reviewsetting,', 'links' => array('reviewsetting|version|'));
$lang->admin->menuList->model['tabMenu']['waterfall']['clcategory'] = array('link' => "{$lang->reviewcl->clcategory}|reviewcl|setcategory|type=waterfall");
$lang->admin->menuList->model['tabMenu']['waterfall']['cmcl']     = array('link' => "{$lang->cmcl->common}|cmcl|browse|", 'subModule' => ',cmcl,baseline,');
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['10'] = 'design';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['15'] = 'auditcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['20'] = 'process';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['25'] = 'reviewcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['30'] = 'clcategory';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['35'] = 'cmcl';

if(isset($lang->admin->menuList->model['subMenu']['scrum'])) $lang->admin->menuList->model['subMenu']['scrum']['subModule'] .= ',auditcl,process,activity,zoutput,classify,';
$lang->admin->menuList->model['tabMenu']['scrum']['auditcl'] = array('link' => "{$lang->auditcl->common}|auditcl|scrumbrowse|processID=0&browseType=scrum", 'subModule' => 'auditcl');
$lang->admin->menuList->model['tabMenu']['scrum']['process'] = array('link' => "{$lang->process->common}|classify|browse|browseType=scrum", 'subModule' => 'process,activity,zoutput,classify,', 'links' => array('activity|browse|model=scrum', 'zoutput|browse|model=waterfall', 'classify|browse|', 'process|browse|'));
$lang->admin->menuList->model['tabMenu']['menuOrder']['scrum']['5']  = 'auditcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['scrum']['10'] = 'process';

if(isset($lang->admin->menuList->model['subMenu']['agileplus'])) $lang->admin->menuList->model['subMenu']['agileplus']['subModule'] .= ',auditcl,process,activity,zoutput,classify,';
$lang->admin->menuList->model['tabMenu']['agileplus']['auditcl'] = array('link' => "{$lang->auditcl->common}|auditcl|agileplusbrowse|processID=0&browseType=agileplus", 'subModule' => 'auditcl');
$lang->admin->menuList->model['tabMenu']['agileplus']['process'] = array('link' => "{$lang->process->common}|classify|browse|browseType=agileplus", 'subModule' => 'process,activity,zoutput,classify,', 'links' => array('activity|browse|model=agileplus', 'zoutput|browse|model=agileplus', 'classify|browse|', 'process|agileplusbrowse|'));
$lang->admin->menuList->model['tabMenu']['menuOrder']['agileplus']['5']  = 'auditcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['agileplus']['10'] = 'process';

if($config->vision != 'lite' and helper::hasFeature('waterfallplus') and isset($lang->admin->menuList->model['subMenu']['waterfallplus'])) $lang->admin->menuList->model['subMenu']['waterfallplus']['subModule'] .= ',design,auditcl,process,reviewcl,cmcl,activity,zoutput,classify,baseline,reviewsetting,';
$lang->admin->menuList->model['tabMenu']['waterfallplus']['design']     = array('link' => "{$lang->design->common}|design|setplustype|", 'subModule' => 'design');
$lang->admin->menuList->model['tabMenu']['waterfallplus']['auditcl']    = array('link' => "{$lang->auditcl->common}|auditcl|waterfallplusbrowse|processID=0", 'subModule' => 'auditcl');
$lang->admin->menuList->model['tabMenu']['waterfallplus']['process']    = array('link' => "{$lang->process->common}|classify|browse|browseType=waterfallplus", 'subModule' => 'process,activity,zoutput,classify,', 'links' => array('activity|browse|model=waterfallplus', 'zoutput|browse|model=waterfallplus', 'classify|browse|', 'process|waterfallplusbrowse|'));
$lang->admin->menuList->model['tabMenu']['waterfallplus']['reviewcl']   = array('link' => "{$lang->reviewcl->common}|reviewcl|waterfallplusbrowse|type=waterfallplus&category=PP|", 'subModule' => ',reviewcl,reviewsetting,', 'links' => array('reviewsetting|waterfallplusversion|type=waterfallplus'));
$lang->admin->menuList->model['tabMenu']['waterfallplus']['clcategory'] = array('link' => "{$lang->reviewcl->clcategory}|reviewcl|setcategory|type=waterfallplus");
$lang->admin->menuList->model['tabMenu']['waterfallplus']['cmcl']       = array('link' => "{$lang->cmcl->common}|cmcl|waterfallplusbrowse|", 'subModule' => ',cmcl,baseline,');
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['10'] = 'design';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['15'] = 'auditcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['20'] = 'process';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['25'] = 'reviewcl';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['30'] = 'clcategory';
$lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['35'] = 'cmcl';

if(!helper::hasFeature('issue') or (!helper::hasFeature('scrum_issue') and !helper::hasFeature('waterfall_issue') and !helper::hasFeature('agileplus_issue') and !helper::hasFeature('waterfallplus_issue'))) unset($lang->admin->menuList->model['tabMenu']['common']['issue'], $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['15']);
if(!helper::hasFeature('risk') or (!helper::hasFeature('scrum_risk') and !helper::hasFeature('waterfall_risk') and !helper::hasFeature('agileplus_risk') and !helper::hasFeature('waterfallplus_risk')))    unset($lang->admin->menuList->model['tabMenu']['common']['risk'], $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['20']);
if(!helper::hasFeature('scrum_opportunity') and !helper::hasFeature('waterfall_opportunity') and !helper::hasFeature('agileplus_opportunity') and !helper::hasFeature('waterfallplus_opportunity')) unset($lang->admin->menuList->model['tabMenu']['common']['opportunity'], $lang->admin->menuList->model['tabMenu']['menuOrder']['common']['25']);
if(!helper::hasFeature('meeting'))    unset($lang->admin->menuList->feature['subMenu']['meetingroom'], $lang->admin->menuList->feature['menuOrder'][55]);
if(!helper::hasFeature('measrecord')) unset($lang->admin->menuList->feature['subMenu']['measure'], $lang->admin->menuList->feature['menuOrder'][45]);

$projectModel = array('scrum', 'waterfall', 'agileplus', 'waterfallplus');
if(!helper::hasFeature('process'))
{
    foreach($projectModel as $model)
    {
        $order = in_array($model, array('scrum', 'agileplus')) ? '10' : '20';
        unset($lang->admin->menuList->model['tabMenu'][$model]['process']);
        unset($lang->admin->menuList->model['tabMenu']['menuOrder'][$model][$order]);
    }
}
if(!helper::hasFeature('auditplan'))
{
    foreach($projectModel as $model)
    {
        $order = in_array($model, array('scrum', 'agileplus')) ? '5' : '15';
        unset($lang->admin->menuList->model['tabMenu'][$model]['auditcl']);
        unset($lang->admin->menuList->model['tabMenu']['menuOrder'][$model][$order]);
    }
}
if(!helper::hasFeature('scrum_process'))           unset($lang->admin->menuList->model['tabMenu']['scrum']['process'], $lang->admin->menuList->model['tabMenu']['menuOrder']['scrum']['10']);
if(!helper::hasFeature('scrum_auditplan'))         unset($lang->admin->menuList->model['tabMenu']['scrum']['auditcl'], $lang->admin->menuList->model['tabMenu']['menuOrder']['scrum']['5']);
if(!helper::hasFeature('waterfall_process'))       unset($lang->admin->menuList->model['tabMenu']['waterfall']['process'], $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['20']);
if(!helper::hasFeature('waterfall_auditplan'))     unset($lang->admin->menuList->model['tabMenu']['waterfall']['auditcl'], $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfall']['15']);
if(!helper::hasFeature('agileplus_process'))       unset($lang->admin->menuList->model['tabMenu']['agileplus']['process'], $lang->admin->menuList->model['tabMenu']['menuOrder']['agileplus']['10']);
if(!helper::hasFeature('agileplus_auditplan'))     unset($lang->admin->menuList->model['tabMenu']['agileplus']['auditcl'], $lang->admin->menuList->model['tabMenu']['menuOrder']['agileplus']['5']);
if(!helper::hasFeature('waterfallplus_process'))   unset($lang->admin->menuList->model['tabMenu']['waterfallplus']['process'], $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['20']);
if(!helper::hasFeature('waterfallplus_auditplan')) unset($lang->admin->menuList->model['tabMenu']['waterfallplus']['auditcl'], $lang->admin->menuList->model['tabMenu']['menuOrder']['waterfallplus']['15']);

global $config;
if($config->vision == 'lite')
{
    unset($lang->admin->menuList->feature['subMenu']['measure'], $lang->admin->menuList->feature['menuOrder']['45']);
    unset($lang->admin->menuList->feature['subMenu']['meetingroom'], $lang->admin->menuList->feature['menuOrder']['55']);
    unset($lang->admin->menuList->model['tabMenu']['waterfall']['auditcl']);
    unset($lang->admin->menuList->model['tabMenu']['waterfallplus']['auditcl']);
    $lang->admin->menuList->feature['dividerMenu'] = ',user,';
}
