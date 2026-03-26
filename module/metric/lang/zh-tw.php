<?php
$lang->metric->common             = "度量項";
$lang->metric->metric             = "度量";
$lang->metric->name               = $lang->metric->common . "名稱";
$lang->metric->stage              = "階段";
$lang->metric->scope              = $lang->metric->metric . "範圍";
$lang->metric->object             = $lang->metric->metric . "對象";
$lang->metric->purpose            = $lang->metric->metric . "目的";
$lang->metric->dateType           = "時間屬性";
$lang->metric->unit               = $lang->metric->metric . "單位";
$lang->metric->alias              = $lang->metric->common . "簡稱";
$lang->metric->code               = $lang->metric->common . "代號";
$lang->metric->desc               = $lang->metric->common . "描述";
$lang->metric->formula            = "計算規則";
$lang->metric->when               = "收集方式";
$lang->metric->createdBy          = "創建者";
$lang->metric->implement          = "實現";
$lang->metric->delist             = "下架";
$lang->metric->implementedBy      = "由誰實現";
$lang->metric->offlineBy          = "由誰下架";
$lang->metric->lastEdited         = "最後修改";
$lang->metric->value              = "數值";
$lang->metric->date               = "日期";
$lang->metric->metricData         = "度量數據";
$lang->metric->system             = "system";
$lang->metric->weekCell           = "%s年第%s周";
$lang->metric->weekS              = "%s周";
$lang->metric->create             = "創建" . $this->lang->metric->common;
$lang->metric->edit               = "編輯" . $this->lang->metric->common;
$lang->metric->view               = '查看' . $this->lang->metric->common;
$lang->metric->afterCreate        = "保存後";
$lang->metric->definition         = "計算規則";
$lang->metric->declaration        = "度量定義";
$lang->metric->customUnit         = "自定義";
$lang->metric->delist             = "下架";
$lang->metric->preview            = "預覽";
$lang->metric->metricList         = "度量項列表";
$lang->metric->manage             = "管理度量項";
$lang->metric->exitManage         = "退出管理";
$lang->metric->filters            = '篩選器配置';
$lang->metric->details            = '詳情';
$lang->metric->remove             = '移除';
$lang->metric->zAnalysis          = 'Z分析';
$lang->metric->sqlStatement       = "SQL語句";
$lang->metric->other              = '其他';
$lang->metric->collectType        = '收集方式';
$lang->metric->oldMetricInfo      = '舊版詳情';
$lang->metric->collectConf        = '定時設置';
$lang->metric->verifyFile         = '校驗檔案';
$lang->metric->verifyResult       = '校驗結果';
$lang->metric->publish            = '發佈';
$lang->metric->moveFailTip        = '移動度量項檔案失敗';
$lang->metric->checkFile          = '檢查運行目錄下度量項檔案是否存在';
$lang->metric->deleteFile         = '請聯繫管理員刪除';
$lang->metric->selectCount        = '已選<span class="font-medium checked-count">%s</span>項';
$lang->metric->testMetric         = '測試度量';
$lang->metric->calcTime           = '採集時間';
$lang->metric->to                 = '至';
$lang->metric->year               = '年份';
$lang->metric->month              = '月份';
$lang->metric->week               = '周';
$lang->metric->day                = '日期';
$lang->metric->nodate             = '採集日期';
$lang->metric->implementType      = '實現方式';
$lang->metric->recalculate        = '重算全部度量項';
$lang->metric->setting            = '設置';
$lang->metric->recalculateAll     = '重算全部數據';
$lang->metric->recalculateHistory = '重算歷史數據';
$lang->metric->startRecalculate   = '開始重算';
$lang->metric->recalculateAction  = '重算度量項';
$lang->metric->recalculateBtnText = '重新計算';
$lang->metric->exit               = '完成';
$lang->metric->zentaoPath         = '【禪道目錄】';

$lang->metric->yearFormat      = '%s年';
$lang->metric->weekFormat      = '%s周';
$lang->metric->monthDayFormat  = '%s-%s';
$lang->metric->yearMonthFormat = '%s-%s';

