<?php
$lang->resource = new stdclass();

$lang->moduleOrder[0]   = 'index';
$lang->moduleOrder[5]   = 'my';
$lang->moduleOrder[10]  = 'todo';
$lang->moduleOrder[15]  = 'demandpool';
$lang->moduleOrder[20]  = 'demand';
$lang->moduleOrder[25]  = 'market';
$lang->moduleOrder[30]  = 'marketreport';
$lang->moduleOrder[35]  = 'marketresearch';
$lang->moduleOrder[40]  = 'product';
$lang->moduleOrder[45]  = 'branch';
$lang->moduleOrder[50]  = 'roadmap';
$lang->moduleOrder[55]  = 'requirement';
$lang->moduleOrder[56]  = 'epic';
$lang->moduleOrder[60]  = 'charter';
$lang->moduleOrder[65]  = 'doc';
$lang->moduleOrder[70]  = 'admin';
$lang->moduleOrder[75]  = 'mail';
$lang->moduleOrder[80]  = 'feedback';
$lang->moduleOrder[90]  = 'workflow';
$lang->moduleOrder[95]  = 'workflowfield';
$lang->moduleOrder[100] = 'workflowaction';
$lang->moduleOrder[105] = 'workflowlayout';
$lang->moduleOrder[110] = 'workflowcondition';
$lang->moduleOrder[115] = 'workflowlinkage';
$lang->moduleOrder[120] = 'workflowhook';
$lang->moduleOrder[125] = 'workflowlabel';
$lang->moduleOrder[130] = 'workflowrelation';
$lang->moduleOrder[135] = 'workflowreport';
$lang->moduleOrder[140] = 'workflowdatasource';
$lang->moduleOrder[145] = 'workflowrule';

/* My module. */
$lang->resource->my = new stdclass();
$lang->resource->my->index           = 'indexAction';
$lang->resource->my->todo            = 'todoAction';
$lang->resource->my->calendar        = 'calendarAction';
$lang->resource->my->work            = 'workAction';
$lang->resource->my->audit           = 'audit';
$lang->resource->my->contribute      = 'contributeAction';
$lang->resource->my->project         = 'project';
$lang->resource->my->uploadAvatar    = 'uploadAvatar';
$lang->resource->my->dynamic         = 'dynamicAction';
$lang->resource->my->editProfile     = 'editProfile';
$lang->resource->my->manageContacts  = 'manageContacts';
$lang->resource->my->deleteContacts  = 'deleteContacts';
$lang->resource->my->score           = 'score';
$lang->resource->my->team            = 'team';
$lang->resource->my->doc             = 'doc';
$lang->resource->my->execution       = 'execution';

$lang->my->methodOrder[1]  = 'index';
$lang->my->methodOrder[5]  = 'todo';
$lang->my->methodOrder[10] = 'work';
$lang->my->methodOrder[15] = 'contribute';
$lang->my->methodOrder[20] = 'project';
$lang->my->methodOrder[30] = 'uploadAvatar';
$lang->my->methodOrder[40] = 'dynamic';
$lang->my->methodOrder[45] = 'editProfile';
$lang->my->methodOrder[55] = 'manageContacts';
$lang->my->methodOrder[60] = 'deleteContacts';
$lang->my->methodOrder[65] = 'score';
$lang->my->methodOrder[70] = 'unbind';
$lang->my->methodOrder[75] = 'team';
$lang->my->methodOrder[80] = 'execution';
$lang->my->methodOrder[85] = 'doc';
$lang->my->methodOrder[90] = 'audit';

/* Todo. */
$lang->resource->todo = new stdclass();
$lang->resource->todo->create       = 'create';
$lang->resource->todo->createcycle  = 'createCycle';
$lang->resource->todo->batchCreate  = 'batchCreate';
$lang->resource->todo->edit         = 'edit';
$lang->resource->todo->batchEdit    = 'batchEdit';
$lang->resource->todo->view         = 'view';
$lang->resource->todo->delete       = 'delete';
$lang->resource->todo->export       = 'export';
$lang->resource->todo->start        = 'start';
$lang->resource->todo->finish       = 'finish';
$lang->resource->todo->batchFinish  = 'batchFinish';
$lang->resource->todo->import2Today = 'import2Today';
$lang->resource->todo->assignTo     = 'assignAction';
$lang->resource->todo->activate     = 'activate';
$lang->resource->todo->close        = 'close';
$lang->resource->todo->batchClose   = 'batchClose';
$lang->resource->todo->calendar     = 'calendar';

/* Product. */
$lang->resource->product = new stdclass();
$lang->resource->product->index           = 'indexAction';
$lang->resource->product->requirement     = 'requirement';
$lang->resource->product->epic            = 'epic';
$lang->resource->product->create          = 'create';
$lang->resource->product->view            = 'view';
$lang->resource->product->edit            = 'edit';
$lang->resource->product->batchEdit       = 'batchEdit';
$lang->resource->product->delete          = 'delete';
$lang->resource->product->roadmap         = 'roadmap';
$lang->resource->product->track           = 'track';
$lang->resource->product->dynamic         = 'dynamic';
$lang->resource->product->project         = 'project';
$lang->resource->product->close           = 'closeAction';
$lang->resource->product->activate        = 'activateAction';
$lang->resource->product->updateOrder     = 'orderAction';
$lang->resource->product->all             = 'list';
$lang->resource->product->kanban          = 'kanban';
$lang->resource->product->manageLine      = 'manageLine';
$lang->resource->product->export          = 'exportAction';
$lang->resource->product->whitelist       = 'whitelist';
$lang->resource->product->addWhitelist    = 'addWhitelist';
$lang->resource->product->unbindWhitelist = 'unbindWhitelist';

