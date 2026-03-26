<?php
/**
 * The install module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: zh-tw.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common = '安裝';
$lang->install->next   = '下一步';
$lang->install->pre    = '返回';
$lang->install->reload = '刷新';
$lang->install->error  = '錯誤 ';

$lang->install->officeDomain = 'https://www.zentao.net';

$lang->install->start            = '開始安裝';
$lang->install->keepInstalling   = '繼續安裝當前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = '歡迎使用禪道項目管理軟件！';
$lang->install->license          = '禪道項目管理軟件授權協議';
$lang->install->desc             = <<<EOT
禪道項目管理軟件(ZenTaoPMS)是一款國產的，基于<a href='http://zpl.pub/page/zplv12.html' target='_blank'>ZPL</a>或<a href='https://www.gnu.org/licenses/agpl-3.0.en.html' target='_blank'>AGPL</a>雙授權協議，開源免費的項目管理軟件，它集產品管理、項目管理、測試管理於一體，同時還包含了事務管理、組織管理等諸多功能，是中小型企業項目管理的首選。

禪道項目管理軟件使用PHP + MySQL開發，基于自主的PHP開發框架──ZenTaoPHP而成。第三方開發者或者企業可以非常方便的開發插件或者進行定製。
EOT;
$lang->install->links = <<<EOT
禪道項目管理軟件由<strong><a href='https://easycorp.cn' target='_blank' class='text-danger'>禪道軟件（青島）有限公司</a></strong>開發。
官方網站：<a href='https://www.zentao.net' target='_blank'>https://www.zentao.net</a>
技術支持：<a href='https://www.zentao.net/ask/' target='_blank'>https://www.zentao.net/ask/</a>
新浪微博：<a href='https://weibo.com/easysoft' target='_blank'>https://weibo.com/easysoft</a>



您現在正在安裝的版本是 <strong class='text-danger'>%s</strong>。
EOT;

$lang->install->selectMode          = "請選擇使用模式";
$lang->install->introduction        = "禪道15系列功能介紹";
$lang->install->howToUse            = "請問您計劃如何使用禪道的新版本呢";
$lang->install->guideVideo          = 'https://dl.zentao.net/vedio/program0716.mp4';
$lang->install->introductionContent = <<<EOT
<div>
  <h4>尊敬的用戶您好，歡迎您使用禪道項目管理系統。</h4>
  <p> 禪道自15系列開始提供了兩種使用模式，一種是經典管理模式，功能較為精簡，主要提供了產品和項目兩個核心功能；另一種是全新項目集管理模式，增加了項目集和執行的概念。下面是全新項目集管理模式的介紹：</p>
  <div class='block-content'>
    <div class='block-details'><p class='block-title'><i class='icon icon-program'></i> <strong>項目集</strong></p><p>項目集用來管理一組相關的產品和項目，公司高層或者PMO可以用來做戰略規劃。</p></div>
    <div class='block-details block-right'>
      <p class='block-title'><i class='icon icon-product'></i> <strong>產品</strong></p>
      <p>產品用來將公司的戰略細分為可以進行研發的需求，產品經理可以用來做產品的發佈計劃。<p>
    </div>
    <div class='block-details'>
      <p class='block-title'><i class='icon icon-project'></i> <strong>項目</strong></p>
      <p>項目用來組織相應的人力進行研發，做好項目過程的跟蹤管理，多快好省地完成項目。</p>
    </div>
    <div class='block-details block-right'>
      <p class='block-title'><i class='icon icon-run'></i> <strong>執行</strong></p>
      <p>執行用來做任務的分解、指派和跟蹤，保證項目目標可以落實到人來執行。<p>
    </div>
  </div>
  <div class='text-center introduction-link'>
    <a href='https://dl.zentao.net/zentao/zentaoconcept.pdf' target='_blank' class='btn btn-wide btn-info'><i class='icon icon-p-square'></i> 文檔介紹</a>
    <a href='javascript:showVideo()' class='btn btn-wide btn-info'><i class='icon icon-video-play'></i> 視頻介紹</a>
  </div>
</div>
EOT;

$lang->install->newReleased = "<strong class='text-danger'>提示</strong>：官網網站已有最新版本<strong class='text-danger'>%s</strong>, 發佈日期于 %s。";
$lang->install->or          = '或者';
$lang->install->checking    = '系統檢查';
$lang->install->ok          = '檢查通過(√)';
$lang->install->fail        = '檢查失敗(×)';
$lang->install->loaded      = '已加載';
$lang->install->unloaded    = '未加載';
$lang->install->exists      = '目錄存在 ';
$lang->install->notExists   = '目錄不存在 ';
$lang->install->writable    = '目錄可寫 ';
$lang->install->notWritable = '目錄不可寫 ';
$lang->install->phpINI      = 'PHP配置檔案';
$lang->install->checkItem   = '檢查項';
$lang->install->current     = '當前配置';
$lang->install->result      = '檢查結果';
$lang->install->action      = '如何修改';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必須大於5.2.0';

$lang->install->pdo           = 'PDO擴展';
$lang->install->pdoFail       = '修改PHP配置檔案，加載PDO擴展。';
$lang->install->pdoMySQL      = 'PDO_MySQL擴展';
$lang->install->pdoMySQLFail  = '修改PHP配置檔案，加載pdo_mysql擴展。';
$lang->install->json          = 'JSON擴展';
$lang->install->jsonFail      = '修改PHP配置檔案，加載JSON擴展。';
$lang->install->openssl       = 'OPENSSL擴展';
$lang->install->opensslFail   = '修改PHP配置檔案，加載OPENSSL擴展。';
$lang->install->mbstring      = 'MBSTRING擴展';
$lang->install->mbstringFail  = '修改PHP配置檔案，加載MBSTRING擴展。';
$lang->install->zlib          = 'ZLIB擴展';
$lang->install->zlibFail      = '修改PHP配置檔案，加載ZLIB擴展。';
$lang->install->curl          = 'CURL擴展';
$lang->install->curlFail      = '修改PHP配置檔案，加載CURL擴展。';
$lang->install->filter        = 'FILTER擴展';
$lang->install->filterFail    = '修改PHP配置檔案，加載FILTER擴展。';
$lang->install->gd            = 'GD擴展';
$lang->install->gdFail        = '修改PHP配置檔案，加載GD擴展。';
$lang->install->iconv         = 'ICONV擴展';
$lang->install->iconvFail     = '修改PHP配置檔案，加載ICONV擴展。';
$lang->install->tmpRoot       = '臨時檔案目錄';
$lang->install->dataRoot      = '上傳檔案目錄';
$lang->install->session       = 'Session存儲目錄';
$lang->install->sessionFail   = '修改PHP配置檔案，設置session.save_path。<br />如果使用寶塔面板，可以到寶塔Web面板中“軟件商店”，打開PHP設置，到“Session配置”項，選擇files，點擊保存。老版本需要修改php配置檔案。';
$lang->install->mkdirWin      = '<p>需要創建目錄%s。命令為：<br /> mkdir %s</p>';
$lang->install->chmodWin      = '需要修改目錄 "%s" 的權限。';
$lang->install->mkdirLinux    = '<p>需要創建目錄%s。<br /> 命令為：<br /> mkdir -p %s</p>';
$lang->install->chmodLinux    = '需要修改目錄 "%s" 的權限。<br />命令為：<br />chmod 777 -R %s';
$lang->install->duckdb        = 'DuckDB引擎';
$lang->install->duckdbLoading = '正在安裝中，請稍後...';
$lang->install->duckdbFail    = '安裝duckdb引擎失敗，請參考手冊進行手動安裝。';

$lang->install->installDuckdb       = '安裝DuckDB';
$lang->install->installingDuckdb    = '正在安裝duckdb引擎...';
$lang->install->installedDuckdb     = '安裝duckdb引擎成功！';
$lang->install->installingExtension = '正在安裝duckdb引擎擴展[%s]...';
$lang->install->installedExtension  = '安裝duckdb引擎擴展[%s]成功！';
$lang->install->installedFail       = '[%s]安裝失敗';

$lang->install->timezone       = '時區設置';
$lang->install->defaultLang    = '預設語言';
$lang->install->dbDriver       = '資料庫類型';
$lang->install->dbHost         = '資料庫伺服器';
$lang->install->dbHostNote     = '如果127.0.0.1無法訪問，嘗試使用localhost';
$lang->install->dbPort         = '伺服器連接埠';
$lang->install->dbEncoding     = '資料庫編碼';
$lang->install->dbUser         = '資料庫用戶名';
$lang->install->dbPassword     = '資料庫密碼';
$lang->install->dbName         = 'PMS使用的庫';
$lang->install->dbPrefix       = '建表使用的首碼';
$lang->install->clearDB        = '清空現有數據';
$lang->install->importDemoData = '導入demo數據';
$lang->install->working        = '工作方式';

$lang->install->dbDriverList = array();
$lang->install->dbDriverList['mysql']     = 'MySQL';
$lang->install->dbDriverList['dm']        = '達夢';
$lang->install->dbDriverList['oceanbase'] = 'OceanBase';

$lang->install->requestTypes['GET']       = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '靜態友好方式';

$lang->install->workingList['full']      = '完整研發管理工具';

$lang->install->errorConnectDB      = '資料庫連接失敗 ';
$lang->install->errorDBName         = '資料庫名不能含有 “.” ';
$lang->install->errorCreateDB       = '資料庫創建失敗';
$lang->install->errorTableExists    = '數據表已經存在，您之前應該有安裝過禪道，繼續安裝請返回前頁並選擇清空數據';
$lang->install->errorCreateTable    = '創建表失敗';
$lang->install->errorEngineInnodb   = '您當前的資料庫不支持使用InnoDB數據表引擎，請修改為MyISAM後重試。';
$lang->install->errorImportDemoData = '導入demo數據失敗';
$lang->install->errorDBUserPriv     = '當前資料庫用戶權限不足！\n請切換root用戶或使用以下sql語句賦予當前用戶權限：\n';

$lang->install->setConfig          = '生成配置檔案';
$lang->install->key                = '配置項';
$lang->install->value              = '值';
$lang->install->saveConfig         = '保存配置檔案';
$lang->install->save2File          = '<div class="text-warning">拷貝上面文本框中的內容，將其保存到 "<strong> %s </strong>"中。您以後還可繼續修改此配置檔案。</div>';
$lang->install->saved2File         = '配置信息已經成功保存到" <strong>%s</strong> "中。您後面還可繼續修改此檔案。';
$lang->install->errorNotSaveConfig = '還沒有保存配置檔案';
$lang->install->errorNotInitConfig = '還沒有生成配置檔案';

global $app;
$lang->install->CSRFNotice = "系統已開啟了CSRF的防禦，如需關閉，請聯繫管理員到{$app->basePath}config/config.php檔案中手動關閉。";

$lang->install->getPriv  = '設置帳號';
$lang->install->company  = '公司名稱';
$lang->install->account  = '管理員帳號';
$lang->install->password = '管理員密碼';

$lang->install->placeholder = new stdclass();
$lang->install->placeholder->password = '6位及以上，包含大小寫字母，數字。';

$lang->install->errorEmpty['company']  = "{$lang->install->company}不能為空";
$lang->install->errorEmpty['account']  = "{$lang->install->account}不能為空";
$lang->install->errorEmpty['password'] = "{$lang->install->password}不能為空";

$lang->install->langList['1'] = array('module' => 'process', 'key' => 'support', 'value' => '支持過程');
$lang->install->langList['2'] = array('module' => 'process', 'key' => 'engineering', 'value' => '工程支持');
$lang->install->langList['3'] = array('module' => 'process', 'key' => 'project', 'value' => '項目管理');

$lang->install->processList['11'] = '立項管理';
$lang->install->processList['12'] = '項目規劃';
$lang->install->processList['13'] = '項目監控';
$lang->install->processList['14'] = '風險管理';
$lang->install->processList['15'] = '結項管理';
$lang->install->processList['16'] = '量化項目管理';
$lang->install->processList['17'] = '需求開發';
$lang->install->processList['18'] = '設計開發';
$lang->install->processList['19'] = '實現與測試';
$lang->install->processList['20'] = '系統測試';
$lang->install->processList['21'] = '客戶驗收';
$lang->install->processList['22'] = '質量保證';
$lang->install->processList['23'] = '配置管理';
$lang->install->processList['24'] = '度量分析';
$lang->install->processList['25'] = '原因分析與解決';
$lang->install->processList['26'] = '決策分析';

$lang->install->basicmeasList['2'] = array('name' => '項目用戶需求初始規模', 'unit' => '故事點或功能點', 'definition' => '項目每個產品的第一個用戶需求規格說明書基線版本的規模之和');
$lang->install->basicmeasList['3'] = array('name' => '項目軟件需求初始規模', 'unit' => '故事點或功能點', 'definition' => '項目每個產品的第一個軟件需求規格說明書基線版本的規模之和');
$lang->install->basicmeasList['4'] = array('name' => '項目用戶需求實時規模', 'unit' => '故事點或功能點', 'definition' => '項目用戶需求實際的規模');
$lang->install->basicmeasList['5'] = array('name' => '項目軟件需求實時規模', 'unit' => '故事點或功能點', 'definition' => '項目軟件需求實際的規模');
$lang->install->basicmeasList['6'] = array('name' => '項目估算規模', 'unit' => '故事點或功能點', 'definition' => '項目最初估算時估計的規模');
$lang->install->basicmeasList['8'] = array('name' => '項目需求階段計劃天數', 'unit' => '天', 'definition' => '項目下面所有需求階段計劃天數的和');
$lang->install->basicmeasList['9'] = array('name' => '項目設計階段計劃天數', 'unit' => '天', 'definition' => '項目下面所有設計階段計劃天數的和');
$lang->install->basicmeasList['10'] = array('name' => '項目開發階段計劃天數', 'unit' => '天', 'definition' => '項目下面所有研發階段計劃天數的和');
$lang->install->basicmeasList['11'] = array('name' => '項目測試階段計劃天數', 'unit' => '天', 'definition' => '項目下面所有測試階段計劃天數的和');
$lang->install->basicmeasList['12'] = array('name' => '項目需求階段實際天數', 'unit' => '天', 'definition' => '項目下面所有需求階段實際天數的和');
$lang->install->basicmeasList['13'] = array('name' => '項目設計階段實際天數', 'unit' => '天', 'definition' => '項目下面所有設計階段實際天數的和');
$lang->install->basicmeasList['14'] = array('name' => '項目開發階段實際天數', 'unit' => '天', 'definition' => '項目下面所有研發階段實際天數的和');
$lang->install->basicmeasList['15'] = array('name' => '項目測試階段實際天數', 'unit' => '天', 'definition' => '項目下面所有測試階段實際天數的和');
$lang->install->basicmeasList['26'] = array('name' => '分產品需求階段計劃天數', 'unit' => '天', 'definition' => '產品下面所有需求階段計劃天數的和');
$lang->install->basicmeasList['27'] = array('name' => '分產品設計階段計劃天數', 'unit' => '天', 'definition' => '產品下面所有設計階段計劃天數的和');
$lang->install->basicmeasList['28'] = array('name' => '分產品開發階段計劃天數', 'unit' => '天', 'definition' => '產品下面所有研發階段計劃天數的和');
$lang->install->basicmeasList['29'] = array('name' => '分產品測試階段計劃天數', 'unit' => '天', 'definition' => '產品下面所有測試階段計劃天數的和');
$lang->install->basicmeasList['30'] = array('name' => '分產品需求階段實際天數', 'unit' => '天', 'definition' => '產品下面所有需求階段實際天數的和');
$lang->install->basicmeasList['31'] = array('name' => '分產品設計階段實際天數', 'unit' => '天', 'definition' => '產品下面所有設計階段實際天數的和');
$lang->install->basicmeasList['32'] = array('name' => '分產品開發階段實際天數', 'unit' => '天', 'definition' => '產品下面所有研發階段實際天數的和');
$lang->install->basicmeasList['33'] = array('name' => '分產品測試階段實際天數', 'unit' => '天', 'definition' => '產品下面所有測試階段實際天數的和');
$lang->install->basicmeasList['34'] = array('name' => '項目任務實時預計工時數', 'unit' => '小時', 'definition' => '項目下面所有任務的最初預計工時和');
$lang->install->basicmeasList['35'] = array('name' => '項目需求工作實時總預計工時數', 'unit' => '小時', 'definition' => '項目所有需求相關任務的最初預計工時和');
$lang->install->basicmeasList['36'] = array('name' => '項目設計工作實時總預計工時數', 'unit' => '小時', 'definition' => '項目所有設計相關任務的最初預計工時和');
$lang->install->basicmeasList['37'] = array('name' => '項目開發工作實時總預計工時數', 'unit' => '小時', 'definition' => '項目所有開發相關任務的最初預計工時和');
$lang->install->basicmeasList['38'] = array('name' => '項目測試工作實時總預計工時數', 'unit' => '小時', 'definition' => '項目所有測試相關任務的最初預計工時和');
$lang->install->basicmeasList['39'] = array('name' => '項目任務實際消耗工時數', 'unit' => '小時', 'definition' => '項目下面所有任務的實際消耗工時和');
$lang->install->basicmeasList['40'] = array('name' => '項目需求工作實際消耗工時數', 'unit' => '小時', 'definition' => '項目所有需求相關任務的實際消耗工時和');
$lang->install->basicmeasList['41'] = array('name' => '項目設計工作實際消耗工時數', 'unit' => '小時', 'definition' => '項目所有設計相關任務的實際消耗工時和');
$lang->install->basicmeasList['42'] = array('name' => '項目開發工作實際消耗工時數', 'unit' => '小時', 'definition' => '項目所有開發相關任務的實際消耗工時和');
$lang->install->basicmeasList['43'] = array('name' => '項目測試工作實際消耗工時數', 'unit' => '小時', 'definition' => '項目所有測試相關任務的實際消耗工時和');
$lang->install->basicmeasList['44'] = array('name' => '項目開發工作最初總預計工時數', 'unit' => '小時', 'definition' => '項目計劃第一個基線版本中所有開發相關工作最初預計工時和');
$lang->install->basicmeasList['45'] = array('name' => '項目設計工作最初總預計工時數', 'unit' => '小時', 'definition' => '項目計劃第一個基線版本中所有設計相關工作最初預計工時和');
$lang->install->basicmeasList['46'] = array('name' => '項目測試工作最初總預計工時數', 'unit' => '小時', 'definition' => '項目計劃第一個基線版本中所有測試相關工作最初預計工時和');
$lang->install->basicmeasList['47'] = array('name' => '項目需求工作最初總預計工時數', 'unit' => '小時', 'definition' => '項目計劃第一個基線版本中所有需求相關工作最初預計工時和');
$lang->install->basicmeasList['48'] = array('name' => '項目任務最初總預計工時數', 'unit' => '小時', 'definition' => '項目計劃第一個基線版本中所有任務最初預計工時和');
$lang->install->basicmeasList['49'] = array('name' => '項目開發工作最終總預計工時數', 'unit' => '小時', 'definition' => '項目計劃最後一個基線版本中所有開發相關任務最初預計工時和');
$lang->install->basicmeasList['50'] = array('name' => '項目需求工作最終總預計工時數', 'unit' => '小時', 'definition' => '項目計劃最後一個基線版本中所有需求相關任務最初預計工時和');
$lang->install->basicmeasList['51'] = array('name' => '項目測試工作最終總預計工時數', 'unit' => '小時', 'definition' => '項目計劃最後一個基線版本中所有測試相關任務最初預計工時和');
$lang->install->basicmeasList['52'] = array('name' => '項目設計工作最終總預計工時數', 'unit' => '小時', 'definition' => '項目計劃最後一個基線版本中所有設計相關任務最初預計工時和');
$lang->install->basicmeasList['53'] = array('name' => '項目任務最終總預計工時數', 'unit' => '小時', 'definition' => '項目計劃最後一個基線版本中所有任務最初預計工時和');

$lang->install->selectedMode     = '選擇模式';
$lang->install->selectedModeTips = '後續您還可以去後台-自定義-模式中進行調整';

$lang->install->groupList['ADMIN']['name']        = '管理員';
$lang->install->groupList['ADMIN']['desc']        = '系統管理員';
$lang->install->groupList['DEV']['name']          = '研發';
$lang->install->groupList['DEV']['desc']          = '研發人員';
$lang->install->groupList['QA']['name']           = '測試';
$lang->install->groupList['QA']['desc']           = '測試人員';
$lang->install->groupList['PM']['name']           = '項目經理';
$lang->install->groupList['PM']['desc']           = '項目經理';
$lang->install->groupList['PO']['name']           = '產品經理';
$lang->install->groupList['PO']['desc']           = '產品經理';
$lang->install->groupList['TD']['name']           = '研發主管';
$lang->install->groupList['TD']['desc']           = '研發主管';
$lang->install->groupList['PD']['name']           = '產品主管';
$lang->install->groupList['PD']['desc']           = '產品主管';
$lang->install->groupList['QD']['name']           = '測試主管';
$lang->install->groupList['QD']['desc']           = '測試主管';
$lang->install->groupList['TOP']['name']          = '高層管理';
$lang->install->groupList['TOP']['desc']          = '高層管理';
$lang->install->groupList['OTHERS']['name']       = '其他';
$lang->install->groupList['OTHERS']['desc']       = '其他';
$lang->install->groupList['LIMITED']['name']      = '受限用戶';
$lang->install->groupList['LIMITED']['desc']      = '受限用戶分組(只能編輯與自己相關的內容)';
$lang->install->groupList['PROJECTADMIN']['name'] = '項目管理員';
$lang->install->groupList['PROJECTADMIN']['desc'] = '項目管理員可以維護項目的權限';
$lang->install->groupList['LITEADMIN']['name']    = '管理員';
$lang->install->groupList['LITEADMIN']['desc']    = '運營管理界面用戶分組';
$lang->install->groupList['LITEPROJECT']['name']  = '項目管理';
$lang->install->groupList['LITEPROJECT']['desc']  = '運營管理界面用戶分組';
$lang->install->groupList['LITETEAM']['name']     = '團隊成員';
$lang->install->groupList['LITETEAM']['desc']     = '運營管理界面用戶分組';

$lang->install->groupList['IPDPRODUCTPLAN']['name'] = '產品規劃人員';
$lang->install->groupList['IPDDEMAND']['name']      = '需求分析人員';
$lang->install->groupList['IPDPMT']['name']         = 'PMT團隊人員';
$lang->install->groupList['IPDADMIN']['name']       = '管理人員';

$lang->install->cronList[''] = '監控定時任務';
$lang->install->cronList['moduleName=execution&methodName=computeBurn'] = '更新燃盡圖';
$lang->install->cronList['moduleName=report&methodName=remind']         = '每日任務提醒';
$lang->install->cronList['moduleName=svn&methodName=run']               = '同步SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = '同步GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = '備份數據和附件';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = '非同步發信';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = '非同步發送Webhook';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = '刪除過期日誌';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = '生成周期性待辦';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = '創建周期性任務';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = '同步Jenkins任務狀態';
$lang->install->cronList['moduleName=ci&methodName=exec']               = '執行Jenkins任務';
$lang->install->cronList['moduleName=mr&methodName=syncMR']             = '定時同步GitLabMR信息';

$lang->install->dbProgress = "正在安裝資料庫表";
$lang->install->dbFinish   = "資料庫表安裝完畢";
$lang->install->success    = "安裝成功";
$lang->install->login      = '登錄禪道管理系統';
$lang->install->register   = '禪道社區註冊';

$lang->install->successLabel       = "<p>您已經成功安裝禪道管理系統%s。</p>";
$lang->install->successNoticeLabel = "<p>您已經成功安裝禪道管理系統%s，<strong class='text-danger'>請及時刪除install.php</strong>。</p>";
$lang->install->congratulations    = "恭喜您，禪道安裝成功！";
$lang->install->joinZentao         = <<<EOT
<p>友情提示：為了您及時獲得禪道的最新動態，請在禪道社區(<a href='https://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>)進行登記。</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo', 'xuanxuan', 'ydisk', 'meshiot');

$lang->install->promotion = "為您推薦禪道軟件旗下其他產品：";

$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = '蟬知門戶';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'https://www.zsite.com';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>專業的企業營銷門戶系統</li>
  <li>功能豐富，操作簡潔方便</li>
  <li>大量細節針對SEO優化</li>
  <li>開源免費，不限商用！</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO協同';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'https://www.zdoo.com';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>客戶管理，訂單跟蹤</li>
  <li>項目任務，公告文檔</li>
  <li>收入支出，出帳入賬</li>
  <li>論壇博客，動態消息</li>
</ul>
EOD;

$lang->install->xuanxuan = new stdclass();
$lang->install->xuanxuan->name = '喧喧聊天';
$lang->install->xuanxuan->logo = 'images/main/xuanxuan.ico';
$lang->install->xuanxuan->url  = 'https://www.xuanim.com';
$lang->install->xuanxuan->desc = <<<EOD
<ul>
  <li>輕：輕量級架構，容易部署</li>
  <li>跨：真正完整跨平台解決方案</li>
  <li>美：基于Html5開發，界面美觀</li>
  <li>開：開放架構，方便二開整合</li>
</ul>
EOD;

$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name = '悅庫網盤';
$lang->install->ydisk->logo = 'images/main/ydisk.ico';
$lang->install->ydisk->url  = 'http://www.ydisk.cn';
$lang->install->ydisk->desc = <<<EOD
<ul>
  <li>絶對私有：只部署在自己的機器上</li>
  <li>海量存儲：只取決於您的硬碟大小</li>
  <li>極限傳輸：只取決於您的網絡頻寬</li>
  <li>極度安全：十二種權限組合</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name = '易天物聯';
$lang->install->meshiot->logo = 'images/main/meshiot.ico';
$lang->install->meshiot->url  = 'https://www.meshiot.com';
$lang->install->meshiot->desc = <<<EOD
<ul>
  <li>超性能網關，一個可管6萬個設備</li>
  <li>自研通訊協議，2.5公里穿牆無障礙</li>
  <li>上百款感測器控製器，獨創調光系統</li>
  <li>可配電池，對既有場地無任何要求</li>
</ul>
EOD;

$lang->install->solution = new stdclass();
$lang->install->solution->skip        = '跳過';
$lang->install->solution->skipInstall = '暫不安裝';
$lang->install->solution->log         = '安裝日誌';
$lang->install->solution->title       = 'DevOps平台應用設置';
$lang->install->solution->progress    = 'DevOps平台應用安裝';
$lang->install->solution->desc        = '歡迎使用DevOps平台，我們將在您安裝平台時同步安裝以下應用，幫助您快速上手!';
$lang->install->solution->overMemory  = '內存不足，無法同時安裝，建議平台啟動後手動安裝應用。';
