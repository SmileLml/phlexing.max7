<?php
namespace zin;

jsVar('confirmPublishTip', $lang->ai->assistant->confirmPublishTip);
jsVar('confirmWithdrawTip', $lang->ai->assistant->confirmWithdrawTip);

featureBar();
if(!$hasModalAvailable)
{
    panel
    (
        center
        (
            set::style(array('height' => 'calc(100vh - 145px)')), // 145px is sum of header and footer height.
            div
            (
                span
                (
                    set::className('p-8 text-gray'),
                    $lang->ai->assistant->noLlm
                )
            )
        )
    );
}
else
{
    if(common::hasPriv('ai', 'assistantCreate'))
    {
        toolbar(
            item(
                set(array(
                    'text' => $lang->ai->assistant->create,
                    'url' => createLink('ai', 'assistantcreate'),
                    'data-app' => $app->tab,
                    'icon'  => 'plus',
                    'class' => 'btn primary',
                ))
            )
        );
    }

    $assistants = initTableData($assistants, $config->ai->dtable->assistants, $this->ai);

    dtable(set::cols($config->ai->dtable->assistants), set::data($assistants), set::orderBy($orderBy), set::sortLink(inlink('assistants', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")), set::footPager(usePager()));

}
