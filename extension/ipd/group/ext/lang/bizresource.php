<?php
$lang->moduleOrder[114] = 'dataview';
$lang->moduleOrder[115] = 'dimension';
$lang->moduleOrder[116] = 'metriclib';

global $app, $config;
/* Feedback */
$lang->resource->feedback = new stdclass();
$lang->resource->feedback->create            = 'create';
$lang->resource->feedback->batchCreate       = 'batchCreate';
$lang->resource->feedback->edit              = 'edit';
$lang->resource->feedback->editOthers        = 'editOthers';
if($config->vision == 'lite') $lang->resource->feedback->view   = 'view';
if($config->vision == 'lite') $lang->resource->feedback->browse = 'browse';
if($config->vision == 'rnd') $lang->resource->feedback->adminView = 'adminView';
if($config->vision == 'rnd') $lang->resource->feedback->admin     = 'admin';
$lang->resource->feedback->assignTo          = 'assignAction';
$lang->resource->feedback->toTask            = 'toTask';
$lang->resource->feedback->toTodo            = 'toTodo';
$lang->resource->feedback->toBug             = 'toBug';
$lang->resource->feedback->toStory           = 'toStory';
$lang->resource->feedback->toTicket          = 'toTicket';
if($config->URAndSR)  $lang->resource->feedback->toUserStory = 'toUserStory';
if($config->enableER) $lang->resource->feedback->toEpic      = 'toEpic';
$lang->resource->feedback->review            = 'reviewAction';
$lang->resource->feedback->comment           = 'comment';
$lang->resource->feedback->reply             = 'reply';
$lang->resource->feedback->ask               = 'ask';
$lang->resource->feedback->close             = 'closeAction';
$lang->resource->feedback->delete            = 'delete';
$lang->resource->feedback->activate          = 'activate';
$lang->resource->feedback->export            = 'exportAction';
$lang->resource->feedback->batchEdit         = 'batchEdit';
$lang->resource->feedback->batchClose        = 'batchClose';
$lang->resource->feedback->batchReview       = 'batchReview';
$lang->resource->feedback->batchAssignTo     = 'batchAssignTo';
$lang->resource->feedback->products          = 'products';
$lang->resource->feedback->manageProduct     = 'manageProduct';
$lang->resource->feedback->import            = 'import';
$lang->resource->feedback->exportTemplate    = 'exportTemplate';
$lang->resource->feedback->syncProduct       = 'syncProduct';
$lang->resource->feedback->productSetting    = 'productSetting';

/* Faq. */
$lang->resource->faq = new stdclass();
$lang->resource->faq->browse = 'browse';
$lang->resource->faq->create = 'create';
$lang->resource->faq->edit   = 'edit';
$lang->resource->faq->delete = 'delete';

$lang->resource->ticket = new stdclass();
$lang->resource->ticket->create         = 'create';
$lang->resource->ticket->edit           = 'edit';
$lang->resource->ticket->view           = 'view';
$lang->resource->ticket->browse         = 'browse';
$lang->resource->ticket->assignTo       = 'assign';
$lang->resource->ticket->createBug      = 'createBug';
$lang->resource->ticket->createStory    = 'createStory';
$lang->resource->ticket->start          = 'start';
$lang->resource->ticket->finish         = 'finish';
$lang->resource->ticket->close          = 'close';
$lang->resource->ticket->activate       = 'activate';
$lang->resource->ticket->delete         = 'delete';
$lang->resource->ticket->exportTemplate = 'exportTemplate';
$lang->resource->ticket->import         = 'import';
$lang->resource->ticket->export         = 'exportAction';
$lang->resource->ticket->batchCreate    = 'batchCreate';
$lang->resource->ticket->batchEdit      = 'batchEdit';
$lang->resource->ticket->batchActivate  = 'batchActivate';
$lang->resource->ticket->batchFinish    = 'batchFinish';
$lang->resource->ticket->batchClose     = 'batchClose';
$lang->resource->ticket->batchAssignTo  = 'batchAssignTo';
$lang->resource->ticket->syncProduct    = 'syncProduct';

