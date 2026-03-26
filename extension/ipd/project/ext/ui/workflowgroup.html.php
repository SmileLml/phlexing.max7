<?php
namespace zin;

if($group->objectID == 0)
{
    formPanel
    (
        set::title($lang->project->workflowGroup),
        set::headingClass('justify-start'),
        set::bodyClass('px-0'),
        set::actions(array('submit')),
        set::submitBtnText($lang->confirm),
        formGroup
        (
            setStyle(array('align-items' => 'center', 'margin-left' => '-1rem')),
            set::label(''),
            set::labelWidth('80px'),
            sprintf($lang->project->toggleGroupTips[0], $group->name)
        ),
        formGroup
        (
            setStyle(array('align-items' => 'center')),
            set::label('1.'),
            set::labelWidth('80px'),
            $lang->project->toggleGroupTips[1]
        ),
        formGroup
        (
            setStyle(array('align-items' => 'center')),
            set::label('2.'),
            set::labelWidth('80px'),
            $lang->project->toggleGroupTips[2]
        ),
        formGroup
        (
            setStyle(array('align-items' => 'center')),
            set::label('3.'),
            set::labelWidth('80px'),
            $lang->project->toggleGroupTips[3]
        )
    );
}
else
{
    featurebar
    (
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

    if(isset($cols['actions']['actionsMap']['setExclusive']))
    {
        $cols['actions']['actionsMap']['setExclusive']['data-confirm'] = $lang->project->setExclusiveConfirm;
        $cols['actions']['actionsMap']['setExclusive']['className']    = 'ajax-submit';
    }

    dtable
    (
        set::cols($cols),
        set::data($data),
        set::sortType(false)
    );
}
