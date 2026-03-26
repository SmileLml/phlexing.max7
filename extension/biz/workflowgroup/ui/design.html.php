<?php
/**
 * The design view file of workflowgroup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     workflowgroup
 * @version     $Id$
 * @link        http://www.zentao.net
 */

namespace zin;

featurebar
(
    backbtn(set::back("workflowgroup-{$group->type}"), $lang->goback),
    li(setClass('nav-item font-bold ml-2'), $group->name)
);

$cols = $config->workflowgroup->dtable->design->fieldList;
$cols['buildin']['map'] = $lang->workflow->buildinList;
$cols['app']['map']     = $apps;
if(common::checkNotCN()) $cols['actions']['width'] = '160px';
if($group->main == '1')  $cols['actions']['width'] = '60px';
foreach($cols['actions']['list'] as $actionKey => $action) $cols['actions']['list'][$actionKey]['url']['params'] = sprintf($action['url']['params'], $group->id);

$data    = initTableData($flows, $cols, $this->workflowgroup);
$hasPriv = array();
$checkPriv = function($actionKey, $flow) use ($group, $cols, &$hasPriv)
{
    $action = $cols['actions']['list'][$actionKey];
    $module = $action['url']['module'];
    $method = $action['url']['method'];

    if(!isset($hasPriv[$module][$method])) $hasPriv[$module][$method] = hasPriv($module, $method);
    $enabled = $hasPriv[$module][$method];
    if(!$enabled) return false;
    if(($actionKey == 'setExclusive' || $actionKey == 'designBuildin' || $actionKey == 'designCustom') && $group->main == '1') return false;

    if($actionKey == 'setExclusive')   $enabled = empty($flow->group);
    if($actionKey == 'designBuildin')  $enabled = $group->id == $flow->group && $flow->buildin;
    if($actionKey == 'designCustom')   $enabled = $group->id == $flow->group && empty($flow->buildin);
    if($actionKey == 'activateFlow')   $enabled = empty($flow->buildin) && strpos(",{$group->disabledModules},", ",{$flow->module},") !== false && $flow->defaultStatus != 'pause';
    if($actionKey == 'deactivateFlow') $enabled = empty($flow->buildin) && strpos(",{$group->disabledModules},", ",{$flow->module},") === false;
    return array('name' => $actionKey, 'disabled' => !$enabled);
};

foreach($data as $flow)
{
    $flow->exclusive = zget($lang->workflowgroup->workflow->exclusiveList, empty($flow->group) ? 0 : 1, '');
    $flow->status    = (empty($flow->buildin) && strpos(",{$group->disabledModules},", ",{$flow->module},") !== false) ? 'pause' : 'normal';
    $flow->actions   = array();
    foreach($cols['actions']['menu'] as $actionKey)
    {
        if(strpos($actionKey, '|') !== false)
        {
            $hasEnabled = false;
            foreach(explode('|', $actionKey) as $actionKey)
            {
                if($hasEnabled) break;
                $action = $checkPriv($actionKey, $flow);
                if(is_array($action) && !$action['disabled'])
                {
                    $flow->actions[] = $action;
                    $hasEnabled = true;
                }
            }
            if(!$hasEnabled) $flow->actions[] = $action;
        }
        else
        {
            $flow->actions[] = $checkPriv($actionKey, $flow);
        }
    }
    $flow->actions = array_values(array_filter($flow->actions));
}

dtable
(
    set::cols($cols),
    set::data($data),
    set::orderBy($orderBy),
    set::sortLink(createLink('workflowgroup', 'design', "groupID={$group->id}&orderBy={name}_{sortType}")),
    set::footPager(usePager())
);
