<?php
$lang->action->objectTypes['waterfall'] = 'Waterfall/IPD Review';

$lang->action->label->retracted           = "Retract the requirment";
$lang->action->label->retractclosed       = 'Performing a retract closes the';
$lang->action->search->label['retracted'] = $lang->action->label->retracted;

$lang->action->dynamicAction->story['retracted']  = "Retract the story";

$lang->action->label->confirmedretract = "Confirmed";
$lang->action->desc->confirmedretract  = '$date, Confirmed by <strong>$actor</strong>.';

$lang->action->label->demandpool     = 'Demand Pool|demandpool|view|id=%s';
$lang->action->label->demand         = 'Demand|demand|view|id=%s';
$lang->action->label->charter        = 'Charter|charter|view|id=%s';
$lang->action->label->roadmap        = 'Roadmap|roadmap|view|id=%s';
$lang->action->label->market         = 'Market|market|view|id=%s';
$lang->action->label->marketreport   = 'Marketreport|marketreport|view|id=%s';
$lang->action->label->marketresearch = 'Marketresearch|marketresearch|task|id=%s';

$lang->action->label->deletechildrendemand = "delete children story";

$lang->action->desc->createchildrendemand = '$date, <strong>$actor</strong> created a child story <strong>$extra</strong>.' . "\n";
$lang->action->desc->deletechildrendemand = '$date, <strong>$actor</strong> deleted a child story <strong>$extra</strong>。' . "\n";

$lang->action->label->distributed               = 'Distributed';
$lang->action->label->reviewreverted            = 'Reviewed';
$lang->action->label->createchildrendemand      = 'Create children demand';
$lang->action->label->linked2roadmap            = 'linked to Roadmap';
$lang->action->label->unlinkedfromroadmap       = 'unlinked from ';
$lang->action->label->changedbycharter          = "update by charter";
$lang->action->label->linkur                    = 'link stories to';
$lang->action->label->unlinkur                  = 'unlink stories from';
$lang->action->label->linked2charter            = 'linked to charter';
$lang->action->label->managedistributedproducts = 'Managed';

$lang->action->dynamicAction->story['linked2roadmap']      = 'Link Story to Roadmap';
$lang->action->dynamicAction->story['unlinkedfromroadmap'] = 'Unlink Story from Roadmap';

$lang->action->dynamicAction->roadmap['linkur']   = "Link Requirement Story";
$lang->action->dynamicAction->roadmap['unlinkur'] = "Unlink Requirement Story";

$lang->action->search->label['linked2roadmap']      = $lang->action->label->linked2roadmap;
$lang->action->search->label['unlinkedfromroadmap'] = $lang->action->label->unlinkedfromroadmap;

$lang->action->desc->published                 = '$date, published by  <strong>$actor</strong> .' . "\n";
$lang->action->desc->managedistributedproducts = '$date, managed distributed products by <strong>$actor</strong>. Distributed products are <strong>$extra</strong>.' . "\n";
