<?php
$lang->deploy->common           = '上線申請';
$lang->deploy->create           = '創建上線申請';
$lang->deploy->view             = '上線申請概況';
$lang->deploy->finish           = '完成';
$lang->deploy->finishAction     = '完成上線申請';
$lang->deploy->edit             = '編輯';
$lang->deploy->editAction       = '編輯上線申請';
$lang->deploy->delete           = '刪除';
$lang->deploy->deleteAction     = '刪除上線申請';
$lang->deploy->deleted          = '已刪除';
$lang->deploy->activate         = '取消上線';
$lang->deploy->activateAction   = '取消上線申請';
$lang->deploy->browse           = '瀏覽上線申請';
$lang->deploy->browseAction     = '上線申請列表';
$lang->deploy->scope            = '上線範圍';
$lang->deploy->manageScope      = '管理上線範圍';
$lang->deploy->cases            = '用例';
$lang->deploy->notify           = '通知';
$lang->deploy->casesAction      = '上線用例';
$lang->deploy->linkCases        = '關聯用例';
$lang->deploy->unlinkCase       = '移除用例';
$lang->deploy->steps            = '上線步驟';
$lang->deploy->manageStep       = '管理上線步驟';
$lang->deploy->finishStep       = '完成上線步驟';
$lang->deploy->activateStep     = '激活上線步驟';
$lang->deploy->assignTo         = '指派';
$lang->deploy->assignAction     = '指派上線步驟';
$lang->deploy->editStep         = '編輯上線步驟';
$lang->deploy->deleteStep       = '刪除上線步驟';
$lang->deploy->viewStep         = '上線步驟詳情';
$lang->deploy->batchUnlinkCases = '批量移除用例';
$lang->deploy->createdDate      = '創建時間';
$lang->deploy->createdBy        = '創建人';
$lang->deploy->publish          = '上線';
$lang->deploy->estimate         = '預計上線時間';

$lang->deploy->name       = '申請標題';
$lang->deploy->desc       = '描述';
$lang->deploy->members    = '上線人員';
$lang->deploy->hosts      = '主機';
$lang->deploy->service    = '服務';
$lang->deploy->product    = $lang->productCommon;
$lang->deploy->release    = '應用版本號';
$lang->deploy->package    = '包地址';
$lang->deploy->begin      = '開始時間';
$lang->deploy->end        = '結束時間';
$lang->deploy->status     = '狀態';
$lang->deploy->owner      = '負責人';
$lang->deploy->stage      = '上線階段';
$lang->deploy->ditto      = '同上';
$lang->deploy->manageAB   = '管理';
$lang->deploy->title      = '上線步驟標題';
$lang->deploy->content    = '上線步驟描述';
$lang->deploy->assignedTo = '指派給';
$lang->deploy->finishedBy = '由誰完成';
$lang->deploy->result     = '上線狀態';
$lang->deploy->updateHost = '修改主機關係';
$lang->deploy->removeHost = '待移除主機';
$lang->deploy->addHost    = '新加主機';
$lang->deploy->hadHost    = '已有主機';
$lang->deploy->type       = '部署方式';
$lang->deploy->date       = '預計上線時間';
$lang->deploy->reviewedBy = '評審人';
$lang->deploy->reviewDate = '評審時間';
$lang->deploy->system     = '所屬應用';

$lang->deploy->lblBeginEnd = '起止時間';
$lang->deploy->lblBasic    = '基本信息';
$lang->deploy->lblProduct  = '關聯' . $lang->productCommon;
$lang->deploy->lblMonth    = '當前顯示';
$lang->deploy->toggle      = '切換';

$lang->deploy->monthFormat = 'Y年m月';

$lang->deploy->statusList['wait']    = '待上線';
$lang->deploy->statusList['doing']   = '上線中';
$lang->deploy->statusList['success'] = '上線成功';
$lang->deploy->statusList['fail']    = '上線失敗';

$lang->deploy->dateList['today']     = '今天';
$lang->deploy->dateList['tomorrow']  = '明天';
$lang->deploy->dateList['thisweek']  = '本週';
$lang->deploy->dateList['thismonth'] = '本月';
$lang->deploy->dateList['done']      = $lang->deploy->statusList['success'];

$lang->deploy->stageList['wait']    = '上線前';
$lang->deploy->stageList['doing']   = '上線中';
$lang->deploy->stageList['testing'] = '冒煙測試';
$lang->deploy->stageList['done']    = '上線後';

$lang->deploy->resultList['success'] = '成功';
$lang->deploy->resultList['fail']    = '失敗';

$lang->deploy->confirmDelete     = '是否刪除該上線申請';
$lang->deploy->confirmDeleteStep = '是否刪除該上線上線步驟';
$lang->deploy->errorTime         = '結束時間必須大於開始時間！';
$lang->deploy->errorStatusWait   = '如果上線步驟狀態是未完成，由誰完成必須為空';
$lang->deploy->errorStatusDone   = '如果上線步驟狀態是已完成，由誰完成不能為空';
$lang->deploy->errorOffline      = '服務的上架機器和下架機器不能共存。';
$lang->deploy->resultNotEmpty    = '結果不能為空';
$lang->deploy->confirmPublish    = '是否發起上線?';

$lang->deploystep = new stdClass();
$lang->deploystep->status       = $lang->deploy->status;
$lang->deploystep->assignedTo   = $lang->deploy->assignedTo;
$lang->deploystep->finishedBy   = $lang->deploy->finishedBy;
$lang->deploystep->finishedDate = '完成日期';
$lang->deploystep->begin        = $lang->deploy->begin;
$lang->deploystep->end          = $lang->deploy->end;

$lang->datepicker->monthNames = array('1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月');

$lang->deploy->featureBar['browse']['all']         = '全部';
$lang->deploy->featureBar['browse']['wait']        = '待上線';
$lang->deploy->featureBar['browse']['success']     = '已上線';
$lang->deploy->featureBar['browse']['createdbyme'] = '由我創建';

$lang->deploy->typeList['manual'] = '手動上線';

$lang->deploy->notice = new stdclass();
$lang->deploy->notice->nameLength = '名稱不能超過50個字元。';
$lang->deploy->notice->descLength = '描述不能超過255個字元。';
$lang->deploy->notice->styleName  = '名稱只能由中文、字母、數字、-和_組成。';

$lang->deploy->stepStatusList['wait'] = '未完成';
$lang->deploy->stepStatusList['done'] = '已完成';
