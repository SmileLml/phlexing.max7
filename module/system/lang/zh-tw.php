<?php
$lang->system->dashboard       = '監控';
$lang->system->systemInfo      = '系統信息';
$lang->system->dbManagement    = '資料庫管理';
$lang->system->ldapManagement  = 'LDAP';
$lang->system->dbList          = '資料庫列表';
$lang->system->configDomain    = '域名管理';
$lang->system->ossView         = '對象存儲管理';
$lang->system->dbName          = '名稱';
$lang->system->dbStatus        = '狀態';
$lang->system->dbType          = '類型';
$lang->system->action          = '操作';
$lang->system->management      = '管理';
$lang->system->visit           = '訪問';
$lang->system->close           = '關閉';
$lang->system->installLDAP     = '安裝LDAP';
$lang->system->editLDAP        = '編輯';
$lang->system->LDAPInfo        = 'LDAP信息';
$lang->system->accountInfo     = '賬號信息';
$lang->system->advance         = '高級';
$lang->system->verify          = '校驗';
$lang->system->copy            = '複製';
$lang->system->copySuccess     = '已複製到剪切板';
$lang->system->cneStatus       = '平台狀態';
$lang->system->cneStatistic    = '資源統計';
$lang->system->latestDynamic   = '最新動態';
$lang->system->nodeQuantity    = '節點數';
$lang->system->serviceQuantity = '服務數';
$lang->system->cpuUsage        = 'CPU（核）';
$lang->system->memUsage        = '內存（GB）';
$lang->system->name            = $lang->product->system . '名稱';
$lang->system->integrated      = '整合' . $lang->product->system;
$lang->system->latestRelease   = '最新版本號';
$lang->system->children        = '包含' . $lang->product->system;
$lang->system->latestRelease   = '最新版本號';
$lang->system->status          = '狀態';
$lang->system->desc            = '描述';
$lang->system->browse          = "{$lang->product->system}列表";
$lang->system->create          = "創建{$lang->product->system}";
$lang->system->edit            = "編輯{$lang->product->system}";
$lang->system->delete          = "刪除{$lang->product->system}";
$lang->system->active          = "上架{$lang->product->system}";
$lang->system->inactive        = "下架{$lang->product->system}";
$lang->system->integratedLabel = '整合';

$lang->system->integratedList = array();
$lang->system->integratedList[0] = '否';
$lang->system->integratedList[1] = '是';

$lang->system->statusList = array();
$lang->system->statusList['active']   = '已上架';
$lang->system->statusList['inactive'] = '已下架';

$lang->system->confirmDelete   = '確定要刪除' . $lang->product->system . '嗎？';
$lang->system->confirmActive   = '確定要上架' . $lang->product->system . '嗎？';
$lang->system->confirmInactive = '確定要下架' . $lang->product->system . '嗎？';
$lang->system->releaseExist    = '關聯發佈的' . $lang->product->system . '不可刪除';
$lang->system->buildExist      = '關聯構建的' . $lang->product->system . '不可刪除';

/* LDAP */
$lang->system->LDAP = new stdclass;
$lang->system->LDAP->info             = 'LDAP信息';
$lang->system->LDAP->ldapEnabled      = '啟用LDAP';
$lang->system->LDAP->ldapQucheng      = '渠成內置';
$lang->system->LDAP->ldapSource       = '來源';
$lang->system->LDAP->ldapInstall      = '安裝並啟用';
$lang->system->LDAP->ldapUpdate       = '更新';
$lang->system->LDAP->accountInfo      = '賬號信息';
$lang->system->LDAP->account          = '賬號';
$lang->system->LDAP->password         = '密碼';
$lang->system->LDAP->ldapUsername     = '用戶名';
$lang->system->LDAP->ldapName         = '名稱';
$lang->system->LDAP->host             = '主機';
$lang->system->LDAP->port             = '連接埠';
$lang->system->LDAP->account          = '賬號';
$lang->system->LDAP->password         = '密碼';
$lang->system->LDAP->ldapRoot         = '根節點';
$lang->system->LDAP->filterUser       = '用戶過濾';
$lang->system->LDAP->email            = '郵件欄位';
$lang->system->LDAP->extraAccount     = '用戶名欄位';
$lang->system->LDAP->ldapAdvance      = '高級設置';
$lang->system->LDAP->updateLDAP       = '更新LDAP';
$lang->system->LDAP->updateInstance   = '更新已關聯LDAP的服務';
$lang->system->LDAP->updatingProgress = '更新中...剩餘 %s 個服務。';

