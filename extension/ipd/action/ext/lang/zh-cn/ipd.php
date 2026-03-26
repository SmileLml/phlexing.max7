<?php
$lang->action->objectTypes['waterfall'] = '瀑布/IPD' . $lang->projectCommon . '审批';

$lang->action->label->retracted           = "撤回了用户需求";
$lang->action->label->retractclosed       = '执行撤回操作关闭了';
$lang->action->search->label['retracted'] = $lang->action->label->retracted;

$lang->action->dynamicAction->story['retracted']  = "撤回需求";

$lang->action->label->confirmedretract = "确认了";
$lang->action->desc->confirmedretract  = '$date, 由 <strong>$actor</strong> 确认用户需求撤销操作。';

$lang->action->label->demandpool     = '需求池|demandpool|view|id=%s';
$lang->action->label->demand         = '需求|demand|view|id=%s';
$lang->action->label->charter        = '立项|charter|view|id=%s';
$lang->action->label->roadmap        = '路标|roadmap|view|id=%s';
$lang->action->label->market         = '市场|market|view|id=%s';
$lang->action->label->marketreport   = '市场报告|marketreport|view|id=%s';
$lang->action->label->marketresearch = '市场调研|marketresearch|task|id=%s';

$lang->action->label->deletechildrendemand = "删除了子需求";

$lang->action->desc->createchildrendemand = '$date, 由 <strong>$actor</strong> 创建子需求 <strong>$extra</strong>。' . "\n";
$lang->action->desc->deletechildrendemand = '$date, 由 <strong>$actor</strong> 删除子需求<strong>$extra</strong>。' . "\n";

$lang->action->label->distributed               = '分发了';
$lang->action->label->reviewreverted            = '评审了';
$lang->action->label->createchildrendemand      = '创建了子需求';
$lang->action->label->linked2roadmap            = "关联了路标";
$lang->action->label->unlinkedfromroadmap       = "移除了路标";
$lang->action->label->changedbycharter          = "变更了路标";
$lang->action->label->linkur                    = '关联需求到了';
$lang->action->label->unlinkur                  = '移除了需求从';
$lang->action->label->linked2charter            = '关联了立项';
$lang->action->label->managedistributedproducts = '维护了';

$lang->action->dynamicAction->story['linked2roadmap']      = "{$lang->URCommon}关联路标";
$lang->action->dynamicAction->story['unlinkedfromroadmap'] = "计划移除{$lang->URCommon}";

$lang->action->dynamicAction->roadmap['linkur']   = "关联需求";
$lang->action->dynamicAction->roadmap['unlinkur'] = "移除需求";

$lang->action->search->label['linked2roadmap']      = $lang->action->label->linked2roadmap;
$lang->action->search->label['unlinkedfromroadmap'] = $lang->action->label->unlinkedfromroadmap;

$lang->action->desc->published                 = '$date, 由 <strong>$actor</strong> 发布。' . "\n";
$lang->action->desc->managedistributedproducts = '$date, 由 <strong>$actor</strong> 维护分发产品。分发产品为 <strong>$extra</strong>。' . "\n";
