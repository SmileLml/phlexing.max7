<?php
$lang->workflowlayout->common  = '工作流界面';
$lang->workflowlayout->admin   = '維護界面';

$lang->workflowlayout->id           = '編號';
$lang->workflowlayout->module       = '所屬流程';
$lang->workflowlayout->action       = '所屬動作';
$lang->workflowlayout->field        = '欄位';
$lang->workflowlayout->order        = '順序';
$lang->workflowlayout->width        = '寬度';
$lang->workflowlayout->position     = '位置';
$lang->workflowlayout->alignment    = '對齊';
$lang->workflowlayout->readonly     = '只讀';
$lang->workflowlayout->mobileShow   = '移動端';
$lang->workflowlayout->summary      = '統計信息';
$lang->workflowlayout->defaultValue = '預設值';
$lang->workflowlayout->layoutRules  = '驗證規則';
$lang->workflowlayout->blockName    = '區塊名稱';
$lang->workflowlayout->tabName      = '標籤名稱';

$lang->workflowlayout->show     = '顯示';
$lang->workflowlayout->hide     = '隱藏';
$lang->workflowlayout->require  = '必選';
$lang->workflowlayout->custom   = '自定義';
$lang->workflowlayout->block    = '區塊設計';
$lang->workflowlayout->showName = '顯示名稱';
$lang->workflowlayout->addBlock = '添加區塊';
$lang->workflowlayout->addTab   = '添加標籤';
$lang->workflowlayout->addUI    = '建界面';
$lang->workflowlayout->editUI   = '編輯界面';
$lang->workflowlayout->deleteUI = '刪除界面';
$lang->workflowlayout->design   = '設計';

$lang->workflowlayout->ui = new stdclass();
$lang->workflowlayout->ui->name      = '界面名稱';
$lang->workflowlayout->ui->other     = '其他界麵條件';
$lang->workflowlayout->ui->condition = '觸發條件';

$lang->workflowlayout->positionList['browse']['left']   = '居左';
$lang->workflowlayout->positionList['browse']['center'] = '居中';
$lang->workflowlayout->positionList['browse']['right']  = '居右';

$lang->workflowlayout->positionList['view']['basic'] = '基本信息';
$lang->workflowlayout->positionList['view']['info']  = '詳細信息';

$lang->workflowlayout->positionList['edit']['basic'] = '居右';
$lang->workflowlayout->positionList['edit']['info']  = '居左';

$lang->workflowlayout->mobileList[1] = '顯示';
$lang->workflowlayout->mobileList[0] = '不顯示';

$lang->workflowlayout->summaryList['sum']     = '合計值';
$lang->workflowlayout->summaryList['average'] = '平均值';
$lang->workflowlayout->summaryList['max']     = '最大值';
$lang->workflowlayout->summaryList['min']     = '最小值';

$lang->workflowlayout->default = new stdclass();
$lang->workflowlayout->default->user['currentUser'] = '當前用戶';
$lang->workflowlayout->default->user['deptManager'] = '部門經理';
$lang->workflowlayout->default->dept['currentDept'] = '當前部門';
$lang->workflowlayout->default->time['currentTime'] = '當前時間';

$lang->workflowlayout->tips = new stdclass();
$lang->workflowlayout->tips->position      = '基本信息是右邊側欄位置，詳細信息是左側主欄位置';
$lang->workflowlayout->tips->confirmDelete = '您確定要刪除該界面嗎？刪除後不可恢復';
$lang->workflowlayout->tips->duplicateUI   = '『%s』界面觸發條件的『%s』欄位';

$lang->workflowlayout->error = new stdclass();
$lang->workflowlayout->error->mobileShow          = '移動端列表頁面最多顯示5個欄位';
$lang->workflowlayout->error->emptyCustomFields   = "您還沒有添加欄位，請前往 <a href='%s' class='alert-link'>主表設計</a> 添加欄位。";
$lang->workflowlayout->error->emptyLayout         = "您還沒有為動作 <strong>%s</strong> 配置界面，會影響您的正常使用，請先配置界面。<br>如果以上動作無需配置界面，請切換到<strong>高級編輯器</strong>，將動作的<strong>打開方式</strong>改為“無頁面”，或者將動作的<strong>狀態</strong>改為“停用”。";
$lang->workflowlayout->error->emptyConditions     = "新界面不能沒有觸發條件。";
$lang->workflowlayout->error->duplicateConditions = "當前的觸發條件和%s存在重複的可能性。";
$lang->workflowlayout->error->contradiction       = "當前的觸發條件設置有矛盾，請重新檢查。";