$lang->metric->tableHeader = array();
$lang->metric->tableHeader['project']   = '項目名稱';
$lang->metric->tableHeader['product']   = '產品名稱';
$lang->metric->tableHeader['execution'] = '執行名稱';
$lang->metric->tableHeader['dept']      = '團隊名稱';
$lang->metric->tableHeader['user']      = '姓名';
$lang->metric->tableHeader['program']   = '項目集名稱';

$lang->metric->placeholder = new stdclass();
$lang->metric->placeholder->select    = "請選擇";
$lang->metric->placeholder->project   = "全部項目";
$lang->metric->placeholder->product   = "全部產品";
$lang->metric->placeholder->execution = "全部執行";
$lang->metric->placeholder->dept      = "全部部門";
$lang->metric->placeholder->user      = "全部用戶";
$lang->metric->placeholder->program   = "全部項目集";

$lang->metric->query = new stdclass();
$lang->metric->query->action = '查詢';

$lang->metric->calcTypeList = array();
$lang->metric->calcTypeList['cron']      = '快照採集';
$lang->metric->calcTypeList['inference'] = '重新計算';

$lang->metric->calcTitleList = array();
$lang->metric->calcTitleList['cron']      = '%user%于%date%進行快照採集';
$lang->metric->calcTitleList['inference'] = '%user%于%date%進行重新計算';

$lang->metric->query->scope = array();
$lang->metric->query->scope['project']   = '項目';
$lang->metric->query->scope['product']   = '產品';
$lang->metric->query->scope['execution'] = '執行';
$lang->metric->query->scope['dept']      = '團隊';
$lang->metric->query->scope['user']      = '姓名';
$lang->metric->query->scope['program']   = '項目集';

$lang->metric->query->yearLabels = array();
$lang->metric->query->yearLabels['3']   = '近3年';
$lang->metric->query->yearLabels['5']   = '近5年';
$lang->metric->query->yearLabels['10']  = '近10年';
$lang->metric->query->yearLabels['all'] = '全部';

$lang->metric->query->monthLabels = array();
$lang->metric->query->monthLabels['6']   = '近6個月';
$lang->metric->query->monthLabels['12']  = '近12個月';
$lang->metric->query->monthLabels['24']  = '近24個月';
$lang->metric->query->monthLabels['36']  = '近36個月';

$lang->metric->query->weekLabels = array();
$lang->metric->query->weekLabels['4']  = '近4周';
$lang->metric->query->weekLabels['8']  = '近8周';
$lang->metric->query->weekLabels['12'] = '近12周';
$lang->metric->query->weekLabels['16'] = '近16周';

$lang->metric->query->dayLabels = array();
$lang->metric->query->dayLabels['7']  = '近7天';
$lang->metric->query->dayLabels['14'] = '近14天';
$lang->metric->query->dayLabels['21'] = '近21天';
$lang->metric->query->dayLabels['28'] = '近28天';

$lang->metric->viewType = new stdclass();
$lang->metric->viewType->single   = '單獨查看';
$lang->metric->viewType->multiple = '組合查看';

$lang->metric->descTip            = '請輸入度量項含義、目的和作用等';
$lang->metric->definitionTip      = '請輸入度量項的計算規則及過濾條件等';
$lang->metric->aliasTip           = '在度量庫頁面會用簡稱展示此度量項';
$lang->metric->collectConfText    = "每%s的%s的%s";
$lang->metric->dirNotExist        = "目錄創建失敗，請創建目錄：<strong>%s</strong>。";
$lang->metric->fileMoveFail       = "拷貝檔案失敗，請將 <strong>%s</strong> 檔案拷貝到 <strong>%s</strong>。";
$lang->metric->emptyCollect       = '暫時沒有收藏度量項。';
$lang->metric->maxSelect          = '最多選擇%s個度量項';
$lang->metric->maxSelectTip       = '可跨範圍勾選多個度量項，最多選擇%s個。';
$lang->metric->upgradeTip         = '此度量項為舊版本支持的度量項，若想進行編輯，請根據新版本度量項的配置規則進行重新配置。同時請注意，新版本度量項不再支持SQL編輯器，暫時無法被報表模板引用。請確認是否需要進行編輯操作。';
$lang->metric->saveSqlMeasSuccess = "查詢成功，測試結果：%s";
$lang->metric->monthText          = "%s號";
$lang->metric->errorDateRange     = "開始日期不能大於結束日期";
$lang->metric->errorCalcTimeRange = "採集開始時間不能大於採集結束時間";
$lang->metric->updateTimeTip      = "更新快照時間：%s";
$lang->metric->builtinMetric      = "系統內置度量項，不可下架";