$lang->resource->feedbackpriv = new stdclass();

$lang->resource->custom->libreoffice = 'libreOffice';

$lang->resource->custom->browserelation    = 'browseRelation';
$lang->resource->custom->createrelation    = 'createRelation';
$lang->resource->custom->editrelation      = 'editRelation';
$lang->resource->custom->deleterelation    = 'deleteRelation';
$lang->resource->custom->relateObject      = 'relateObject';
$lang->resource->custom->removeObjects     = 'removeObjects';
$lang->resource->custom->showRelationGraph = 'relationGraph';
$lang->resource->custom->setCharterInfo    = 'setCharterInfo';
$lang->resource->custom->resetCharterInfo  = 'resetCharterInfo';

$lang->custom->methodOrder[120] = 'browseRelation';
$lang->custom->methodOrder[121] = 'createRelation';
$lang->custom->methodOrder[122] = 'editRelation';
$lang->custom->methodOrder[123] = 'deleteRelation';
$lang->custom->methodOrder[124] = 'relateObject';
$lang->custom->methodOrder[125] = 'removeObjects';
$lang->custom->methodOrder[126] = 'showRelationGraph';
$lang->custom->methodOrder[127] = 'setCharterInfo';
$lang->custom->methodOrder[128] = 'resetCharterInfo';

$lang->group->package->relateObject = $lang->relateObject->common;
$lang->group->package->relation     = $lang->relation->common;
$lang->group->package->charter      = $lang->charter->common;

/* User */
$lang->resource->user->export         = 'export';
$lang->resource->user->exportTemplate = 'exportTemplate';

$lang->user->methodOrder[90] = 'export';
$lang->user->methodOrder[95] = 'exportTemplate';

/* Attend */
$lang->resource->attend = new stdclass();
$lang->resource->attend->department       = 'department';
$lang->resource->attend->company          = 'company';
$lang->resource->attend->browseReview     = 'browseReview';
$lang->resource->attend->review           = 'review';
$lang->resource->attend->export           = 'exportAction';
$lang->resource->attend->stat             = 'reportAction';
$lang->resource->attend->saveStat         = 'saveStatAction';
$lang->resource->attend->exportStat       = 'exportStat';
$lang->resource->attend->detail           = 'detailAction';
$lang->resource->attend->exportDetail     = 'exportDetail';
$lang->resource->attend->settings         = 'settings';
$lang->resource->attend->personalSettings = 'personalSettings';
$lang->resource->attend->setManager       = 'setManager';

$lang->resource->attend->personal         = 'personal';
$lang->resource->attend->edit             = 'editAction';

$lang->attend->methodOrder[5]  = 'department';
$lang->attend->methodOrder[10] = 'company';
$lang->attend->methodOrder[15] = 'browseReview';
$lang->attend->methodOrder[20] = 'review';
$lang->attend->methodOrder[25] = 'export';
$lang->attend->methodOrder[30] = 'stat';
$lang->attend->methodOrder[35] = 'saveStat';
$lang->attend->methodOrder[40] = 'exportStat';
$lang->attend->methodOrder[45] = 'detail';
$lang->attend->methodOrder[60] = 'exportDetail';
$lang->attend->methodOrder[65] = 'settings';
$lang->attend->methodOrder[70] = 'personalSettings';
$lang->attend->methodOrder[75] = 'setManager';

$lang->attend->methodOrder[80] = 'personal';
$lang->attend->methodOrder[85] = 'edit';

/* Leave */
$lang->resource->leave = new stdclass();
$lang->resource->leave->browseReview   = 'browseReview';
$lang->resource->leave->company        = 'companyAction';
$lang->resource->leave->review         = 'reviewAction';
$lang->resource->leave->export         = 'exportAction';
$lang->resource->leave->setReviewer    = 'setReviewerAction';
$lang->resource->leave->personalAnnual = 'personalAnnual';

