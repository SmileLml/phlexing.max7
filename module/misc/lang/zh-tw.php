<?php
/**
 * The misc module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: zh-tw.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->misc = new stdclass();
$lang->misc->common  = '雜項';
$lang->misc->ping    = '防超時';
$lang->misc->view    = '查看';
$lang->misc->cancel  = '取消';

$lang->misc->zentao = new stdclass();
$lang->misc->zentao->version           = '開源版 %s';
$lang->misc->zentao->labels['about']   = '關於禪道';
$lang->misc->zentao->labels['support'] = '技術支持';
$lang->misc->zentao->labels['cowin']   = '幫助我們';
$lang->misc->zentao->labels['service'] = '服務列表';
$lang->misc->zentao->labels['others']  = '其他產品';

$lang->misc->zentao->icons['about']   = 'group';
$lang->misc->zentao->icons['support'] = 'question-sign';
$lang->misc->zentao->icons['cowin']   = 'hand-right';
$lang->misc->zentao->icons['service'] = 'heart';

$lang->misc->zentao->about['bizversion']   = '升級企業版本';
$lang->misc->zentao->about['official']     = "官方網站";
$lang->misc->zentao->about['changelog']    = "版本歷史";
$lang->misc->zentao->about['license']      = "授權協議";
$lang->misc->zentao->about['extension']    = "插件平台";
$lang->misc->zentao->about['follow']       = "關注我們";

$lang->misc->zentao->support['vip']        = "商業技術支持";
$lang->misc->zentao->support['manual']     = "用戶手冊";
$lang->misc->zentao->support['faq']        = "常見問題";
$lang->misc->zentao->support['ask']        = "官方問答";
$lang->misc->zentao->support['video']      = "使用視頻";
$lang->misc->zentao->support['qqgroup']    = "官方QQ群";

$lang->misc->zentao->cowin['reportbug']    = "反饋Bug";
$lang->misc->zentao->cowin['feedback']     = "反饋需求";
$lang->misc->zentao->cowin['recommend']    = "推薦給朋友";

$lang->misc->zentao->service['zentaotrain'] = '禪道使用培訓';
$lang->misc->zentao->service['idc']         = '禪道在綫託管';
$lang->misc->zentao->service['custom']      = '禪道定製開發';

global $config;
$lang->misc->zentao->others['chanzhi']  = "<img src='{$config->webRoot}theme/default/images/main/chanzhi.ico' /> 蟬知門戶";
$lang->misc->zentao->others['zdoo']     = "<img src='{$config->webRoot}theme/default/images/main/zdoo.ico' /> ZDOO協同";
$lang->misc->zentao->others['xuanxuan'] = "<img src='{$config->webRoot}theme/default/images/main/xuanxuan.ico' /> 喧喧聊天";
$lang->misc->zentao->others['ydisk']    = "<img src='{$config->webRoot}theme/default/images/main/ydisk.ico' /> 悅庫網盤";
$lang->misc->zentao->others['meshiot' ] = "<img src='{$config->webRoot}theme/default/images/main/meshiot.ico' /> 易天物聯";

$lang->misc->mobile      = "手機訪問";
$lang->misc->noGDLib     = "請用手機瀏覽器訪問：<strong>%s</strong>";
$lang->misc->copyright   = "&copy; 2009 - " . date('Y') . " <a href='https://www.easycorp.cn' target='_blank'>禪道軟件（青島）有限公司</a> 電話：4006-8899-23 Email：<a href='mailto:co@zentao.net'>co@zentao.net</a>  QQ：1492153927";
$lang->misc->checkTable  = "檢查修復數據表";
$lang->misc->needRepair  = "修復表";
$lang->misc->repairTable = "資料庫表可能因為斷電原因損壞，需要檢查修復！！";
$lang->misc->repairFail  = "修復失敗，請到該資料庫的數據目錄下，嘗試執行<code>myisamchk -r -f %s.MYI</code>進行修復。";
$lang->misc->withoutCmd  = '修復失敗。';
$lang->misc->connectFail = "連接資料庫失敗，錯誤：%s，<br/> 請檢查mysql錯誤日誌，排查錯誤。";
$lang->misc->tableName   = "表名";
$lang->misc->tableStatus = "狀態";
$lang->misc->novice      = "您可能初次使用禪道，是否進入新手模式？";
$lang->misc->showAnnual  = '新增年度總結功能';
$lang->misc->annualDesc  = '12.0版本後，新增年度總結功能，可以到『統計->年度總結』頁面查看。 是否現在<a href="%s" target="_blank" id="showAnnual" class="btn mini primary">查看</a>';
$lang->misc->remind      = '新功能提醒';

$lang->misc->expiredTipsTitle    = '尊敬的系統管理員，您好：';
$lang->misc->expiredCountTips    = '系統中有<span class="expired-tips text-blue" data-toggle="tooltip" data-placement="bottom" title="%s">%s個插件</span>即將到期，為避免影響您的正常使用，請聯繫管理員及時續費或卸載。';
$lang->misc->expiredPluginTips   = '已到期的插件為：%s。';
$lang->misc->expiringPluginTips  = '即將到期的插件為：%s。';
$lang->misc->expiredTipsForAdmin = '當前系統中有%s個插件即將到期，為避免影響功能的正常使用，請儘快到系統後台插件管理中進行續費或卸載處理。';

$lang->misc->noticeRepair = "<h5>普通用戶請聯繫管理員進行修復</h5>
    <h5>管理員請登錄禪道所在的伺服器，創建<span>%s</span>檔案。</h5>
    <p>注意：</p>
    <ol>
    <li>檔案內容為空。</li>
    <li>如果之前檔案存在，刪除之後重新創建。</li>
    </ol>";

$lang->misc->feature = new stdclass();
$lang->misc->feature->lastest           = '最新版本';
$lang->misc->feature->detailed          = '詳情';
$lang->misc->feature->introduction      = '新功能介紹';
$lang->misc->feature->tutorial          = '新手引導教程';
$lang->misc->feature->tutorialImage     = 'theme/default/images/main/tutorial.png';
$lang->misc->feature->youngBlueTheme    = '全新青春藍主題';
$lang->misc->feature->youngBlueImage    = 'theme/default/images/main/new_theme.png';
$lang->misc->feature->visions           = "不同場景界面切換";
$lang->misc->feature->nextStep          = '下一頁';
$lang->misc->feature->prevStep          = '上一頁';
$lang->misc->feature->close             = '開始體驗';
$lang->misc->feature->learnMore         = '瞭解更多';
$lang->misc->feature->downloadFile      = '下載新版本功能介紹文檔';
$lang->misc->feature->tutorialDesc      = "<p>禪道15系列新增了多項功能，您可以通過“<strong>新手引導教程</strong>”快速瞭解禪道的基本使用方法。</p><p>通過滑鼠經過 [<span style='color: #0c60e1'>頭像-新手引導</span>]，點擊新手引導，即可進入新手引導教程。</p>";
$lang->misc->feature->themeDesc         = "<p>禪道15系列上線了全新的“青春藍”主題，頁面呈現更加美觀，體驗更加友好。</p><p>通過滑鼠經過 [<span style='color: #0c60e1'>頭像-主題-青春藍</span>]，點擊青春藍，即可設置成功。</p>";
$lang->misc->feature->visionsDesc       = "<p>從16.5開始增加了界面概念，用戶可以在<span style='color:#0c60e1'>[研發綜合界面]</span>中處理研發事務、在<span style='color:#0c60e1'>[運營管理界面]</span>處理日常辦公事務。</p><p>在頭像右側即可查看當前所處界面，點擊當前界面名稱可查看和切換其他的界面。</p>";
$lang->misc->feature->visionsImage      = 'theme/default/images/main/visions.png';
$lang->misc->feature->aiPrompts         = 'AI提詞功能';
$lang->misc->feature->aiPromptsImage    = 'theme/default/images/main/ai_prompts.svg';
$lang->misc->feature->promptDesign      = '設計AI提詞';
$lang->misc->feature->promptDesignImage = 'theme/default/images/main/prompt_design.svg';
$lang->misc->feature->promptExec        = '執行AI提詞';
$lang->misc->feature->promptExecImage   = 'theme/default/images/main/prompt_exec.svg';
$lang->misc->feature->promptLearnMore   = 'https://www.zentao.net/book/zentaopms/1097.html';

/* Release Date. */
$lang->misc->releaseDate['21.7.2']      = '2025-06-27';
$lang->misc->releaseDate['21.7.1']      = '2025-05-30';
$lang->misc->releaseDate['21.7']        = '2025-05-16';
$lang->misc->releaseDate['21.6.1']      = '2025-04-30';
$lang->misc->releaseDate['21.6']        = '2025-04-11';
$lang->misc->releaseDate['21.6.beta']   = '2025-03-21';
$lang->misc->releaseDate['21.5']        = '2025-03-06';
$lang->misc->releaseDate['21.4']        = '2025-01-15';
$lang->misc->releaseDate['21.3']        = '2024-12-27';
$lang->misc->releaseDate['21.2']        = '2024-12-03';
$lang->misc->releaseDate['21.1']        = '2024-11-15';
$lang->misc->releaseDate['21.0']        = '2024-11-01';
$lang->misc->releaseDate['20.8']        = '2024-10-21';
$lang->misc->releaseDate['20.7.1']      = '2024-09-30';
$lang->misc->releaseDate['20.7']        = '2024-09-14';
$lang->misc->releaseDate['20.6']        = '2024-08-30';
$lang->misc->releaseDate['20.5']        = '2024-08-16';
$lang->misc->releaseDate['18.13']       = '2024-08-09';
$lang->misc->releaseDate['20.4']        = '2024-08-02';
$lang->misc->releaseDate['20.3.0']      = '2024-07-22';
$lang->misc->releaseDate['20.2.0']      = '2024-07-10';
$lang->misc->releaseDate['20.1.1']      = '2024-06-21';
$lang->misc->releaseDate['20.1.0']      = '2024-06-03';
$lang->misc->releaseDate['20.0']        = '2024-04-30';
$lang->misc->releaseDate['18.12']       = '2024-04-12';
$lang->misc->releaseDate['20.0.beta2']  = '2024-03-15';
$lang->misc->releaseDate['18.11']       = '2024-02-28';
$lang->misc->releaseDate['18.10.1']     = '2024-01-17';
$lang->misc->releaseDate['20.0.beta1']  = '2024-01-26';
$lang->misc->releaseDate['20.0.alpha1'] = '2024-01-08';
$lang->misc->releaseDate['18.10']       = '2023-12-18';
$lang->misc->releaseDate['18.9']        = '2023-11-09';
$lang->misc->releaseDate['18.8']        = '2023-09-28';
$lang->misc->releaseDate['18.7']        = '2023-08-29';
$lang->misc->releaseDate['18.6']        = '2023-08-15';
$lang->misc->releaseDate['18.5']        = '2023-07-05';
$lang->misc->releaseDate['18.4']        = '2023-06-14';
$lang->misc->releaseDate['18.4.beta1']  = '2023-05-31';
$lang->misc->releaseDate['18.4.alpha1'] = '2023-04-21';
$lang->misc->releaseDate['18.3']        = '2023-03-15';
$lang->misc->releaseDate['18.2']        = '2023-02-27';
$lang->misc->releaseDate['18.1']        = '2023-02-08';
$lang->misc->releaseDate['18.0']        = '2023-01-03';
$lang->misc->releaseDate['18.0.beta3']  = '2022-12-26';
$lang->misc->releaseDate['18.0.beta2']  = '2022-12-14';
$lang->misc->releaseDate['18.0.beta1']  = '2022-11-16';
$lang->misc->releaseDate['17.8']        = '2022-11-02';
$lang->misc->releaseDate['17.7']        = '2022-10-19';
$lang->misc->releaseDate['17.6.2']      = '2022-09-23';
$lang->misc->releaseDate['17.6.1']      = '2022-09-08';
$lang->misc->releaseDate['17.6']        = '2022-08-26';
$lang->misc->releaseDate['17.5']        = '2022-08-11';
$lang->misc->releaseDate['17.4']        = '2022-07-27';
$lang->misc->releaseDate['17.3']        = '2022-07-13';
$lang->misc->releaseDate['17.2']        = '2022-06-29';
$lang->misc->releaseDate['17.1']        = '2022-06-16';
$lang->misc->releaseDate['17.0']        = '2022-06-02';
$lang->misc->releaseDate['17.0.beta2']  = '2022-05-26';
$lang->misc->releaseDate['17.0.beta1']  = '2022-05-06';
$lang->misc->releaseDate['16.5']        = '2022-03-24';
$lang->misc->releaseDate['16.5.beta1']  = '2022-03-16';
$lang->misc->releaseDate['16.4']        = '2022-02-15';
$lang->misc->releaseDate['16.3']        = '2022-01-26';
$lang->misc->releaseDate['16.2']        = '2022-01-17';
$lang->misc->releaseDate['16.1']        = '2022-01-11';
$lang->misc->releaseDate['16.0']        = '2021-12-24';
$lang->misc->releaseDate['16.0.beta1']  = '2021-12-06';
$lang->misc->releaseDate['15.7.1']      = '2021-11-02';
$lang->misc->releaseDate['15.7']        = '2021-10-18';
$lang->misc->releaseDate['15.6']        = '2021-10-12';
$lang->misc->releaseDate['15.5']        = '2021-09-14';
$lang->misc->releaseDate['15.4']        = '2021-08-23';
$lang->misc->releaseDate['15.3']        = '2021-08-04';
$lang->misc->releaseDate['15.2']        = '2021-07-20';
$lang->misc->releaseDate['15.0.3']      = '2021-06-24';
$lang->misc->releaseDate['15.0.2']      = '2021-06-12';
$lang->misc->releaseDate['15.0.1']      = '2021-06-06';
$lang->misc->releaseDate['15.0']        = '2021-04-30';
$lang->misc->releaseDate['15.0.rc3']    = '2021-04-16';
$lang->misc->releaseDate['15.0.rc2']    = '2021-04-09';
$lang->misc->releaseDate['15.0.rc1']    = '2021-04-05';
$lang->misc->releaseDate['12.5.3']      = '2021-01-06';
$lang->misc->releaseDate['12.5.2']      = '2020-12-18';
$lang->misc->releaseDate['12.5.1']      = '2020-11-30';
$lang->misc->releaseDate['12.5.stable'] = '2020-11-19';
// $lang->misc->releaseDate['20.0.alpha1'] = '2020-10-30';
$lang->misc->releaseDate['12.4.4']      = '2020-10-30';
$lang->misc->releaseDate['12.4.3']      = '2020-10-13';
$lang->misc->releaseDate['12.4.2']      = '2020-09-18';
$lang->misc->releaseDate['12.4.1']      = '2020-08-10';
$lang->misc->releaseDate['12.4.stable'] = '2020-07-28';
$lang->misc->releaseDate['12.3.3']      = '2020-07-02';
$lang->misc->releaseDate['12.3.2']      = '2020-06-01';
$lang->misc->releaseDate['12.3.1']      = '2020-05-15';
$lang->misc->releaseDate['12.3']        = '2020-04-08';
$lang->misc->releaseDate['12.2']        = '2020-03-25';
$lang->misc->releaseDate['12.1']        = '2020-03-10';
$lang->misc->releaseDate['12.0.1']      = '2020-02-12';
$lang->misc->releaseDate['12.0']        = '2020-01-03';
$lang->misc->releaseDate['11.7']        = '2019-11-28';
$lang->misc->releaseDate['11.6.5']      = '2019-11-08';
$lang->misc->releaseDate['11.6.4']      = '2019-10-17';
$lang->misc->releaseDate['11.6.3']      = '2019-09-24';
$lang->misc->releaseDate['11.6.2']      = '2019-09-06';
$lang->misc->releaseDate['11.6.1']      = '2019-08-23';
$lang->misc->releaseDate['11.6.stable'] = '2019-07-12';
$lang->misc->releaseDate['11.5.2']      = '2019-06-26';
$lang->misc->releaseDate['11.5.1']      = '2019-06-24';
$lang->misc->releaseDate['11.5.stable'] = '2019-05-08';
$lang->misc->releaseDate['11.4.1']      = '2019-04-08';
$lang->misc->releaseDate['11.4.stable'] = '2019-03-25';
$lang->misc->releaseDate['11.3.stable'] = '2019-02-27';
$lang->misc->releaseDate['11.2.stable'] = '2019-01-30';
$lang->misc->releaseDate['11.1.stable'] = '2019-01-04';
$lang->misc->releaseDate['11.0.stable'] = '2018-12-21';
$lang->misc->releaseDate['10.6.stable'] = '2018-11-20';
$lang->misc->releaseDate['10.5.stable'] = '2018-10-25';
$lang->misc->releaseDate['10.4.stable'] = '2018-09-28';
$lang->misc->releaseDate['10.3.stable'] = '2018-08-10';
$lang->misc->releaseDate['10.2.stable'] = '2018-08-02';
$lang->misc->releaseDate['10.0.stable'] = '2018-06-26';
$lang->misc->releaseDate['9.8.stable']  = '2018-01-17';
$lang->misc->releaseDate['9.7.stable']  = '2017-12-22';
$lang->misc->releaseDate['9.6.stable']  = '2017-11-06';
$lang->misc->releaseDate['9.5.1']       = '2017-09-27';
$lang->misc->releaseDate['9.3.beta']    = '2017-06-21';
$lang->misc->releaseDate['9.1.stable']  = '2017-03-23';
$lang->misc->releaseDate['9.0.beta']    = '2017-01-03';
$lang->misc->releaseDate['8.3.stable']  = '2016-11-09';
$lang->misc->releaseDate['8.2.stable']  = '2016-05-17';
$lang->misc->releaseDate['7.4.beta']    = '2015-11-13';
$lang->misc->releaseDate['7.2.stable']  = '2015-05-22';
$lang->misc->releaseDate['7.1.stable']  = '2015-03-07';
$lang->misc->releaseDate['6.3.stable']  = '2014-11-07';