$lang->product->methodOrder[0]   = 'index';
$lang->product->methodOrder[5]   = 'browse';
$lang->product->methodOrder[6]   = 'requirement';
$lang->product->methodOrder[7]   = 'epic';
$lang->product->methodOrder[10]  = 'create';
$lang->product->methodOrder[15]  = 'view';
$lang->product->methodOrder[20]  = 'edit';
$lang->product->methodOrder[25]  = 'batchEdit';
$lang->product->methodOrder[35]  = 'delete';
$lang->product->methodOrder[40]  = 'roadmap';
$lang->product->methodOrder[45]  = 'track';
$lang->product->methodOrder[50]  = 'dynamic';
$lang->product->methodOrder[55]  = 'project';
$lang->product->methodOrder[65]  = 'close';
$lang->product->methodOrder[70]  = 'activate';
$lang->product->methodOrder[75]  = 'updateOrder';
$lang->product->methodOrder[80]  = 'all';
$lang->product->methodOrder[85]  = 'kanban';
$lang->product->methodOrder[90]  = 'manageLine';
$lang->product->methodOrder[95]  = 'build';
$lang->product->methodOrder[100] = 'export';
$lang->product->methodOrder[105] = 'whitelist';
$lang->product->methodOrder[110] = 'addWhitelist';
$lang->product->methodOrder[115] = 'unbindWhitelist';

/* Branch. */
$lang->resource->branch = new stdclass();
$lang->resource->branch->manage      = 'manage';
$lang->resource->branch->create      = 'createAction';
$lang->resource->branch->edit        = 'editAction';
$lang->resource->branch->close       = 'closeAction';
$lang->resource->branch->activate    = 'activateAction';
$lang->resource->branch->sort        = 'sort';
$lang->resource->branch->batchEdit   = 'batchEdit';
$lang->resource->branch->mergeBranch = 'mergeBranchAction';

$lang->branch->methodOrder[0]  = 'manage';
$lang->branch->methodOrder[5]  = 'create';
$lang->branch->methodOrder[10] = 'edit';
$lang->branch->methodOrder[15] = 'close';
$lang->branch->methodOrder[20] = 'activate';
$lang->branch->methodOrder[25] = 'sort';
$lang->branch->methodOrder[30] = 'batchEdit';
$lang->branch->methodOrder[35] = 'mergeBranch';

/* Requirement. */
$lang->resource->requirement = new stdclass();
$lang->resource->requirement->create             = 'create';
$lang->resource->requirement->batchCreate        = 'batchCreate';
$lang->resource->requirement->edit               = 'editAction';
$lang->resource->requirement->linkStory          = 'linkStory';
$lang->resource->requirement->batchEdit          = 'batchEdit';
$lang->resource->requirement->export             = 'exportAction';
$lang->resource->requirement->delete             = 'deleteAction';
$lang->resource->requirement->view               = 'view';
$lang->resource->requirement->change             = 'changeAction';
$lang->resource->requirement->review             = 'reviewAction';
$lang->resource->requirement->submitReview       = 'submitReview';
$lang->resource->requirement->batchReview        = 'batchReview';
$lang->resource->requirement->recall             = 'recall';
$lang->resource->requirement->assignTo           = 'assignAction';
$lang->resource->requirement->close              = 'closeAction';
$lang->resource->requirement->batchClose         = 'batchClose';
$lang->resource->requirement->activate           = 'activateAction';
$lang->resource->requirement->report             = 'reportAction';
$lang->resource->requirement->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->requirement->batchAssignTo      = 'batchAssignTo';
$lang->resource->requirement->batchChangeModule  = 'batchChangeModule';
$lang->resource->requirement->linkRequirements   = 'linkRequirementsAB';
$lang->resource->requirement->batchChangeRoadmap = 'batchChangeRoadmap';
$lang->resource->requirement->exportTemplate     = 'exportTemplate';
$lang->resource->requirement->import             = 'importCase';
$lang->resource->requirement->relation           = 'relation';
$lang->resource->requirement->processStoryChange = 'processStoryChange';

$lang->requirement->methodOrder[5]   = 'create';
$lang->requirement->methodOrder[10]  = 'batchCreate';
$lang->requirement->methodOrder[15]  = 'edit';
$lang->requirement->methodOrder[20]  = 'export';
$lang->requirement->methodOrder[25]  = 'delete';
$lang->requirement->methodOrder[30]  = 'view';
$lang->requirement->methodOrder[35]  = 'change';
$lang->requirement->methodOrder[40]  = 'review';
$lang->requirement->methodOrder[44]  = 'submitReview';
$lang->requirement->methodOrder[45]  = 'batchReview';
$lang->requirement->methodOrder[50]  = 'recall';
$lang->requirement->methodOrder[55]  = 'close';
$lang->requirement->methodOrder[60]  = 'batchClose';
$lang->requirement->methodOrder[65]  = 'assignTo';
$lang->requirement->methodOrder[70]  = 'batchAssignTo';
$lang->requirement->methodOrder[75]  = 'activate';
$lang->requirement->methodOrder[80]  = 'report';
$lang->requirement->methodOrder[85]  = 'linkStory';
$lang->requirement->methodOrder[90]  = 'batchChangeBranch';
$lang->requirement->methodOrder[95]  = 'batchChangeModule';
$lang->requirement->methodOrder[100] = 'linkRequirements';
$lang->requirement->methodOrder[105] = 'exportTemplate';
$lang->requirement->methodOrder[110] = 'import';
$lang->requirement->methodOrder[115] = 'relation';
$lang->requirement->methodOrder[120] = 'processStoryChange';

/* Doc. */
$lang->resource->doc = new stdclass();
$lang->resource->doc->index              = 'index';
$lang->resource->doc->mySpace            = 'mySpace';
$lang->resource->doc->quick              = 'quick';
$lang->resource->doc->createSpace        = 'createSpace';
$lang->resource->doc->createLib          = 'createLibAction';
$lang->resource->doc->editLib            = 'editLibAction';
$lang->resource->doc->deleteLib          = 'deleteLibAction';
$lang->resource->doc->moveLib            = 'moveLibAction';
$lang->resource->doc->create             = 'create';
$lang->resource->doc->edit               = 'edit';
$lang->resource->doc->view               = 'view';
$lang->resource->doc->delete             = 'delete';
$lang->resource->doc->deleteFile         = 'deleteFile';
$lang->resource->doc->collect            = 'collectAction';
$lang->resource->doc->productSpace       = 'productSpace';
$lang->resource->doc->projectSpace       = 'projectSpace';
$lang->resource->doc->teamSpace          = 'teamSpace';
$lang->resource->doc->showFiles          = 'showFiles';
$lang->resource->doc->addCatalog         = 'addCatalog';
$lang->resource->doc->editCatalog        = 'editCatalog';
$lang->resource->doc->sortDoclib         = 'sortDoclib';
$lang->resource->doc->sortCatalog        = 'sortCatalog';
$lang->resource->doc->sortDoc            = 'sortDoc';
$lang->resource->doc->deleteCatalog      = 'deleteCatalog';
$lang->resource->doc->displaySetting     = 'displaySetting';
$lang->resource->doc->mine2export        = 'mine2export';
$lang->resource->doc->product2export     = 'product2export';
$lang->resource->doc->custom2export      = 'custom2export';
$lang->resource->doc->exportFiles        = 'exportFiles';
$lang->resource->doc->diff               = 'diffAction';
$lang->resource->doc->browseTemplate     = 'browseTemplate';
$lang->resource->doc->createTemplate     = 'createTemplate';
$lang->resource->doc->editTemplate       = 'editTemplate';
$lang->resource->doc->moveTemplate       = 'moveTemplate';
$lang->resource->doc->sortTemplate       = 'sortTemplate';
$lang->resource->doc->deleteTemplate     = 'deleteTemplate';
$lang->resource->doc->viewTemplate       = 'viewTemplate';
$lang->resource->doc->addTemplateType    = 'addTemplateType';
$lang->resource->doc->editTemplateType   = 'editTemplateType';
$lang->resource->doc->deleteTemplateType = 'deleteTemplateType';
$lang->resource->doc->manageScope        = 'manageScope';