$lang->resource->leave->personal     = 'personal';
$lang->resource->leave->create       = 'createAction';
$lang->resource->leave->edit         = 'editAction';
$lang->resource->leave->delete       = 'deleteAction';
$lang->resource->leave->view         = 'viewAction';
$lang->resource->leave->switchstatus = 'switchstatus';
$lang->resource->leave->back         = 'backAction';

$lang->leave->methodOrder[0]  = 'browseReview';
$lang->leave->methodOrder[5]  = 'company';
$lang->leave->methodOrder[10] = 'review';
$lang->leave->methodOrder[15] = 'export';
$lang->leave->methodOrder[20] = 'setReviewer';
$lang->leave->methodOrder[25] = 'personalAnnual';

$lang->leave->methodOrder[30] = 'personal';
$lang->leave->methodOrder[35] = 'create';
$lang->leave->methodOrder[40] = 'edit';
$lang->leave->methodOrder[45] = 'delete';
$lang->leave->methodOrder[50] = 'view';
$lang->leave->methodOrder[55] = 'switchstatus';
$lang->leave->methodOrder[60] = 'back';

/* Makeup */
$lang->resource->makeup = new stdclass();
$lang->resource->makeup->browseReview = 'browseReview';
$lang->resource->makeup->company      = 'companyAction';
$lang->resource->makeup->review       = 'reviewAction';
$lang->resource->makeup->export       = 'exportAction';
$lang->resource->makeup->setReviewer  = 'setReviewerAction';

$lang->resource->makeup->personal     = 'personal';
$lang->resource->makeup->create       = 'createAction';
$lang->resource->makeup->edit         = 'editAction';
$lang->resource->makeup->view         = 'viewAction';
$lang->resource->makeup->delete       = 'deleteAction';
$lang->resource->makeup->switchstatus = 'switchstatus';

$lang->makeup->methodOrder[0]  = 'browseReview';
$lang->makeup->methodOrder[5]  = 'company';
$lang->makeup->methodOrder[10] = 'review';
$lang->makeup->methodOrder[15] = 'export';
$lang->makeup->methodOrder[20] = 'setReviewer';

$lang->makeup->methodOrder[25]  = 'personal';
$lang->makeup->methodOrder[30]  = 'create';
$lang->makeup->methodOrder[35] = 'edit';
$lang->makeup->methodOrder[40] = 'view';
$lang->makeup->methodOrder[45] = 'delete';
$lang->makeup->methodOrder[50] = 'switchstatus';

/* Overtime */
$lang->resource->overtime = new stdclass();
$lang->resource->overtime->browseReview = 'browseReview';
$lang->resource->overtime->company      = 'companyAction';
$lang->resource->overtime->review       = 'reviewAction';
$lang->resource->overtime->export       = 'exportAction';
$lang->resource->overtime->setReviewer  = 'setReviewerAction';

$lang->resource->overtime->personal     = 'personal';
$lang->resource->overtime->create       = 'createAction';
$lang->resource->overtime->edit         = 'editAction';
$lang->resource->overtime->view         = 'viewAction';
$lang->resource->overtime->delete       = 'deleteAction';
$lang->resource->overtime->switchstatus = 'switchstatus';

$lang->overtime->methodOrder[0]  = 'browseReview';
$lang->overtime->methodOrder[5]  = 'company';
$lang->overtime->methodOrder[10] = 'review';
$lang->overtime->methodOrder[15] = 'export';
$lang->overtime->methodOrder[20] = 'setReviewer';

$lang->overtime->methodOrder[25]  = 'personal';
$lang->overtime->methodOrder[30]  = 'create';
$lang->overtime->methodOrder[35] = 'edit';
$lang->overtime->methodOrder[40] = 'view';
$lang->overtime->methodOrder[45] = 'delete';
$lang->overtime->methodOrder[50] = 'switchstatus';

/* Lieu */
$lang->resource->lieu = new stdclass();
$lang->resource->lieu->company      = 'companyAction';
$lang->resource->lieu->browseReview = 'browseReviewAction';
$lang->resource->lieu->review       = 'reviewAction';
$lang->resource->lieu->setReviewer  = 'setReviewerAction';

