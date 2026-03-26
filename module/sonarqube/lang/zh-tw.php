<?php
$lang->sonarqube = new stdclass;
$lang->sonarqube->common            = 'SonarQube';
$lang->sonarqube->browse            = 'SonarQube列表';
$lang->sonarqube->search            = '搜索';
$lang->sonarqube->create            = '添加SonarQube';
$lang->sonarqube->edit              = '編輯SonarQube';
$lang->sonarqube->delete            = '刪除SonarQube';
$lang->sonarqube->serverFail        = '連接SonarQube伺服器異常，請檢查SonarQube伺服器。';
$lang->sonarqube->browseProject     = "SonarQube項目列表";
$lang->sonarqube->createProject     = "創建SonarQube項目";
$lang->sonarqube->deleteProject     = "刪除SonarQube項目";
$lang->sonarqube->placeholderSearch = '請輸入項目名稱';
$lang->sonarqube->execJob           = "執行SonarQube任務";
$lang->sonarqube->desc              = '描述';
$lang->sonarqube->reportView        = "SonarQube報告";
$lang->sonarqube->browseIssue       = "SonarQube問題列表";
$lang->sonarqube->createBug         = "轉bug";
$lang->sonarqube->delError          = "該伺服器下有綁定的構建，請刪除關聯之後操作";

$lang->sonarqube->id             = 'ID';
$lang->sonarqube->name           = "應用名稱";
$lang->sonarqube->url            = '伺服器地址';
$lang->sonarqube->account        = '用戶名';
$lang->sonarqube->password       = '密碼';
$lang->sonarqube->token          = 'Token';
$lang->sonarqube->defaultProject = '預設項目';
$lang->sonarqube->private        = 'MD5驗證';

$lang->sonarqube->createServer  = '添加SonarQube伺服器';
$lang->sonarqube->editServer    = '修改SonarQube伺服器';
$lang->sonarqube->createSuccess = "創建成功";

$lang->sonarqube->placeholder = new stdclass;
$lang->sonarqube->placeholder->name        = '';
$lang->sonarqube->placeholder->url         = "請填寫SonarQube Server首頁的訪問地址，如：https://sonarqube.zentao.net。";
$lang->sonarqube->placeholder->account     = "請填寫具有Administrator權限的SonarQube用戶信息";
$lang->sonarqube->placeholder->projectName = '最多255個字元';
$lang->sonarqube->placeholder->projectKey  = "最多400個字元。 允許的字元為字母、數字，'-'，'_'，'. '和':'，至少有一個非數字";
$lang->sonarqube->placeholder->searchIssue = "請輸入問題名稱或檔案";

$lang->sonarqube->nameRepeatError      = "伺服器名稱已存在！";
$lang->sonarqube->urlRepeatError       = "伺服器地址已存在！";
$lang->sonarqube->validError           = "SonarQube 用戶權限認證失敗！";
$lang->sonarqube->hostError            = "無效的SonarQube服務地址。";
$lang->sonarqube->lengthError          = "『%s』長度應當不超過『%d』";
$lang->sonarqube->confirmDelete        = '確認刪除該SonarQube嗎？';
$lang->sonarqube->confirmDeleteProject = '確認刪除該SonarQube項目嗎？';
$lang->sonarqube->noReport             = "暫無報告";
$lang->sonarqube->notAdminer           = "請填寫具有Administrator權限的SonarQube用戶信息";

$lang->sonarqube->projectKey          = '項目標識';
$lang->sonarqube->projectName         = '項目名稱';
$lang->sonarqube->projectlastAnalysis = '最後執行時間';
$lang->sonarqube->serverList          = '伺服器列表';

$lang->sonarqube->report = new stdclass();
$lang->sonarqube->report->bugs                       = 'Bugs';
$lang->sonarqube->report->vulnerabilities            = '弱點';
$lang->sonarqube->report->security_hotspots_reviewed = '複審熱點';
$lang->sonarqube->report->code_smells                = '異味';
$lang->sonarqube->report->coverage                   = '覆蓋率';
$lang->sonarqube->report->duplicated_lines_density   = '重複率';
$lang->sonarqube->report->ncloc                      = '行數';

$lang->sonarqube->qualitygateList = array();
$lang->sonarqube->qualitygateList['OK']    = 'Passed';
$lang->sonarqube->qualitygateList['WARN']  = 'Warning';
$lang->sonarqube->qualitygateList['ERROR'] = 'Failed';

$lang->sonarqube->apiErrorMap[1] = "/Malformed key for Project: '([\s\S]+)'. Allowed characters are alphanumeric, '-', '_', '\.' and ':', with at least one non-digit\./";
$lang->sonarqube->apiErrorMap[2] = "/Could not create Project, key already exists: ([\s\S]+)/";

$lang->sonarqube->errorLang[1] = "項目標識的格式不正確。允許的字元為字母、數字、'-'、''、'.'和“：”，至少有一個非數字。";
$lang->sonarqube->errorLang[2] = "無法創建項目，項目標識已存在：%s";

$lang->sonarqube->issue = new stdclass();
$lang->sonarqube->issue->message      = '問題名稱';
$lang->sonarqube->issue->severity     = '嚴重程度';
$lang->sonarqube->issue->type         = '類型';
$lang->sonarqube->issue->status       = '狀態';
$lang->sonarqube->issue->file         = '所屬檔案';
$lang->sonarqube->issue->line         = '行數';
$lang->sonarqube->issue->effort       = '預計修復時長';
$lang->sonarqube->issue->creationDate = '創建日期';