$lang->system->ldapTypeList = array();
$lang->system->ldapTypeList['qucheng'] = '渠成內置';
$lang->system->ldapTypeList['extra']   = '外部映射';

/* OSS */
$lang->system->oss = new stdclass;
$lang->system->oss->common    = '對象存儲';
$lang->system->oss->appURL    = '應用地址';
$lang->system->oss->user      = '用戶名';
$lang->system->oss->password  = '密碼';
$lang->system->oss->manage    = '管理';
$lang->system->oss->apiURL    = 'API地址';
$lang->system->oss->accessKey = 'Access Key';
$lang->system->oss->secretKey = 'Secret Key';

/* SMTP */
$lang->system->SMTP = new stdclass;
$lang->system->SMTP->common   = '郵箱配置';
$lang->system->SMTP->enabled  = '啟用SMTP';
$lang->system->SMTP->install  = '安裝';
$lang->system->SMTP->update   = '更新';
$lang->system->SMTP->edit     = '編輯';
$lang->system->SMTP->editSMTP = '編輯SMTP';
$lang->system->SMTP->account  = '發信郵箱';
$lang->system->SMTP->password = '密碼';
$lang->system->SMTP->host     = 'SMTP伺服器';
$lang->system->SMTP->port     = 'SMTP連接埠';
$lang->system->SMTP->save     = '保存';

/* Domain */
$lang->system->customDomain = '新域名';
$lang->system->certPem      = '公鑰證書';
$lang->system->certKey      = '私鑰';

$lang->system->domain = new stdclass;
$lang->system->domain->common        = '域名管理';
$lang->system->domain->editDomain    = '修改域名配置';
$lang->system->domain->config        = '配置域名和證書';
$lang->system->domain->currentDomain = '當前域名';
$lang->system->domain->oldDomain     = '舊域名';
$lang->system->domain->newDomain     = '新域名';
$lang->system->domain->expiredDate   = '證書過期時間';
$lang->system->domain->uploadCert    = '上傳證書（僅支持泛域名證書）';

$lang->system->domain->notReuseOldDomain     = '使用自定義域名後無法改回預設域名';
$lang->system->domain->setDNS                = '建議修改域名前請先進行DNS解析，';
$lang->system->domain->dnsHelperLink         = '點擊查看幫助文檔';
$lang->system->domain->updateInstancesDomain = '更新已安裝服務的域名';
$lang->system->domain->totalOldDomain        = '共 %s 個。';
$lang->system->domain->updatingProgress      = '更新中...，剩餘 %s 個,';
$lang->system->domain->updating              = '更新中...';

$lang->system->SLB = new stdclass;
$lang->system->SLB->common        = '負載均衡';
$lang->system->SLB->config        = '配置負載均衡';
$lang->system->SLB->edit          = '修改負載均衡';
$lang->system->SLB->ipPool        = 'IP段';
$lang->system->SLB->ipPoolExample = '示例：192.168.10.0/24或者192.168.10.0-192.168.10.100';
$lang->system->SLB->installing    = '正在配置負載均衡';
$lang->system->SLB->leftSeconds   = '預計剩餘';
$lang->system->SLB->second        = '秒';

$lang->system->notices = new stdclass;
$lang->system->notices->success               = '成功';
$lang->system->notices->fail                  = '失敗';
$lang->system->notices->attention             = '注意';
$lang->system->notices->noLDAP                = '找不到LDAP配置數據';
$lang->system->notices->ldapUsed              = '%s個服務已關聯了LDAP';
$lang->system->notices->ldapInstallSuccess    = 'LDAP安裝成功';
$lang->system->notices->ldapUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->confirmUpdateLDAP     = '修改LDAP後，會自動更新並重啟已關聯的服務，確定要修改嗎？';
$lang->system->notices->verifyLDAPSuccess     = '校驗LDAP成功！';
$lang->system->notices->fillAllRequiredFields = '請填寫全部必填項！';
$lang->system->notices->smtpInstallSuccess    = 'LDAP安裝成功';
$lang->system->notices->smtpUpdateSuccess     = 'LDAP更新成功';
$lang->system->notices->smtpWhiteList         = '為防止郵件被屏蔽，請在郵件伺服器裡面將發信郵箱設為白名單';
$lang->system->notices->smtpAuthCode          = '有些郵箱要填寫單獨申請的授權碼，具體請到郵箱相關設置查詢';
$lang->system->notices->smtpUsed              = '%s 個服務關聯了SMTP';
$lang->system->notices->verifySMTPSuccess     = '校驗成功！';
$lang->system->notices->pleaseCheckSMTPInfo   = '校驗失敗！請檢查用戶名和密碼是否正確';
$lang->system->notices->confirmUpdateDomain   = '修改域名後，會自動更新已安裝服務的域名，確定要修改嗎？';
$lang->system->notices->updateDomainSuccess   = '域名修改成功。';
$lang->system->notices->configSLBSuccess      = '配置負載均衡成功。';
$lang->system->notices->validCert             = '校驗成功';

