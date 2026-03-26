<?php
/* Flow*/

$lang->moduleOrder[800] = 'workflow';
$lang->moduleOrder[805] = 'workflowfield';
$lang->moduleOrder[810] = 'workflowaction';
$lang->moduleOrder[815] = 'workflowlayout';
$lang->moduleOrder[820] = 'workflowcondition';
$lang->moduleOrder[825] = 'workflowlinkage';
$lang->moduleOrder[830] = 'workflowhook';
$lang->moduleOrder[835] = 'workflowlabel';
$lang->moduleOrder[840] = 'workflowrelation';
$lang->moduleOrder[845] = 'workflowreport';
$lang->moduleOrder[850] = 'workflowdatasource';
$lang->moduleOrder[855] = 'workflowrule';
$lang->moduleOrder[860] = 'workflowgroup';

/* workflow */
$lang->resource->workflow = new stdclass();
$lang->resource->workflow->browseFlow  = 'browseFlow';
$lang->resource->workflow->browseDB    = 'browseDB';
$lang->resource->workflow->create      = 'create';
$lang->resource->workflow->copy        = 'copy';
$lang->resource->workflow->edit        = 'edit';
$lang->resource->workflow->backup      = 'backup';
$lang->resource->workflow->upgrade     = 'upgradeAction';
$lang->resource->workflow->view        = 'view';
$lang->resource->workflow->delete      = 'delete';
$lang->resource->workflow->flowchart   = 'flowchart';
$lang->resource->workflow->ui          = 'ui';
$lang->resource->workflow->release     = 'release';
$lang->resource->workflow->deactivate  = 'deactivate';
$lang->resource->workflow->activate    = 'activate';
if($config->edition != 'open') $lang->resource->workflow->setApproval = 'setApproval';
$lang->resource->workflow->setFulltextSearch = 'setFulltextSearch';
$lang->resource->workflow->setJS             = 'setJS';
$lang->resource->workflow->setCSS            = 'setCSS';
$lang->resource->workflow->quoteDB           = 'quoteDB';

if(!isset($lang->workflow)) $lang->workflow = new stdclass();
$lang->workflow->methodOrder[5]  = 'browseFlow';
$lang->workflow->methodOrder[10] = 'browseDB';
$lang->workflow->methodOrder[15] = 'create';
$lang->workflow->methodOrder[20] = 'copy';
$lang->workflow->methodOrder[25] = 'edit';
$lang->workflow->methodOrder[30] = 'backup';
$lang->workflow->methodOrder[35] = 'upgrade';
$lang->workflow->methodOrder[40] = 'view';
$lang->workflow->methodOrder[45] = 'delete';
$lang->workflow->methodOrder[50] = 'flowchart';
$lang->workflow->methodOrder[55] = 'ui';
$lang->workflow->methodOrder[60] = 'release';
$lang->workflow->methodOrder[65] = 'deactivate';
$lang->workflow->methodOrder[70] = 'activate';
$lang->workflow->methodOrder[73] = 'setApproval';
$lang->workflow->methodOrder[74] = 'setFulltextSearch';
$lang->workflow->methodOrder[75] = 'setJS';
$lang->workflow->methodOrder[80] = 'setCSS';

/* workflowfield */
$lang->resource->workflowfield = new stdclass();
$lang->resource->workflowfield->browse         = 'browse';
$lang->resource->workflowfield->create         = 'create';
$lang->resource->workflowfield->edit           = 'edit';
$lang->resource->workflowfield->delete         = 'delete';
$lang->resource->workflowfield->import         = 'import';
$lang->resource->workflowfield->showImport     = 'showImport';
$lang->resource->workflowfield->sort           = 'sort';
$lang->resource->workflowfield->exportTemplate = 'exportTemplate';
$lang->resource->workflowfield->setValue       = 'setValue';
$lang->resource->workflowfield->setExport      = 'setExport';
$lang->resource->workflowfield->setSearch      = 'setSearch';
$lang->resource->workflowfield->quote          = 'quote';

