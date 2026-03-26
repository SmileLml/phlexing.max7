<?php
$lang->reviewissue->common         = '评审问题';
$lang->reviewissue->issue          = '问题列表';
$lang->reviewissue->issueBrowse    = '评审问题列表';
$lang->reviewissue->create         = '添加问题';
$lang->reviewissue->edit           = '编辑';
$lang->reviewissue->view           = '问题详情';
$lang->reviewissue->updateStatus   = '激活/关闭问题';
$lang->reviewissue->confirmSolve   = '确认该问题已解决？';
$lang->reviewissue->confirmActive  = '确认该问题重新激活？';
$lang->reviewissue->confirmClose   = '确认该问题需要关闭？';
$lang->reviewissue->confirmDelete  = '确认该问题需要删除？';
$lang->reviewissue->undeleteAction = '该问题的所属评审已被删除，请先还原评审再还原问题';
$lang->reviewissue->resolved       = '解决';
$lang->reviewissue->activation     = '激活';
$lang->reviewissue->close          = '关闭';
$lang->reviewissue->delete         = '删除';
$lang->reviewissue->deleted        = '已删除';
$lang->reviewissue->issueInfo      = '问题详情';
$lang->reviewissue->hasResolved    = '问题是否解决';
$lang->reviewissue->searchReview   = '选择评审项';
$lang->reviewissue->injection      = '注入阶段';
$lang->reviewissue->byQuery        = '搜索';

$lang->reviewissue->id          = '编号';
$lang->reviewissue->review      = '评审';
$lang->reviewissue->listID      = '检查单';
$lang->reviewissue->title       = '问题标题';
$lang->reviewissue->opinion     = '问题意见';
$lang->reviewissue->status      = '状态';
$lang->reviewissue->type        = '类型';
$lang->reviewissue->createdBy   = '创建人';
$lang->reviewissue->createdDate = '创建日期';

$lang->reviewissue->issueType['review'] = '评审问题';
$lang->reviewissue->issueType['audit']  = '审计问题';

$lang->reviewissue->statusList['active']   = '待解决';
$lang->reviewissue->statusList['resolved'] = '已解决';
$lang->reviewissue->statusList['closed']   = '已关闭';

$lang->reviewissue->featureBar['issue']['all']       = '全部';
$lang->reviewissue->featureBar['issue']['active']    = '待解决';
$lang->reviewissue->featureBar['issue']['resolved']  = '已解决';
$lang->reviewissue->featureBar['issue']['closed']    = '已关闭';
$lang->reviewissue->featureBar['issue']['createdBy'] = '由我创建';
$lang->reviewissue->featureBar['issue']['review']    = '评审问题';
$lang->reviewissue->featureBar['issue']['audit']     = '审计问题';

$lang->reviewissue->review         = '评审项';
$lang->reviewissue->checklist      = '检查单';
$lang->reviewissue->listType       = '检查单分类';
$lang->reviewissue->resolution     = '解决方案';
$lang->reviewissue->resolutionBy   = '解决者';
$lang->reviewissue->resolutionDate = '解决日期';

$lang->reviewissue->resolutionList['']           = '';
$lang->reviewissue->resolutionList['bydesign']   = '设计如此';
$lang->reviewissue->resolutionList['duplicate']  = '重复问题';
$lang->reviewissue->resolutionList['external']   = '外部原因';
$lang->reviewissue->resolutionList['fixed']      = '已解决';
$lang->reviewissue->resolutionList['notrepro']   = '无法重现';
$lang->reviewissue->resolutionList['postponed']  = '延期处理';
$lang->reviewissue->resolutionList['willnotfix'] = "不予解决";

/* 操作记录。*/
$lang->reviewissue->action = new stdclass();
$lang->reviewissue->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解决，方案为 <strong>$extra</strong>。', 'extra' => 'resolutionList');