$lang->doc->methodOrder[5]   = 'index';
$lang->doc->methodOrder[10]  = 'mySpace';
$lang->doc->methodOrder[15]  = 'myView';
$lang->doc->methodOrder[20]  = 'myCollection';
$lang->doc->methodOrder[25]  = 'myCreation';
$lang->doc->methodOrder[30]  = 'myEdited';
$lang->doc->methodOrder[35]  = 'createSpace';
$lang->doc->methodOrder[40]  = 'createLib';
$lang->doc->methodOrder[45]  = 'editLib';
$lang->doc->methodOrder[50]  = 'deleteLib';
$lang->doc->methodOrder[55]  = 'moveLib';
$lang->doc->methodOrder[60]  = 'create';
$lang->doc->methodOrder[65]  = 'edit';
$lang->doc->methodOrder[70]  = 'view';
$lang->doc->methodOrder[75]  = 'delete';
$lang->doc->methodOrder[80]  = 'deleteFile';
$lang->doc->methodOrder[85]  = 'collect';
$lang->doc->methodOrder[90]  = 'productSpace';
$lang->doc->methodOrder[95]  = 'projectSpace';
$lang->doc->methodOrder[100] = 'teamSpace';
$lang->doc->methodOrder[105] = 'showFiles';
$lang->doc->methodOrder[110] = 'addCatalog';
$lang->doc->methodOrder[115] = 'editCatalog';
$lang->doc->methodOrder[120] = 'sortDoclib';
$lang->doc->methodOrder[125] = 'sortCatalog';
$lang->doc->methodOrder[130] = 'sortDoc';
$lang->doc->methodOrder[135] = 'deleteCatalog';
$lang->doc->methodOrder[140] = 'displaySetting';
$lang->doc->methodOrder[145] = 'diff';

/* Custom. */
$lang->resource->custom = new stdclass();
$lang->resource->custom->set                = 'set';
$lang->resource->custom->product            = 'productName';
$lang->resource->custom->execution          = 'executionCommon';
$lang->resource->custom->required           = 'required';
$lang->resource->custom->restore            = 'restore';
$lang->resource->custom->flow               = 'flow';
$lang->resource->custom->timezone           = 'timezone';
$lang->resource->custom->setStoryConcept    = 'setStoryConcept';
$lang->resource->custom->editStoryConcept   = 'editStoryConcept';
$lang->resource->custom->browseStoryConcept = 'browseStoryConcept';
$lang->resource->custom->setDefaultConcept  = 'setDefaultConcept';
$lang->resource->custom->deleteStoryConcept = 'deleteStoryConcept';
$lang->resource->custom->kanban             = 'kanban';
$lang->resource->custom->code               = 'code';
$lang->resource->custom->hours              = 'hours';
$lang->resource->custom->percent            = 'percent';
$lang->resource->custom->limitTaskDate      = 'limitTaskDateAction';
$lang->resource->custom->epicGrade          = 'epicGrade';
$lang->resource->custom->requirementGrade   = 'requirementGrade';
$lang->resource->custom->closeGrade         = 'closeGrade';
$lang->resource->custom->activateGrade      = 'activateGrade';
$lang->resource->custom->deleteGrade        = 'deleteGrade';
$lang->resource->custom->relateObject       = 'relateObject';
$lang->resource->custom->removeObjects      = 'removeObjects';
$lang->resource->custom->showRelationGraph  = 'relationGraph';
$lang->resource->custom->setCharterInfo    = 'setCharterInfo';
$lang->resource->custom->resetCharterInfo  = 'resetCharterInfo';

$lang->custom->methodOrder[10]  = 'set';
$lang->custom->methodOrder[15]  = 'product';
$lang->custom->methodOrder[20]  = 'execution';
$lang->custom->methodOrder[25]  = 'required';
$lang->custom->methodOrder[30]  = 'restore';
$lang->custom->methodOrder[35]  = 'flow';
$lang->custom->methodOrder[45]  = 'timezone';
$lang->custom->methodOrder[50]  = 'setStoryConcept';
$lang->custom->methodOrder[55]  = 'editStoryConcept';
$lang->custom->methodOrder[60]  = 'browseStoryConcept';
$lang->custom->methodOrder[65]  = 'setDefaultConcept';
$lang->custom->methodOrder[70]  = 'deleteStoryConcept';
$lang->custom->methodOrder[75]  = 'kanban';
$lang->custom->methodOrder[80]  = 'code';
$lang->custom->methodOrder[85]  = 'hours';
$lang->custom->methodOrder[90]  = 'percent';
$lang->custom->methodOrder[95]  = 'limitTaskDate';
$lang->custom->methodOrder[96]  = 'epicGrade';
$lang->custom->methodOrder[97]  = 'requirementGrade';
$lang->custom->methodOrder[100] = 'closeGrade';
$lang->custom->methodOrder[105] = 'activateGrade';
$lang->custom->methodOrder[110] = 'deleteGrade';
$lang->custom->methodOrder[115] = 'relateObject';
$lang->custom->methodOrder[120] = 'removeObjects';
$lang->custom->methodOrder[121] = 'showRelationGraph';
$lang->custom->methodOrder[122] = 'setCharterInfo';
$lang->custom->methodOrder[123] = 'resetCharterInfo';