$lang->metric->noDesc    = "暫無描述";
$lang->metric->noFormula = "暫無計算規則";
$lang->metric->noCalc    = "暫未實現度量項PHP算法";
$lang->metric->noSQL     = "暫無";

$lang->metric->noData              = "暫時沒有數據。";
$lang->metric->noDataBeforeCollect = "未到收集數據的時間，暫無數據。";
$lang->metric->noDataAfterCollect  = "度量範圍內未產生數據，暫無數據。";

$lang->metric->legendBasicInfo  = '基本信息';
$lang->metric->legendCreateInfo = '創建編輯信息';

$lang->metric->confirmDelete       = "確認要刪除嗎？";
$lang->metric->confirmDelist       = "確認要下架嗎？";
$lang->metric->confirmDelistInUsed = "該度量項已被大屏引用，確認要下架嗎？";
$lang->metric->confirmRecalculate  = "重算結果可能會覆蓋已存在的度量值，是否繼續？";
$lang->metric->notExist            = "度量項不存在";

$lang->metric->browse          = '瀏覽度量項';
$lang->metric->browseAction    = '度量項列表';
$lang->metric->viewAction      = '查看度量項';
$lang->metric->editAction      = '編輯度量項';
$lang->metric->implementAction = '實現度量項';
$lang->metric->deleteAction    = '刪除度量項';
$lang->metric->delistAction    = '下架度量項';
$lang->metric->detailsAction   = '度量項詳情';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "未發佈";
$lang->metric->stageList['released'] = "已發佈";

$lang->metric->featureBar['browse']['all']      = '全部';
$lang->metric->featureBar['browse']['wait']     = '未發佈';
$lang->metric->featureBar['browse']['released'] = '已發佈';

$lang->metric->featureBar['preview']['project']   = '項目';
$lang->metric->featureBar['preview']['product']   = '產品';
$lang->metric->featureBar['preview']['execution'] = '執行';
$lang->metric->featureBar['preview']['user']      = '個人';
$lang->metric->featureBar['preview']['program']   = '項目集';
$lang->metric->featureBar['preview']['system']    = '系統';

$lang->metric->more        = '更多';
$lang->metric->collect     = '我收藏的';
$lang->metric->collectStar = '收藏';

$lang->metric->oldMetric      = new stdclass();
$lang->metric->oldMetric->sql = 'SQL';
$lang->metric->oldMetric->tip = '這是舊版度量項的實現方式';

$lang->metric->oldMetric->dayNames = array(1 => '星期一', 2 => '星期二', 3 => '星期三', 4 => '星期四', 5 => '星期五', 6 => '星期六', 0 => '星期日');

$lang->metric->moreSelects = array();

$lang->metric->unitList = array();
$lang->metric->unitList['count']      = '個';
$lang->metric->unitList['measure']    = '工時';
$lang->metric->unitList['hour']       = '小時';
$lang->metric->unitList['day']        = '天';
$lang->metric->unitList['manday']     = '人天';
$lang->metric->unitList['percentage'] = '百分比';
$lang->metric->unitList['times']      = '次';
$lang->metric->unitList['people']     = '人';
$lang->metric->unitList['row']        = '行';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']      = '返回列表頁';
$lang->metric->afterCreateList['implement'] = '去實現度量項';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = '年';
$lang->metric->dateList['month'] = '月';
$lang->metric->dateList['week']  = '周';
$lang->metric->dateList['day']   = '日';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "規模估算";
$lang->metric->purposeList['time']  = "工期控制";
$lang->metric->purposeList['cost']  = "成本計算";
$lang->metric->purposeList['hour']  = "工時統計";
$lang->metric->purposeList['qc']    = "質量控制";
$lang->metric->purposeList['rate']  = "效率提升";
$lang->metric->purposeList['other'] = "其他";

