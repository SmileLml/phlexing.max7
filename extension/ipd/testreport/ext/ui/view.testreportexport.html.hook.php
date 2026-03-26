<?php
namespace zin;

if(hasPriv('testreport', 'export') && !isInModal())
{
    global $lang;
    $report = data('report');
    $exportBtn = btn
    (
        set::text($lang->testreport->export),
        set::icon('export'),
        set::className('ghost'),
        setData(array('toggle' => 'modal', 'size' => 'sm')),
        set::url(helper::createLink('testreport', 'export', "reportID={$report->id}"))
    );
    query('.detail-header')->append($exportBtn);
}
