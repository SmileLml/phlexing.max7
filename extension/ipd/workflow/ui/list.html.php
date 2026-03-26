<?php
namespace zin;

$cols = $config->workflow->dtable->fieldList;
$cols['app']['map'] = $apps;

$flows = initTableData($flows, $cols, $this->workflow);

dtable
(
    set::cols($cols),
    set::data($flows),
    set::orderBy($orderBy),
    set::sortLink(inlink('browseFlow', "mode={$mode}&status={$status}&app={$currentApp}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('onRenderCell')),
    set::footPager(usePager())
);