$lang->metric->scopeList = array();
$lang->metric->scopeList['project']   = "項目";
$lang->metric->scopeList['product']   = "產品";
$lang->metric->scopeList['execution'] = "執行";
$lang->metric->scopeList['user']      = "個人";
$lang->metric->scopeList['system']    = "系統";
$lang->metric->scopeList['other']     = "其他";
$lang->metric->scopeList['program']   = "項目集";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']       = "項目集";
$lang->metric->objectList['line']          = "產品綫";
$lang->metric->objectList['product']       = "產品";
$lang->metric->objectList['project']       = "項目";
$lang->metric->objectList['productplan']   = "計劃";
$lang->metric->objectList['execution']     = "執行";
$lang->metric->objectList['release']       = "發佈";
$lang->metric->objectList['epic']          = $lang->ERCommon;
$lang->metric->objectList['story']         = $lang->SRCommon;
$lang->metric->objectList['requirement']   = $lang->URCommon;
$lang->metric->objectList['task']          = "任務";
$lang->metric->objectList['bug']           = "Bug";
$lang->metric->objectList['case']          = "用例";
$lang->metric->objectList['user']          = "人員";
$lang->metric->objectList['effort']        = "工時";
$lang->metric->objectList['doc']           = "文檔";
if(in_array($config->edition, array('biz', 'max', 'ipd'))) $lang->metric->objectList['feedback'] = "反饋";
$lang->metric->objectList['review']        = "評審";
if($config->edition == 'ipd' && $config->vision == 'or') $lang->metric->objectList['demand'] = "需求池需求";
$lang->metric->objectList['codebase']      = "代碼庫";
$lang->metric->objectList['pipeline']      = "流水綫";
$lang->metric->objectList['artifact']      = "製品庫";
$lang->metric->objectList['deployment']    = "上線";
$lang->metric->objectList['node']          = "節點";
$lang->metric->objectList['application']   = "應用";
$lang->metric->objectList['cpu']           = "CPU";
$lang->metric->objectList['memory']        = "內存";
$lang->metric->objectList['commit']        = "代碼提交";
$lang->metric->objectList['mergeRequest']  = "合併請求";
$lang->metric->objectList['code']          = "代碼";
$lang->metric->objectList['vulnerability'] = "安全漏洞";
$lang->metric->objectList['codeAnalysis']  = "代碼分析";
if(in_array($config->edition, array('biz', 'max', 'ipd'))) $lang->metric->objectList['ticket'] = "工單";
if(in_array($config->edition, array('max', 'ipd')))
{
    $lang->metric->objectList['risk']  = "風險";
    $lang->metric->objectList['issue'] = "問題";
    $lang->metric->objectList['qa']    = "QA";
}
$lang->metric->objectList['host']  = "主機";
$lang->metric->objectList['other'] = "其他";

$lang->metric->chartTypeList = array();
$lang->metric->chartTypeList['line'] = '折線圖';
$lang->metric->chartTypeList['barX'] = '柱形圖';
$lang->metric->chartTypeList['barY'] = '條形圖';
$lang->metric->chartTypeList['pie']  = '餅圖';

$lang->metric->dateTypeList['nodate'] = '無';
$lang->metric->dateTypeList['year']   = '按年統計';
$lang->metric->dateTypeList['month']  = '按月統計';
$lang->metric->dateTypeList['week']   = '按周統計';
$lang->metric->dateTypeList['day']    = '按日統計';

$lang->metric->filter = new stdclass();
$lang->metric->filter->common  = '篩選';
$lang->metric->filter->scope   = '範圍';
$lang->metric->filter->object  = '對象';
$lang->metric->filter->purpose = '目的';
$lang->metric->filter->clear   = '全部清除';