$lang->resource->lieu->personal     = 'personal';
$lang->resource->lieu->create       = 'createAction';
$lang->resource->lieu->edit         = 'editAction';
$lang->resource->lieu->delete       = 'deleteAction';
$lang->resource->lieu->view         = 'viewAction';
$lang->resource->lieu->switchstatus = 'switchstatus';

$lang->lieu->methodOrder[0]  = 'company';
$lang->lieu->methodOrder[5]  = 'browseReview';
$lang->lieu->methodOrder[10] = 'review';
$lang->lieu->methodOrder[15] = 'setReviewer';

$lang->lieu->methodOrder[20]  = 'personal';
$lang->lieu->methodOrder[25]  = 'create';
$lang->lieu->methodOrder[30] = 'edit';
$lang->lieu->methodOrder[35] = 'delete';
$lang->lieu->methodOrder[40] = 'view';
$lang->lieu->methodOrder[45] = 'switchstatus';

$lang->resource->officeapproval = new stdclass();
$lang->resource->officesetting  = new stdclass();
$lang->resource->datapermission = new stdclass();
$lang->resource->officeexport   = new stdclass();

/* Ops */
$lang->resource->tree->browsegroup = 'browsegroup';

$lang->host->methodOrder[45] = 'browsegroup';

$lang->resource->deploy = new stdclass();
$lang->resource->deploy->browse           = 'browseAction';
$lang->resource->deploy->create           = 'create';
$lang->resource->deploy->edit             = 'editAction';
$lang->resource->deploy->delete           = 'deleteAction';
$lang->resource->deploy->activate         = 'activateAction';
$lang->resource->deploy->finish           = 'finishAction';
$lang->resource->deploy->view             = 'view';
$lang->resource->deploy->cases            = 'casesAction';
$lang->resource->deploy->linkCases        = 'linkCases';
$lang->resource->deploy->unlinkCase       = 'unlinkCase';
$lang->resource->deploy->batchUnlinkCases = 'batchUnlinkCases';
$lang->resource->deploy->steps            = 'steps';
$lang->resource->deploy->manageStep       = 'manageStep';
$lang->resource->deploy->finishStep       = 'finishStep';
$lang->resource->deploy->assignTo         = 'assignAction';
$lang->resource->deploy->viewStep         = 'viewStep';
$lang->resource->deploy->publish          = 'publish';

$lang->deploy->methodOrder[5]  = 'browse';
$lang->deploy->methodOrder[10] = 'create';
$lang->deploy->methodOrder[15] = 'edit';
$lang->deploy->methodOrder[20] = 'delete';
$lang->deploy->methodOrder[25] = 'activate';
$lang->deploy->methodOrder[30] = 'finish';
$lang->deploy->methodOrder[35] = 'view';
$lang->deploy->methodOrder[40] = 'cases';
$lang->deploy->methodOrder[45] = 'linkCases';
$lang->deploy->methodOrder[50] = 'unlinkCase';
$lang->deploy->methodOrder[55] = 'batchUnlinkCases';
$lang->deploy->methodOrder[60] = 'steps';
$lang->deploy->methodOrder[65] = 'manageStep';
$lang->deploy->methodOrder[70] = 'finishStep';
$lang->deploy->methodOrder[75] = 'assignTo';
$lang->deploy->methodOrder[80] = 'viewStep';

$lang->resource->project->workflowGroup = 'workflowGroup';

$lang->resource->testtask->runDeployCase     = 'runDeployCase';
$lang->resource->testtask->deployCaseResults = 'deployCaseResults';

//$lang->resource->doc->diff             = 'diffAction';
$lang->resource->doc->mine2export      = 'mine2export';
$lang->resource->doc->product2export   = 'product2export';
$lang->resource->doc->project2export   = 'project2export';
$lang->resource->doc->custom2export    = 'custom2export';
$lang->resource->doc->execution2export = 'execution2export';

$lang->resource->conference = new stdclass();
$lang->resource->conference->admin = 'admin';

$lang->resource->watermark = new stdclass();
$lang->resource->watermark->index = 'index';