/* Company. */
$lang->resource->company = new stdclass();
$lang->resource->company->browse = 'browse';
$lang->resource->company->edit   = 'edit';
$lang->resource->company->view   = 'view';
$lang->resource->company->dynamic= 'dynamic';

$lang->company->methodOrder[5]  = 'browse';
$lang->company->methodOrder[15] = 'edit';
$lang->company->methodOrder[25] = 'dynamic';

/* Department. */
$lang->resource->dept = new stdclass();
$lang->resource->dept->browse      = 'browse';
$lang->resource->dept->updateOrder = 'updateOrder';
$lang->resource->dept->manageChild = 'manageChildAction';
$lang->resource->dept->edit        = 'edit';
$lang->resource->dept->delete      = 'delete';

$lang->dept->methodOrder[5]  = 'browse';
$lang->dept->methodOrder[10] = 'updateOrder';
$lang->dept->methodOrder[15] = 'manageChild';
$lang->dept->methodOrder[20] = 'edit';
$lang->dept->methodOrder[25] = 'delete';

/* Group. */
$lang->resource->group = new stdclass();
$lang->resource->group->browse              = 'browseAction';
$lang->resource->group->create              = 'create';
$lang->resource->group->edit                = 'edit';
$lang->resource->group->copy                = 'copy';
$lang->resource->group->delete              = 'delete';
$lang->resource->group->manageView          = 'manageView';
$lang->resource->group->managePriv          = 'managePriv';
$lang->resource->group->manageMember        = 'manageMember';
$lang->resource->group->manageProjectAdmin  = 'manageProjectAdmin';
//$lang->resource->group->editManagePriv      = 'editManagePriv';
//$lang->resource->group->managePrivPackage   = 'managePrivPackage';
//$lang->resource->group->createPrivPackage   = 'createPrivPackage';
//$lang->resource->group->editPrivPackage     = 'editPrivPackage';
//$lang->resource->group->deletePrivPackage   = 'deletePrivPackage';
//$lang->resource->group->sortPrivPackages    = 'sortPrivPackages';
//$lang->resource->group->addRelation         = 'addRelation';
//$lang->resource->group->deleteRelation      = 'deleteRelation';
//$lang->resource->group->batchDeleteRelation = 'batchDeleteRelation';
//$lang->resource->group->createPriv          = 'createPriv';
//$lang->resource->group->editPriv            = 'editPriv';
//$lang->resource->group->deletePriv          = 'deletePriv';
//$lang->resource->group->batchChangePackage  = 'batchChangePackage';

$lang->group->methodOrder[5]   = 'browse';
$lang->group->methodOrder[10]  = 'create';
$lang->group->methodOrder[15]  = 'edit';
$lang->group->methodOrder[20]  = 'copy';
$lang->group->methodOrder[25]  = 'delete';
$lang->group->methodOrder[30]  = 'managePriv';
$lang->group->methodOrder[35]  = 'manageMember';
$lang->group->methodOrder[40]  = 'manageProjectAdmin';
$lang->group->methodOrder[45]  = 'editManagePriv';
$lang->group->methodOrder[50]  = 'managePrivPackage';
$lang->group->methodOrder[55]  = 'createPrivPackage';
$lang->group->methodOrder[60]  = 'editPrivPackage';
$lang->group->methodOrder[65]  = 'deletePrivPackage';
$lang->group->methodOrder[70]  = 'sortPrivPackages';
$lang->group->methodOrder[75]  = 'batchChangePackage';
$lang->group->methodOrder[80]  = 'addRelation';
$lang->group->methodOrder[85]  = 'deleteRelation';
$lang->group->methodOrder[90]  = 'batchDeleteRelation';
$lang->group->methodOrder[95]  = 'createPriv';
$lang->group->methodOrder[100] = 'editPriv';
$lang->group->methodOrder[105] = 'deletePriv';

/* User. */
$lang->resource->user = new stdclass();
$lang->resource->user->create            = 'create';
$lang->resource->user->batchCreate       = 'batchCreate';
$lang->resource->user->view              = 'view';
$lang->resource->user->edit              = 'edit';
$lang->resource->user->unlock            = 'unlock';
$lang->resource->user->delete            = 'delete';
$lang->resource->user->todo              = 'todo';
$lang->resource->user->story             = 'story';
$lang->resource->user->task              = 'task';
$lang->resource->user->bug               = 'bug';
$lang->resource->user->testTask          = 'testTask';
$lang->resource->user->testCase          = 'testCase';
$lang->resource->user->execution         = 'execution';
$lang->resource->user->dynamic           = 'dynamic';
$lang->resource->user->profile           = 'profile';
$lang->resource->user->batchEdit         = 'batchEdit';
$lang->resource->user->unbind            = 'unbind';
$lang->resource->user->setPublicTemplate = 'setPublicTemplate';
$lang->resource->user->export            = 'export';
$lang->resource->user->exportTemplate    = 'exportTemplate';
$lang->resource->user->import            = 'import';

$lang->user->methodOrder[5]  = 'create';
$lang->user->methodOrder[7]  = 'batchCreate';
$lang->user->methodOrder[10] = 'view';
$lang->user->methodOrder[15] = 'edit';
$lang->user->methodOrder[20] = 'unlock';
$lang->user->methodOrder[25] = 'delete';
$lang->user->methodOrder[30] = 'todo';
$lang->user->methodOrder[35] = 'task';
$lang->user->methodOrder[40] = 'bug';
$lang->user->methodOrder[45] = 'project';
$lang->user->methodOrder[60] = 'dynamic';
$lang->user->methodOrder[70] = 'profile';
$lang->user->methodOrder[75] = 'batchEdit';
$lang->user->methodOrder[80] = 'unbind';
$lang->user->methodOrder[85] = 'setPublicTemplate';

/* Tree. */
$lang->resource->tree = new stdclass();
$lang->resource->tree->browse      = 'browse';
$lang->resource->tree->browseTask  = 'browseTask';
$lang->resource->tree->updateOrder = 'updateOrder';
$lang->resource->tree->manageChild = 'manageChild';
$lang->resource->tree->edit        = 'edit';
$lang->resource->tree->fix         = 'fix';
$lang->resource->tree->delete      = 'delete';

