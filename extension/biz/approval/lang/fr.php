<?php
$lang->approval->common         = 'Apporval';
$lang->approval->startNode      = 'Start';
$lang->approval->node           = 'Approval node';
$lang->approval->nodeName       = 'Approval node name';
$lang->approval->addNodeTitle   = 'Add Node';
$lang->approval->reviewer       = 'Reviewer';
$lang->approval->cc             = 'CC';
$lang->approval->ccer           = 'CCer';
$lang->approval->progress       = 'Progress';
$lang->approval->noResult       = 'No result';
$lang->approval->setReviewer    = 'Next node approver';
$lang->approval->toNodeID       = 'Revert to node';
$lang->approval->revertType     = 'Revert Type';
$lang->approval->revertOpinion  = 'Revert Opinion';
$lang->approval->addNodeMethod  = 'Add Node Method';
$lang->approval->addNodeOpinion = 'Add Node Opinion';
$lang->approval->multipleType   = 'Multiple Approval Type';

$lang->approval->start          = 'Submit Review';
$lang->approval->end            = 'End Review';
$lang->approval->cancel         = 'Cancel Review';
$lang->approval->revert         = 'Revert';
$lang->approval->addNode        = 'Add Node';
$lang->approval->forward        = 'Forward';
$lang->approval->forwardTo      = 'Forward To';
$lang->approval->forwardOpinion = 'Forward Opinion';

$lang->approval->otherReviewer = 'Other Reviewer : ';
$lang->approval->otherCcer     = 'Other Ccer : ';

$lang->approval->revertOpinionRequired  = 'Revert opinion cannot be empty!';
$lang->approval->forwardOpinionRequired = 'Forward opinion cannot be empty!';

$lang->approval->reviewDesc = new stdclass();
$lang->approval->reviewDesc->start      = '%s by <strong>$actor</strong>. <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->pass       = 'Approved by <strong>$actor</strong> . Result is <span class="result pass">Pass</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->fail       = 'Approved by <strong>$actor</strong>. Result is <span class="result fail">Fail</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->doing      = 'Approving by <strong>$actor</strong> . <span class="result reviewing">In approval</span> <span class="text-muted">$date</span>' . "\n";
$lang->approval->reviewDesc->reverted   = 'Reverted to <strong>$node</strong> by <strong>$actor</strong> <span class="text-muted">$date</span>.' . "\n";
$lang->approval->reviewDesc->wait       = '<strong>$actor</strong> <span class="text-muted">Pending approval</span>' . "\n";
$lang->approval->reviewDesc->cc         = 'CC to <strong>$actor</strong>' . "\n";
$lang->approval->reviewDesc->forwardBy  = '(Forward by <strong>$actor</strong>)';
$lang->approval->reviewDesc->addPrev    = 'Added prev node by <strong>$actor</strong>, reviewers is <span class="text-muted">$reviewer</span>, <span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addNext    = 'Added next node by <strong>$actor</strong>, reviewers is <span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';
$lang->approval->reviewDesc->addCurrent = 'Added reviewer by <strong>$actor</strong>, reviewers is <span class="text-muted">$reviewer</span>，<span class="text-muted">$date</span>';

$lang->approval->reviewDesc->autoReject        = 'Automatic rejection is set' . "\n";
$lang->approval->reviewDesc->autoPass          = 'Automatic consent is set' . "\n";
$lang->approval->reviewDesc->autoRejected      = 'Automatically rejected' . "\n";
$lang->approval->reviewDesc->autoPassed        = 'Automatically agreed' . "\n";
$lang->approval->reviewDesc->pass4NoReviewer   = 'No reviewer is selected, it will pass automatically according to the configuration' . "\n";
$lang->approval->reviewDesc->reject4NoReviewer = 'No reviewer is selected, it will be rejected automatically according to the configuration' . "\n";
$lang->approval->reviewDesc->setReviewer       = 'No reviewer selected, waiting for higher-level node settings' . "\n";

$lang->approval->notice = new stdclass();
$lang->approval->notice->orSign       = '(One person pass that will do)';
$lang->approval->notice->times        = 'Submission %s';
$lang->approval->notice->approvalTime = 'Reviewed %s';
$lang->approval->notice->day          = 'Day';
$lang->approval->notice->hour         = 'Hour';

$lang->approval->currentResult = 'Result';
$lang->approval->currentNode   = 'Current node';

$lang->approval->cannotOperate = 'Approval status has been updated, please refresh the page';

$lang->approval->revertTypeList = array();
$lang->approval->revertTypeList['order']  = 'Approval in order';
$lang->approval->revertTypeList['revert'] = 'Start from current node';

$lang->approval->addNodeMethodList = array();
$lang->approval->addNodeMethodList['prev']    = 'Add node before current node';
$lang->approval->addNodeMethodList['next']    = 'Add node after current node';
$lang->approval->addNodeMethodList['current'] = 'Add reviewer in current node';

$lang->approval->statusList = array();
$lang->approval->statusList['wait']  = 'Wait';
$lang->approval->statusList['doing'] = 'Doing';
$lang->approval->statusList['done']  = 'Done';

$lang->approval->resultList = array();
$lang->approval->resultList['pass'] = 'Pass';
$lang->approval->resultList['fail'] = 'Fail';

$lang->approval->mailResultList = array();
$lang->approval->mailResultList['pass']   = 'Pass';
$lang->approval->mailResultList['fail']   = 'Reject';
$lang->approval->mailResultList['ignore'] = 'Cancel';

$lang->approval->nodeList = array();
$lang->approval->nodeList['cc']     = 'CC';
$lang->approval->nodeList['review'] = 'Review';
$lang->approval->nodeList['doing']  = 'Reviewing';

$lang->approval->mailContent = array();
$lang->approval->mailContent['mail'] = '
<p>Dear user:</p>
<p>[<a href="%s">%s</a>] You are currently required for approval. Please go to the Zen Path for approval.</p>
';
$lang->approval->mailContent['mailto'] = '
<p>Dear user:</p>
<p>[<a href="%s">%s</a>] Under approval.</p>
';
$lang->approval->mailContent['result'] = '
<p>Dear user:</p>
<p>[<a href="%s">%s</a>] The final approval result is <span class="%s"><strong>%s</strong></span>.</p>
';