/* Train Course. */
$lang->resource->traincourse = new stdclass();
$lang->resource->traincourse->browse      = 'browseAction';
$lang->resource->traincourse->viewCourse  = 'viewCourse';
$lang->resource->traincourse->viewChapter = 'viewChapter';
$lang->resource->traincourse->admin       = 'adminAction';
/*
$lang->resource->traincourse->browseCategory = 'browseCategory';
$lang->resource->traincourse->categoryChildren = 'categoryChildren';
$lang->resource->traincourse->editCategory = 'editCategory';
$lang->resource->traincourse->deleteCategory = 'deleteCategory';
$lang->resource->traincourse->createCourse = 'createCourse';
$lang->resource->traincourse->editCourse = 'editCourse';
$lang->resource->traincourse->manageCourse = 'manageCourse';
 */

$lang->resource->traincourse->deleteCourse = 'deleteCourse';
$lang->resource->traincourse->changeStatus = 'changeStatus';

/*
$lang->resource->traincourse->manageChapter = 'manageChapter';
$lang->resource->traincourse->editChapter = 'editChapter';
$lang->resource->traincourse->deleteChapter = 'deleteChapter';
 */

$lang->resource->traincourse->uploadCourse = 'uploadCourse';
$lang->resource->traincourse->batchImport  = 'batchImport';
$lang->resource->traincourse->cloudImport  = 'cloudImport';

$lang->resource->traincourse->practice       = 'practiceAction';
$lang->resource->traincourse->practiceBrowse = 'practiceBrowse';
$lang->resource->traincourse->practiceView   = 'practiceView';
$lang->resource->traincourse->updatePractice = 'updatePractice';

$lang->traincourse->methodOrder[5]   = 'browse';
$lang->traincourse->methodOrder[10]  = 'viewcourse';
$lang->traincourse->methodOrder[15]  = 'viewchapter';
$lang->traincourse->methodOrder[20]  = 'practice';
$lang->traincourse->methodOrder[25]  = 'practiceBrowse';
$lang->traincourse->methodOrder[30]  = 'practiceView';
$lang->traincourse->methodOrder[35]  = 'updatePractice';
$lang->traincourse->methodOrder[40]  = 'admin';
$lang->traincourse->methodOrder[45]  = 'browseCategory';
$lang->traincourse->methodOrder[50]  = 'categoryChildren';
$lang->traincourse->methodOrder[55]  = 'editCategory';
$lang->traincourse->methodOrder[60]  = 'deleteCategory';
$lang->traincourse->methodOrder[65]  = 'createCourse';
$lang->traincourse->methodOrder[70]  = 'editCourse';
$lang->traincourse->methodOrder[75]  = 'manageCourse';
$lang->traincourse->methodOrder[80]  = 'deleteCourse';
$lang->traincourse->methodOrder[85]  = 'changeStatus';
$lang->traincourse->methodOrder[90]  = 'manageChapter';
$lang->traincourse->methodOrder[95]  = 'editChapter';
$lang->traincourse->methodOrder[100] = 'deleteChapter';
$lang->traincourse->methodOrder[105] = 'uploadCourse';
$lang->traincourse->methodOrder[110] = 'batchImport';
$lang->traincourse->methodOrder[115] = 'cloudImport';

$lang->resource->api->export = 'export';

$lang->resource->dimension = new stdclass();
$lang->resource->dimension->browse = 'browse';
$lang->resource->dimension->create = 'create';
$lang->resource->dimension->edit   = 'edit';
$lang->resource->dimension->delete = 'delete';

$lang->dimension->methodOrder[0]  = 'browse';
$lang->dimension->methodOrder[5]  = 'create';
$lang->dimension->methodOrder[10] = 'edit';
$lang->dimension->methodOrder[15] = 'delete';

$lang->resource->screen->create  = 'create';
$lang->resource->screen->edit    = 'editScreen';
$lang->resource->screen->design  = 'designScreen';
$lang->resource->screen->publish = 'publishScreen';
$lang->resource->screen->delete  = 'deleteScreen';

