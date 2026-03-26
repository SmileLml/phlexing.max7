<?php
$lang->workflowfield->common         = '工作流欄位';
$lang->workflowfield->browse         = '瀏覽欄位';
$lang->workflowfield->create         = '添加欄位';
$lang->workflowfield->edit           = '編輯欄位';
$lang->workflowfield->quote          = '使用其他流程欄位';
$lang->workflowfield->delete         = '刪除欄位';
$lang->workflowfield->import         = '導入欄位';
$lang->workflowfield->showImport     = '導入確認';
$lang->workflowfield->exportTemplate = '下載模板';
$lang->workflowfield->sort           = '欄位排序';
$lang->workflowfield->setValue       = '顯示值設置';
$lang->workflowfield->setExport      = '導出設置';
$lang->workflowfield->setSearch      = '搜索設置';
$lang->workflowfield->settings       = '欄位及屬性設置';

$lang->workflowfield->id           = '編號';
$lang->workflowfield->module       = '所屬流程';
$lang->workflowfield->field        = '代號';
$lang->workflowfield->type         = '數據類型';
$lang->workflowfield->length       = '長度';
$lang->workflowfield->name         = '名稱';
$lang->workflowfield->control      = '控件';
$lang->workflowfield->group        = '來源流程';
$lang->workflowfield->expression   = '計算方式';
$lang->workflowfield->options      = '選項';
$lang->workflowfield->defaultValue = '預設值';
$lang->workflowfield->rules        = '驗證規則';
$lang->workflowfield->tipInfo      = '提示信息';
$lang->workflowfield->canSetValue  = '啟用顯示值設置功能';
$lang->workflowfield->canExport    = '啟用導出功能';
$lang->workflowfield->extendExport = '擴展導出功能';
$lang->workflowfield->canSearch    = '啟用搜索功能';
$lang->workflowfield->extendSearch = '擴展搜索功能';
$lang->workflowfield->isKeyValue   = '鍵值';
$lang->workflowfield->order        = '順序';
$lang->workflowfield->buildin      = '內置';
$lang->workflowfield->desc         = '描述';
$lang->workflowfield->createdBy    = '由誰創建';
$lang->workflowfield->createdDate  = '創建時間';
$lang->workflowfield->editedBy     = '由誰編輯';
$lang->workflowfield->editedDate   = '編輯時間';

$lang->workflowfield->position         = '位於';
$lang->workflowfield->datasource       = '數據源';
$lang->workflowfield->sql              = 'SQL';
$lang->workflowfield->vars             = '變數';
$lang->workflowfield->addVar           = '添加變數';
$lang->workflowfield->varName          = '變數名稱';
$lang->workflowfield->showName         = '顯示名稱';
$lang->workflowfield->requestType      = '輸入方式';
$lang->workflowfield->status           = '狀態';
$lang->workflowfield->subStatus        = '子狀態';
$lang->workflowfield->key              = '鍵';
$lang->workflowfield->value            = '值';
$lang->workflowfield->defaultSubStatus = '預設值';
$lang->workflowfield->fieldName        = '欄位名稱';
$lang->workflowfield->tableParent      = '主表編號';
$lang->workflowfield->template         = '欄位模板';
$lang->workflowfield->detail           = '『%s』詳細信息';
$lang->workflowfield->use              = '使用欄位';
$lang->workflowfield->remove           = '移除欄位';

$lang->workflowfield->integerDigits = '整數位數';
$lang->workflowfield->decimalDigits = '小數位數';

$lang->workflowfield->typeGroup['integer']  = '整數';
$lang->workflowfield->typeGroup['decimal']  = '小數';
$lang->workflowfield->typeGroup['date']     = '日期';
$lang->workflowfield->typeGroup['time']     = '時間';
$lang->workflowfield->typeGroup['varchar']  = '單行文本';
$lang->workflowfield->typeGroup['text']     = '多行文本';

$lang->workflowfield->controlTypeList['label']        = '標籤';
$lang->workflowfield->controlTypeList['input']        = '單行文本';
$lang->workflowfield->controlTypeList['textarea']     = '多行文本';
$lang->workflowfield->controlTypeList['select']       = '單選下拉';
$lang->workflowfield->controlTypeList['multi-select'] = '多選下拉';
$lang->workflowfield->controlTypeList['radio']        = '單選按鈕';
$lang->workflowfield->controlTypeList['checkbox']     = '複選框';
$lang->workflowfield->controlTypeList['richtext']     = '富文本';
$lang->workflowfield->controlTypeList['date']         = '日期';
$lang->workflowfield->controlTypeList['datetime']     = '時間';
$lang->workflowfield->controlTypeList['decimal']      = '小數';
$lang->workflowfield->controlTypeList['integer']      = '整數';
$lang->workflowfield->controlTypeList['formula']      = '公式';
$lang->workflowfield->controlTypeList['file']         = '附件';

