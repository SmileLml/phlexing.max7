<?php
$lang->workflowdatasource->common   = '工作流數據源';
$lang->workflowdatasource->browse   = '瀏覽數據源';
$lang->workflowdatasource->create   = '添加數據源';
$lang->workflowdatasource->edit     = '編輯數據源';
$lang->workflowdatasource->view     = '數據源詳情';
$lang->workflowdatasource->delete   = '刪除數據源';
$lang->workflowdatasource->category = '維護分類';

$lang->workflowdatasource->id          = '編號';
$lang->workflowdatasource->type        = '類別';
$lang->workflowdatasource->name        = '名稱';
$lang->workflowdatasource->code        = '代號';
$lang->workflowdatasource->datasource  = '數據源';
$lang->workflowdatasource->buildin     = '內置';
$lang->workflowdatasource->createdBy   = '由誰創建';
$lang->workflowdatasource->createdDate = '創建時間';
$lang->workflowdatasource->editedBy    = '由誰編輯';
$lang->workflowdatasource->editedDate  = '編輯時間';

$lang->workflowdatasource->key         = '鍵';
$lang->workflowdatasource->value       = '值';
$lang->workflowdatasource->app         = '所屬應用';
$lang->workflowdatasource->module      = '所屬模組';
$lang->workflowdatasource->method      = '方法';
$lang->workflowdatasource->desc        = '描述';
$lang->workflowdatasource->param       = '參數';
$lang->workflowdatasource->paramType   = '類型';
$lang->workflowdatasource->paramValue  = '值';
$lang->workflowdatasource->sql         = 'SQL語句';

$lang->workflowdatasource->apps['sys'] = '通用';

$lang->workflowdatasource->options['user']        = '系統用戶';
$lang->workflowdatasource->options['dept']        = '系統部門';
$lang->workflowdatasource->options['deptManager'] = '部門經理';
$lang->workflowdatasource->options['actor']       = '操作人';
$lang->workflowdatasource->options['today']       = '操作日期';
$lang->workflowdatasource->options['now']         = '操作時間';
$lang->workflowdatasource->options['form']        = '表單數據';
$lang->workflowdatasource->options['record']      = '當前數據';
$lang->workflowdatasource->options['custom']      = '自定義';

$lang->workflowdatasource->typeList['option']   = '自定義選項';
$lang->workflowdatasource->typeList['sql']      = '自定義SQL';
$lang->workflowdatasource->typeList['category'] = '自定義分類';
$lang->workflowdatasource->typeList['system']   = '系統函數';
$lang->workflowdatasource->typeList['lang']     = '系統語言';

$lang->workflowdatasource->langList['orderStatus']      = '訂單狀態';
$lang->workflowdatasource->langList['orderReason']      = '訂單關閉原因';
$lang->workflowdatasource->langList['contractType']     = '合同類型';
$lang->workflowdatasource->langList['contractDelivery'] = '合同交付狀態';
$lang->workflowdatasource->langList['contractReturn']   = '合同回款狀態';
$lang->workflowdatasource->langList['contractPay']      = '合同付款狀態';
$lang->workflowdatasource->langList['contractStatus']   = '合同狀態';
$lang->workflowdatasource->langList['customerType']     = '客戶類型';
$lang->workflowdatasource->langList['customerRelation'] = '客戶關係';
$lang->workflowdatasource->langList['customerSource']   = '客戶來源';
$lang->workflowdatasource->langList['customerStatus']   = '客戶狀態';
$lang->workflowdatasource->langList['providerStatus']   = '供應商狀態';
$lang->workflowdatasource->langList['contactStatus']    = '聯繫人狀態';
$lang->workflowdatasource->langList['leadsStatus']      = '名單狀態';
$lang->workflowdatasource->langList['productType']      = '產品類型';
$lang->workflowdatasource->langList['productModel']     = '產品規格';
$lang->workflowdatasource->langList['productUnit']      = '產品單位';
$lang->workflowdatasource->langList['productStatus']    = '產品狀態';
$lang->workflowdatasource->langList['invoiceKind']      = '發票類別(銷項/進項)';
$lang->workflowdatasource->langList['invoiceType']      = '發票類型(普票/專票)';
$lang->workflowdatasource->langList['invoiceSaleType']  = '發票銷售類型(產品類/服務類)';
$lang->workflowdatasource->langList['invoiceSendway']   = '發票發送方式';
$lang->workflowdatasource->langList['invoiceStatus']    = '發票狀態';
$lang->workflowdatasource->langList['feedbackPri']      = '售後優先順序';
$lang->workflowdatasource->langList['feedbackType']     = '售後類型';
$lang->workflowdatasource->langList['feedbackReason']   = '售後關閉原因';
$lang->workflowdatasource->langList['feedbackStatus']   = '售後狀態';
$lang->workflowdatasource->langList['public']           = '是否公開';
$lang->workflowdatasource->langList['deleted']          = '是否刪除';
$lang->workflowdatasource->langList['gender']           = '性別';
$lang->workflowdatasource->langList['role']             = '角色';
$lang->workflowdatasource->langList['express']          = '快遞';
$lang->workflowdatasource->langList['bank']             = '銀行';

$lang->workflowdatasource->placeholder = new stdclass();
$lang->workflowdatasource->placeholder->optionCode = '可以使用字母或數字';
$lang->workflowdatasource->placeholder->sql        = '直接寫入一條SQL查詢語句，只能進行查詢操作，禁止其他SQL操作。查詢結果是鍵值對。查詢語句的第一個欄位作為鍵，第二個欄位作為值，其它欄位會被忽略。如果只有一個欄位，這個欄位同時作為鍵和值。';

$lang->workflowdatasource->error = new stdclass();;
$lang->workflowdatasource->error->emptyOptions        = '請輸入選項的『鍵』和『值』。';
$lang->workflowdatasource->error->buildinCannotDelete = '內置數據源不允許刪除。';