$lang->screen->methodOrder[6]  = 'create';
$lang->screen->methodOrder[7]  = 'edit';
$lang->screen->methodOrder[8]  = 'design';
$lang->screen->methodOrder[9]  = 'publish';
$lang->screen->methodOrder[10] = 'delete';

$lang->resource->pivot->browse            = 'browseAction';
$lang->resource->pivot->preview           = 'preview';
$lang->resource->pivot->create            = 'create';
$lang->resource->pivot->edit              = 'edit';
$lang->resource->pivot->design            = 'design';
$lang->resource->pivot->delete            = 'delete';
$lang->resource->pivot->export            = 'export';
$lang->resource->pivot->showProduct       = 'showProduct';
$lang->resource->pivot->showProject       = 'showProject';
$lang->resource->pivot->projectDeviation  = 'projectDeviation';
$lang->resource->pivot->productSummary    = 'productSummary';
$lang->resource->pivot->bugCreate         = 'bugCreate';
$lang->resource->pivot->bugAssign         = 'bugAssign';
$lang->resource->pivot->workload          = 'workload';
$lang->resource->pivot->casesrun          = 'casesrun';
$lang->resource->pivot->storyLinkedBug    = 'storyLinkedBug';
$lang->resource->pivot->testcase          = 'testcase';
$lang->resource->pivot->build             = 'build';
$lang->resource->pivot->workSummary       = 'workSummary';
$lang->resource->pivot->roadmap           = 'roadmap';
$lang->resource->pivot->productInvest     = 'productInvest';
$lang->resource->pivot->bugSummary        = 'bugSummary';
$lang->resource->pivot->bugAssignSummary  = 'bugAssignSummary';
$lang->resource->pivot->workAssignSummary = 'workAssignSummary';
if(!helper::hasFeature('product_roadmap')) unset($lang->resource->pivot->roadmap);

$lang->pivot->methodOrder[1]  = 'browse';
$lang->pivot->methodOrder[2]  = 'preview';
$lang->pivot->methodOrder[3]  = 'create';
$lang->pivot->methodOrder[4]  = 'edit';
$lang->pivot->methodOrder[5]  = 'design';
$lang->pivot->methodOrder[6]  = 'delete';
$lang->pivot->methodOrder[7]  = 'export';
$lang->pivot->methodOrder[8]  = 'showProduct';
$lang->pivot->methodOrder[9]  = 'showProject';
$lang->pivot->methodOrder[10] = 'projectDeviation';
$lang->pivot->methodOrder[11] = 'productSummary';
$lang->pivot->methodOrder[12] = 'bugCreate';
$lang->pivot->methodOrder[13] = 'bugAssign';
$lang->pivot->methodOrder[14] = 'workload';
$lang->pivot->methodOrder[15] = 'casesrun';
$lang->pivot->methodOrder[16] = 'storyLinkedBug';
$lang->pivot->methodOrder[17] = 'testcase';
$lang->pivot->methodOrder[18] = 'build';
$lang->pivot->methodOrder[19] = 'workSummary';
$lang->pivot->methodOrder[20] = 'roadmap';
$lang->pivot->methodOrder[21] = 'productInvest';
$lang->pivot->methodOrder[22] = 'bugSummary';
$lang->pivot->methodOrder[23] = 'bugAssignSummary';
$lang->pivot->methodOrder[24] = 'workAssignSummary';
$lang->pivot->methodOrder[25] = 'roadmap';

$lang->resource->chart->browse  = 'browseAction';
$lang->resource->chart->preview = 'preview';
$lang->resource->chart->create  = 'create';
$lang->resource->chart->edit    = 'edit';
$lang->resource->chart->design  = 'design';
$lang->resource->chart->delete  = 'delete';
$lang->resource->chart->export  = 'export';

$lang->chart->methodOrder[0]  = 'browse';
$lang->chart->methodOrder[5]  = 'preview';
$lang->chart->methodOrder[10] = 'create';
$lang->chart->methodOrder[15] = 'edit';
$lang->chart->methodOrder[20] = 'design';
$lang->chart->methodOrder[25] = 'delete';
$lang->chart->methodOrder[30] = 'export';

