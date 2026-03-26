<?php
$lang->reviewissue->common         = '評審問題';
$lang->reviewissue->issue          = '問題列表';
$lang->reviewissue->issueBrowse    = '評審問題列表';
$lang->reviewissue->create         = '添加問題';
$lang->reviewissue->edit           = '編輯';
$lang->reviewissue->view           = '問題詳情';
$lang->reviewissue->updateStatus   = '激活/關閉問題';
$lang->reviewissue->confirmSolve   = '確認該問題已解決？';
$lang->reviewissue->confirmActive  = '確認該問題重新激活？';
$lang->reviewissue->confirmClose   = '確認該問題需要關閉？';
$lang->reviewissue->confirmDelete  = '確認該問題需要刪除？';
$lang->reviewissue->undeleteAction = '該問題的所屬評審已被刪除，請先還原評審再還原問題';
$lang->reviewissue->resolved       = '解決';
$lang->reviewissue->activation     = '激活';
$lang->reviewissue->close          = '關閉';
$lang->reviewissue->delete         = '刪除';
$lang->reviewissue->deleted        = '已刪除';
$lang->reviewissue->issueInfo      = '問題詳情';
$lang->reviewissue->hasResolved    = '問題是否解決';
$lang->reviewissue->searchReview   = '選擇評審項';
$lang->reviewissue->injection      = '注入階段';
$lang->reviewissue->byQuery        = '搜索';

$lang->reviewissue->id          = '編號';
$lang->reviewissue->review      = '評審';
$lang->reviewissue->listID      = '檢查單';
$lang->reviewissue->title       = '問題標題';
$lang->reviewissue->opinion     = '問題意見';
$lang->reviewissue->status      = '狀態';
$lang->reviewissue->type        = '類型';
$lang->reviewissue->createdBy   = '創建人';
$lang->reviewissue->createdDate = '創建日期';

$lang->reviewissue->issueType['review'] = '評審問題';
$lang->reviewissue->issueType['audit']  = '審計問題';

$lang->reviewissue->statusList['active']   = '待解決';
$lang->reviewissue->statusList['resolved'] = '已解決';
$lang->reviewissue->statusList['closed']   = '已關閉';

$lang->reviewissue->featureBar['issue']['all']       = '全部';
$lang->reviewissue->featureBar['issue']['active']    = '待解決';
$lang->reviewissue->featureBar['issue']['resolved']  = '已解決';
$lang->reviewissue->featureBar['issue']['closed']    = '已關閉';
$lang->reviewissue->featureBar['issue']['createdBy'] = '由我創建';
$lang->reviewissue->featureBar['issue']['review']    = '評審問題';
$lang->reviewissue->featureBar['issue']['audit']     = '審計問題';

$lang->reviewissue->review         = '評審項';
$lang->reviewissue->checklist      = '檢查單';
$lang->reviewissue->listType       = '檢查單分類';
$lang->reviewissue->resolution     = '解決方案';
$lang->reviewissue->resolutionBy   = '解決者';
$lang->reviewissue->resolutionDate = '解決日期';

$lang->reviewissue->resolutionList['']           = '';
$lang->reviewissue->resolutionList['bydesign']   = '設計如此';
$lang->reviewissue->resolutionList['duplicate']  = '重複問題';
$lang->reviewissue->resolutionList['external']   = '外部原因';
$lang->reviewissue->resolutionList['fixed']      = '已解決';
$lang->reviewissue->resolutionList['notrepro']   = '無法重現';
$lang->reviewissue->resolutionList['postponed']  = '延期處理';
$lang->reviewissue->resolutionList['willnotfix'] = "不予解決";

/* 操作記錄。*/
$lang->reviewissue->action = new stdclass();
$lang->reviewissue->action->resolved = array('main' => '$date, 由 <strong>$actor</strong> 解決，方案為 <strong>$extra</strong>。', 'extra' => 'resolutionList');
