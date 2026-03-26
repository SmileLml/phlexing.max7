<?php
$lang->resource->story->browse = 'browse';
$lang->resource->task->browse  = 'browse';

/* Flow*/
$lang->moduleOrder[100] = 'workflow';
$lang->moduleOrder[101] = 'workflowfield';
$lang->moduleOrder[102] = 'workflowaction';
$lang->moduleOrder[103] = 'workflowcondition';
$lang->moduleOrder[104] = 'workflowlayout';
$lang->moduleOrder[105] = 'workflowlinkage';
$lang->moduleOrder[106] = 'workflowhook';
$lang->moduleOrder[107] = 'workflowlabel';
$lang->moduleOrder[108] = 'workflowrelation';
$lang->moduleOrder[109] = 'workflowdatasource';
$lang->moduleOrder[110] = 'workflowrule';

/* workflow */
$lang->resource->workflow = new stdclass();
$lang->resource->workflow->browseFlow = 'browseFlow';
$lang->resource->workflow->browseDB   = 'browseDB';
$lang->resource->workflow->create     = 'create';
$lang->resource->workflow->copy       = 'copy';
$lang->resource->workflow->edit       = 'edit';
$lang->resource->workflow->backup     = 'backup';
$lang->resource->workflow->upgrade    = 'upgradeAction';
$lang->resource->workflow->view       = 'view';
$lang->resource->workflow->delete     = 'delete';

$lang->workflow->methodOrder[5]  = 'browseFlow';
$lang->workflow->methodOrder[10] = 'browseDB';
$lang->workflow->methodOrder[15] = 'create';
$lang->workflow->methodOrder[20] = 'copy';
$lang->workflow->methodOrder[25] = 'edit';
$lang->workflow->methodOrder[30] = 'backup';
$lang->workflow->methodOrder[35] = 'upgrade';
$lang->workflow->methodOrder[40] = 'view';
$lang->workflow->methodOrder[45] = 'delete';

/* workflowfield */
$lang->resource->workflowfield = new stdclass();
$lang->resource->workflowfield->browse    = 'browse';
$lang->resource->workflowfield->create    = 'create';
$lang->resource->workflowfield->edit      = 'edit';
$lang->resource->workflowfield->delete    = 'delete';
$lang->resource->workflowfield->sort      = 'sort';
$lang->resource->workflowfield->setExport = 'setExport';

$lang->workflowfield->methodOrder[5]  = 'browse';
$lang->workflowfield->methodOrder[10] = 'create';
$lang->workflowfield->methodOrder[15] = 'edit';
$lang->workflowfield->methodOrder[20] = 'delete';
$lang->workflowfield->methodOrder[25] = 'sort';
$lang->workflowfield->methodOrder[30] = 'setExport';

/* workflowaction */
$lang->resource->workflowaction = new stdclass();
$lang->resource->workflowaction->browse          = 'browse';
$lang->resource->workflowaction->create          = 'create';
$lang->resource->workflowaction->edit            = 'edit';
$lang->resource->workflowaction->view            = 'view';
$lang->resource->workflowaction->delete          = 'delete';
$lang->resource->workflowaction->setVerification = 'setVerification';
$lang->resource->workflowaction->setNotice       = 'setNotice';

$lang->workflowaction->methodOrder[5]  = 'browse';
$lang->workflowaction->methodOrder[10] = 'create';
$lang->workflowaction->methodOrder[15] = 'edit';
$lang->workflowaction->methodOrder[20] = 'view';
$lang->workflowaction->methodOrder[25] = 'delete';
$lang->workflowaction->methodOrder[30] = 'setVerification';
$lang->workflowaction->methodOrder[35] = 'setNotice';

/* workflowcondition */
$lang->resource->workflowcondition = new stdclass();
$lang->resource->workflowcondition->browse = 'browse';
$lang->resource->workflowcondition->create = 'create';
$lang->resource->workflowcondition->edit   = 'edit';
$lang->resource->workflowcondition->delete = 'delete';

$lang->workflowcondition->methodOrder[5]  = 'browse';
$lang->workflowcondition->methodOrder[10] = 'create';
$lang->workflowcondition->methodOrder[15] = 'edit';
$lang->workflowcondition->methodOrder[20] = 'delete';

/* workflowlayout */
$lang->resource->workflowlayout = new stdclass();
$lang->resource->workflowlayout->admin = 'admin';

$lang->workflowlayout->methodOrder[5] = 'admin';

/* workflowlinkage */
$lang->resource->workflowlinkage = new stdclass();
$lang->resource->workflowlinkage->browse = 'browse';
$lang->resource->workflowlinkage->create = 'create';
$lang->resource->workflowlinkage->edit   = 'edit';
$lang->resource->workflowlinkage->delete = 'delete';

$lang->workflowlinkage->methodOrder[5]  = 'browse';
$lang->workflowlinkage->methodOrder[10] = 'create';
$lang->workflowlinkage->methodOrder[15] = 'edit';
$lang->workflowlinkage->methodOrder[20] = 'delete';

/* workflowhook */
$lang->resource->workflowhook = new stdclass();
$lang->resource->workflowhook->browse = 'browse';
$lang->resource->workflowhook->create = 'create';
$lang->resource->workflowhook->edit   = 'edit';
$lang->resource->workflowhook->delete = 'delete';

$lang->workflowhook->methodOrder[5]  = 'browse';
$lang->workflowhook->methodOrder[10] = 'create';
$lang->workflowhook->methodOrder[15] = 'edit';
$lang->workflowhook->methodOrder[20] = 'delete';

/* workflowlabel */
$lang->resource->workflowlabel = new stdclass();
$lang->resource->workflowlabel->browse = 'browse';
$lang->resource->workflowlabel->create = 'create';
$lang->resource->workflowlabel->edit   = 'edit';
$lang->resource->workflowlabel->delete = 'delete';
$lang->resource->workflowlabel->sort   = 'sort';

$lang->workflowlabel->methodOrder[5]  = 'browse';
$lang->workflowlabel->methodOrder[10] = 'create';
$lang->workflowlabel->methodOrder[15] = 'edit';
$lang->workflowlabel->methodOrder[20] = 'delete';
$lang->workflowlabel->methodOrder[25] = 'sort';

/* workflowrelation */
$lang->resource->workflowrelation = new stdclass();
$lang->resource->workflowrelation->admin = 'admin';

$lang->workflowrelation->methodOrder[5] = 'admin';

/* workflowdatasource */
$lang->resource->workflowdatasource = new stdclass();
$lang->resource->workflowdatasource->browse = 'browse';
$lang->resource->workflowdatasource->create = 'create';
$lang->resource->workflowdatasource->edit   = 'edit';
$lang->resource->workflowdatasource->delete = 'delete';

$lang->workflowdatasource->methodOrder[5]  = 'browse';
$lang->workflowdatasource->methodOrder[10] = 'create';
$lang->workflowdatasource->methodOrder[15] = 'edit';
$lang->workflowdatasource->methodOrder[20] = 'delete';

/* workflowrule */
$lang->resource->workflowrule = new stdclass();
$lang->resource->workflowrule->browse = 'browse';
$lang->resource->workflowrule->create = 'create';
$lang->resource->workflowrule->edit   = 'edit';
$lang->resource->workflowrule->view   = 'view';
$lang->resource->workflowrule->delete = 'delete';

$lang->workflowrule->methodOrder[5]  = 'browse';
$lang->workflowrule->methodOrder[10] = 'create';
$lang->workflowrule->methodOrder[15] = 'edit';
$lang->workflowrule->methodOrder[20] = 'view';
$lang->workflowrule->methodOrder[25] = 'delete';
