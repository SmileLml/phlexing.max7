<?php
/**
 * The issue module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->issue->resolvedBy        = 'Resolved By';
$lang->issue->project           = 'Linked' . $lang->projectCommon;
$lang->issue->title             = 'Issue Name';
$lang->issue->risk              = 'Link Risk';
$lang->issue->desc              = 'Description';
$lang->issue->pri               = 'Priority';
$lang->issue->priAB             = 'P';
$lang->issue->severity          = 'Severity';
$lang->issue->type              = 'Type';
$lang->issue->execution         = $lang->projectCommon;
$lang->issue->effectedArea      = 'Impact';
$lang->issue->activity          = 'Activities';
$lang->issue->deadline          = 'Deadline';
$lang->issue->resolution        = 'Resolution';
$lang->issue->resolutionComment = 'Comment';
$lang->issue->resolvedDate      = 'Resolved Date';
$lang->issue->noAssigned        = 'Unassigned';
$lang->issue->status            = 'Result';
$lang->issue->lib               = 'Issue lib';
$lang->issue->approver          = 'Approver';
$lang->issue->createdBy         = 'CreatedBy';
$lang->issue->createdDate       = 'CreatedDate';
$lang->issue->owner             = 'Owner';
$lang->issue->editedBy          = 'EditedBy';
$lang->issue->editedDate        = 'EditedDate';
$lang->issue->activateBy        = 'ActivatedBy';
$lang->issue->activateDate      = 'ActivatedDate';
$lang->issue->closedBy          = 'ClosedBy';
$lang->issue->closedDate        = 'ClosedDate';
$lang->issue->assignedTo        = 'AssignedTo';
$lang->issue->assignedBy        = 'AssignedBy';
$lang->issue->assignedDate      = 'AssignedDate';
$lang->issue->id                = 'ID';
$lang->issue->deleted           = 'Deleted';
$lang->issue->files             = 'Files';
$lang->issue->allLib            = 'All Issue Library';

$lang->issue->browse           = 'List';
$lang->issue->view             = 'Issue Details';
$lang->issue->close            = 'Closed';
$lang->issue->cancel           = 'Cancel';
$lang->issue->confirm          = 'Confirm';
$lang->issue->resolve          = 'Resolved';
$lang->issue->delete           = 'Delete';
$lang->issue->search           = 'Search';
$lang->issue->basicInfo        = 'Basic Information';
$lang->issue->lifeTime         = 'Issue Life';
$lang->issue->legendMisc       = 'Misc.';
$lang->issue->linkedRisk       = 'Linked Risk';
$lang->issue->activate         = 'Activate';
$lang->issue->assignTo         = 'Assign';
$lang->issue->create           = 'Create Issue';
$lang->issue->edit             = 'Edit Issue';
$lang->issue->export           = 'Export Data';
$lang->issue->exportAction     = 'Export Issue';
$lang->issue->batchCreate      = 'Batch Create';
$lang->issue->effort           = 'Effort';
$lang->issue->consumed         = 'Consumed';
$lang->issue->importToLib      = 'Import To Lib';
$lang->issue->batchImportToLib = 'Batch import to lib';
$lang->issue->isExist          = 'This issue is already added in the Issue Lib. Please do not add it again.';

$lang->issue->editAction      = 'Edit issue';
$lang->issue->deleteAction    = 'Delete issue';
$lang->issue->confirmAction   = 'Confirm issue';
$lang->issue->assignToAction  = 'Assign issue';
$lang->issue->closeAction     = 'Close issue';
$lang->issue->cancelAction    = 'Cancel issue';
$lang->issue->activateAction  = 'Activate issue';
$lang->issue->resolveAction   = 'Resolve issue';

$lang->issue->importFromLib = 'Import From Library';
$lang->issue->import        = 'Import';
$lang->issue->noIssueLib    = 'No issue library exists. Please create one first.';

$lang->issue->labelList['all']      = 'All';
$lang->issue->labelList['open']     = 'Open';
$lang->issue->labelList['assignto'] = 'AssignedToMe';
$lang->issue->labelList['assignby'] = 'AssignedByMe';
$lang->issue->labelList['closed']   = 'Closed';
$lang->issue->labelList['resolved'] = 'Resolved';
$lang->issue->labelList['canceled'] = 'Canceled';

$lang->issue->priList['0'] = '';
$lang->issue->priList['1'] = 1;
$lang->issue->priList['2'] = 2;
$lang->issue->priList['3'] = 3;
$lang->issue->priList['4'] = 4;

$lang->issue->severityList['0']  = '';
$lang->issue->severityList['1'] = '1';
$lang->issue->severityList['2'] = '2';
$lang->issue->severityList['3'] = '3';
$lang->issue->severityList['4'] = '4';

$lang->issue->typeList[''] = '';
$lang->issue->typeList['design']       = 'Design';
$lang->issue->typeList['code']         = 'Code';
$lang->issue->typeList['performance']  = 'Performance';
$lang->issue->typeList['version']      = 'Version';
$lang->issue->typeList['storyadd']     = 'New Story';
$lang->issue->typeList['storychanged'] = 'Story Change';
$lang->issue->typeList['storyremoved'] = 'Story Deleted';
$lang->issue->typeList['data']         = 'Data';

$lang->issue->resolutionList['resolved'] = 'Resolved';
$lang->issue->resolutionList['tostory']  = 'To Story';
$lang->issue->resolutionList['tobug']    = 'To Bug';
$lang->issue->resolutionList['torisk']   = 'To Risk';
$lang->issue->resolutionList['totask']   = 'To Task';

$lang->issue->statusList['unconfirmed'] = 'Unconfirmed';
$lang->issue->statusList['confirmed']   = 'Confirmed';
$lang->issue->statusList['resolved']    = 'Resolved';
$lang->issue->statusList['canceled']    = 'Canceled';
$lang->issue->statusList['closed']      = 'Closed';
$lang->issue->statusList['active']      = 'Active';

$lang->issue->resolveMethods = array();
$lang->issue->resolveMethods['resolved'] = 'Resolved';
$lang->issue->resolveMethods['totask']   = 'To Task';
$lang->issue->resolveMethods['tobug']    = 'To Bug';
$lang->issue->resolveMethods['tostory']  = 'To Story';
$lang->issue->resolveMethods['torisk']   = 'To Risk';

$lang->issue->confirmDelete = 'Do you want to delete this issue?';
$lang->issue->typeEmpty     = 'ID: %s Type cannot be empty.';
$lang->issue->titleEmpty    = 'ID: %s Title cannot be empty.';
$lang->issue->severityEmpty = 'ID: %s Severity cannot be empty.';

$lang->issue->logComments = array();
$lang->issue->logComments['totask']  = " created Task %s";
$lang->issue->logComments['tostory'] = " created Story %s";
$lang->issue->logComments['tobug']   = " created Bug %s" ;
$lang->issue->logComments['torisk']  = " created Risk %s";

$lang->issue->action = new stdclass();
$lang->issue->action->importfromissuelib = array('main' => '$date, imported by <strong>$actor</strong> from <strong>$extra</strong>.');;
