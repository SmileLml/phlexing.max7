<?php
$lang->flow->template         = ' Template';
$lang->flow->showImport       = ' Show Import';
$lang->flow->link             = 'Link ';
$lang->flow->unlink           = 'Unlink ';
$lang->flow->detail           = ' Detail';
$lang->flow->search           = 'Search';
$lang->flow->ditto            = 'Ditto';
$lang->flow->importMode       = 'Import Mode';
$lang->flow->approvalProgress = 'Approval Progress';
$lang->flow->setExport        = 'Flow Design => Export Settings';
$lang->flow->setSearch        = 'Flow Design => Search Settings';

$lang->flow->selectLinkType = 'Select Link Type';
$lang->flow->unlinkConfirm  = 'Do you want to unlink this %s?';
$lang->flow->filesNotEmpty  = '%s files should not be empty!';

$lang->flow->importModeList['template'] = 'Template';
//$lang->flow->importModeList['auto']     = 'Auto';

$lang->flow->reportGranularity['week'] = 'Week %s, %s';
$lang->flow->reportGranularity['Q1']   = 'Q1 %s';
$lang->flow->reportGranularity['Q2']   = 'Q2 %s';
$lang->flow->reportGranularity['Q3']   = 'Q3 %s';
$lang->flow->reportGranularity['Q4']   = 'Q4 %s';

$lang->flow->notset     = 'Not Set';
$lang->task->noAssigned = 'Unassigned';

$lang->flow->tips = new stdclass();
$lang->flow->tips->notice = 'Send a notification to selected users.';
$lang->flow->tips->emptyExportFields      = 'No fields to export. Go <strong>%s</strong> to set.';
$lang->flow->tips->emptySearchFields      = 'No fields to search. Go <strong>%s</strong> to set.';
$lang->flow->tips->importMode['template'] = 'The data that can be matched to the template will be imported.';
$lang->flow->tips->importMode['auto']     = 'The data that can be matched to the export settings will be imported.';

$lang->flow->error = new stdclass();;
$lang->flow->error->notFound          = 'Not found.';
$lang->flow->error->emptyLayoutFields = "Go to [Flow] => [%s] => [Action] => [%s] => [Layout] to set fields to display.";
$lang->flow->error->approval          = 'Review failed, please contact the administrator.';
