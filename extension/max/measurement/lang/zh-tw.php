<?php
$lang->measurement->common       = '度量';
$lang->measurement->setTips      = '區間提示';
$lang->measurement->scope        = '範圍';
$lang->measurement->object       = '對象';
$lang->measurement->purpose      = '目的';
$lang->measurement->code         = '代號';
$lang->measurement->order        = '排序';
$lang->measurement->returns      = '返回類型';
$lang->measurement->definition   = '度量定義';
$lang->measurement->name         = '度量名稱';
$lang->measurement->type         = '度量類型';
$lang->measurement->params       = '參數設置';
$lang->measurement->basicMeas    = '基本度量';
$lang->measurement->deriveMeas   = '衍生度量';
$lang->measurement->measList     = '度量列表';
$lang->measurement->reportList   = '報告列表';
$lang->measurement->saveReport   = '保存此報告';
$lang->measurement->saveReportAB = '保存報告';
$lang->measurement->test         = '測試度量';
$lang->measurement->batchEdit    = '批量編輯';
$lang->measurement->sqlBuilder   = '度量數據';
$lang->measurement->template     = '報表模板';
$lang->measurement->model        = $lang->projectCommon .'模型';

$lang->measurement->browseAction    = '度量定義列表';
$lang->measurement->batchEditAction = '批量編輯度量';
$lang->measurement->deleteAction    = '刪除度量';

$lang->measurement->modelList['waterfall'] = '瀑布';
$lang->measurement->modelList['scrum']     = '敏捷';

$lang->measurement->report = new stdclass;
$lang->measurement->report->name        = '報告名稱';
$lang->measurement->report->program     = $lang->projectCommon;
$lang->measurement->report->product     = $lang->productCommon;
$lang->measurement->report->project     = '階段';
$lang->measurement->report->createdBy   = '創建人';
$lang->measurement->report->createdDate = '創建時間';

$lang->measurement->searchMeas         = "搜索度量";
$lang->measurement->designPHP          = "設置PHP";
$lang->measurement->designSQL          = "設置度量SQL";
$lang->measurement->initCrontabQueue   = "初始化計劃任務隊列";
$lang->measurement->execCrontabQueue   = "處理計劃任務隊列";
$lang->measurement->saveSqlMeasSuccess = "查詢成功，測試結果：%s";

$lang->measurement->actionConfig = "動作設置";
$lang->measurement->moduleName   = '模組名稱';
$lang->measurement->actionName   = '動作名稱';
$lang->measurement->cycleConfig  = "定時設置";
$lang->measurement->execTime     = "執行時間";
$lang->measurement->cycleDay     = '天';
$lang->measurement->cycleWeek    = '周';
$lang->measurement->cycleMonth   = '月';
$lang->measurement->every        = '間隔';
$lang->measurement->dayNames     = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

$lang->measurement->cycleType['day']   = '每隔%s天';
$lang->measurement->cycleType['week']  = '每週%s';
$lang->measurement->cycleType['month'] = '每月%s';

$lang->measurement->scopeList[''] = '';
$lang->measurement->scopeList['project'] = $lang->projectCommon;
$lang->measurement->scopeList['product'] = $lang->productCommon;
$lang->measurement->scopeList['sprint']  = '階段';

$lang->measurement->purposeList[''] = '';
$lang->measurement->purposeList['scale']    = '規模';
$lang->measurement->purposeList['duration'] = '工期';
$lang->measurement->purposeList['workload'] = '工作量';
$lang->measurement->purposeList['cost']     = '成本';
$lang->measurement->purposeList['quality']  = '質量';

$lang->measurement->objectList[''] = '';
$lang->measurement->objectList['staff']       = '人員';
$lang->measurement->objectList['finance']     = '任務';
$lang->measurement->objectList['case']        = '用例';
$lang->measurement->objectList['bug']         = '缺陷';
$lang->measurement->objectList['review']      = '評審';
$lang->measurement->objectList['stage']       = '階段';
$lang->measurement->objectList['program']     = $lang->projectCommon;
$lang->measurement->objectList['softRequest'] = '軟件需求';
$lang->measurement->objectList['userRequest'] = '用戶需求';