$lang->system->errors = new stdclass;
$lang->system->errors->notFoundDB                  = '找不到該資料庫';
$lang->system->errors->notFoundLDAP                = '找不到LDAP數據';
$lang->system->errors->dbNameIsEmpty               = '資料庫名為空';
$lang->system->errors->notSupportedLDAP            = '暫不支持該類型的LDAP';
$lang->system->errors->failToInstallLDAP           = '安裝內置LDAP失敗';
$lang->system->errors->failToInstallExtraLDAP      = '對接外部LDAP失敗';
$lang->system->errors->failToUpdateExtraLDAP       = '更新外部LDAP失敗';
$lang->system->errors->failToUninstallQuChengLDAP  = '卸載渠成內部LDAP失敗';
$lang->system->errors->failToUninstallExtraLDAP    = '卸載外部LDAP失敗';
$lang->system->errors->failToDeleteLDAPSnippet     = '刪除LDAP片段失敗';
$lang->system->errors->verifyLDAPFailed            = '校驗LDAP失敗';
$lang->system->errors->LDAPLinked                  = '有服務已經關聯了LDAP';
$lang->system->errors->SMTPLinked                  = '有服務已經關聯了SMTP服務';
$lang->system->errors->failGetOssAccount           = '獲取對象存儲賬號失敗';
$lang->system->errors->failToInstallSMTP           = '安裝SMTP失敗';
$lang->system->errors->failToUninstallSMTP         = '卸載SMTP失敗';
$lang->system->errors->failToUpdateSMTP            = '更新SMTP失敗';
$lang->system->errors->verifySMTPFailed            = '校驗SMTP失敗';
$lang->system->errors->notFoundSMTPApp             = '找不到SMTP代理應用';
$lang->system->errors->notFoundSMTPService         = '找不到SMTP代理服務';
$lang->system->errors->domainIsRequired            = '必須填寫域名';
$lang->system->errors->invalidDomain               = '無效的域名或格式錯誤。域名只允許小寫字母、數字、點(.)和中橫線(-)';
$lang->system->errors->failToUpdateDomain          = '更新域名失敗';
$lang->system->errors->forbiddenOriginalDomain     = '不能修改為平台預設域名';
$lang->system->errors->newDomainIsSameWithOld      = '新域名不能與原域名相同';
$lang->system->errors->failedToConfigSLB           = '配置負載均衡失敗';
$lang->system->errors->wrongIPRange                = 'IP段格式錯誤，請參照示例格式，' . $lang->system->SLB->ipPoolExample;
$lang->system->errors->ippoolRequired              = 'IP段不能為空';
$lang->system->errors->failedToInstallSLBComponent = '安裝負載均衡組件失敗';
$lang->system->errors->tryReinstallSLB             = '安裝負載均衡組件超時，請重試。';

$lang->system->backup = new stdclass();
$lang->system->backup->common       = '系統備份';
$lang->system->backup->shortCommon  = '備份';
$lang->system->backup->systemInfo   = '系統信息';
$lang->system->backup->index        = '備份首頁';
$lang->system->backup->history      = '備份記錄';
$lang->system->backup->delete       = '刪除備份';
$lang->system->backup->backup       = '備份';
$lang->system->backup->change       = '保留時間';
$lang->system->backup->changeAB     = '修改';
$lang->system->backup->rmPHPHeader  = '去除安全設置';
$lang->system->backup->setting      = '設置';
$lang->system->backup->creator      = '創建者';
$lang->system->backup->type         = '備份類型';

$lang->system->backup->settingAction = '備份設置';

$lang->system->backup->name           = '名稱';
$lang->system->backup->currentVersion = '當前版本';
$lang->system->backup->latestVersion  = '最新版本';

$lang->system->backup->files    = '備份檔案';
$lang->system->backup->allCount = '總檔案數';
$lang->system->backup->count    = '備份檔案數';
$lang->system->backup->size     = '大小';
$lang->system->backup->status   = '狀態';
$lang->system->backup->running  = '運行中';
$lang->system->backup->done     = '完成';