$lang->metric->filter->clearAction = '清除已選%s';
$lang->metric->filter->checkedInfo = '已篩選：範圍(%s)、對象(%s)、目的(%s)';
$lang->metric->filter->filterTotal = '篩選結果(%s)';

$lang->metric->implement = new stdclass();
$lang->metric->implement->common      = "實現";
$lang->metric->implement->tip         = "請通過PHP實現該度量項的計算邏輯。";
$lang->metric->implement->instruction = "實現說明";
$lang->metric->implement->downloadPHP = "下載度量模板";

$lang->metric->implement->instructionTips = array();
$lang->metric->implement->instructionTips[] = '1.下載度量項模板檔案，對檔案進行編碼開發操作，操作參考手冊。<a class="btn text-primary ghost" target="_blank" href="https://www.zentao.net/book/zentaopms/1103.html">查看參考手冊>></a>';
$lang->metric->implement->instructionTips[] = '2.請將開發後的檔案放到下方目錄，<strong>需保持檔案名稱與度量代號一致</strong>。<br/> <span class="label code-slate">{tmpRoot}metric</span>';

$lang->metric->verifyCustom = new stdclass();
$lang->metric->verifyCustom->checkCustomCalcExists = array();
$lang->metric->verifyCustom->checkCustomCalcExists['text']       = '檢查度量項檔案是否存在';
$lang->metric->verifyCustom->checkCustomCalcExists['error']      = '度量項檔案不存在';

$lang->metric->verifyCustom->checkCustomCalcSyntax = array();
$lang->metric->verifyCustom->checkCustomCalcSyntax['text']       = '檢查語法錯誤';
$lang->metric->verifyCustom->checkCustomCalcSyntax['error']      = '語法錯誤';

$lang->metric->verifyCustom->checkCustomCalcClassName = array();
$lang->metric->verifyCustom->checkCustomCalcClassName['text']    = '檢查度量項類名是否正確';
$lang->metric->verifyCustom->checkCustomCalcClassName['error']   = '度量項類名錯誤';

$lang->metric->verifyCustom->checkCustomCalcClassMethod = array();
$lang->metric->verifyCustom->checkCustomCalcClassMethod['text']  = '檢查度量項是否定義了必須的方法';
$lang->metric->verifyCustom->checkCustomCalcClassMethod['error'] = '度量項沒有定義必須的方法';

$lang->metric->verifyCustom->checkCustomCalcRuntime = array();
$lang->metric->verifyCustom->checkCustomCalcRuntime['text']      = '檢查度量項運行時錯誤';
$lang->metric->verifyCustom->checkCustomCalcRuntime['error']     = '';

$lang->metric->weekList = array();
$lang->metric->weekList['1'] = '星期一';
$lang->metric->weekList['2'] = '星期二';
$lang->metric->weekList['3'] = '星期三';
$lang->metric->weekList['4'] = '星期四';
$lang->metric->weekList['5'] = '星期五';
$lang->metric->weekList['6'] = '星期六';
$lang->metric->weekList['0'] = '星期日';

$lang->metric->old = new stdclass();

$lang->metric->old->scopeList = array();
$lang->metric->old->scopeList['project'] = '項目';
$lang->metric->old->scopeList['product'] = '產品';
$lang->metric->old->scopeList['sprint']  = '階段';

$lang->metric->old->purposeList = array();
$lang->metric->old->purposeList['scale']    = '規模';
$lang->metric->old->purposeList['duration'] = '工期';
$lang->metric->old->purposeList['workload'] = '工作量';
$lang->metric->old->purposeList['cost']     = '成本';
$lang->metric->old->purposeList['quality']  = '質量';

$lang->metric->old->objectList = array();
$lang->metric->old->objectList['staff']       = '人員';
$lang->metric->old->objectList['finance']     = '任務';
$lang->metric->old->objectList['case']        = '用例';
$lang->metric->old->objectList['bug']         = '缺陷';
$lang->metric->old->objectList['review']      = '評審';
$lang->metric->old->objectList['stage']       = '階段';
$lang->metric->old->objectList['program']     = '項目';
$lang->metric->old->objectList['softRequest'] = '軟件需求';
$lang->metric->old->objectList['userRequest'] = '用戶需求';

