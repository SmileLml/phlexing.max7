<?php
$lang->leave->switchstatus      = '提交或撤销';
$lang->leave->companyAction     = '所有请假';
$lang->leave->reviewAction      = '审核请假';
$lang->leave->exportAction      = '导出请假记录';
$lang->leave->createAction      = '申请请假';
$lang->leave->editAction        = '编辑请假';
$lang->leave->deleteAction      = '删除请假';
$lang->leave->viewAction        = '请假详情';
$lang->leave->backAction        = '销假';
$lang->leave->setReviewerAction = '请假设置';
$lang->leave->submit            = '提交';
$lang->leave->browseReview      = '请假审核列表';

$lang->leave->action = new stdclass();
$lang->leave->action->reviewed = '$date, 由 <strong>$actor</strong> 审批，审批意见为<strong>$extra</strong>'. "\n";

$lang->leave->featureBar = array();
$lang->leave->featureBar['personal']['personal']     = '我的请假';
$lang->leave->featureBar['personal']['browseReview'] = '我的审核';
$lang->leave->featureBar['personal']['company']      = '全部请假';
$lang->leave->featureBar['personal']['setReviewer']  = '设置';