$lang->workflowfield->optionTypeList['sql']        = '自定義SQL';
$lang->workflowfield->optionTypeList['category']   = '分類設置';
$lang->workflowfield->optionTypeList['prevModule'] = '前置流程';

$lang->workflowfield->positionList['before'] = '之前';
$lang->workflowfield->positionList['after']  = '之後';

$lang->workflowfield->exportList[1] = '可以導出';
$lang->workflowfield->exportList[0] = '不能導出';

$lang->workflowfield->searchList[1] = '可以檢索';
$lang->workflowfield->searchList[0] = '不能檢索';

$lang->workflowfield->keyValueList['key']   = '鍵';
$lang->workflowfield->keyValueList['value'] = '值';

$lang->workflowfield->buildinList['0'] = '否';
$lang->workflowfield->buildinList['1'] = '是';

$lang->workflowfield->all['program']   = '所有項目集';
$lang->workflowfield->all['product']   = '所有產品';
$lang->workflowfield->all['project']   = '所有項目';
$lang->workflowfield->all['execution'] = '所有執行';

$lang->workflowfield->default = new stdclass();
$lang->workflowfield->default->fields['id']           = '編號';
$lang->workflowfield->default->fields['parent']       = '父流程ID';
$lang->workflowfield->default->fields['program']      = '所屬項目集';
$lang->workflowfield->default->fields['product']      = '所屬產品';
$lang->workflowfield->default->fields['project']      = '所屬項目';
$lang->workflowfield->default->fields['execution']    = '所屬執行';
$lang->workflowfield->default->fields['assignedTo']   = '指派給';
$lang->workflowfield->default->fields['status']       = '狀態';
$lang->workflowfield->default->fields['createdBy']    = '由誰創建';
$lang->workflowfield->default->fields['createdDate']  = '創建日期';
$lang->workflowfield->default->fields['editedBy']     = '由誰編輯';
$lang->workflowfield->default->fields['editedDate']   = '編輯日期';
$lang->workflowfield->default->fields['assignedBy']   = '由誰指派';
$lang->workflowfield->default->fields['assignedDate'] = '指派日期';
$lang->workflowfield->default->fields['mailto']       = '抄送給';
$lang->workflowfield->default->fields['deleted']      = '是否刪除';

$lang->workflowfield->default->options = new stdclass();
$lang->workflowfield->default->options->deleted = array();
$lang->workflowfield->default->options->deleted['0'] = '未刪除';
$lang->workflowfield->default->options->deleted['1'] = '已刪除';

$lang->workflowfield->approval = new stdclass();
$lang->workflowfield->approval->fields['reviewers']     = '審批人';
$lang->workflowfield->approval->fields['reviewOpinion'] = '審批意見';
$lang->workflowfield->approval->fields['reviewResult']  = '審批結果';
$lang->workflowfield->approval->fields['reviewStatus']  = '審批狀態';
$lang->workflowfield->approval->fields['approval']      = '審批流編號';

$lang->workflowfield->approval->options = new stdclass();
$lang->workflowfield->approval->options->reviewResult = array();
$lang->workflowfield->approval->options->reviewResult['pass']   = '通過';
$lang->workflowfield->approval->options->reviewResult['reject'] = '不通過';

$lang->workflowfield->approval->options->reviewStatus = array();
$lang->workflowfield->approval->options->reviewStatus['wait']      = '待審批';
$lang->workflowfield->approval->options->reviewStatus['doing']     = '審批中';
$lang->workflowfield->approval->options->reviewStatus['pass']      = '通過';
$lang->workflowfield->approval->options->reviewStatus['reject']    = '不通過';
$lang->workflowfield->approval->options->reviewStatus['reverting'] = '回退中';