$lang->measurement->typeList['basic']  = $lang->measurement->basicMeas;

$lang->measurement->sysData = $lang->measurement->typeList;
$lang->measurement->sysData['report'] = '單一報表';

$lang->measurement->collectTypeList['crontab'] = '定時計劃';
$lang->measurement->collectTypeList['action']  = '動作觸發';

$lang->measurement->buildinParams = new stdclass;
$lang->measurement->buildinParams->program = $lang->projectCommon;
$lang->measurement->buildinParams->day     = '日期';

$lang->measurement->codeExistence    = '度量代號：%s已存在。';
$lang->measurement->codeEmpty        = 'ID：%s的度量代號不能為空。';
$lang->measurement->nameEmpty        = 'ID：%s的度量名稱不能為空。';
$lang->measurement->unitEmpty        = 'ID：%s的度量單位不能為空。';
$lang->measurement->definitionEmpty  = 'ID：%s的度量定義不能為空。';

$lang->measurement->noticeScope      = '通知範圍';
$lang->measurement->design           = '設計';
$lang->measurement->browse           = '瀏覽列表';
$lang->measurement->browseBasic      = '基本度量';
$lang->measurement->browseDerivation = '衍生度量';
$lang->measurement->create           = '創建新度量';
$lang->measurement->createBasic      = $lang->measurement->create;
$lang->measurement->editBasic        = '編輯基本度量';
$lang->measurement->editDerivation   = '編輯衍生度量';
$lang->measurement->delete           = '刪除';
$lang->measurement->deleted          = '已刪除';
$lang->measurement->collectType      = '收集方式';
$lang->measurement->collectConf      = '收集配置';
$lang->measurement->collectedBy      = '收集人';
$lang->measurement->unit             = '度量單位';
$lang->measurement->save             = '保存';
$lang->measurement->saveSuccess      = '保存成功';
$lang->measurement->reDesign         = '重新設計';
$lang->measurement->confirmDelete    = '確定刪除嗎？';
$lang->measurement->options          = '操作';
$lang->measurement->id               = '編號';
$lang->measurement->createTemplate   = '創建複合模板';
$lang->measurement->createSingle     = '創建單一模板';
$lang->measurement->editTemplate     = '編輯模板';
$lang->measurement->viewTemplate     = '查看模板';
$lang->measurement->content          = '內容';
$lang->measurement->addMeas          = '添加度量項';
$lang->measurement->dataSource       = '數據源';
$lang->measurement->dataName         = '數據標識';
$lang->measurement->setSQL           = '設置SQL語句';
$lang->measurement->setPHP           = '設置PHP代碼';
$lang->measurement->callSqlBuilder   = '調用SQL構建器';
$lang->measurement->query            = '查詢';
$lang->measurement->byQuery          = '搜索';
$lang->measurement->call             = '調用';
$lang->measurement->queryResult      = '查詢結果：';
$lang->measurement->setParams        = '設置參數';
$lang->measurement->createdBy        = '由誰創建';
$lang->measurement->createdDate      = '創建日期';
$lang->measurement->purpose          = '目的';
$lang->measurement->aim              = '度量目標';
$lang->measurement->analyst          = '分析人';
$lang->measurement->analysisMethod   = '分析方法';

$lang->measurement->placeholder = new stdclass();
$lang->measurement->placeholder->sql = '請填寫完整的mysql自定義函數語句。';
$lang->measurement->placeholder->php = '請按照系統要求的編碼，類名不能修改，必須包含get 方法。';
$lang->measurement->codeTemplate = <<<EOT
<?php
class %sModel extends model
{
    public function get(\$param1)
    {
        return \$param1 + \$param2;
    }
}
?>
EOT;

$lang->measurement->sqlTemplate = <<<EOT
CREATE FUNCTION `%s`(%s) RETURNS
BEGIN

END
EOT;