/* Release Detail. */
$lang->misc->feature->all['21.7.2'][]      = array('title' => '文檔功能優化;項目模板功能發佈。', 'desc' => '');
$lang->misc->feature->all['21.7.1'][]      = array('title' => '文檔編輯器增加頂部工具欄；單項目流程管理配置；優化需求；增加交付物管理功能。', 'desc' => '');
$lang->misc->feature->all['21.7'][]        = array('title' => '瀑布、融合瀑布支持批量維護父子階段；支持建立跨執行任務依賴關係；IPD項目支持批量維護父子階段，修復bug。', 'desc' => '');
$lang->misc->feature->all['21.6.1'][]      = array('title' => '修復文檔相關Bug。', 'desc' => '');
$lang->misc->feature->all['21.6'][]        = array('title' => 'Jira導入優化，文檔多人協作。', 'desc' => '');
$lang->misc->feature->all['21.6.beta'][]   = array('title' => 'Jira導入2.0版本，Confluence導入。', 'desc' => '');
$lang->misc->feature->all['21.5'][]        = array('title' => '性能優化，備註支持上傳附件性能優化，文檔優化。', 'desc' => '');
$lang->misc->feature->all['21.4'][]        = array('title' => '測試相關細節優化，工作流細節優化，重構機會模組。', 'desc' => '');
$lang->misc->feature->all['21.3'][]        = array('title' => '項目集、項目、執行列表中增加已延期的篩選標籤，項目創建成功後增加下一步工作的引導，瀑布項目中已有任務和其他數據的階段可以拆分子階段，工作流新增的欄位組件中支持設置提示信息，重構創建工單頁面，敏捷項目中增加機會功能，在不啟用迭代功能的項目中增加問題、風險、機會、過程、QA、會議功能。', 'desc' => '');
$lang->misc->feature->all['21.2'][]        = array('title' => '發佈下增加應用，文檔下拉菜單增加項目集分組顯示和文檔權限提示，複製任務、需求、Bug、用例可以帶入附件，系統管理員可以刪除公共聯繫人數據，項目執行列表中增加任務搜索功能，執行列表中增加已延期篩選標籤，立項支持工作流擴展，BI增加版本管理，文檔編輯器兼容優化，反饋增加複製功能，創建反饋頁面所屬模組為空時可以維護模組，問題、風險、機會、審計增加消息通知，地盤貢獻增加貢獻的審計、基線列表，評審、審計詳情頁面細節優，甘特圖頁面支持任務搜索功能，設計增加需求變更後確認功能，評審報告、基線狀態報告支持導出。', 'desc' => '');
$lang->misc->feature->all['21.1'][]        = array('title' => '文檔介面空間優化升級，介面空間核心功能入口優化，主機功能優化，對象間關聯關係完善，度量項補0邏輯，DuckDB相關優化，上線功能優化。', 'desc' => '');
$lang->misc->feature->all['21.0'][]        = array('title' => '文檔功能優化；產品流程模板、項目流程模板BI設計器優化。', 'desc' => '');
$lang->misc->feature->all['20.8'][]        = array('title' => '文檔優化：優化任務關係，修復Bug。', 'desc' => '');
$lang->misc->feature->all['20.7.1'][]      = array('title' => '修復已知Bug。', 'desc' => '');
$lang->misc->feature->all['20.7'][]        = array('title' => '新手引導優化；菜單自定義；工作流優化；OR界面增加貢獻模組。', 'desc' => '');
$lang->misc->feature->all['20.6'][]        = array('title' => '工作流支持配置多界面；審批流支持使用工作流中欄位配置條件；修復Bug。', 'desc' => '');
$lang->misc->feature->all['20.5'][]        = array('title' => '文檔優化；新增23個內置度量項。', 'desc' => '');
$lang->misc->feature->all['18.13'][]       = array('title' => '性能優化：我的待辦和需求、任務、Bug列表頁面、項目、產品的詳情頁面。禪道兼容達夢資料庫；複製需求、任務、Bug、用例時，可以複製工作流中添加的欄位和值；修復Bug。', 'desc' => '');
$lang->misc->feature->all['20.4'][]        = array('title' => '新增消息中心;完善發佈管理;增加分支和標籤管理;審批流支持加簽設置。', 'desc' => '');
$lang->misc->feature->all['20.3.0'][]      = array('title' => '透視表自定義下鑽；需求池需求矩陣支持查看多層級的需求池需求、業務需求、用戶需求和研發需求；上游需求池需求發生變更時，下級需求將需要進行確認；產品路標支持關聯任意級的業務需求和用戶需求；優化了需求池需求、業務需求和用戶需求的操作按鈕邏輯；優化了需求池需求、業務需求和用戶需求的檢索標籤條件；還原已刪除需求時重算上游需求的階段。', 'desc' => '');
$lang->misc->feature->all['20.2.0'][]      = array('title' => '產品矩陣的優化，增加平台版應用配置功能，優化審批流，OR界面新增業務需求，用需新增已設路標和Charter立項階段，支持業用需求的無限層級，支持業用需求研發階段的推算，支持分發和拆分OR需求時交付階段的推算，OR需求支持分發為業務需求，實現老版本升級時OR需求和用戶需求的推算，開發階段增加TR4A評審點。', 'desc' => '');
$lang->misc->feature->all['20.1.1'][]      = array('title' => '重構底層PHP和UI框架，重構核心表單和儀表盤，用戶體驗全新升級。支持APCu 緩存，大幅提升系統性能。基線評審列表中的搜索功能。OR界面增加待處理功能、IPD項目的階段和評審點支持自定義、基線評審列表中的搜索功能。', 'desc' => '');
$lang->misc->feature->all['20.1.0'][]      = array('title' => '支持APCu 緩存，大幅提升系統性能，細節交互設計優化，DevOps細節優化，修復Bug。', 'desc' => '');
$lang->misc->feature->all['20.0'][]        = array('title' => '重構底層PHP和UI框架，重構核心表單和儀表盤，用戶體驗全新升級。', 'desc' => '');
$lang->misc->feature->all['18.12'][]       = array('title' => '禪道新版本中取消非研發用戶的信息和相關判斷，技術服務臨期增加提醒。實現度量項條目化管理、自定義度量項、基礎度量庫、一鍵重算歷史度量數據。需求池需求矩陣，路標支持移除已立項需求，運營管理界面增加反饋設置功能，需求池需求搜索條件中增加所屬需求池搜索條件。', 'desc' => '');
$lang->misc->feature->all['20.0.beta2'][]  = array('title' => '完善細節功能，解已知Bug。', 'desc' => '');
$lang->misc->feature->all['18.11'][]       = array('title' => '實現AI小程序功能，實現大屏引用度量項、全局篩選器功能,OR界面實現反饋功能，需求池需求增加關鍵詞，撤回用戶需求後需求池需求可以再次分發。', 'desc' => '');
$lang->misc->feature->all['18.10.1'][]     = array('title' => '需求池增加通知功能，支持產品綫功能，支持一個需求分發到多個產品。', 'desc' => '');
$lang->misc->feature->all['20.0.beta1'][]  = array('title' => '本次發佈主要對代碼進行了大規模重構，同時對用戶界面(UI)進行了全新升級，帶來了更好的產品性能、更強的安全性和更友好的用戶體驗。', 'desc' => '');
$lang->misc->feature->all['20.0.alpha1'][] = array('title' => '針對大規模重構重構，全面升級用戶界面(UI)的內部發佈。', 'desc' => '');
$lang->misc->feature->all['18.10'][]       = array('title' => '用例庫可以從其他用例庫導入用例，文檔導出可以按Word自適應圖片比例，歷史記錄排序方式增加cookie記錄，優化修改他人日誌邏輯。反饋、工單轉化時可自動帶入附件，反饋增加關鍵詞、抄送欄位，圖表類型新增水球圖，優化度量項的採集邏輯和展示樣式。', 'desc' => '');
$lang->misc->feature->all['18.9'][]        = array('title' => '全面接入AI大模型，客戶端引入增強版會議，測試單增加參與人，視頻附件增加在綫預覽，評審檢查分類增加自定義等。', 'desc' => '');
$lang->misc->feature->all['18.8'][]        = array('title' => 'BI中新增了度量項功能和應用巡檢報告大屏，DevOps平台版增加了配置嚮導，需求與市場管理界面中增加了市場管理功能，客戶端導航及個人中心全新改版。', 'desc' => '');
$lang->misc->feature->all['18.7'][]        = array('title' => 'DevOps新增了雲原生平台、製品庫和應用管理功能，優化了導航結構和相關UI交互。同時，新增了AI提詞設計器功能，支持與大語言模型對接，支持自定義AI應用等。', 'desc' => '');
$lang->misc->feature->all['18.6'][]        = array('title' => '優化了常用列表性能和BI功能的細節，並完善了瀑布項目的功能細節。修復Bug。', 'desc' => '');
$lang->misc->feature->all['18.5'][]        = array('title' => '學堂課程支持從雲端導入，支持課程中PDF檔案的預覽，同時還優化了常用列表的加載速度，修復了多處Bug。', 'desc' => '');
$lang->misc->feature->all['18.4'][]        = array('title' => '本次發佈優化了核心列表的性能，兼容達夢資料庫，修復了多處Bug。', 'desc' => '');
$lang->misc->feature->all['18.4.beta1'][]  = array('title' => '解Bug。', 'desc' => '');
$lang->misc->feature->all['18.4.alpha1'][] = array('title' => '優化權限、文檔交互體驗，測試新增場景概念，用例支持xmind導入，並對BI模組中的大屏、透視表、圖表、數據表進行了全面升級。', 'desc' => '');
$lang->misc->feature->all['18.3'][]        = array('title' => '二次開發增加語言項自定義,支持對菜單和檢索標籤的語言項進行定義；二次開發增加編輯器功能，支持用戶按需開啟和關閉；表單意外退出支持表單暫存，下次進入自動代入填寫的未保存信息。', 'desc' => '');
$lang->misc->feature->all['18.2'][]        = array('title' => '新增融合敏捷、融合瀑布管理模型，瀑布項目階段支持無限級拆分，後台進行全新UI改版。修復Bug。', 'desc' => '');
$lang->misc->feature->all['18.1'][]        = array('title' => '自動化測試解決方案交互優化、新增快照管理功能。禪道客戶端實現了 PPT文檔在綫協作。修復Bug。', 'desc' => '');
$lang->misc->feature->all['18.0'][]        = array('title' => '推出自動化測試解決方案；運營管理界面增加工單功能；審批流支持增加所有類型的通知以及掙值計算規則完善。', 'desc' => '');
$lang->misc->feature->all['18.0.beta3'][]  = array('title' => '統計模組升級為BI，內置5張宏觀管理維度大屏。', 'desc' => '');
$lang->misc->feature->all['18.0.beta2'][]  = array('title' => '優化多分支/多平台產品，支持創建孿生需求，計劃、版本、發佈支持跨分支關聯需求和bug，並且禪道客戶端實現了機器人會話機制。', 'desc' => '');
$lang->misc->feature->all['18.0.beta1'][]  = array('title' => '主要對禪道多項核心流程進行改進，新增項目型項目、無迭代項目；支持項目跨項目集關聯產品；支持輕量管理模式和全生命周期管理模式進行切換。', 'desc' => '');
$lang->misc->feature->all['17.8'][]        = array('title' => '列表狀態顏色、儀表盤顏色的改版和任務日誌頁面的優化。', 'desc' => '');
$lang->misc->feature->all['17.7'][]        = array('title' => '過渡版本表格優化完成。新增工單功能，優化了反饋功能。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.6.2'][]      = array('title' => '禪道更新葉蘭綠、禪道藍、青春藍三大主題。實現附件批量上傳功能。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.6.1'][]      = array('title' => '優化了多人任務的處理邏輯，修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.6'][]        = array('title' => '優化了需求的處理邏輯，拆分了用需和軟需的權限。甘特圖支持手動拖拽維護任務關係。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.5'][]        = array('title' => '提供高效的可視化統計工具。優化禪道性能，資料庫引擎從MyISAM調整為InnoDB。甘特圖優化升級，旗艦版的複製項目可以複製任務等更多信息。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.4'][]        = array('title' => '詳情頁面的視覺優化和部分頁面跳轉邏輯優化。看板功能完善。文檔創建和編輯頁面優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.3'][]        = array('title' => '統計、後台等模組的UI優化，用例庫同步用例信息功能優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.2'][]        = array('title' => '調整敏捷項目區塊的展示，項目集、項目和測試相關UI優化，細節體驗優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.1'][]        = array('title' => '修改執行、項目模組的交互問題，完成客戶巴高優先順序需求，細節體驗優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.0'][]        = array('title' => '細節體驗優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.0.beta2'][]  = array('title' => '細節體驗優化。修復Bug。', 'desc' => '');
$lang->misc->feature->all['17.0.beta1'][]  = array('title' => '完成客戶巴高優先順序需求。修復Bug。', 'desc' => '');
$lang->misc->feature->all['16.5'][]        = array('title' => '修復Bug。', 'desc' => '');
$lang->misc->feature->all['16.5.beta1'][]  = array('title' => '將禪道收費版和開源版整合到一個包中，優化升級步驟。', 'desc' => '');
$lang->misc->feature->all['16.4'][]        = array('title' => '實現JIRA導入功能，完善插件擴展機制。', 'desc' => '');
$lang->misc->feature->all['16.3'][]        = array('title' => '看板增加關聯計劃/發佈/版本/迭代功能，細節體驗優化。', 'desc' => '');
$lang->misc->feature->all['16.2'][]        = array('title' => '新增專業研發看板，可以創建看板模型項目，修復Bug。', 'desc' => '');
$lang->misc->feature->all['16.1'][]        = array('title' => '計劃增加狀態管理和看板視圖，升級流程優化，修復Bug。', 'desc' => '');
$lang->misc->feature->all['16.0'][]        = array('title' => '新增通用看板，完善分支管理，修復Bug。', 'desc' => '');
$lang->misc->feature->all['16.0.beta1'][]  = array('title' => '新增瀑布模型項目，新增任務看板，完善分支管理和細節，修復Bug。', 'desc' => '');
$lang->misc->feature->all['15.7.1'][]      = array('title' => '修復Bug。', 'desc' => '');
$lang->misc->feature->all['15.7'][]        = array('title' => '新增介面庫。修復Bug。', 'desc' => '');
$lang->misc->feature->all['15.6'][]        = array('title' => '修復Bug。', 'desc' => '');
$lang->misc->feature->all['15.5'][]        = array('title' => '增加項目集/產品/項目看板視圖、全局添加功能、新手引導。 修復Bug。', 'desc' => '');
$lang->misc->feature->all['15.4'][]        = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['15.3'][]        = array('title' => '實現界面風格改動和文檔優化，修復Bug', 'desc' => '');
$lang->misc->feature->all['15.2'][]        = array('title' => '優化新版本升級流程，增加執行看板。', 'desc' => '');