/* Tips */
$lang->workflowfield->tips = new stdclass();
$lang->workflowfield->tips->keyValue           = '<strong>顯示值</strong> 在其他流程調用本流程數據時生效。<br /><strong>顯示值</strong> 可以有多個，多個值以空格拼接顯示。<br />如果沒有設置顯示值，則按照 <strong>流程名稱 + ID</strong> 的格式顯示。';
$lang->workflowfield->tips->lengthNotice       = '該類型修改可能會造成數據丟失，請慎重使用！';
$lang->workflowfield->tips->emptyStatus        = '請先設置狀態欄位的選項值，再設置子狀態的選項值。';
$lang->workflowfield->tips->emptySubStatus     = '<strong>%s</strong>的子狀態的選項值不能為空。';
$lang->workflowfield->tips->emptyValue         = '您沒有選擇任何欄位，無法啟用顯示值設置功能。確定保存此設置嗎？';
$lang->workflowfield->tips->emptyExport        = '您沒有為表 <strong>TABLE</strong> 選擇任何欄位，該表無法啟用導出功能。確定保存此設置嗎？';
$lang->workflowfield->tips->emptySearch        = '您沒有選擇任何欄位，無法啟用搜索功能。確定保存此設置嗎？';
$lang->workflowfield->tips->number             = '最大值 MAX，最小值 MIN。';
$lang->workflowfield->tips->string             = '最多可以輸入 LENGTH 個字元和標點符號。';
$lang->workflowfield->tips->syncQuoteFields    = '該欄位已在『%s』中引用，修改後將同步修改。（選項、預設值、驗證規則不同步）';
$lang->workflowfield->tips->syncQuoteNoOptions = "該欄位已在『%s』中引用，修改後將同步修改。<span class='nosyncbox'>（預設值、驗證規則不同步）</span>";
$lang->workflowfield->tips->noQuoteFields      = '其他流程沒有可以引用的欄位。';
$lang->workflowfield->tips->placeholder        = '如果設置了預設值，提示信息在前端將不會預設顯示。';

/* Placeholder */
$lang->workflowfield->placeholder = new stdclass();
$lang->workflowfield->placeholder->code          = '只能包含英文字母';
$lang->workflowfield->placeholder->sql           = '直接寫入一條SQL查詢語句，只能進行查詢操作，禁止其他SQL操作。查詢結果是鍵值對。查詢語句的第一個欄位作為鍵，第二個欄位作為值，其它欄位會被忽略。如果只有一個欄位，這個欄位同時作為鍵和值。';
$lang->workflowfield->placeholder->defaultValue  = '多個值之間用空格或逗號隔開';
$lang->workflowfield->placeholder->optionCode    = '可以使用字母或數字';
$lang->workflowfield->placeholder->auto          = '系統自動生成';
$lang->workflowfield->placeholder->varcharLength = '預設長度255，最大長度1000，最小長度1';
$lang->workflowfield->placeholder->charLength    = '預設長度50，最大長度255，最小長度1';
$lang->workflowfield->placeholder->integerDigits = '預設位數10，最大位數12，最小位數1';
$lang->workflowfield->placeholder->decimalDigits = '預設位數2，最大位數5，最小位數1';

/* Alert */
$lang->workflowfield->alert = new stdclass();
$lang->workflowfield->alert->common                       = '警告';
$lang->workflowfield->alert->update                       = '系統檢測到欄位可能在以下功能中使用，無法自動更新，請檢查並手動更新：';
$lang->workflowfield->alert->delete                       = '系統檢測到欄位可能在以下功能中使用，無法自動刪除，請檢查並手動刪除：';
$lang->workflowfield->alert->separater                    = '，';
$lang->workflowfield->alert->confirmDelete                = '您確定要執行刪除操作嗎？';
$lang->workflowfield->alert->confirmDeleteHasQuote        = '刪除後，其他流程中引用該欄位的數據均被同步刪除，操作不可逆，您確定要刪除嗎？';
$lang->workflowfield->alert->confirmDeleteInQuote         = '移除後，該工作流內使用此欄位的配置均同步刪除，操作不可逆，您確定要移除嗎？';
$lang->workflowfield->alert->types['fieldExpression']     = '<strong>%s</strong>流程的<strong>%s欄位的公式</strong>';
$lang->workflowfield->alert->types['conditionSql']        = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>觸發條件的SQL</strong>';
$lang->workflowfield->alert->types['verificationSql']     = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>數據校驗的SQL</strong>';
$lang->workflowfield->alert->types['verificationSqlVar']  = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>數據校驗的SQL的%s變數</strong>';
$lang->workflowfield->alert->types['hookConditionSql']    = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的觸發條件的SQL</strong>';
$lang->workflowfield->alert->types['hookConditionSqlVar'] = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的觸發條件的SQL的%s變數</strong>';
$lang->workflowfield->alert->types['hookConditionField']  = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的觸發條件的%s欄位</strong>';
$lang->workflowfield->alert->types['hookField']           = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的%s欄位</strong>';
$lang->workflowfield->alert->types['hookFieldFormula']    = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的%s欄位的公式</strong>';
$lang->workflowfield->alert->types['hookWhere']           = '<strong>%s</strong>流程的<strong>%s</strong>動作的<strong>擴展動作的%s條件</strong>';