if(!isset($lang->workflowfield)) $lang->workflowfield = new stdclass();
$lang->workflowfield->methodOrder[5]  = 'browse';
$lang->workflowfield->methodOrder[10] = 'create';
$lang->workflowfield->methodOrder[15] = 'edit';
$lang->workflowfield->methodOrder[20] = 'delete';
$lang->workflowfield->methodOrder[25] = 'sort';
$lang->workflowfield->methodOrder[30] = 'import';
$lang->workflowfield->methodOrder[35] = 'showImport';
$lang->workflowfield->methodOrder[40] = 'exportTemplate';
$lang->workflowfield->methodOrder[45] = 'setValue';
$lang->workflowfield->methodOrder[50] = 'setExport';
$lang->workflowfield->methodOrder[55] = 'setSearch';
$lang->workflowfield->methodOrder[60] = 'quote';

/* workflowaction */
$lang->resource->workflowaction = new stdclass();
$lang->resource->workflowaction->browse          = 'browse';
$lang->resource->workflowaction->create          = 'create';
$lang->resource->workflowaction->edit            = 'edit';
$lang->resource->workflowaction->view            = 'view';
$lang->resource->workflowaction->delete          = 'delete';
$lang->resource->workflowaction->sort            = 'sort';
$lang->resource->workflowaction->setVerification = 'setVerification';
$lang->resource->workflowaction->setNotice       = 'setNotice';
$lang->resource->workflowaction->setJS           = 'setJS';
$lang->resource->workflowaction->setCSS          = 'setCSS';

if(!isset($lang->workflowaction)) $lang->workflowaction = new stdclass();
$lang->workflowaction->methodOrder[5]  = 'browse';
$lang->workflowaction->methodOrder[10] = 'create';
$lang->workflowaction->methodOrder[15] = 'edit';
$lang->workflowaction->methodOrder[20] = 'view';
$lang->workflowaction->methodOrder[25] = 'delete';
$lang->workflowaction->methodOrder[30] = 'sort';
$lang->workflowaction->methodOrder[35] = 'setVerification';
$lang->workflowaction->methodOrder[40] = 'setNotice';
$lang->workflowaction->methodOrder[45] = 'setJS';
$lang->workflowaction->methodOrder[50] = 'setCSS';

/* workflowcondition */
$lang->resource->workflowcondition = new stdclass();
$lang->resource->workflowcondition->browse = 'browse';
$lang->resource->workflowcondition->create = 'create';
$lang->resource->workflowcondition->edit   = 'edit';
$lang->resource->workflowcondition->delete = 'delete';

if(!isset($lang->workflowcondition)) $lang->workflowcondition = new stdclass();
$lang->workflowcondition->methodOrder[5]  = 'browse';
$lang->workflowcondition->methodOrder[10] = 'create';
$lang->workflowcondition->methodOrder[15] = 'edit';
$lang->workflowcondition->methodOrder[20] = 'delete';

/* workflowcondition */
$lang->resource->workflowgroup = new stdclass();
$lang->resource->workflowgroup->product        = 'product';
$lang->resource->workflowgroup->project        = 'project';
$lang->resource->workflowgroup->create         = 'create';
$lang->resource->workflowgroup->edit           = 'edit';
$lang->resource->workflowgroup->delete         = 'delete';
$lang->resource->workflowgroup->view           = 'view';
$lang->resource->workflowgroup->design         = 'design';
$lang->resource->workflowgroup->release        = 'release';
$lang->resource->workflowgroup->deactivate     = 'deactivate';
$lang->resource->workflowgroup->setExclusive   = 'setExclusive';
$lang->resource->workflowgroup->activateFlow   = 'activateFlow';
$lang->resource->workflowgroup->deactivateFlow = 'deactivateFlow';