$lang->tree->methodOrder[5]  = 'browse';
$lang->tree->methodOrder[10] = 'browseTask';
$lang->tree->methodOrder[15] = 'updateOrder';
$lang->tree->methodOrder[20] = 'manageChild';
$lang->tree->methodOrder[25] = 'edit';
$lang->tree->methodOrder[30] = 'delete';

/* Search. */
$lang->resource->search = new stdclass();
$lang->resource->search->buildForm   = 'buildForm';
$lang->resource->search->buildQuery  = 'buildQuery';
$lang->resource->search->saveQuery   = 'saveQuery';
$lang->resource->search->deleteQuery = 'deleteQuery';
$lang->resource->search->index       = 'index';
$lang->resource->search->buildIndex  = 'buildIndex';

$lang->search->methodOrder[5]  = 'buildForm';
$lang->search->methodOrder[10] = 'buildQuery';
$lang->search->methodOrder[15] = 'saveQuery';
$lang->search->methodOrder[20] = 'deleteQuery';
$lang->search->methodOrder[30] = 'index';
$lang->search->methodOrder[35] = 'buildIndex';

/* Admin. */
$lang->resource->admin = new stdclass();
$lang->resource->admin->index           = 'index';
$lang->resource->admin->safe            = 'safeIndex';
$lang->resource->admin->checkWeak       = 'checkWeak';
$lang->resource->admin->sso             = 'ssoAction';
$lang->resource->admin->register        = 'register';
$lang->resource->admin->resetPWDSetting = 'resetPWDSetting';
$lang->resource->admin->tableEngine     = 'tableEngine';

$lang->admin->methodOrder[0]  = 'index';
$lang->admin->methodOrder[10] = 'safeIndex';
$lang->admin->methodOrder[15] = 'checkWeak';
$lang->admin->methodOrder[20] = 'sso';
$lang->admin->methodOrder[25] = 'register';
$lang->admin->methodOrder[35] = 'resetPWDSetting';
$lang->admin->methodOrder[40] = 'tableEngine';

$lang->resource->file = new stdclass();
$lang->resource->file->download     = 'download';
$lang->resource->file->preview      = 'preview';
$lang->resource->file->edit         = 'edit';
$lang->resource->file->delete       = 'delete';
$lang->resource->file->uploadImages = 'uploadImages';
$lang->resource->file->setPublic    = 'setPublic';

$lang->file->methodOrder[5]  = 'download';
$lang->file->methodOrder[10] = 'preview';
$lang->file->methodOrder[15] = 'edit';
$lang->file->methodOrder[20] = 'delete';
$lang->file->methodOrder[25] = 'uploadImages';
$lang->file->methodOrder[30] = 'setPublic';

$lang->resource->charter = new stdclass();
$lang->resource->charter->browse                  = 'browse';
$lang->resource->charter->create                  = 'create';
$lang->resource->charter->edit                    = 'editAction';
$lang->resource->charter->view                    = 'view';
$lang->resource->charter->delete                  = 'deleteAction';
$lang->resource->charter->review                  = 'approval';
$lang->resource->charter->approvalcancel          = 'approvalCancelAction';
$lang->resource->charter->close                   = 'closeAction';
$lang->resource->charter->projectApproval         = 'projectApproval';
$lang->resource->charter->completionApproval      = 'completionApproval';
$lang->resource->charter->cancelProjectApproval   = 'cancelProjectApproval';
$lang->resource->charter->activateProjectApproval = 'activateProjectApproval';
$lang->resource->charter->approvalProgress        = 'approvalProgressAction';

$lang->resource->demandpool = new stdclass();
$lang->resource->demandpool->browse   = 'browse';
$lang->resource->demandpool->create   = 'create';
$lang->resource->demandpool->edit     = 'edit';
$lang->resource->demandpool->view     = 'view';
$lang->resource->demandpool->track    = 'track';
$lang->resource->demandpool->close    = 'close';
$lang->resource->demandpool->activate = 'activate';
$lang->resource->demandpool->delete   = 'delete';

$lang->resource->demand = new stdclass();
$lang->resource->demand->browse              = 'browse';
$lang->resource->demand->create              = 'create';
$lang->resource->demand->batchCreate         = 'batchCreate';
$lang->resource->demand->edit                = 'edit';
$lang->resource->demand->view                = 'view';
$lang->resource->demand->assignTo            = 'assignedTo';
$lang->resource->demand->change              = 'change';
$lang->resource->demand->review              = 'review';
$lang->resource->demand->submitReview        = 'submitReview';
$lang->resource->demand->recall              = 'recall';
$lang->resource->demand->delete              = 'delete';
$lang->resource->demand->close               = 'close';
$lang->resource->demand->activate            = 'activate';
$lang->resource->demand->distribute          = 'distribute';
$lang->resource->demand->retract             = 'retract';
$lang->resource->demand->export              = 'export';
$lang->resource->demand->exportTemplate      = 'exportTemplate';
$lang->resource->demand->import              = 'import';
$lang->resource->demand->processDemandChange = 'processDemandChange';

$lang->resource->roadmap = new stdclass();
$lang->resource->roadmap->browse           = 'browse';
$lang->resource->roadmap->view             = 'view';
$lang->resource->roadmap->linkUR           = 'linkUR';
$lang->resource->roadmap->create           = 'create';
$lang->resource->roadmap->edit             = 'edit';
$lang->resource->roadmap->close            = 'close';
$lang->resource->roadmap->delete           = 'delete';
$lang->resource->roadmap->activate         = 'activate';
$lang->resource->roadmap->unlinkUR         = 'unlinkUR';
$lang->resource->roadmap->batchUnlinkUR    = 'batchUnlinkUR';

/* Mail. */
$lang->resource->mail = new stdclass();
$lang->resource->mail->index       = 'index';
$lang->resource->mail->detect      = 'detectAction';
$lang->resource->mail->edit        = 'edit';
$lang->resource->mail->save        = 'saveAction';
$lang->resource->mail->test        = 'test';
$lang->resource->mail->reset       = 'resetAction';
$lang->resource->mail->browse      = 'browse';
$lang->resource->mail->delete      = 'delete';
$lang->resource->mail->resend      = 'resendAction';
$lang->resource->mail->batchDelete = 'batchDelete';