$lang->metric->old->collectTypeList = array();
$lang->metric->old->collectTypeList['crontab'] = '定時計劃';
$lang->metric->old->collectTypeList['action']  = '動作觸發';

$lang->metric->tips = new stdclass();
$lang->metric->tips->nameError               = 'Mysql 自定義函數名錯誤，請檢查函數名。';
$lang->metric->tips->createError             = "創建 Mysql 自定義函數失敗，錯誤信息：<br/> %s";
$lang->metric->tips->noticeSelect            = 'SQL語句只能是查詢語句';
$lang->metric->tips->noticeBlack             = 'SQL中含有禁用SQL關鍵字 %s';
$lang->metric->tips->noticeVarName           = '變數名稱沒有設置';
$lang->metric->tips->noticeVarType           = '變數 %s 的類型沒有設置';
$lang->metric->tips->noticeShowName          = '變數 %s 的顯示名稱沒有設置';
$lang->metric->tips->noticeQueryValue        = '變數 %s 的測試值沒有設置。';
$lang->metric->tips->showNameMissed          = '變數 %s 的顯示名沒有設置。';
$lang->metric->tips->errorSql                = 'SQL語句有錯！錯誤：';
$lang->metric->tips->click2SetParams         = '請先點擊紅色變數塊設置參數，然後';
$lang->metric->tips->view                    = '預覽';
$lang->metric->tips->click2InsertData        = "點擊 <span class='ke-icon-holder'></span> 來插入度量指標或報表";
$lang->metric->tips->noticeUnchangeable      = '[範圍]、[對象]、[目的]、[時間屬性]、[代號]會影響度量值的獲取，創建後不可變更。';
$lang->metric->tips->noticeCode              = "代號必須是英文字母、數字或下劃線的組合。";
$lang->metric->tips->noticeRecalculate       = "更新數據中，請勿操作度量數據。";
$lang->metric->tips->noticeRepublish         = "若存在多次發佈情況，則從最後一次發佈時間之前開始重算。";
$lang->metric->tips->banRecalculate          = "該度量項尚未發佈或無時間屬性。";
$lang->metric->tips->noticeDeduplication     = "去除重複數據中...";
$lang->metric->tips->noticeDoneDeduplication = "去除重複數據完成";

$lang->metric->tips->noticeRecalculateConfig = array();
$lang->metric->tips->noticeRecalculateConfig['all']    = "系統會預設重新計算已發佈且有時間屬性的度量項中，非定時任務採集的歷史數據。";
$lang->metric->tips->noticeRecalculateConfig['single'] = "系統會預設重新計算此度量項中，非定時任務採集的歷史數據。";

$lang->metric->tips->noticeRewriteHistoryLib = array();
$lang->metric->tips->noticeRewriteHistoryLib['all']    = "(勾選後，系統會基于歷史數據重新計算已發佈且有時間屬性的度量項，並覆蓋已存在的全部度量值)";
$lang->metric->tips->noticeRewriteHistoryLib['single'] = "(勾選後，系統會基于歷史數據重新計算此度量項，並覆蓋已存在的全部度量值)";

$lang->metric->recalculateLog = "%s計算完成";

$lang->metric->param = new stdclass();
$lang->metric->param->varName      = '變數名';
$lang->metric->param->showName     = '顯示名';
$lang->metric->param->varType      = '類型';
$lang->metric->param->defaultValue = '預設值';
$lang->metric->param->queryValue   = '測試值';

$lang->metric->param->typeList['input']  = '文本框';
$lang->metric->param->typeList['date']   = '日期';
$lang->metric->param->typeList['select'] = '下拉菜單';

$lang->metric->param->options['project'] = '項目列表';
$lang->metric->param->options['product'] = $lang->productCommon . '列表';
$lang->metric->param->options['sprint']  = $lang->executionCommon . '列表';