$lang->misc->feature->all['15.0.3'][]      = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['15.0.2'][]      = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['15.0.1'][]      = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['15.0'][]        = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['15.0.rc3'][]    = array('title' => '完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['15.0.rc2'][]    = array('title' => '修復Bug，優化界面交互', 'desc' => '');
$lang->misc->feature->all['15.0.rc1'][]    = array('title' => '升級到15版本，重構導航、文檔庫，增加項目集管理', 'desc' => '');
$lang->misc->feature->all['12.5.3'][]      = array('title' => '優化年度總結', 'desc' => '');
$lang->misc->feature->all['12.5.2'][]      = array('title' => '修復Bug', 'desc' => '');
$lang->misc->feature->all['12.5.1'][]      = array('title' => '修復漏洞。', 'desc' => '');
$lang->misc->feature->all['12.5.stable'][] = array('title' => '解決bug，完成高優先順序需求。', 'desc' => '');

$lang->misc->feature->all['12.4.4'][] = array('title'=>'兼容專業版和企業版', 'desc' => '');
$lang->misc->feature->all['12.4.3'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['12.4.2'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['12.4.1'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.4.stable'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.3.3'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['12.3.2'][] = array('title'=>'修復工作流。', 'desc' => '');
$lang->misc->feature->all['12.3.1'][] = array('title'=>'修復重要程度高的Bug。', 'desc' => '');
$lang->misc->feature->all['12.3'][]   = array('title'=>'整合單元測試，打通持續整合閉環。', 'desc' => '');
$lang->misc->feature->all['12.2'][]   = array('title'=>'增加父子需求，兼容最新喧喧。', 'desc' => '');
$lang->misc->feature->all['12.1'][]   = array('title'=>'增加構建功能', 'desc' => '<p>增加構建功能，整合Jenkins進行構建</p>');
$lang->misc->feature->all['12.0.1'][] = array('title'=>'修復Bug', 'desc' => '');

$lang->misc->feature->all['12.0'][]   = array('title'=>'將代碼功能版本瀏覽功能轉移到開源版', 'desc' => '');
$lang->misc->feature->all['12.0'][]   = array('title'=>'增加年度總結', 'desc' => '根據角色顯示年度總結。');
$lang->misc->feature->all['12.0'][]   = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.7'][]   = array('title'=>'完善細節，修復Bug', 'desc' => '<p>增加用戶是否使用敏捷概念的選擇</p><p>webhook類型中增加企業微信</p><p>實現到釘釘個人消息的通知</p>');
$lang->misc->feature->all['11.6.5'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.4'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.3'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.2'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.6.1'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.6.stable'][] = array('title'=>'改善國際版界面', 'desc' => '');
$lang->misc->feature->all['11.6.stable'][] = array('title'=>'添加翻譯功能', 'desc' => '');

$lang->misc->feature->all['11.5.2'][] = array('title'=>'增加禪道安全性，增加登錄禪道弱口令檢查', 'desc' => '');
$lang->misc->feature->all['11.5.1'][] = array('title'=>'新增第三方應用免密登錄禪道，修復Bug', 'desc' => '');

$lang->misc->feature->all['11.5.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'新增動態過濾機制', 'desc' => '');
$lang->misc->feature->all['11.5.stable'][] = array('title'=>'整合新版本客戶端', 'desc' => '');

$lang->misc->feature->all['11.4.1'][]      = array('title'=>'完善細節，修復Bug', 'desc' => '');

$lang->misc->feature->all["11.4.stable"][] = array("title"=>"完善細節，修復Bug", "desc" => "<p>增強測試任務管理</p><p>優化計劃、發佈、版本關聯{$lang->SRCommon}和bug的交互</p><p>文檔庫可以自定義是否顯示子分類裡的文檔</p><p>修復bug，完善細節</p>");

$lang->misc->feature->all['11.3.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '<p>計劃添加子計劃功能</p><p>優化chosen交互</p><p>添加時區設置</p><p>優化文檔庫和文檔</p>');

$lang->misc->feature->all['11.2.stable'][] = array('title'=>'完善細節，修復Bug', 'desc' => '<p>增加升級日誌和升級後資料庫檢查的功能</p><p>修復禪道整合客戶端和其他若干bug，完善細節</p>');

$lang->misc->feature->all['11.1.stable'][] = array('title'=>'主要修復Bug。', 'desc' => '');

$lang->misc->feature->all['11.0.stable'][] = array('title'=>'禪道整合喧喧', 'desc' => '');

$lang->misc->feature->all['10.6.stable'][] = array('title'=>'調整備份機制', 'desc' => '<p>增加備份設置，備份更加靈活</p><p>顯示備份進度</p><p>可以更改備份目錄</p>');
$lang->misc->feature->all['10.6.stable'][] = array('title'=>'優化和調整菜單', 'desc' => '<p>調整後台菜單</p><p>調整我的地盤和項目的二級菜單</p>');

$lang->misc->feature->all['10.5.stable'][] = array('title'=>'調整文檔顯示', 'desc' => '<p>調整文檔庫左側的佈局方式</p><p>文檔庫左側導航底部增加篩選條件</p>');
$lang->misc->feature->all['10.5.stable'][] = array('title'=>'調整子任務邏輯，優化父子任務顯示。', 'desc' => '');

$lang->misc->feature->all['10.4.stable'][] = array('title'=>'優化調整新界面', 'desc' => '<p>詳情頁面還原我們之前的排版佈局</p><p>重構添加用戶頁面的表單</p><p>用例執行時，如果用戶手工選擇了通過，寫結果的時候不要更新用例狀態</p>');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'用戶機器休眠登錄失效後，重新刷新session', 'desc' => '');
$lang->misc->feature->all['10.4.stable'][] = array('title'=>'提升現有的介面機制', 'desc' => '');

$lang->misc->feature->all['10.3.stable'][] = array('title'=>'修復Bug', 'desc' => '');
$lang->misc->feature->all['10.2.stable'][] = array('title'=>'整合喧喧IM', 'desc' => '');

$lang->misc->feature->all['10.0.stable'][] = array('title'=>'全新的界面和交互體驗', 'desc' => '<ol><li>全新的我的地盤</li><li>全新的動態頁面</li><li>全新的產品主頁</li><li>全新的產品概況</li><li>全新的路線圖</li><li>全新的項目主頁</li><li>全新的項目概況</li><li>全新的測試主頁</li><li>全新的文檔主頁</li><li>我的地盤新增工作統計區塊</li><li>我的地盤待辦區塊可以直接添加、編輯、完成待辦</li><li>產品主頁新增產品統計區塊</li><li>產品主頁新增產品總覽區塊</li><li>項目主頁新增項目統計區塊</li><li>項目主頁新增項目總覽區塊</li><li>測試主頁新增測試統計區塊</li><li>所有產品、產品主頁、所有項目、項目主頁、測試主頁等按鈕從二級導航右側移動到了左側</li><li>項目任務列表看板、燃盡圖、樹狀圖、分組查看等按鈕從三級導航中移動到二級導航中，樹狀圖、分組查看和任務列表整合到一個下拉列表中</li><li>項目下二級導航中Bug、版本、測試單三個跟測試相關的導航整合到一個下拉列表中</li><li>版本、測試單列表按照產品分組展示，佈局更加合理</li><li>文檔左側增加樹狀圖顯示</li><li>文檔增加快速訪問功能，包括最近更新、我的文檔、我的收藏三個入口</li><li>文檔增加收藏功能</li><ol>');

$lang->misc->feature->all['9.8.stable'][] = array('title'=>'實現集中的消息處理機制', 'desc' => '<p>郵件，短信，webhook都放統一的消息發送</p><p>移植ZDOO裡面的消息通知功能</p>');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'實現周期性待辦功能', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'增加指派給我的區塊', 'desc' => '');
$lang->misc->feature->all['9.8.stable'][] = array('title'=>'項目可以選擇多個測試單生成報告', 'desc' => '');

$lang->misc->feature->all['9.7.stable'][] = array('title'=>'調整國際版，增加英文Demo數據。', 'desc' => '');

$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增了webhook功能', 'desc' => '實現與倍冾、釘釘的消息通知介面');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'新增禪道操作獲取積分的功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'項目任務新增了多人任務和子任務功能', 'desc' => '');
$lang->misc->feature->all['9.6.stable'][] = array('title'=>'產品視圖新增了產品綫功能', 'desc' => '');

$lang->misc->feature->all['9.5.1'][] = array('title'=>'新增受限操作', 'desc' => '');

$lang->misc->feature->all['9.3.beta'][] = array('title'=>'升級框架，增強程序安全', 'desc' => '');

$lang->misc->feature->all['9.1.stable'][] = array('title'=>'完善測試視圖', 'desc' => '<p>增加測試套件、公共測試庫和測試總結功能</p>');
$lang->misc->feature->all['9.1.stable'][] = array('title'=>'支持測試步驟分組', 'desc' => '');

$lang->misc->feature->all['9.0.beta'][] = array('title'=>'增加禪道雲發信功能', 'desc' => '<p>禪道雲發信是禪道聯合SendCloud推出的一項免費發信服務，只有用戶綁定禪道，並通過驗證即可使用。</p>');
$lang->misc->feature->all['9.0.beta'][] = array('title'=>'優化富文本編輯器和markdown編輯器', 'desc' => '');

$lang->misc->feature->all['8.3.stable'][] = array('title'=>'調整文檔功能', 'desc' => '<p>增加文檔模組首頁，重新組織文檔庫結構，增加權限</p><p>多種檔案瀏覽方式，文檔支持Markdown，增加文檔權限管理，增加檔案版本管理。</p>');

$lang->misc->feature->all['8.2.stable'][] = array('title'=>'首頁自定義', 'desc' => '<p>我的地盤由我做主。現在開始，你可以向首頁添加多種多樣的內容區塊，而且還可以決定如何排列和顯示他們。</p><p>我的地盤、產品、項目、測試模組下均支持首頁自定義功能。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導航定製', 'desc' => '<p>導航上顯示的項目現在完全由你來決定，不僅僅可以決定在導航上展示哪些內容，還可以決定展示的順序。</p><p>將滑鼠懸浮在導航上稍後會在右側顯示定製按鈕，點擊打開定製對話框，通過點擊切換是否顯示，拖放操作來更改顯示順序。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'批量添加、編輯自定義', 'desc' => '<p>可以在批量添加和批量編輯頁面自定義操作的欄位。</p>');
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"添加{$lang->SRCommon}、任務、Bug、用例自定義", 'desc' => "<p>可以在添加{$lang->SRCommon}、任務、Bug、用例頁面，自定義部分欄位是否顯示。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'導出自定義', 'desc' => "<p>在導出{$lang->SRCommon}、任務、Bug、用例的時候，用戶可以自定義導出的欄位，也可以保存模板方便每次導出。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>"{$lang->SRCommon}、任務、Bug、用例組合檢索功能", 'desc' => "<p>在{$lang->SRCommon}、任務、Bug、用例列表頁面，可以實現模組和標籤的組合檢索。</p>");
$lang->misc->feature->all['8.2.stable'][] = array('title'=>'增加新手教程', 'desc' => '<p>增加新手教程，方便新用戶瞭解禪道使用。</p>');

$lang->misc->feature->all['7.4.beta'][] = array('title'=>'產品實現分支功能', 'desc' => "<p>產品增加平台/分支類型，相應的{$lang->SRCommon}、計劃、Bug、用例、模組等都增加分支。</p>");
$lang->misc->feature->all['7.4.beta'][] = array('title'=>'調整發佈模組', 'desc' => '<p>發佈增加停止維護操作，當發佈停止維護時，創建Bug將不顯示這個發佈。</p><p>發佈中遺留的bug改為手工關聯。</p>');
$lang->misc->feature->all['7.4.beta'][] = array('title'=>"調整{$lang->SRCommon}和Bug的創建頁面", 'desc' => '');

$lang->misc->feature->all['7.2.stable'][] = array('title'=>'增強安全', 'desc' => '<p>加強對管理員弱口令的檢查。</p><p>寫插件，上傳插件的時候需要創建ok檔案。</p><p>敏感操作增加管理員口令的檢查</p><p>對輸入內容做striptags, specialchars處理。</p>');
$lang->misc->feature->all['7.2.stable'][] = array('title'=>'完善細節', 'desc' => '');

$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供計劃任務框架', 'desc' => '增加計劃任務框架，加入每日提醒、更新燃盡圖、備份、發信等重要任務。');
$lang->misc->feature->all['7.1.stable'][] = array('title'=>'提供rpm和deb包', 'desc' => '');

$lang->misc->feature->all['6.3.stable'][] = array('title'=>'增加數據表格功能', 'desc' => '<p>可配置數據表格中可顯示的欄位，按照配置欄位顯示想看的數據</p>');
$lang->misc->feature->all['6.3.stable'][] = array('title'=>'繼續完善細節', 'desc' => '');