$lang->mail->methodOrder[5]  = 'index';
$lang->mail->methodOrder[10] = 'detect';
$lang->mail->methodOrder[15] = 'edit';
$lang->mail->methodOrder[20] = 'save';
$lang->mail->methodOrder[25] = 'test';
$lang->mail->methodOrder[30] = 'reset';
$lang->mail->methodOrder[35] = 'browse';
$lang->mail->methodOrder[40] = 'delete';
$lang->mail->methodOrder[45] = 'batchDelete';
$lang->mail->methodOrder[50] = 'resend';

$lang->resource->message = new stdclass();
$lang->resource->message->index   = 'index';
$lang->resource->message->browser = 'browser';
$lang->resource->message->setting = 'setting';

$lang->message->methodOrder[5]  = 'index';
$lang->message->methodOrder[10] = 'browser';
$lang->message->methodOrder[15] = 'setting';

/* Webhook. */
$lang->resource->webhook = new stdclass();
$lang->resource->webhook->browse     = 'list';
$lang->resource->webhook->create     = 'create';
$lang->resource->webhook->edit       = 'edit';
$lang->resource->webhook->delete     = 'delete';
$lang->resource->webhook->log        = 'logAction';
$lang->resource->webhook->bind       = 'bind';
$lang->resource->webhook->chooseDept = 'chooseDept';

$lang->webhook->methodOrder[5]  = 'browse';
$lang->webhook->methodOrder[10] = 'create';
$lang->webhook->methodOrder[15] = 'edit';
$lang->webhook->methodOrder[20] = 'delete';
$lang->webhook->methodOrder[25] = 'log';
$lang->webhook->methodOrder[30] = 'bind';
$lang->webhook->methodOrder[35] = 'chooseDept';

$lang->resource->sms        = new stdclass();
$lang->resource->sms->index = 'index';
$lang->resource->sms->test  = 'test';
$lang->resource->sms->reset = 'reset';

$lang->resource->my->effort         = 'effortAction';
$lang->resource->company->effort    = 'companyEffort';
$lang->resource->company->alleffort = 'allEffort';

if(!isset($lang->resource->effort)) $lang->resource->effort = new stdclass();
$lang->resource->effort->batchCreate     = 'batchCreate';
$lang->resource->effort->createForObject = 'createForObject';
$lang->resource->effort->edit            = 'edit';
$lang->resource->effort->batchEdit       = 'batchEdit';
$lang->resource->effort->view            = 'view';
$lang->resource->effort->delete          = 'delete';
$lang->resource->effort->export          = 'exportAction';
$lang->resource->effort->calendar        = 'calendarAction';

$lang->resource->user->effort = 'effort';

/* Action. */
$lang->resource->action = new stdclass();
$lang->resource->action->trash    = 'trashAction';
$lang->resource->action->undelete = 'undeleteAction';
$lang->resource->action->hideOne  = 'hideOneAction';
$lang->resource->action->hideAll  = 'hideAll';
$lang->resource->action->comment  = 'comment';
$lang->resource->action->editComment = 'editComment';

$lang->action->methodOrder[5]  = 'trash';
$lang->action->methodOrder[10] = 'undelete';
$lang->action->methodOrder[15] = 'hideOne';
$lang->action->methodOrder[20] = 'hideAll';
$lang->action->methodOrder[25] = 'comment';
$lang->action->methodOrder[30] = 'editComment';

$lang->resource->backup = new stdclass();
$lang->resource->backup->index       = 'index';
$lang->resource->backup->backup      = 'backup';
$lang->resource->backup->restore     = 'restoreAction';
$lang->resource->backup->change      = 'change';
$lang->resource->backup->delete      = 'delete';
$lang->resource->backup->setting     = 'settingAction';
$lang->resource->backup->rmPHPHeader = 'rmPHPHeader';

$lang->backup->methodOrder[5]  = 'index';
$lang->backup->methodOrder[10] = 'backup';
$lang->backup->methodOrder[15] = 'restore';
$lang->backup->methodOrder[20] = 'delete';
$lang->backup->methodOrder[25] = 'setting';
$lang->backup->methodOrder[30] = 'rmPHPHeader';

$lang->resource->market = new stdclass();
$lang->resource->market->browse = 'browse';
$lang->resource->market->create = 'create';
$lang->resource->market->edit   = 'edit';
$lang->resource->market->view   = 'view';
$lang->resource->market->delete = 'delete';

$lang->market->methodOrder[5]  = 'browse';
$lang->market->methodOrder[10] = 'create';
$lang->market->methodOrder[15] = 'edit';
$lang->market->methodOrder[20] = 'view';
$lang->market->methodOrder[25] = 'delete';

$lang->resource->marketreport = new stdclass();
$lang->resource->marketreport->all     = 'all';
$lang->resource->marketreport->browse  = 'browse';
$lang->resource->marketreport->create  = 'create';
$lang->resource->marketreport->edit    = 'edit';
$lang->resource->marketreport->view    = 'view';
$lang->resource->marketreport->delete  = 'delete';
$lang->resource->marketreport->publish = 'publish';

$lang->marketreport->methodOrder[5]  = 'all';
$lang->marketreport->methodOrder[10] = 'browse';
$lang->marketreport->methodOrder[15] = 'create';
$lang->marketreport->methodOrder[20] = 'edit';
$lang->marketreport->methodOrder[25] = 'view';
$lang->marketreport->methodOrder[30] = 'delete';
$lang->marketreport->methodOrder[35] = 'publish';

$lang->resource->marketresearch = new stdclass();
$lang->resource->marketresearch->all           = 'all';
$lang->resource->marketresearch->browse        = 'browse';
$lang->resource->marketresearch->create        = 'create';
$lang->resource->marketresearch->edit          = 'edit';
$lang->resource->marketresearch->view          = 'view';
$lang->resource->marketresearch->activate      = 'activate';
$lang->resource->marketresearch->start         = 'start';
$lang->resource->marketresearch->close         = 'close';
$lang->resource->marketresearch->team          = 'teamAction';
$lang->resource->marketresearch->manageMembers = 'manageMembers';
$lang->resource->marketresearch->unlinkMember  = 'unlinkMember';
$lang->resource->marketresearch->reports       = 'reports';
$lang->resource->marketresearch->delete        = 'delete';
$lang->resource->marketresearch->task          = 'task';
$lang->resource->marketresearch->createStage   = 'createStage';
$lang->resource->marketresearch->editStage     = 'editStage';
$lang->resource->marketresearch->batchStage    = 'batchStage';
$lang->resource->marketresearch->startStage    = 'startStage';
$lang->resource->marketresearch->deleteStage   = 'deleteStage';
$lang->resource->marketresearch->closeStage    = 'closeStage';
$lang->resource->marketresearch->activateStage = 'activateStage';

