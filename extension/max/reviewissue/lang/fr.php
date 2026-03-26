<?php
$lang->reviewissue->common         = 'Review Issues';
$lang->reviewissue->issue          = 'Issues';
$lang->reviewissue->issueBrowse    = 'View';
$lang->reviewissue->create         = 'Create';
$lang->reviewissue->edit           = 'Edit';
$lang->reviewissue->view           = 'Details';
$lang->reviewissue->updateStatus   = 'Activate/Close Issues';
$lang->reviewissue->confirmSolve   = 'Is the issue resolved?';
$lang->reviewissue->confirmActive  = 'Is the issue reactivated?';
$lang->reviewissue->confirmClose   = 'Is the issue needs to be closed?';
$lang->reviewissue->confirmDelete  = 'Is the issue needs to be deleted?';
$lang->reviewissue->undeleteAction = 'The review that this issue belongs to has been deleted. Please restore the review first and then restore the issue';
$lang->reviewissue->resolved       = 'Resolve';
$lang->reviewissue->activation     = 'Active';
$lang->reviewissue->close          = 'Close';
$lang->reviewissue->delete         = 'Delete';
$lang->reviewissue->deleted        = 'Deleted';
$lang->reviewissue->issueInfo      = 'Issue Details';
$lang->reviewissue->hasResolved    = 'Resolve?';
$lang->reviewissue->searchReview   = 'Select Review Item';
$lang->reviewissue->injection      = 'Injection Stage';
$lang->reviewissue->byQuery        = 'Search';

$lang->reviewissue->id          = 'ID';
$lang->reviewissue->review      = 'Review';
$lang->reviewissue->listID      = 'Checklist';
$lang->reviewissue->title       = 'Title';
$lang->reviewissue->opinion     = 'Suggestion';
$lang->reviewissue->status      = 'Status';
$lang->reviewissue->type        = 'Type';
$lang->reviewissue->createdBy   = 'CreatedBy';
$lang->reviewissue->createdDate = 'Created';

$lang->reviewissue->issueType['review']  = 'Review Issue';
$lang->reviewissue->issueType['audit']   = 'Audit Issue';

$lang->reviewissue->statusList['active']   = 'Active';
$lang->reviewissue->statusList['resolved'] = 'Resolved';
$lang->reviewissue->statusList['closed']   = 'Closed';

$lang->reviewissue->featureBar['issue']['all']       = 'All';
$lang->reviewissue->featureBar['issue']['active']    = 'Active';
$lang->reviewissue->featureBar['issue']['resolved']  = 'Resolved';
$lang->reviewissue->featureBar['issue']['closed']    = 'Closed';
$lang->reviewissue->featureBar['issue']['createdBy'] = 'CreatedBy';
$lang->reviewissue->featureBar['issue']['review']    = 'Review Issue';
$lang->reviewissue->featureBar['issue']['audit']     = 'Audit Issue';

$lang->reviewissue->review         = 'Review Item';
$lang->reviewissue->checklist      = 'Checklist';
$lang->reviewissue->listType       = 'Checklist Type';
$lang->reviewissue->resolution     = 'Resolution';
$lang->reviewissue->resolutionBy   = 'Resolved By';
$lang->reviewissue->resolutionDate = 'Resolved Date';

$lang->reviewissue->resolutionList['']           = '';
$lang->reviewissue->resolutionList['bydesign']   = 'As Designed';
$lang->reviewissue->resolutionList['duplicate']  = 'Duplicate';
$lang->reviewissue->resolutionList['external']   = 'External';
$lang->reviewissue->resolutionList['fixed']      = 'Resolved';
$lang->reviewissue->resolutionList['notrepro']   = 'Irreproducible';
$lang->reviewissue->resolutionList['postponed']  = 'Postponed';
$lang->reviewissue->resolutionList['willnotfix'] = "Won't Fix";

/* Operating record. */
$lang->reviewissue->action = new stdclass();
$lang->reviewissue->action->resolved = array('main' => '$date, resolved by <strong>$actor</strong> and the resolution is <strong>$extra</strong>.', 'extra' => 'resolutionList');