/* Error */
$lang->workflowfield->error = new stdclass();
$lang->workflowfield->error->remainFields     = '<strong> %s </strong>為系統保留關鍵字，請更改欄位代號。';
$lang->workflowfield->error->emptyOptions     = '請輸入選項的<strong>鍵</strong>和<strong>值</strong>。';
$lang->workflowfield->error->wrongCode        = '<strong> %s </strong>只能包含英文字母。';
$lang->workflowfield->error->longCode         = '<strong> %s </strong>長度不能超過30。';
$lang->workflowfield->error->wrongSQL         = 'SQL語句有錯！錯誤：';
$lang->workflowfield->error->notunique        = '必須添加唯一驗證';
$lang->workflowfield->error->defaultValue     = '<strong> 預設值 </strong>的長度不應該超過%s。';
$lang->workflowfield->error->textDefaultValue = 'text類型欄位不能設置預設值';
$lang->workflowfield->error->duplicatedCode   = '<strong>鍵名</strong> %s 重複，請修改。';
$lang->workflowfield->error->duplicatedName   = '<strong>值</strong> %s 重複，請修改。';
$lang->workflowfield->error->emptyDefault     = '請選擇一個<strong>預設值</strong>。';
$lang->workflowfield->error->emptyCustomField = '您還沒有添加欄位，會影響您的正常使用，請先添加欄位。';
$lang->workflowfield->error->length           = '<strong>%s</strong>應該大於等於<strong>%s</strong>，且小於等於<strong>%s</strong>。';
$lang->workflowfield->error->digits           = '<strong>%s</strong>應該大於等於<strong>%s</strong>。';
$lang->workflowfield->error->dateFormat       = '<strong> 日期 </strong>格式錯誤，必須為Y-m-d。';
$lang->workflowfield->error->timeFormat       = '<strong> 時間 </strong>格式錯誤，必須為Y-m-d H:i。';
$lang->workflowfield->error->intSize          = '<strong> 預設值 </strong>應該大於等於%s，且小於等於%s。';
$lang->workflowfield->error->float            = '<strong> 預設值 </strong>應該為數字，可以是小數。';
$lang->workflowfield->error->existDelay       = '<strong> %s </strong>已在系統中使用，請更改欄位代號。';
$lang->workflowfield->error->unique           = '『%s』已被其他流程使用。如需使用此欄位，請從【使用其他流程欄位】中選擇。';
$lang->workflowfield->error->usedKey          = '鍵：『%s』已被『%s』使用，對應的值是：%s。';
$lang->workflowfield->error->usedValue        = '值：『%s』已被『%s』使用，對應的鍵是：%s。';

/* Formula */
$lang->workflowfield->formula = new stdclass();
$lang->workflowfield->formula->common    = '公式';
$lang->workflowfield->formula->target    = '計算對象';
$lang->workflowfield->formula->operator  = '計算符號';
$lang->workflowfield->formula->numbers   = '數字';
$lang->workflowfield->formula->clearLast = '刪除';
$lang->workflowfield->formula->clearAll  = '清空';
$lang->workflowfield->formula->set       = '設置';

$lang->workflowfield->formula->functions['sum']     = '%s_%s_合計值';
$lang->workflowfield->formula->functions['average'] = '%s_%s_平均值';
$lang->workflowfield->formula->functions['max']     = '%s_%s_最大值';
$lang->workflowfield->formula->functions['min']     = '%s_%s_最小值';
$lang->workflowfield->formula->functions['count']   = '%s_%s_計數';

$lang->workflowfield->formula->error = new stdclass();
$lang->workflowfield->formula->error->empty = '請為公式設置計算方式';
$lang->workflowfield->formula->error->error = '公式存在錯誤，請檢查後再保存';

$lang->workflowfield->excel = new stdclass();
$lang->workflowfield->excel->tips       = "1、欄位名稱和代號為必填項。\r\n2、“選項”的輸入格式為“鍵,值”，鍵/值之間用英文逗號分隔，多組鍵/值之間用換行分隔。";
$lang->workflowfield->excel->defaultTip = "\r\n3、預設值請填寫選項的鍵。";