$lang->resource->dataview = new stdclass();
$lang->resource->dataview->browse = 'browsePriv';
$lang->resource->dataview->create = 'createPriv';
$lang->resource->dataview->edit   = 'editPriv';
$lang->resource->dataview->query  = 'designPriv';
$lang->resource->dataview->delete = 'deletePriv';
$lang->resource->dataview->export = 'export';

$lang->resource->metriclib = new stdclass();
$lang->resource->metriclib->browse  = 'browse';
$lang->resource->metriclib->details = 'libraryDetails';

$lang->metriclib->methodOrder[10] = 'browse';

$lang->resource->metric->browse      = 'browse';
$lang->resource->metric->create      = 'create';
$lang->resource->metric->edit        = 'edit';
$lang->resource->metric->view        = 'view';
$lang->resource->metric->implement   = 'implementAction';
$lang->resource->metric->delist      = 'delistAction';
$lang->resource->metric->delete      = 'deleteAction';
$lang->resource->metric->details     = 'detailsAction';
$lang->resource->metric->recalculate = 'recalculateAction';

$lang->metric->methodOrder[10] = 'browse';
$lang->metric->methodOrder[15] = 'create';
$lang->metric->methodOrder[20] = 'edit';
$lang->metric->methodOrder[25] = 'view';
$lang->metric->methodOrder[30] = 'implement';
$lang->metric->methodOrder[35] = 'delist';
$lang->metric->methodOrder[40] = 'delete';
$lang->metric->methodOrder[40] = 'recalculate';

$lang->resource->ai->createPrompt           = 'promptCreate';
$lang->resource->ai->promptEdit             = 'promptEdit';
$lang->resource->ai->promptDelete           = 'promptDelete';
$lang->resource->ai->promptAssignRole       = 'promptAssignRole';
$lang->resource->ai->promptSelectDataSource = 'promptSelectDataSource';
$lang->resource->ai->promptSetPurpose       = 'promptSetPurpose';
$lang->resource->ai->promptSetTargetForm    = 'promptSetTargetForm';
$lang->resource->ai->promptFinalize         = 'promptFinalize';
$lang->resource->ai->promptAudit            = 'promptAudit';
$lang->resource->ai->roleTemplates          = 'roleTemplates';

$lang->resource->product->exportTrack = 'exportTrack';
$lang->product->methodOrder[] = 'exportTrack';

$lang->resource->projectstory->exportTrack = 'exportTrack';
$lang->projectstory->methodOrder[] = 'exportTrack';

$inUpgrade = (defined('IN_UPGRADE') and IN_UPGRADE);
if(!$inUpgrade)
{
    if(!helper::hasFeature('OA'))
    {
        unset($lang->resource->attend);
        unset($lang->resource->leave);
        unset($lang->resource->makeup);
        unset($lang->resource->overtime);
        unset($lang->resource->lieu);
    }
    if(!helper::hasFeature('deploy'))
    {
        unset($lang->resource->deploy);
        unset($lang->resource->host);
        unset($lang->resource->vm);
        unset($lang->resource->serverroom);
        unset($lang->resource->account);
        unset($lang->resource->ops);
    }
    if(!helper::hasFeature('traincourse')) unset($lang->resource->traincourse);
}

unset($lang->resource->workflowrule->view);

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

if($config->systemMode == 'light')
{
    unset($lang->resource->charter);
    unset($lang->resource->custom->setCharterInfo, $lang->resource->custom->resetCharterInfo);
}

$lang->resource->approvalflow = new stdclass();
$lang->resource->approvalflow->browse     = 'browse';
$lang->resource->approvalflow->view       = 'view';
$lang->resource->approvalflow->design     = 'design';
$lang->resource->approvalflow->role       = 'roleList';
$lang->resource->approvalflow->createRole = 'createRole';
$lang->resource->approvalflow->editRole   = 'editRole';
$lang->resource->approvalflow->deleteRole = 'deleteRole';
