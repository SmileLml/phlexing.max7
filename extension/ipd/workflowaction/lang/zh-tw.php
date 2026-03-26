<?php
$lang->workflowaction->common            = '工作流動作';
$lang->workflowaction->browse            = '瀏覽動作';
$lang->workflowaction->create            = '添加動作';
$lang->workflowaction->edit              = '編輯動作';
$lang->workflowaction->view              = '動作詳情';
$lang->workflowaction->delete            = '刪除動作';
$lang->workflowaction->sort              = '動作排序';
$lang->workflowaction->setVerification   = '數據校驗';
$lang->workflowaction->resetVerification = '全部清除';
$lang->workflowaction->setNotice         = '設置提醒';
$lang->workflowaction->setJS             = 'JS';
$lang->workflowaction->setCSS            = 'CSS';
$lang->workflowaction->settings          = '動作及屬性設置';

$lang->workflowaction->id            = '編號';
$lang->workflowaction->module        = '所屬流程';
$lang->workflowaction->action        = '動作代號';
$lang->workflowaction->name          = '動作名稱';
$lang->workflowaction->type          = '操作數據方式';
$lang->workflowaction->batchMode     = '批量操作模式';
$lang->workflowaction->extensionType = '擴展方式';
$lang->workflowaction->open          = '打開方式';
$lang->workflowaction->position      = '顯示位置';
$lang->workflowaction->show          = '顯示方式';
$lang->workflowaction->order         = '顯示順序';
$lang->workflowaction->buildin       = '內置';
$lang->workflowaction->conditions    = '觸發條件';
$lang->workflowaction->verifications = '數據校驗';
$lang->workflowaction->hooks         = '擴展動作';
$lang->workflowaction->toList        = '抄送';
$lang->workflowaction->desc          = '描述';
$lang->workflowaction->status        = '狀態';
$lang->workflowaction->createdBy     = '由誰創建';
$lang->workflowaction->createdDate   = '創建時間';
$lang->workflowaction->editdeBy      = '由誰編輯';
$lang->workflowaction->editdeDate    = '編輯時間';
$lang->workflowaction->actionBy      = '由誰%s';
$lang->workflowaction->actionDate    = '%s日期';

$lang->workflowaction->actionWidth = 165;

$lang->workflowaction->layout      = '界面';
$lang->workflowaction->setLayout   = '去設計';
$lang->workflowaction->condition   = '觸發條件';
$lang->workflowaction->linkage     = '界面聯動';
$lang->workflowaction->hook        = '擴展動作';
$lang->workflowaction->previewData = "<div class='example-text-holder'></div>";

$lang->workflowaction->statusList['enable']  = '啟用';
$lang->workflowaction->statusList['disable'] = '停用';

$lang->workflowaction->typeList['single'] = '操作單條數據';
$lang->workflowaction->typeList['batch']  = '批量操作多條數據';

$lang->workflowaction->batchModeList['same']      = '執行相同操作';
$lang->workflowaction->batchModeList['different'] = '執行不同操作';

$lang->workflowaction->extensionTypeList['none']     = '不擴展';
$lang->workflowaction->extensionTypeList['extend']   = '擴展';

$lang->workflowaction->openList['normal'] = '普通頁面';
$lang->workflowaction->openList['modal']  = '彈窗頁面';
$lang->workflowaction->openList['none']   = '無頁面';

$lang->workflowaction->positionList['browse']        = '列表頁';
$lang->workflowaction->positionList['view']          = '詳情頁';
$lang->workflowaction->positionList['browseandview'] = '列表頁和詳情頁';

$lang->workflowaction->showList['dropdownlist'] = '顯示在下拉菜單中';
$lang->workflowaction->showList['direct']       = '直接顯示在頁面上';

$lang->workflowaction->buildinList['0'] = '否';
$lang->workflowaction->buildinList['1'] = '是';

