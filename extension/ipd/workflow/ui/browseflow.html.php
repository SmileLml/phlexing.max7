<?php
namespace zin;

jsVar('activateTips', $lang->workflow->tips->activate);
jsVar('activateList', $lang->workflow->activateList);

$items[] = array
(
    'text'      => $lang->workflow->all,
    'active'    => !$currentApp,
    'url'       => inlink('browseFlow', "mode=$mode&status=&app=&param={$param}&orderBy=$orderBy"),
    'props'     => array('data-id' => 'all', 'title' => $lang->workflow->all),
    'textClass' => 'text-ellipsis max-w-32'
);

$flowApps = $this->workflow->getFlowApps();
foreach($flowApps as $appCode)
{
    $appName = zget($apps, $appCode, '');
    if($appCode == 'project') $appName = $lang->project->common;
    if(!$appName) continue;

    $isActive = $appCode == $currentApp;

    $items[] = array
    (
        'text'      => $appName,
        'active'    => $isActive,
        'url'       => inlink('browseFlow', "mode=browse&status=&app=$appCode&param={$param}&orderBy=$orderBy"),
        'props'     => array('data-id' => $appCode, 'title' => $appName),
        'textClass' => 'text-ellipsis max-w-32'
    );
}

featureBar
(
    set::items($items),
    li(searchToggle(set::open($mode == 'bysearch')))
);

$viewType = $this->cookie->flowViewType ? $this->cookie->flowViewType : 'card';
toolbar
(
    item(set(array
    (
        'type'  => 'btnGroup',
        'items' => array(
            array
            (
                'icon'      => 'cards-view',
                'class'     => 'btn-icon switchButton' . ($viewType == 'card' ? ' text-primary' : ''),
                'data-type' => 'card'
            ),
            array
            (
                'icon'      => 'bars',
                'class'     => 'switchButton btn-icon' . ($viewType == 'list' ? ' text-primary' : ''),
                'data-type' => 'list'
            )
        )
    ))),
    hasPriv('workflow', 'create') ? btn
    (
        set(array(
            'className'   => 'primary',
            'icon'        => 'plus',
            'text'        => $lang->workflow->create,
            'url'         => createLink('workflow', 'create', "type=flow&parent=&app=$currentApp"),
            'data-toggle' => 'modal',
            'data-size'   => 'sm'
        ))
    ) : null
);

if($viewType == 'card') include 'card.html.php';
if($viewType == 'list') include 'list.html.php';