$lang->measurement->param = new stdclass();
$lang->measurement->param->varName      = '變數名';
$lang->measurement->param->showName     = '顯示名';
$lang->measurement->param->varType      = '類型';
$lang->measurement->param->defaultValue = '預設值';
$lang->measurement->param->queryValue   = '測試值';

$lang->measurement->param->typeList['input']   = '文本框';
$lang->measurement->param->typeList['date']    = '日期';
$lang->measurement->param->typeList['select']  = '下拉菜單';

$lang->measurement->param->options['project'] = $lang->projectCommon .'列表';
$lang->measurement->param->options['product'] = $lang->productCommon . '列表';
$lang->measurement->param->options['sprint']  = $lang->projectCommon . '列表';

$lang->measurement->tips = new stdclass();
$lang->measurement->tips->nameError        = 'Mysql 自定義函數名錯誤，請檢查函數名。';
$lang->measurement->tips->createError      = "創建 Mysql 自定義函數失敗，錯誤信息：<br/> %s";
$lang->measurement->tips->noticeSelect     = 'SQL語句只能是查詢語句';
$lang->measurement->tips->noticeBlack      = 'SQL中含有禁用SQL關鍵字 %s';
$lang->measurement->tips->noticeVarName    = '變數名稱沒有設置';
$lang->measurement->tips->noticeVarType    = '變數 %s 的類型沒有設置';
$lang->measurement->tips->noticeShowName   = '變數 %s 的顯示名稱沒有設置';
$lang->measurement->tips->noticeQueryValue = '變數 %s 的測試值沒有設置。';
$lang->measurement->tips->showNameMissed   = '變數 %s 的顯示名沒有設置。';
$lang->measurement->tips->errorSql         = 'SQL語句有錯！錯誤：';
$lang->measurement->tips->click2SetParams  = '請先點擊紅色變數塊設置參數，然後';
$lang->measurement->tips->view             = '預覽';
$lang->measurement->tips->click2InsertData = "點擊 <span class='ke-icon-holder'></span> 來插入度量指標或報表";

$lang->basicmeas = new stdclass();
$lang->basicmeas->name       = $lang->measurement->name;
$lang->basicmeas->code       = $lang->measurement->code;
$lang->basicmeas->unit       = $lang->measurement->unit;
$lang->basicmeas->definition = $lang->measurement->definition;

$lang->derivemeas = new stdclass();
$lang->derivemeas->name    = $lang->measurement->name;
$lang->derivemeas->purpose = $lang->measurement->purpose;

$lang->meastemplate = new stdclass();
$lang->meastemplate->id          = '編號';
$lang->meastemplate->single      = '單一模板';
$lang->meastemplate->complex     = '複合模板';
$lang->meastemplate->name        = '模板名稱';
$lang->meastemplate->desc        = '描述';
$lang->meastemplate->content     = '內容';
$lang->meastemplate->createdBy   = '由誰創建';
$lang->meastemplate->createdDate = '創建時間';
$lang->meastemplate->addedBy     = '由誰創建';
$lang->meastemplate->addedDate   = '創建時間';

$lang->meastemplate->actions = array();
$lang->measurement->actions[] = '單元測試前';
$lang->measurement->actions[] = '測試完成後';
$lang->measurement->actions[] = '測試報告評審結束';
$lang->measurement->actions[] = '測試計劃評審';
$lang->measurement->actions[] = '里程碑報告評審後';
$lang->measurement->actions[] = '里程碑評審過後';
$lang->measurement->actions[] = '量化' . $lang->projectCommon . '監控過程中';
$lang->measurement->actions[] = '需求完成後';
$lang->measurement->actions[] = '需求評審後';
$lang->measurement->actions[] = '需求說明書編撰完成後';
$lang->measurement->actions[] = '需求說明書評審';
$lang->measurement->actions[] = '需求里程碑過後';
$lang->measurement->actions[] = $lang->projectCommon .'計劃評審後';
$lang->measurement->actions[] = $lang->projectCommon .'計劃評審結束';

$lang->measurement->actions['project.close'] = $lang->projectCommon . '結束';
