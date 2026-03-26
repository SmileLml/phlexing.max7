<?php
$lang->programplan->stageCustom['point'] = 'Show Point';

$lang->programplan->parallel      = 'Top level phase whether parallel is allowed';
$lang->programplan->sortableTip   = 'Top level phase does not support sorting';
$lang->programplan->addSiblingTip = 'Top level phase does not support adding';
$lang->programplan->pointTip      = 'Only top level phase can configure review point';

$lang->programplan->parallelTip = "<i class='icon icon-help text-gray' title='Not supporting parallelism means that the next stage can't start until the previous stage is complete, for example, the planning stage can't start until the concept stage is complete, including the tasks under the stage.
Parallelism means that there is no dependency between phases, and the beginning state of phases and tasks is not affected by the state of other phases.'></i>";

$lang->programplan->parallelList[0] = 'No';
$lang->programplan->parallelList[1] = 'Yes';

$lang->programplan->error->outOfDate  = 'The start time of the plan should be greater than the end time of the previous phase.';
$lang->programplan->error->lessOfDate = 'The end time of the plan should be less than the start time of the next phase.';
