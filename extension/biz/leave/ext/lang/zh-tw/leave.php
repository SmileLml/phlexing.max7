<?php
$lang->leave->switchstatus      = '提交或撤銷';
$lang->leave->companyAction     = '所有請假';
$lang->leave->reviewAction      = '審核請假';
$lang->leave->exportAction      = '導出請假記錄';
$lang->leave->createAction      = '申請請假';
$lang->leave->editAction        = '編輯請假';
$lang->leave->deleteAction      = '刪除請假';
$lang->leave->viewAction        = '請假詳情';
$lang->leave->backAction        = '銷假';
$lang->leave->setReviewerAction = '請假設置';
$lang->leave->submit            = '提交';
$lang->leave->browseReview      = '請假審核列表';

$lang->leave->action = new stdclass();
$lang->leave->action->reviewed = '$date, 由 <strong>$actor</strong> 審批，審批意見為<strong>$extra</strong>'. "\n";

$lang->leave->featureBar = array();
$lang->leave->featureBar['personal']['personal']     = '我的請假';
$lang->leave->featureBar['personal']['browseReview'] = '我的審核';
$lang->leave->featureBar['personal']['company']      = '全部請假';
$lang->leave->featureBar['personal']['setReviewer']  = '設置';
