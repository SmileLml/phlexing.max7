<?php
/**
 * The file module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: zh-tw.php 4630 2013-04-10 05:54:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = '附件';
$lang->file->id            = '編號';
$lang->file->objectType    = '對象類型';
$lang->file->objectID      = '對象ID';
$lang->file->deleted       = '已刪除';
$lang->file->uploadImages  = '多圖上傳';
$lang->file->download      = '下載附件';
$lang->file->uploadDate    = '上傳時間：';
$lang->file->edit          = '重命名';
$lang->file->inputFileName = '請輸入附件名稱';
$lang->file->delete        = '刪除附件';
$lang->file->label         = '標題：';
$lang->file->maxUploadSize = "（不超過%s）";
$lang->file->applyTemplate = "應用模板";
$lang->file->tplTitle      = "模板名稱";
$lang->file->tplTitleAB    = "模板";
$lang->file->setPublic     = "設置公共模板";
$lang->file->exportFields  = "導出內容";
$lang->file->exportRange   = "導出範圍";
$lang->file->defaultTPL    = "預設模板";
$lang->file->setExportTPL  = "設置";
$lang->file->preview       = "預覽";
$lang->file->previewFile   = "預覽附件";
$lang->file->addFile       = '添加檔案';
$lang->file->beginUpload   = '開始上傳';
$lang->file->uploadSuccess = '上傳成功';
$lang->file->batchExport   = '分批導出';
$lang->file->downloadFile  = '下載';
$lang->file->playFailed    = '視頻預覽失敗，請聯繫管理員';
$lang->file->exportData    = "導出數據";

$lang->file->pathname  = '路徑';
$lang->file->title     = '標題';
$lang->file->fileName  = '檔案名稱';
$lang->file->untitled  = '未命名';
$lang->file->extension = '檔案類型';
$lang->file->size      = '大小';
$lang->file->encoding  = '編碼';
$lang->file->addedBy   = '由誰添加';
$lang->file->addedDate = '添加時間';
$lang->file->downloads = '下載次數';
$lang->file->extra     = '備註';

$lang->file->attachmentName = "附件名稱";
$lang->file->sourceObject   = "來源對象";
$lang->file->sourceID       = "來源ID";

$lang->file->dragFile            = '請拖拽檔案到此處';
$lang->file->childTaskTips       = "任務名稱前有'>'標記的為子任務";
$lang->file->uploadImagesExplain = '註：請上傳"jpg, jpeg, gif, png"格式的圖片，程序會以檔案名作為標題，以圖片作為內容。';
$lang->file->uploadingImages     = '共有 <strong>%s</strong> 個檔案正在上傳';
$lang->file->saveAndNext         = '保存並跳轉下一頁';
$lang->file->importPager         = '共有<strong>%s</strong>條記錄，當前第<strong>%s</strong>頁，共有<strong>%s</strong>頁';
$lang->file->importSummary       = "本次導入共有<strong id='totalAmount'>%s</strong>條記錄，每頁導入%s條，需要導入<strong id='times'>%s</strong>次";
$lang->file->accessDenied        = '您無權訪問該附件！';
$lang->file->uploadImagesTip     = '可點擊添加或拖拽上傳，圖片格式支持jpg、jpeg、gif、png';
$lang->file->waitDownloadTip     = '檔案正在導出中，請耐心等待';

$lang->file->errorNotExists   = "<span class='text-red'>檔案夾 '%s' 不存在</span>";
$lang->file->errorCanNotWrite = "<span class='text-red'>檔案夾 '%s' 不可寫,請改變檔案夾的權限。在linux中輸入指令: <span class='code'>sudo chmod -R 777 %s</span></span>";
$lang->file->confirmDelete    = " 您確定刪除該附件嗎？";
$lang->file->errorFileSize    = " 檔案大小已經超過%s，可能不能成功上傳！";
$lang->file->errorFileUpload  = " 檔案上傳失敗，檔案大小可能超出限制";
$lang->file->errorFileFormat  = " 檔案上傳失敗，檔案格式不在規定範圍內";
$lang->file->errorFileMove    = " 檔案上傳失敗，移動檔案時出錯";
$lang->file->errorFileCount   = " 只能上傳 %s 個檔案，超出數量限制的檔案無法上傳。";
$lang->file->dangerFile       = " 您選擇的檔案存在安全風險，系統將不予上傳。";
$lang->file->errorSuffix      = '壓縮包格式錯誤，只能上傳zip壓縮包！';
$lang->file->errorExtract     = '解壓縮失敗！可能檔案已經損壞，或壓縮包裡含有非法上傳檔案。';
$lang->file->errorUploadEmpty = '沒有等待上傳的檔案';
$lang->file->fileNotFound     = '未找到該檔案，可能物理檔案已被刪除！';
$lang->file->fileContentEmpty = '上傳檔案內容為空，請檢查後重新上傳。';
$lang->file->bizGuide         = '如需使用Excel導入導出功能，可升級到 %s';

$lang->file->uploadError[1] = "上傳的檔案大小超過了限制，請修改 php.ini 中 upload_max_filesize 與 post_max_size 選項限制的值";
$lang->file->uploadError[2] = '上傳檔案的大小超過了 HTML 表單中 MAX_FILE_SIZE 選項指定的值';
$lang->file->uploadError[3] = '檔案只有部分被上傳,請重新上傳';
$lang->file->uploadError[4] = '沒有檔案被上傳';
$lang->file->uploadError[5] = '上傳檔案大小為0,請重新上傳';