if(!isset($lang->workflowgroup)) $lang->workflowgroup = new stdclass();
$lang->workflowgroup->methodOrder[5]   = 'product';
$lang->workflowgroup->methodOrder[10]  = 'project';
$lang->workflowgroup->methodOrder[15]  = 'create';
$lang->workflowgroup->methodOrder[20]  = 'edit';
$lang->workflowgroup->methodOrder[25]  = 'delete';
$lang->workflowgroup->methodOrder[30]  = 'view';
$lang->workflowgroup->methodOrder[35]  = 'design';
$lang->workflowgroup->methodOrder[40]  = 'release';
$lang->workflowgroup->methodOrder[45]  = 'deactivate';
$lang->workflowgroup->methodOrder[50]  = 'setExclusive';
$lang->workflowgroup->methodOrder[55]  = 'activateFlow';
$lang->workflowgroup->methodOrder[60]  = 'deactivateFlow';

/* workflowlayout */
$lang->resource->workflowlayout = new stdclass();
$lang->resource->workflowlayout->admin    = 'admin';
$lang->resource->workflowlayout->block    = 'block';
$lang->resource->workflowlayout->addUI    = 'addUI';
$lang->resource->workflowlayout->editUI   = 'editUI';
$lang->resource->workflowlayout->deleteUI = 'deleteUI';

if(!isset($lang->workflowlayout)) $lang->workflowlayout = new stdclass();
$lang->workflowlayout->methodOrder[5]  = 'admin';
$lang->workflowlayout->methodOrder[10] = 'block';
$lang->workflowlayout->methodOrder[15] = 'addUI';
$lang->workflowlayout->methodOrder[20] = 'editUI';
$lang->workflowlayout->methodOrder[25] = 'deleteUI';

/* workflowlinkage */
$lang->resource->workflowlinkage = new stdclass();
$lang->resource->workflowlinkage->browse = 'browse';
$lang->resource->workflowlinkage->create = 'create';
$lang->resource->workflowlinkage->edit   = 'edit';
$lang->resource->workflowlinkage->delete = 'delete';

if(!isset($lang->workflowlinkage)) $lang->workflowlinkage = new stdclass();
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

if(!isset($lang->workflowhook)) $lang->workflowhook = new stdclass();
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

if(!isset($lang->workflowlabel)) $lang->workflowlabel = new stdclass();
$lang->workflowlabel->methodOrder[5]  = 'browse';
$lang->workflowlabel->methodOrder[10] = 'create';
$lang->workflowlabel->methodOrder[15] = 'edit';
$lang->workflowlabel->methodOrder[20] = 'delete';
$lang->workflowlabel->methodOrder[25] = 'sort';

/* workflowrelation */
$lang->resource->workflowrelation = new stdclass();
$lang->resource->workflowrelation->admin = 'admin';

if(!isset($lang->workflowrelation)) $lang->workflowrelation = new stdclass();
$lang->workflowrelation->methodOrder[5] = 'admin';

/* workflowreport*/
$lang->resource->workflowreport = new stdclass();
$lang->resource->workflowreport->browse = 'brow';
$lang->resource->workflowreport->create = 'create';
$lang->resource->workflowreport->edit   = 'edit';
$lang->resource->workflowreport->delete = 'delete';
$lang->resource->workflowreport->sort   = 'sort';

if(!isset($lang->workflowreport)) $lang->workflowreport = new stdclass();
$lang->workflowreport->methodOrder[5]  = 'browse';
$lang->workflowreport->methodOrder[10] = 'create';
$lang->workflowreport->methodOrder[15] = 'edit';
$lang->workflowreport->methodOrder[20] = 'delete';
$lang->workflowreport->methodOrder[25] = 'sort';

/* workflowdatasource */
$lang->resource->workflowdatasource = new stdclass();
$lang->resource->workflowdatasource->browse = 'browse';
$lang->resource->workflowdatasource->create = 'create';
$lang->resource->workflowdatasource->edit   = 'edit';
$lang->resource->workflowdatasource->delete = 'delete';

if(!isset($lang->workflowdatasource)) $lang->workflowdatasource = new stdclass();
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

if(!isset($lang->workflowrule)) $lang->workflowrule = new stdclass();
$lang->workflowrule->methodOrder[5]  = 'browse';
$lang->workflowrule->methodOrder[10] = 'create';
$lang->workflowrule->methodOrder[15] = 'edit';
$lang->workflowrule->methodOrder[20] = 'view';
$lang->workflowrule->methodOrder[25] = 'delete';
