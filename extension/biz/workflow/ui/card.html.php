<?php
namespace zin;

$cards = array();
foreach($flows as $flow)
{
    $btnGroup       = array();
    $actionItems    = array();
    $canEdit        = $this->workflow->isClickable(null, 'edit');
    $canCopy        = $this->workflow->isClickable($flow, 'copy');
    $canDelete      = $this->workflow->isClickable($flow, 'delete');
    $canUpgrade     = $this->workflow->isClickable($flow, 'upgrade');
    $canRelease     = $this->workflow->isClickable($flow, 'release');
    $canDeactivate  = $this->workflow->isClickable($flow, 'deactivate');
    $canActivate    = $this->workflow->isClickable($flow, 'activate');
    $canDesignUI    = $this->workflow->isClickable($flow, 'ui');
    $canBrowseField = $flow->buildin && commonModel::hasPriv('workflowfield', 'browse');
    $labelClass     = 'gray';

    if($flow->status == 'normal') $labelClass = 'success';
    if($flow->status == 'wait')   $labelClass = 'warning';

    if($canEdit) $actionItems[] = array
    (
        'text'        => $lang->edit,
        'url'         => inlink('edit', "id=$flow->id"),
        'data-toggle' => 'modal',
        'data-size'   => 'sm'
    );

    if($canCopy) $actionItems[] = array
    (
        'text'        => $lang->workflow->copyFlow,
        'url'         => inlink('copy', "id=$flow->id"),
        'data-toggle' => 'modal',
        'data-size'   => 'sm'
    );

    if($canDelete) $actionItems[] = array
    (
        'text'         => $lang->delete,
        'url'          => inlink('delete', "id=$flow->id"),
        'data-confirm' => array('message' => array('html' => $lang->workflow->tips->deleteConfirm), 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x'),
        'innerClass'   => 'ajax-submit'
    );

    if($canDesignUI) $btnGroup[] = btn(
        setClass('btn primary btn-default ml-2'),
        $lang->workflow->design,
        set::url(inlink('ui', "module=$flow->module"))
    );

    if($canBrowseField) $btnGroup[] = btn(
        setClass('btn primary btn-default ml-2'),
        $lang->workflow->design,
        set::url(createLink('workflowfield', 'browse', "module=$flow->module"))
    );

    if($canRelease) $btnGroup[] = btn(
        setClass('btn secondary btn-default ml-2'),
        $lang->workflow->release,
        set::url(inlink('release', "id=$flow->id")),
        setData(['toggle' => 'modal', 'size' => 'sm'])
    );

    if($canDeactivate) $btnGroup[] = btn(
        setClass('btn secondary btn-default ml-2'),
        $lang->workflow->deactivate,
        set::url(inlink('deactivate', "id=$flow->id")),
        $flow->belong ? set('data-confirm', $lang->workflow->tips->syncDeactivate) : null
    );

    if($canActivate) $btnGroup[] = btn(
        setClass('btn secondary btn-default ml-2'),
        $lang->workflow->activate,
        set::url($flow->belong ? 'javascript:activate(' . $flow->id . ')' : $this->createLink('workflow', 'activate', "id={$flow->id}&type=all"))
    );

    $cards[] = div
    (
        setClass('col flex-none w-1/4'),
        div
        (
            setClass('border py-2 pl-4 ml-4 mt-4'),
            div
            (
                setClass('flex justify-between items-center'),
                div
                (
                    setClass('name ml-2'),
                    h::strong($flow->name),
                    $flow->buildin ? span(setClass('text-danger font-bold'), " [{$lang->workflow->buildin}]") : ''
                ),
                div
                (
                    $actionItems ? dropdown
                    (
                        set::caret(false),
                        btn
                        (
                            setClass('ghost square mr-2 open-url not-open-url'),
                            set::icon('ellipsis-v')
                        ),
                        set::items($actionItems)
                    ) : null
                )
            ),
            div
            (
                set::className('h-20 ml-2 mt-2 overflow-hidden'),
                div($lang->workflow->app, span(setClass('text-primary ml-1'), zget($apps, $flow->app, $flow->name))),
                div(setClass('break-all text-gray-400 mt-1'), html(nl2br($flow->desc)))
            ),
            div
            (
                setClass('flex items-center mb-2'),
                div
                (
                    setClass('cell flex items-center ml-2'),
                    span(setClass("label capitalize $labelClass"), zget($lang->workflow->statusList, $flow->status))
                ),
                div
                (
                    setClass('cell flex-1 text-right mr-4 btnGroup'),
                    setStyle('visibility', 'hidden'),
                    $btnGroup
                )
            )
        )
    );
}

panel
(
    setID('cards'),
    setClass('row cell canvas mb-4'),
    set::bodyClass('w-full'),
    div
    (
        setClass('flex flex-wrap'),
        $cards
    ),
    div(set::className('table-footer'), setKey('pager'), pager(set(usePager()), set::className('pull-right')))
);
