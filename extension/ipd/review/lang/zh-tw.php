<?php
$lang->review->id                = '編號';
$lang->review->delete            = '刪除';
$lang->review->deleted           = '已刪除';
$lang->review->common            = '評審';
$lang->review->assess            = '評審';
$lang->review->record            = '評審記錄';
$lang->review->explain           = '評審說明';
$lang->review->resultExplain     = '評審結論說明';
$lang->review->reviewResult      = '評審結果';
$lang->review->conclusion        = '評審結論（是否通過）';
$lang->review->recall            = '撤回';
$lang->review->files             = '附件';
$lang->review->start             = '發起';
$lang->review->finish            = '結束';
$lang->review->submit            = '提交評審';
$lang->review->toAudit           = '提交審計';
$lang->review->create            = '發起評審';
$lang->review->edit              = '編輯評審';
$lang->review->browse            = '瀏覽評審';
$lang->review->view              = '評審詳情';
$lang->review->viewFlow          = '預覽評審流程';
$lang->review->title             = '評審標題';
$lang->review->object            = '評審對象';
$lang->review->content           = '評審內容來源';
$lang->review->doclib            = '相關文檔庫';
$lang->review->template          = '相關模板';
$lang->review->doc               = '相關文檔';
$lang->review->version           = '對象版本號';
$lang->review->reviewedBy        = '由誰評審';
$lang->review->reviewer          = '評審人員';
$lang->review->reviewReport      = '評審報告';
$lang->review->exportReport      = '導出評審報告';
$lang->review->reviewerCount     = '評審人數';
$lang->review->deadline          = '計劃完成時間';
$lang->review->comment           = '備註';
$lang->review->createdBy         = '由誰創建';
$lang->review->createdDate       = '創建時間';
$lang->review->reviewedHours     = '評審時長（小時）';
$lang->review->area              = '評審地點';
$lang->review->audit             = '審計';
$lang->review->auditedBy         = '由誰審計';
$lang->review->objectScale       = '文檔規模';
$lang->review->issueCount        = '缺陷總數';
$lang->review->issueRate         = '缺陷率';
$lang->review->issueFoundRate    = '缺陷發現率';
$lang->review->issues            = '發現的問題';
$lang->review->isIssue           = '是否缺陷';
$lang->review->result            = '評審節點及結果';
$lang->review->nodeDetail        = '節點詳情';
$lang->review->status            = '狀態';
$lang->review->opinion           = '修改意見';
$lang->review->finalOpinion      = '評審意見';
$lang->review->reviewcl          = '檢查清單';
$lang->review->reviewedDate      = '評審時間';
$lang->review->consumed          = '消耗工時';
$lang->review->basicInfo         = '基本信息';
$lang->review->product           = '所屬' . $lang->productCommon;
$lang->review->auditResult       = '審計結果';
$lang->review->auditedDate       = '審計時間';
$lang->review->auditOpinion      = '審計意見';
$lang->review->issueList         = '問題清單';
$lang->review->lastIssue         = '上次遺留問題';
$lang->review->fullScreen        = '全屏';
$lang->review->auditedByEmpty    = '由誰審計不能為空！';
$lang->review->exporting         = '正在導出...';
$lang->review->lastReviewedDate  = '最後評審時間';
$lang->review->lastAuditedDate   = '最後審計時間';
$lang->review->createBaseline    = '打基線';
$lang->review->opinionDate       = '建議解決時間';
$lang->review->setReviewer       = '下一節點審批人';
$lang->review->objectScaleTip    = "{$lang->review->objectScale}統計的是軟件需求或用戶需求規模點數";
$lang->review->issueCountTip     = "{$lang->review->issueCount}統計的是{$lang->review->common}不符合項的問題總數";
$lang->review->issueRateTip      = "{$lang->review->issueRate}={$lang->review->issueCount}/評審規模";
$lang->review->issueFoundRateTip = "{$lang->review->issueFoundRate}={$lang->review->issueCount}/{$lang->review->reviewedHours}";
$lang->review->untitled          = '未命名';

$lang->review->browseAction = '基線評審列表';

$lang->review->pageAllSummary = '本頁共 %total% 個評審，待評審 %wait%，評審中 %reviewing%，評審通過 %pass%，審計中 %auditing%，完成 %done%。';
$lang->review->pageSummary    = '本頁共 %s 個評審。';

$lang->object = new stdclass();
$lang->object->product = $lang->review->product;

$lang->review->report = new stdclass();
$lang->review->report->common = '評審報告';