$lang->workflowaction->default = new stdclass();
$lang->workflowaction->default->actions['browse']         = '瀏覽列表';
$lang->workflowaction->default->actions['create']         = '新建';
$lang->workflowaction->default->actions['batchcreate']    = '批量新建';
$lang->workflowaction->default->actions['batchedit']      = '批量編輯';
$lang->workflowaction->default->actions['batchassign']    = '批量指派';
$lang->workflowaction->default->actions['edit']           = '編輯';
$lang->workflowaction->default->actions['assign']         = '指派';
$lang->workflowaction->default->actions['view']           = '詳情';
$lang->workflowaction->default->actions['delete']         = '刪除';
$lang->workflowaction->default->actions['link']           = '關聯數據';
$lang->workflowaction->default->actions['unlink']         = '移除數據';
$lang->workflowaction->default->actions['export']         = '導出數據';
$lang->workflowaction->default->actions['exporttemplate'] = '下載模板';
$lang->workflowaction->default->actions['import']         = '導入數據';
$lang->workflowaction->default->actions['showimport']     = '導入確認';
$lang->workflowaction->default->actions['report']         = '報表';

$lang->workflowaction->approval = new stdclass();
$lang->workflowaction->approval->actions['approvalsubmit'] = '發起';
$lang->workflowaction->approval->actions['approvalcancel'] = '撤回';
$lang->workflowaction->approval->actions['approvalreview'] = '審批';

$lang->workflowaction->tips = new stdclass();
$lang->workflowaction->tips->buildin     = '內置動作不支持預覽。';
$lang->workflowaction->tips->emptyLayout = '當前動作未設計頁面，無法預覽。';
$lang->workflowaction->tips->noLayout    = '當前動作不支持預覽。';
$lang->workflowaction->tips->position    = '列表頁會顯示在列表操作列，詳情頁會顯示在底部的操作按鈕組。';
$lang->workflowaction->tips->show        = '如果動作操作比較多可以把相對不常用的放到下拉菜單，反之直接顯示在列表頁面的操作列';

$lang->workflowaction->placeholder = new stdclass();
$lang->workflowaction->placeholder->code = '只能包含英文字母';

$lang->workflowaction->error = new stdclass();
$lang->workflowaction->error->emptyName   = '請雙擊控件設置動作名稱';
$lang->workflowaction->error->emptyCode   = '請雙擊控件設置動作代號';
$lang->workflowaction->error->existCode   = '動作代號已經有 %s 這條記錄了。';
$lang->workflowaction->error->wrongCode   = '<strong> %s </strong>只能包含英文字母。';
$lang->workflowaction->error->builtinCode = '不能使用內置動作代號 %s。';
$lang->workflowaction->error->conflict    = '<strong> %s </strong>與系統語言衝突。';

/* Verification */
$lang->workflowverification = new stdclass();
$lang->workflowverification->common   = '數據校驗';
$lang->workflowverification->type     = '校驗類型';
$lang->workflowverification->result   = '校驗結果';
$lang->workflowverification->field    = '欄位';
$lang->workflowverification->sql      = 'SQL';
$lang->workflowverification->varName  = '變數名';
$lang->workflowverification->varValue = '變數值';
$lang->workflowverification->message  = '提示信息';

$lang->workflowverification->typeList['no']   = '不進行校驗';
$lang->workflowverification->typeList['data'] = '以數據作為校驗方式';
$lang->workflowverification->typeList['sql']  = '以SQL語句作為校驗方式';

$lang->workflowverification->resultList['empty']    = '查詢結果為空或0時通過校驗';
$lang->workflowverification->resultList['notempty'] = '查詢結果不為空和0時通過校驗';

$lang->workflowverification->logicalOperatorList['and'] = '並且';
$lang->workflowverification->logicalOperatorList['or']  = '或者';

$lang->workflowverification->placeholder = new stdclass();
$lang->workflowverification->placeholder->sql     = '直接寫入一條SQL查詢語句，只能進行查詢操作。';
$lang->workflowverification->placeholder->message = '校驗不通過時顯示的提示信息';
