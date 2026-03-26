<?php
$lang->marketresearch->create        = '發起調研';
$lang->marketresearch->name          = '調研名稱';
$lang->marketresearch->market        = '目標市場';
$lang->marketresearch->PM            = '負責人';
$lang->marketresearch->dateRange     = '計划起止日期';
$lang->marketresearch->desc          = '調研描述';
$lang->marketresearch->acl           = '訪問控制';
$lang->marketresearch->begin         = '計劃開始';
$lang->marketresearch->to            = '至';
$lang->marketresearch->end           = '計劃完成';
$lang->marketresearch->longTime      = '長期';
$lang->marketresearch->status        = '狀態';
$lang->marketresearch->realBegan     = '實際開始';
$lang->marketresearch->realEnd       = '實際結束';
$lang->marketresearch->progress      = '進度';
$lang->marketresearch->openedBy      = '創建者';
$lang->marketresearch->mine          = '我參與的';
$lang->marketresearch->view          = '調研概況';
$lang->marketresearch->edit          = '編輯調研';
$lang->marketresearch->activate      = '激活調研';
$lang->marketresearch->start         = '開始調研';
$lang->marketresearch->close         = '結束調研';
$lang->marketresearch->teamAction    = '團隊列表';
$lang->marketresearch->report        = '報告';
$lang->marketresearch->delete        = '刪除調研';
$lang->marketresearch->all           = '全部調研列表';
$lang->marketresearch->browse        = '調研列表';
$lang->marketresearch->team          = '團隊成員';
$lang->marketresearch->manageMembers = '團隊管理';
$lang->marketresearch->unlinkMember  = '移除成員';
$lang->marketresearch->reports       = '調研報告';
$lang->marketresearch->task          = '調研任務列表';
$lang->marketresearch->createStage   = '設置階段';
$lang->marketresearch->batchStage    = '細分階段';
$lang->marketresearch->editStage     = '編輯階段';
$lang->marketresearch->startStage    = '開始階段';
$lang->marketresearch->closeStage    = '關閉階段';
$lang->marketresearch->closedBy      = '由誰結束';
$lang->marketresearch->closedDate    = '結束日期';
$lang->marketresearch->activateStage = '激活階段';
$lang->marketresearch->deleteStage   = '刪除階段';
$lang->marketresearch->common        = '調研';
$lang->marketresearch->whitelist     = '調研白名單';
$lang->marketresearch->execution     = '所屬階段';

$lang->researchstage = new stdclass();
$lang->researchstage->common = '調研階段';

$lang->marketresearch->marketNotEmpty      = '『目標市場』不能為空';
$lang->marketresearch->readjustTime        = '重新調整調研的起止時間';
$lang->marketresearch->cannotGe            = '%s『%s』不能大於 %s『%s』';
$lang->marketresearch->closedReason        = '結束原因';
$lang->marketresearch->confirmDelete       = '您確定刪除調研\"%s\"嗎？刪除後與該調研相關的任務、報告將被隱藏！';
$lang->marketresearch->stageConfirmDelete  = '您確定要刪除該階段嗎？刪除後，階段下的任務將被隱藏。';
$lang->marketresearch->noMembers           = '暫時沒有團隊成員。';
$lang->marketresearch->confirmUnlinkMember = '您確定從該調研中移除該用戶嗎？';

$lang->marketresearch->createTask = '建任務';
$lang->marketresearch->planDate   = '計劃日期';

$lang->marketresearch->endList[31]  = '一個月';
$lang->marketresearch->endList[93]  = '三個月';
$lang->marketresearch->endList[186] = '半年';
$lang->marketresearch->endList[365] = '一年';
$lang->marketresearch->endList[999] = '長期';

$lang->marketresearch->aclList['private'] = "私有 (只有調研負責人、團隊成員可訪問)";
$lang->marketresearch->aclList['open']    = "公開 (有調研視圖權限即可訪問)";

$lang->marketresearch->shortAclList['open']    = '公開';
$lang->marketresearch->shortAclList['private'] = '私有';

$lang->marketresearch->statusList = array();
$lang->marketresearch->statusList['wait']   = '未開始';
$lang->marketresearch->statusList['doing']  = '進行中';
$lang->marketresearch->statusList['closed'] = '已結束';

$lang->marketresearch->reasonList = array();
$lang->marketresearch->reasonList['finished']  = '完成';
$lang->marketresearch->reasonList['cancelled'] = '取消';

$lang->marketresearch->featureBar = array();
$lang->marketresearch->featureBar['all']['all']    = '全部';
$lang->marketresearch->featureBar['all']['wait']   = '未開始';
$lang->marketresearch->featureBar['all']['doing']  = '進行中';
$lang->marketresearch->featureBar['all']['closed'] = '已結束';

$lang->marketresearch->featureBar['browse'] = $lang->marketresearch->featureBar['all'];

$lang->marketresearch->featureBar['task']['all']          = '全部';
$lang->marketresearch->featureBar['task']['unclosed']     = '未關閉';
$lang->marketresearch->featureBar['task']['assignedtome'] = '指派給我';
$lang->marketresearch->featureBar['task']['myinvolved']   = '由我參與';
$lang->marketresearch->featureBar['task']['assignedbyme'] = '由我指派';
$lang->marketresearch->featureBar['task']['status']       = $lang->more;

$lang->marketresearch->featureBar['team']['all'] = '團隊成員';

$lang->marketresearch->moreSelects['task']['status']['wait']         = '未開始';
$lang->marketresearch->moreSelects['task']['status']['doing']        = '進行中';
$lang->marketresearch->moreSelects['task']['status']['undone']       = '未完成';
$lang->marketresearch->moreSelects['task']['status']['finishedbyme'] = '我完成';
$lang->marketresearch->moreSelects['task']['status']['done']         = '已完成';
$lang->marketresearch->moreSelects['task']['status']['closed']       = '已關閉';
$lang->marketresearch->moreSelects['task']['status']['cancel']       = '已取消';
$lang->marketresearch->moreSelects['task']['status']['delayed']      = '已延期';

$lang->marketresearch->stageAcl['open'] = "繼承{$lang->marketresearch->common}訪問權限（能訪問當前{$lang->marketresearch->common}，即可訪問）";

$lang->marketresearch->summary    = "本頁共 <strong>%s</strong> 個頂級階段，<strong>%s</strong> 個子階段，<strong>%s</strong> 個任務。";
$lang->marketresearch->allSummary = "本頁共 <strong>{0}</strong> 個頂級階段，<strong>{1}</strong> 個子階段，<strong>{2}</strong> 個任務，未關閉任務<strong>{3}</strong> 個，未開始任務<strong>{4}</strong> 個，進行中任務<strong>{5}</strong> 個。";