$lang->review->reportCreatedBy  = '報告提交人';
$lang->review->reportApprovedBy = '報告審批人';

$lang->review->listCategory = '分類';
$lang->review->listTitle    = '檢查內容';
$lang->review->listItem     = '檢查項';
$lang->review->listResult   = '是否符合';

$lang->review->contentList['template'] = '系統模板生成';
$lang->review->contentList['doc']      = $lang->projectCommon . '文檔';

$lang->review->noBook        = '暫無相關說明書，請到文檔維護說明書';
$lang->review->stopSubmit    = '檢查清單中存在不符合項';
$lang->review->confirmDelete = '您確定刪除該評審嗎？刪除後該評審下的問題也會刪除！';

$lang->review->statusList['draft']     = '草稿';
$lang->review->statusList['wait']      = '待評審';
$lang->review->statusList['reverting'] = '回退中';
$lang->review->statusList['reviewing'] = '評審中';
$lang->review->statusList['pass']      = '評審通過';
$lang->review->statusList['fail']      = '評審失敗';
$lang->review->statusList['auditing']  = '審計中';
$lang->review->statusList['done']      = '完成';

$lang->review->resultList['pass'] = '通過';
$lang->review->resultList['fail'] = '不通過';

$lang->review->auditResultList['pass']    = '通過';
$lang->review->auditResultList['needfix'] = '修改後再次審計';
$lang->review->auditResultList['fail']    = '重新走評審流程';

$lang->review->resultLable['pass']    = 'success';
$lang->review->resultLable['fail']    = 'danger';
$lang->review->resultLable['needfix'] = 'info';

$lang->review->reviewResultList['pass']    = '通過';
$lang->review->reviewResultList['needfix'] = '修改後通過';
$lang->review->reviewResultList['fail']    = '不通過';

$lang->review->checkList['1'] = '符合';
$lang->review->checkList['0'] = '不符合';

$lang->review->resolvedList['1'] = '已解決';
$lang->review->resolvedList['0'] = '未解決';

$lang->review->featureBar['browse']['all']          = '全部';
$lang->review->featureBar['browse']['reviewing']    = '評審中';
$lang->review->featureBar['browse']['done']         = '已結束';
$lang->review->featureBar['browse']['wait']         = '待我評審';
$lang->review->featureBar['browse']['reviewedbyme'] = '由我評審';
$lang->review->featureBar['browse']['createdbyme']  = '由我發起';

$lang->review->resultExplainList['pass']    = "通過：工作成果合格，“無需修改”或者“需要輕微修改但不必再審核”。";
//$lang->review->resultExplainList['needFix'] = '修改後通過：有條件通過：工作成果基本合格，需要作少量的修改，之後通過驗證即可。';
$lang->review->resultExplainList['fail']    = '不通過：工作成果不合格，需要作比較大的修改，之後必須重新對其評審。';

$lang->review->issue = new stdclass();
$lang->review->issue->id           = '序號';
$lang->review->issue->summary      = '缺陷分析及跟蹤';
$lang->review->issue->desc         = '缺陷描述';
$lang->review->issue->analyse      = '缺陷分析';
$lang->review->issue->introAnalyse = '引入分析';
$lang->review->issue->resolvedBy   = '修改人';
$lang->review->issue->deadline     = '修改期限';
$lang->review->issue->resolvedDate = '修改完成時間';
$lang->review->issue->severity     = '嚴重程度';
$lang->review->issue->verifiedBy   = '驗證人';
$lang->review->issue->status       = '狀態';

$lang->review->action = new stdclass();
$lang->review->action->reviewed  = array('main' => '$date, 由 <strong>$actor</strong> 評審，結果為 <strong>$extra</strong>。', 'extra' => 'resultList');
$lang->review->action->submit    = array('main' => '$date, 由 <strong>$actor</strong> 提交評審。');
$lang->review->action->recall    = array('main' => '$date, 由 <strong>$actor</strong> 撤回評審。');
$lang->review->action->toaudit   = array('main' => '$date, 由 <strong>$actor</strong> 提交審計， 指派給 <strong>$extra</strong>。');
$lang->review->action->audited   = array('main' => '$date, 由 <strong>$actor</strong> 審計，結果為 <strong>$extra</strong>。', 'extra' => 'auditResultList');

$lang->reviewresult = new stdclass();
$lang->reviewresult->consumed    = '消耗工時';
$lang->reviewresult->createdDate = '審計時間';

$lang->review->selectApprovalText = '評審：第%s次';

$lang->review->cannotReview = '抱歉！您沒有該條數據的審批權限。';