$lang->marketresearch->methodOrder[5]   = 'all';
$lang->marketresearch->methodOrder[10]  = 'browse';
$lang->marketresearch->methodOrder[15]  = 'create';
$lang->marketresearch->methodOrder[20]  = 'edit';
$lang->marketresearch->methodOrder[25]  = 'view';
$lang->marketresearch->methodOrder[30]  = 'activate';
$lang->marketresearch->methodOrder[35]  = 'start';
$lang->marketresearch->methodOrder[40]  = 'close';
$lang->marketresearch->methodOrder[45]  = 'team';
$lang->marketresearch->methodOrder[50]  = 'manageMembers';
$lang->marketresearch->methodOrder[55]  = 'unlinkMember';
$lang->marketresearch->methodOrder[60]  = 'reports';
$lang->marketresearch->methodOrder[65]  = 'delete';
$lang->marketresearch->methodOrder[70]  = 'task';
$lang->marketresearch->methodOrder[75]  = 'createStage';
$lang->marketresearch->methodOrder[80]  = 'editStage';
$lang->marketresearch->methodOrder[85]  = 'batchStage';
$lang->marketresearch->methodOrder[90]  = 'deleteStage';
$lang->marketresearch->methodOrder[95]  = 'closeStage';
$lang->marketresearch->methodOrder[100] = 'activateStage';

$lang->resource->researchtask = new stdclass();
$lang->resource->researchtask->create         = 'create';
$lang->resource->researchtask->edit           = 'edit';
$lang->resource->researchtask->close          = 'close';
$lang->resource->researchtask->start          = 'start';
$lang->resource->researchtask->finish         = 'finish';
$lang->resource->researchtask->delete         = 'delete';
$lang->resource->researchtask->cancel         = 'cancel';
$lang->resource->researchtask->activate       = 'activate';
$lang->resource->researchtask->assignTo       = 'assignTo';
$lang->resource->researchtask->view           = 'view';
$lang->resource->researchtask->batchCreate    = 'batchCreate';
$lang->resource->researchtask->recordWorkhour = 'recordWorkhour';
$lang->resource->researchtask->editEffort     = 'editEffort';
$lang->resource->researchtask->deleteWorkhour = 'deleteWorkhour';

$lang->researchtask->methodOrder[5]  = 'create';
$lang->researchtask->methodOrder[10] = 'close';
$lang->researchtask->methodOrder[15] = 'start';
$lang->researchtask->methodOrder[20] = 'finish';
$lang->researchtask->methodOrder[25] = 'delete';
$lang->researchtask->methodOrder[30] = 'cancel';
$lang->researchtask->methodOrder[35] = 'activate';
$lang->researchtask->methodOrder[40] = 'assignTo';
$lang->researchtask->methodOrder[45] = 'view';
$lang->researchtask->methodOrder[50] = 'recordWorkhour';
$lang->researchtask->methodOrder[55] = 'editEffort';
$lang->researchtask->methodOrder[60] = 'deleteWorkhour';
$lang->researchtask->methodOrder[65] = 'batchCreate';
$lang->researchtask->methodOrder[70] = 'edit';

/* Feedback */
$lang->resource->feedback = new stdclass();
$lang->resource->feedback->create         = 'create';
$lang->resource->feedback->batchCreate    = 'batchCreate';
$lang->resource->feedback->edit           = 'edit';
$lang->resource->feedback->editOthers     = 'editOthers';
$lang->resource->feedback->adminView      = 'adminView';
$lang->resource->feedback->admin          = 'admin';
$lang->resource->feedback->assignTo       = 'assignAction';
$lang->resource->feedback->toTodo         = 'toTodo';
$lang->resource->feedback->toUserStory    = 'toUserStory';
$lang->resource->feedback->toDemand       = 'toDemand';
$lang->resource->feedback->review         = 'reviewAction';
$lang->resource->feedback->comment        = 'comment';
$lang->resource->feedback->reply          = 'reply';
$lang->resource->feedback->ask            = 'ask';
$lang->resource->feedback->close          = 'closeAction';
$lang->resource->feedback->delete         = 'delete';
$lang->resource->feedback->activate       = 'activate';
$lang->resource->feedback->export         = 'exportAction';
$lang->resource->feedback->batchEdit      = 'batchEdit';
$lang->resource->feedback->batchClose     = 'batchClose';
$lang->resource->feedback->batchReview    = 'batchReview';
$lang->resource->feedback->batchAssignTo  = 'batchAssignTo';
$lang->resource->feedback->products       = 'products';
$lang->resource->feedback->manageProduct  = 'manageProduct';
$lang->resource->feedback->import         = 'import';
$lang->resource->feedback->exportTemplate = 'exportTemplate';
$lang->resource->feedback->syncProduct    = 'syncProduct';
$lang->resource->feedback->productSetting = 'productSetting';
if($config->enableER) $lang->resource->feedback->toEpic = 'toEpic';

/* AI methods. */
$lang->resource->ai = new stdclass();
$lang->resource->ai->models              = 'modelBrowse';
$lang->resource->ai->modelView           = 'modelView';
$lang->resource->ai->modelCreate         = 'modelCreate';
$lang->resource->ai->modelEdit           = 'modelEdit';
$lang->resource->ai->modelEnable         = 'modelEnable';
$lang->resource->ai->modelDisable        = 'modelDisable';
$lang->resource->ai->modelDelete         = 'modelDelete';
$lang->resource->ai->modelTestConnection = 'modelTestConnection';
$lang->resource->ai->chat                = 'chat';

