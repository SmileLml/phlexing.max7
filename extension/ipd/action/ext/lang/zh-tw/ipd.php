<?php
$lang->action->objectTypes['waterfall'] = '瀑布/IPD' . $lang->projectCommon . '審批';

$lang->action->label->retracted           = "撤回了用戶需求";
$lang->action->label->retractclosed       = '執行撤回操作關閉了';
$lang->action->search->label['retracted'] = $lang->action->label->retracted;

$lang->action->dynamicAction->story['retracted']  = "撤回需求";

$lang->action->label->confirmedretract = "確認了";
$lang->action->desc->confirmedretract  = '$date, 由 <strong>$actor</strong> 確認用戶需求撤銷操作。';

$lang->action->label->demandpool     = '需求池|demandpool|view|id=%s';
$lang->action->label->demand         = '需求|demand|view|id=%s';
$lang->action->label->charter        = '立項|charter|view|id=%s';
$lang->action->label->roadmap        = '路標|roadmap|view|id=%s';
$lang->action->label->market         = '市場|market|view|id=%s';
$lang->action->label->marketreport   = '市場報告|marketreport|view|id=%s';
$lang->action->label->marketresearch = '市場調研|marketresearch|task|id=%s';

$lang->action->label->deletechildrendemand = "刪除了子需求";

$lang->action->desc->createchildrendemand = '$date, 由 <strong>$actor</strong> 創建子需求 <strong>$extra</strong>。' . "\n";
$lang->action->desc->deletechildrendemand = '$date, 由 <strong>$actor</strong> 刪除子需求<strong>$extra</strong>。' . "\n";

$lang->action->label->distributed               = '分發了';
$lang->action->label->reviewreverted            = '評審了';
$lang->action->label->createchildrendemand      = '創建了子需求';
$lang->action->label->linked2roadmap            = "關聯了路標";
$lang->action->label->unlinkedfromroadmap       = "移除了路標";
$lang->action->label->changedbycharter          = "變更了路標";
$lang->action->label->linkur                    = '關聯需求到了';
$lang->action->label->unlinkur                  = '移除了需求從';
$lang->action->label->linked2charter            = '關聯了立項';
$lang->action->label->managedistributedproducts = '維護了';

$lang->action->dynamicAction->story['linked2roadmap']      = "{$lang->URCommon}關聯路標";
$lang->action->dynamicAction->story['unlinkedfromroadmap'] = "計劃移除{$lang->URCommon}";

$lang->action->dynamicAction->roadmap['linkur']   = "關聯需求";
$lang->action->dynamicAction->roadmap['unlinkur'] = "移除需求";

$lang->action->search->label['linked2roadmap']      = $lang->action->label->linked2roadmap;
$lang->action->search->label['unlinkedfromroadmap'] = $lang->action->label->unlinkedfromroadmap;

$lang->action->desc->published                 = '$date, 由 <strong>$actor</strong> 發佈。' . "\n";
$lang->action->desc->managedistributedproducts = '$date, 由 <strong>$actor</strong> 維護分發產品。分發產品為 <strong>$extra</strong>。' . "\n";
