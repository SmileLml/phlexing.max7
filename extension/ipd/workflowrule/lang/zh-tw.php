<?php
$lang->workflowrule->common = '工作流驗證規則';
$lang->workflowrule->browse = '瀏覽規則';
$lang->workflowrule->create = '添加規則';
$lang->workflowrule->edit   = '編輯規則';
$lang->workflowrule->view   = '規則詳情';
$lang->workflowrule->delete = '刪除規則';

$lang->workflowrule->id          = '編號';
$lang->workflowrule->type        = '類別';
$lang->workflowrule->name        = '名稱';
$lang->workflowrule->rule        = '規則';
$lang->workflowrule->createdBy   = '由誰創建';
$lang->workflowrule->createdDate = '創建時間';
$lang->workflowrule->editedBy    = '由誰編輯';
$lang->workflowrule->editedDate  = '編輯時間';

$lang->workflowrule->typeList['system'] = '系統函數';
$lang->workflowrule->typeList['regex']  = '正則表達式';
$lang->workflowrule->typeList['func']   = '函數';

$lang->workflowrule->default = new stdclass();
$lang->workflowrule->default->rules['notempty'] = '必填';
$lang->workflowrule->default->rules['date']     = '日期';
$lang->workflowrule->default->rules['email']    = 'email';
$lang->workflowrule->default->rules['float']    = '數字';
$lang->workflowrule->default->rules['phone']    = '電話';
$lang->workflowrule->default->rules['ip']       = 'IP';

$lang->workflowrule->error = new stdclass();
$lang->workflowrule->error->wrongRegex = '正則表達式有錯！錯誤：';

$lang->workflowrule->placeholder = new stdclass();
$lang->workflowrule->placeholder->regex = '正則表達式需要包含定界符';