$lang->system->backup->backupName   = '備份名稱：';
$lang->system->backup->backupSql    = '備份資料庫：';
$lang->system->backup->backupFile   = '備份附件：';
$lang->system->backup->restoreImage = '回滾平台鏡像：';
$lang->system->backup->restoreSQL   = '回滾資料庫：';
$lang->system->backup->restoreFile  = '回滾附件：';
$lang->system->backup->checkService = '檢查服務：';

$lang->system->backup->upgrade  = '升級';
$lang->system->backup->rollback = '回滾';
$lang->system->backup->restart  = '重啟';
$lang->system->backup->delete   = '刪除';

$lang->system->backup->statusList['pending']       = '等待中';
$lang->system->backup->statusList['inprogress']    = '進行中';
$lang->system->backup->statusList['completed']     = '完成';
$lang->system->backup->statusList['failed']        = '失敗';
$lang->system->backup->statusList['deleting']      = '刪除中';
$lang->system->backup->statusList['executeFailed'] = '執行失敗';

$lang->system->backup->restoreProgress['doing'] = '進行中';
$lang->system->backup->restoreProgress['done']  = '完成';

$lang->system->backup->typeList['manual']  = '手動備份';
$lang->system->backup->typeList['upgrade'] = '升級前自動備份';
$lang->system->backup->typeList['restore'] = '還原前自動備份';

$lang->system->backup->waiting         = '備份正在進行中，請稍候...';
$lang->system->backup->waitingStore    = '正在還原應用數據，請稍候...';
$lang->system->backup->progress        = '備份中，進度（%d/%d）';
$lang->system->backup->progressStore   = '還原中，進度（%d/%d）';
$lang->system->backup->progressSQL     = '備份中，已備份%s';
$lang->system->backup->progressAttach  = '備份中，共有%s個檔案，已經備份%s個';
$lang->system->backup->progressCode    = '代碼備份中，共有%s個檔案，已經備份%s個';
$lang->system->backup->confirmDelete   = '是否刪除該備份？';
$lang->system->backup->confirmRestore  = '平台還原過程中需要重啟，這將導致您當前的所有操作中斷並且無法恢復，您確定要繼續嗎？';
$lang->system->backup->holdDays        = '備份保留最近 %s 天';
$lang->system->backup->copiedFail      = '複製失敗的檔案：';
$lang->system->backup->restoreTip      = '還原功能只還原資料庫。';
$lang->system->backup->versionInfo     = '點擊查看新版本介紹';
$lang->system->backup->confirmUpgrade  = '升級期間禪道將無法使用，請確認是否升級？';
$lang->system->backup->confirmBackup   = '備份期間普通用戶將無法使用禪道，請確認是否備份？';
$lang->system->backup->upgrading       = '升級中';
$lang->system->backup->backupTitle     = '正在備份 渠成平台...';
$lang->system->backup->restoreTitle    = '正在回滾 渠成平台...';
$lang->system->backup->backingUp       = '進行中';
$lang->system->backup->restoring       = '進行中';
$lang->system->backup->backupSucceed   = '備份成功';
$lang->system->backup->restoreSucceed  = '還原成功';

$lang->system->backup->success = new stdclass();
$lang->system->backup->success->upgrade = '升級成功！';
$lang->system->backup->success->degrade = '降級成功！';

$lang->system->backup->error = new stdclass();
$lang->system->backup->error->backupFail        = "備份失敗!";
$lang->system->backup->error->restoreFail       = "還原失敗!";
$lang->system->backup->error->upgradeFail       = "升級失敗!";
$lang->system->backup->error->upgradeOvertime   = "升級超時!";
$lang->system->backup->error->degradeFail       = "降級失敗!";
$lang->system->backup->error->beenLatestVersion = "已經是最新版，無需升級!";
$lang->system->backup->error->requireVersion    = "必須上傳版本號!";

$lang->system->maintenance = new stdclass();
$lang->system->maintenance->reason['backup']  = '平台正在備份中，請稍後訪問';
$lang->system->maintenance->reason['restore'] = '平台正在恢復中，請稍後訪問';
$lang->system->maintenance->reason['upgrade'] = '平台正在升級中，請稍後訪問';

$lang->system->platform = new stdclass();
$lang->system->platform->navs['dblist']     = '資料庫';
$lang->system->platform->navs['domainView'] = '域名';
$lang->system->platform->navs['ossview']    = '對象存儲';

$lang->system->runningStatus['normal'] = '正常';
$lang->system->runningStatus['error']  = '異常';

$lang->system->serviceNotice = '僅統計應用市場安裝的服務，手工配置的服務不在統計範圍內。';
$lang->system->nodeNotice    = '節點(%s) 異常原因：%s';
