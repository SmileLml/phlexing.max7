<?php
/**
 * The upgrade module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-tw.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->upgrade->common          = '升級';
$lang->upgrade->start           = '開始';
$lang->upgrade->result          = '升級結果';
$lang->upgrade->fail            = '升級失敗';
$lang->upgrade->successTip      = '升級成功';
$lang->upgrade->success         = "<p>恭喜您！您的禪道已經成功升級。</p>";
$lang->upgrade->tohome          = '訪問禪道';
$lang->upgrade->license         = '禪道項目管理軟件已更換授權協議至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning        = '警告';
$lang->upgrade->checkExtension  = '檢查插件';
$lang->upgrade->consistency     = '一致性檢查';
$lang->upgrade->warnningContent = <<<EOT
<p>升級對資料庫權限要求較高，請使用root用戶。</p>
<p>升級有危險，請先備份資料庫，以防萬一。</p>
<pre class='bg-white space-y-2 p-3'>
1. 可以通過phpMyAdmin進行備份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   要將上面紅色的部分分別替換成對應的用戶名和禪道系統的資料庫名。
   比如： mysqldump -u root -p zentao > zentao.bak
</pre>
EOT;

if($config->db->driver == 'dm')
{
    $lang->upgrade->warnningContent = <<<EOT
<p>升級對資料庫權限要求較高，請使用管理員用戶。<br>
   升級有危險，請先備份資料庫，以防萬一。</p>
<pre>
1. 可以通過圖形化客戶端工具進行備份。
2. 使用DIsql工具進行備份。
   $> BACKUP DATABASE BACKUPSET  <span class='text-danger'>'filename'</span>;
   語句執行完後會在預設的備份路徑下生成名為“filename”的備份集目錄。
   預設的備份路徑為 dm.ini 中 BAK_PATH 配置的路徑，若未配置 BAK_PATH，則預設使用 SYSTEM_PATH 下的 bak 目錄。
   這是最簡單的資料庫備份語句，如果要設置其他的備份選項需瞭解聯機備份資料庫的語法。
</pre>
EOT;
}

$lang->upgrade->createFileWinCMD   = '打開命令行，執行<strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = '在命令行執行: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>升級之前請先完成下面的操作：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者刪掉"<strong style="color:#ed980f">%s</strong>" 這個檔案 ，重新創建一個<strong style="color:#ed980f">ok.txt</strong>檔案，不需要內容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已經仔細閲讀上面提示且完成上述工作，<a href="#" onclick="location.reload()">繼續更新</a></strong></p>';

$lang->upgrade->selectVersion = '選擇版本';
$lang->upgrade->continue      = '繼續';
$lang->upgrade->noteVersion   = "務必選擇正確的版本，否則會造成數據丟失。";
$lang->upgrade->fromVersion   = '原來的版本';
$lang->upgrade->toVersion     = '升級到';
$lang->upgrade->confirm       = '確認要執行的SQL語句';
$lang->upgrade->sureExecute   = '確認執行';
$lang->upgrade->upgradingTips = '正在升級中，請耐心等待，切勿刷新頁面、斷電、關機！';
$lang->upgrade->forbiddenExt  = '以下插件與新版本不兼容，已經自動禁用：';
$lang->upgrade->updateFile    = '需要更新附件信息。';
$lang->upgrade->showSQLLog    = '檢查到你的資料庫跟標準不一致，正在嘗試修復。以下是修復SQL語句。';
$lang->upgrade->noticeErrSQL  = '檢查到你的資料庫跟標準不一致，嘗試修復失敗。請手動執行以下SQL語句，再刷新頁面檢查。';
$lang->upgrade->afterDeleted  = '請執行上面命令刪除檔案， 刪除後刷新！';
$lang->upgrade->afterExec     = '請根據以上報錯信息手動修改資料庫，修改後刷新！';
$lang->upgrade->afterDuckdb   = '請等待安裝Duckdb引擎。';
$lang->upgrade->mergeProgram  = '數據遷移';
$lang->upgrade->mergeTips     = '數據遷移提示';
$lang->upgrade->toPMS15Guide  = '禪道開源版15版本升級';
$lang->upgrade->toPRO10Guide  = '禪道專業版10版本升級';
$lang->upgrade->toBIZ5Guide   = '禪道企業版5版本升級';
$lang->upgrade->toMAXGuide    = '禪道旗艦版版本升級';

$lang->upgrade->line            = '產品綫';
$lang->upgrade->allLines        = "所有產品綫";
$lang->upgrade->program         = '目標項目集和項目';
$lang->upgrade->existProgram    = '已有項目集';
$lang->upgrade->existProject    = '已有項目';
$lang->upgrade->existLine       = '已有產品綫';
$lang->upgrade->product         = $lang->productCommon;
$lang->upgrade->project         = '迭代';
$lang->upgrade->repo            = '版本庫';
$lang->upgrade->mergeRepo       = '歸併版本庫';
$lang->upgrade->setProgram      = '設置項目所屬項目集';
$lang->upgrade->setProject      = "設置{$lang->executionCommon}所屬項目";
$lang->upgrade->dataMethod      = '數據遷移方式';
$lang->upgrade->selectMergeMode = '請選擇數據歸併方式';
$lang->upgrade->mergeMode       = '數據歸併方式：';
$lang->upgrade->begin           = '開始日期';
$lang->upgrade->end             = '結束日期';
$lang->upgrade->unknownDate     = '無明確時間的項目';
$lang->upgrade->selectProject   = '目標項目';
$lang->upgrade->programName     = '項目集名稱';
$lang->upgrade->projectName     = '項目名稱';
$lang->upgrade->compatibleEXT   = '擴展機制兼容';
$lang->upgrade->fileName        = '檔案名稱';
$lang->upgrade->next            = '下一步';
$lang->upgrade->back            = '上一步';

$lang->upgrade->upgradeDocs     = '升級文檔數據';
$lang->upgrade->upgradingDocs   = '正在升級文檔，請稍候...';
$lang->upgrade->upgradeDocsTip  = '檢測到 %s 個文檔相關數據需要升級';

$lang->upgrade->upgradeDocTemplates    = '升級文檔模板數據';
$lang->upgrade->upgradingDocTemplates  = '正在升級文檔模板，請稍候...';
$lang->upgrade->upgradeDocTemplatesTip = '正在升級後台文檔模板的歷史數據，升級後可在文檔下模板廣場中查看與維護。';

$lang->upgrade->newProgram        = '新建';
$lang->upgrade->editedName        = '調整後名稱';
$lang->upgrade->projectEmpty      = '所屬項目不能為空！';
$lang->upgrade->mergeSummary      = "尊敬的用戶，您的系統中共有%s等待遷移。";
$lang->upgrade->productCount      = "%s個{$lang->productCommon}";
$lang->upgrade->projectCount      = "%s個{$lang->projectCommon}";
$lang->upgrade->mergeByProject    = "當前提供如下2種數據遷移方式，如果歷史的{$lang->projectCommon}都是長周期的，那麼我們建議把歷史的{$lang->projectCommon}作為項目升級。</br>如果歷史的{$lang->projectCommon}都是短周期的，那麼我們建議把歷史的{$lang->projectCommon}作為{$lang->executionCommon}升級。";
$lang->upgrade->mergeRepoTips     = "將選中的版本庫歸併到所選產品下。";
$lang->upgrade->needBuild4Add     = '本次升級需要創建索引。請到 [後台->系統設置->重建索引] 頁面，重新創建索引。';
$lang->upgrade->needChangeEngine  = '本次升級需要更換表引擎， [後台->系統設置->表引擎] 頁面更換引擎。';
$lang->upgrade->errorEngineInnodb = '您當前的資料庫不支持使用InnoDB數據表引擎，請修改為MyISAM後重試。';
$lang->upgrade->duplicateProject  = "同一個項目集內項目名稱不能重複，請調整重名的項目名稱";
$lang->upgrade->upgradeTips       = "歷史刪除數據不參與升級，升級後將不支持還原，請知悉";
$lang->upgrade->moveEXTFileFail   = '遷移檔案失敗， 請執行上面命令後刷新！';
$lang->upgrade->deleteDirTip      = '升級後，如下檔案夾會影響系統功能的使用，請刪除。';
$lang->upgrade->errorNoProduct    = "請選擇需要歸併的{$lang->productCommon}。";
$lang->upgrade->errorNoExecution  = "請選擇需要歸併的{$lang->projectCommon}。";
$lang->upgrade->moveExtFileTip    = <<<EOT
<p>新版本將對歷史的定製/插件進行擴展機制兼容處理，需要將定製/插件相關的檔案遷移到extension/custom下，否則定製/插件功能將無法使用。</p>
<p>請您確認系統是否有做過定製/插件，如沒有做過定製/插件，可取消勾選如下檔案；如果不清楚是否做過定製/插件，也可保持檔案勾選。</p>
EOT;

$lang->upgrade->projectType['project']   = "把歷史的{$lang->projectCommon}作為項目升級";
$lang->upgrade->projectType['execution'] = "把歷史的{$lang->projectCommon}作為{$lang->executionCommon}升級";

$lang->upgrade->createProjectTip = <<<EOT
<p>升級後歷史的{$lang->projectCommon}一一對應新版本中的項目。</p>
<p>系統會根據歷史{$lang->projectCommon}分別創建一個與該{$lang->projectCommon}同名的{$lang->executionCommon}，並將之前{$lang->projectCommon}的任務、需求、Bug等數據遷移至{$lang->executionCommon}中。</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>系統會把歷史的{$lang->projectCommon}作為{$lang->executionCommon}進行升級。</p>
<p>升級後歷史的{$lang->projectCommon}數據將對應新版本中項目下的{$lang->executionCommon}。</p>
EOT;

$lang->upgrade->mergeModes = array();
$lang->upgrade->mergeModes['project']   = "自動歸併數據，將歷史的{$lang->projectCommon}作為項目升級";
$lang->upgrade->mergeModes['execution'] = "自動歸併數據，將歷史的{$lang->projectCommon}作為{$lang->executionCommon}升級";
$lang->upgrade->mergeModes['manually']  = '手工歸併數據';

$lang->upgrade->mergeProjectTip   = "歷史的{$lang->projectCommon}將直接同步到新版本的項目中，同時系統將會根據歷史{$lang->projectCommon}分別創建一個與該{$lang->projectCommon}同名的{$lang->executionCommon}，並將之前{$lang->projectCommon}下的任務、需求、Bug等數據遷移至{$lang->executionCommon}中。";
$lang->upgrade->mergeExecutionTip = "系統將自動按年創建項目，將歷史的{$lang->projectCommon}數據按照年份歸併到對應的項目下。";
$lang->upgrade->createProgramTip  = "同時系統將自動創建一個預設的項目集，將所有的{$lang->projectCommon}都放在預設的項目集下。";
$lang->upgrade->mergeManuallyTip  = '可以手工選擇數據歸併的方式。';

$lang->upgrade->defaultGroup = '預設分組';

include dirname(__FILE__) . '/version.php';

$lang->upgrade->recoveryActions = new stdclass();
$lang->upgrade->recoveryActions->cancel = '取消';
$lang->upgrade->recoveryActions->review = '評審';

$lang->upgrade->remark     = '備註';
$lang->upgrade->remarkDesc = '後續您還可以在禪道的後台-系統設置-模式中進行切換。';

$lang->upgrade->upgradingTip = '系統正在升級中，請耐心等待...';

$lang->upgrade->addTraincoursePrivTips = '為了幫助大家更好的學習項目管理相關知識，預設給所有權限分組分配了學堂的課程和實踐庫權限，便于大家都能訪問。如果您不需要該功能，可以到後台功能開關中關閉該功能。';

$lang->upgrade->storyStageList['']           = '';
$lang->upgrade->storyStageList['wait']       = '未開始';
$lang->upgrade->storyStageList['planned']    = "已計劃";
$lang->upgrade->storyStageList['projected']  = '研發立項';
$lang->upgrade->storyStageList['designing']  = '設計中';
$lang->upgrade->storyStageList['designed']   = '設計完畢';
$lang->upgrade->storyStageList['developing'] = '研發中';
$lang->upgrade->storyStageList['developed']  = '研發完畢';
$lang->upgrade->storyStageList['testing']    = '測試中';
$lang->upgrade->storyStageList['tested']     = '測試完畢';
$lang->upgrade->storyStageList['verified']   = '已驗收';
$lang->upgrade->storyStageList['rejected']   = '驗收失敗';
$lang->upgrade->storyStageList['delivering'] = '交付中';
$lang->upgrade->storyStageList['delivered']  = '已交付';
$lang->upgrade->storyStageList['released']   = '已發佈';
$lang->upgrade->storyStageList['closed']     = '已關閉';

$lang->upgrade->flowFields['program']   = '所屬項目集';
$lang->upgrade->flowFields['product']   = '所屬產品';
$lang->upgrade->flowFields['project']   = '所屬項目';
$lang->upgrade->flowFields['execution'] = '所屬執行';

$lang->upgrade->defaultCharterApprovalFlow = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->projectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->projectApproval->title = '立項審批流';
$lang->upgrade->defaultCharterApprovalFlow->projectApproval->desc  = '可以為發起立項審批設計審批流程。';

$lang->upgrade->defaultCharterApprovalFlow->completionApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->completionApproval->title = '結項審批流';
$lang->upgrade->defaultCharterApprovalFlow->completionApproval->desc  = '可以為發起結項審批設計審批流程。';

$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval->title = '取消立項審批流';
$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval->desc  = '可以為取消立項審批設計審批流程。';

$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval->title = '激活立項審批流';
$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval->desc  = '可以為激活立項審批設計審批流程。';