/* Epic. */
$lang->resource->epic = new stdclass();
$lang->resource->epic->create             = 'create';
$lang->resource->epic->batchCreate        = 'batchCreate';
$lang->resource->epic->edit               = 'editAction';
$lang->resource->epic->batchEdit          = 'batchEdit';
$lang->resource->epic->linkStory          = 'linkStory';
$lang->resource->epic->export             = 'exportAction';
$lang->resource->epic->delete             = 'deleteAction';
$lang->resource->epic->view               = 'view';
$lang->resource->epic->change             = 'changeAction';
$lang->resource->epic->review             = 'reviewAction';
$lang->resource->epic->submitReview       = 'submitReview';
$lang->resource->epic->batchReview        = 'batchReview';
$lang->resource->epic->recall             = 'recall';
$lang->resource->epic->assignTo           = 'assignAction';
$lang->resource->epic->close              = 'closeAction';
$lang->resource->epic->batchClose         = 'batchClose';
$lang->resource->epic->activate           = 'activateAction';
$lang->resource->epic->report             = 'reportAction';
$lang->resource->epic->batchChangeBranch  = 'batchChangeBranch';
$lang->resource->epic->batchAssignTo      = 'batchAssignTo';
$lang->resource->epic->batchChangeModule  = 'batchChangeModule';
$lang->resource->epic->batchChangeParent  = 'batchChangeParent';
$lang->resource->epic->batchChangeGrade   = 'batchChangeGrade';
$lang->resource->epic->batchChangeRoadmap = 'batchChangeRoadmap';
$lang->resource->epic->import             = 'importCase';
$lang->resource->epic->exportTemplate     = 'exportTemplate';
$lang->resource->epic->processStoryChange = 'processStoryChange';

$lang->epic->methodOrder[5]   = 'create';
$lang->epic->methodOrder[10]  = 'batchCreate';
$lang->epic->methodOrder[15]  = 'edit';
$lang->epic->methodOrder[16]  = 'batchEdit';
$lang->epic->methodOrder[17]  = 'linkStory';
$lang->epic->methodOrder[20]  = 'export';
$lang->epic->methodOrder[25]  = 'delete';
$lang->epic->methodOrder[30]  = 'view';
$lang->epic->methodOrder[35]  = 'change';
$lang->epic->methodOrder[40]  = 'review';
$lang->epic->methodOrder[44]  = 'submitReview';
$lang->epic->methodOrder[45]  = 'batchReview';
$lang->epic->methodOrder[50]  = 'recall';
$lang->epic->methodOrder[55]  = 'close';
$lang->epic->methodOrder[60]  = 'batchClose';
$lang->epic->methodOrder[65]  = 'assignTo';
$lang->epic->methodOrder[70]  = 'batchAssignTo';
$lang->epic->methodOrder[75]  = 'activate';
$lang->epic->methodOrder[80]  = 'report';
$lang->epic->methodOrder[90]  = 'batchChangeBranch';
$lang->epic->methodOrder[95]  = 'batchChangeModule';
$lang->epic->methodOrder[100] = 'batchChangeParent';
$lang->epic->methodOrder[105] = 'batchChangeGrade';
$lang->epic->methodOrder[110] = 'batchChangeRoadmap';
$lang->epic->methodOrder[115] = 'processStoryChange';

/* workflow */
$lang->resource->workflow = new stdclass();
$lang->resource->workflow->browseFlow        = 'browseFlow';
$lang->resource->workflow->browseDB          = 'browseDB';
$lang->resource->workflow->create            = 'create';
$lang->resource->workflow->copy              = 'copy';
$lang->resource->workflow->edit              = 'edit';
$lang->resource->workflow->backup            = 'backup';
$lang->resource->workflow->upgrade           = 'upgradeAction';
$lang->resource->workflow->view              = 'view';
$lang->resource->workflow->delete            = 'delete';
$lang->resource->workflow->flowchart         = 'flowchart';
$lang->resource->workflow->ui                = 'ui';
$lang->resource->workflow->release           = 'release';
$lang->resource->workflow->deactivate        = 'deactivate';
$lang->resource->workflow->activate          = 'activate';
if(in_array($config->edition, array('max', 'ipd'))) $lang->resource->workflow->setApproval = 'setApproval';
$lang->resource->workflow->setFulltextSearch = 'setFulltextSearch';
$lang->resource->workflow->setJS             = 'setJS';
$lang->resource->workflow->setCSS            = 'setCSS';

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

$lang->resource->contact            = new stdclass();
$lang->resource->programstakeholder = new stdclass();
$lang->resource->researchplan       = new stdclass();
$lang->resource->workestimation     = new stdclass();
$lang->resource->gapanalysis        = new stdclass();
$lang->resource->executionview      = new stdclass();
$lang->resource->managespace        = new stdclass();
$lang->resource->systemteam         = new stdclass();
$lang->resource->systemschedule     = new stdclass();
$lang->resource->systemeffort       = new stdclass();
$lang->resource->systemdynamic      = new stdclass();
$lang->resource->systemcompany      = new stdclass();
$lang->resource->pipeline           = new stdclass();
$lang->resource->devopssetting      = new stdclass();
$lang->resource->featureswitch      = new stdclass();
$lang->resource->importdata         = new stdclass();
$lang->resource->systemsetting      = new stdclass();
$lang->resource->staffmanage        = new stdclass();
$lang->resource->modelconfig        = new stdclass();
$lang->resource->featureconfig      = new stdclass();
$lang->resource->doctemplate        = new stdclass();
$lang->resource->notifysetting      = new stdclass();
$lang->resource->bidesign           = new stdclass();
$lang->resource->personalsettings   = new stdclass();
$lang->resource->projectsettings    = new stdclass();
$lang->resource->dataaccess         = new stdclass();
$lang->resource->executiongantt     = new stdclass();
$lang->resource->executionkanban    = new stdclass();
$lang->resource->executionburn      = new stdclass();
$lang->resource->executioncfd       = new stdclass();
$lang->resource->executionstory     = new stdclass();
$lang->resource->executionqa        = new stdclass();
$lang->resource->executionsettings  = new stdclass();
$lang->resource->generalcomment     = new stdclass();
$lang->resource->generalping        = new stdclass();
$lang->resource->generaltemplate    = new stdclass();
$lang->resource->generaleffort      = new stdclass();
$lang->resource->productsettings    = new stdclass();
$lang->resource->projectreview      = new stdclass();
$lang->resource->projecttrack       = new stdclass();
$lang->resource->projectqa          = new stdclass();
