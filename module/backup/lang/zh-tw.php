<?php
$lang->backup->common      = '備份';
$lang->backup->name        = '備份名稱';
$lang->backup->index       = '備份列表';
$lang->backup->history     = '備份歷史';
$lang->backup->delete      = '刪除備份';
$lang->backup->backup      = '開始備份';
$lang->backup->restore     = '還原';
$lang->backup->change      = '保留天數';
$lang->backup->changeAB    = '修改';
$lang->backup->rmPHPHeader = '去除安全設置';
$lang->backup->setting     = '設置';

$lang->backup->restoreAction = '還原備份';
$lang->backup->settingAction = '備份設置';

$lang->backup->time     = '備份時間';
$lang->backup->files    = '備份檔案';
$lang->backup->allCount = '總檔案數';
$lang->backup->count    = '備份檔案數';
$lang->backup->size     = '大小';
$lang->backup->status   = '狀態';

$lang->backup->statusList['success'] = '成功';
$lang->backup->statusList['fail']    = '失敗';

$lang->backup->settingDir = '備份目錄';
$lang->backup->settingList['nofile'] = '不備份附件和代碼';
$lang->backup->settingList['nosafe'] = '不需要防下載PHP檔案頭';

global $config;
if($config->inContainer) $lang->backup->settingList['nofile'] = '不備份附件';

$lang->backup->waiting          = '<span id="backupType"></span>正在進行中，請稍候...';
$lang->backup->progressSQL      = '<p>SQL備份中，已備份%s</p>';
$lang->backup->progressAttach   = '<p>SQL備份完成</p><p>附件備份中，共有%s個檔案，已經備份%s個</p>';
$lang->backup->progressCode     = '<p>SQL備份完成</p><p>附件備份完成</p><p>代碼備份中，共有%s個檔案，已經備份%s個</p>';
$lang->backup->confirmDelete    = '是否刪除備份？';
$lang->backup->confirmRestore   = '是否還原該備份？';
$lang->backup->holdDays         = '備份保留最近 %s 天';
$lang->backup->copiedFail       = '複製失敗的檔案：';
$lang->backup->restoreTip       = '還原功能只還原附件和資料庫，如果需要還原代碼，可以手動還原。';
$lang->backup->insufficientDisk = '磁碟空間小於NEED_SPACEG，可能會導致備份空間不足或影響使用，請處理後再試。';
$lang->backup->ongoBackup       = '繼續備份';
$lang->backup->cancelBackup     = '取消備份';
$lang->backup->getSpaceLoading  = '正在計算備份空間';

$lang->backup->success = new stdclass();
$lang->backup->success->backup  = '備份成功！';
$lang->backup->success->restore = '還原成功！';

$lang->backup->error = new stdclass();
$lang->backup->error->noCreateDir     = '備份目錄不存在，也無法創建該目錄';
$lang->backup->error->noWritable      = "<code>%s</code> 不可寫！請檢查該目錄權限，否則無法備份。";
$lang->backup->error->plainNoWritable = "%s 不可寫！請檢查該目錄權限，否則無法備份。";
$lang->backup->error->noDelete        = "檔案 %s 無法刪除，修改權限或手工刪除。";
$lang->backup->error->restoreSQL      = "資料庫還原失敗，錯誤：%s";
$lang->backup->error->restoreFile     = "附件還原失敗，錯誤：%s";
$lang->backup->error->backupFile      = "附件備份失敗，錯誤：%s";
$lang->backup->error->backupCode      = "代碼備份失敗，錯誤：%s";
$lang->backup->error->timeout         = "備份超時";
$lang->backup->error->int             = '『%s』應當是正整數。';

$lang->backup->notice = new stdclass();
$lang->backup->notice->higherVersion     = '還原的版本高於當前運行版本，請更新鏡像版本到%s後再還原。';
$lang->backup->notice->lowerVersion      = '還原的版本低於當前運行版本，還原後會執行升級流程。';
$lang->backup->notice->unknownVersion    = '未從當前備份中讀取到版本信息，是否還原該備份？';
$lang->backup->notice->settingsInQuickon = '您目前使用的是禪道DevOps平台版，無需設置其他選項。';
$lang->backup->notice->gotoUpgrade       = '還原成功，即將跳轉到升級頁面，如未跳轉請手動刷新頁面';
