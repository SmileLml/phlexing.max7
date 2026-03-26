<?php
$lang->im->settings = '喧喧設置';
$lang->im->debug    = '調試功能';

$lang->im->version         = '版本';
$lang->im->backendLang     = '伺服器端語言';
$lang->im->key             = '密鑰';
$lang->im->systemGroup     = '系統';
$lang->im->url             = '訪問地址';
$lang->im->pollingInterval = '輪詢間隔';
$lang->im->createKey       = '重新生成密鑰';
$lang->im->connector       = '、';
$lang->im->viewDebug       = '查看調試信息';
$lang->im->log             = '日誌';
$lang->im->xxdStatus       = 'XXD狀態';
$lang->im->debugInfo       = '調試信息';
$lang->im->serverInfo      = '伺服器信息';
$lang->im->errorInfo       = '錯誤提示';
$lang->im->xxbConfigError  = 'XXB參數設置不正確。';
$lang->im->disabled = '不啟用';
$lang->im->enabled  = '啟用';

$lang->im->debugStatus[0] = $lang->im->disabled;
$lang->im->debugStatus[1] = $lang->im->enabled;

$lang->im->xxdServer       = '喧喧伺服器';
$lang->im->createKey       = '重新生成密鑰';
$lang->im->downloadXXD     = '下載XXD服務端';
$lang->im->listenIP        = '監聽IP';
$lang->im->chatPort        = '客戶端通訊連接埠';
$lang->im->uploadFileSize  = '上傳檔案大小';
$lang->im->downloadPackage = '下載完整包';
$lang->im->downloadConfig  = '只下載配置檔案';
$lang->im->changeSetting   = '修改配置';
$lang->im->downloadConf    = '下載配置';
$lang->im->tokenLifetime   = 'Token 有效期';
$lang->im->tokenAuthWindow = 'Token 驗證窗口時間';
$lang->im->iceServers      = 'ICE 伺服器';

$lang->im->logLevel        = '日誌級別';
$lang->im->logLevelSimple  = '簡單';
$lang->im->logLevelDetail  = '詳細';
$lang->im->logLevelOptions = array($lang->im->logLevelSimple, $lang->im->logLevelDetail);

$lang->im->mobileClient         = '手機客戶端';
$lang->im->mobileOptions['on']  = $lang->im->enabled;
$lang->im->mobileOptions['off'] = $lang->im->disabled;

$lang->im->day    = '天';
$lang->im->hours  = '小時';
$lang->im->minute = '分鐘';
$lang->im->secs   = '秒';

$lang->im->notAdmin         = '不是系統管理員。';
$lang->im->notGroupCreator  = '不是群組創建人';
$lang->im->notSystemChat    = '不是系統會話。';
$lang->im->notGroupChat     = '不是多人會話。';
$lang->im->notPublic        = '不是公開會話。';
$lang->im->cantChat         = '沒有發言權限。';
$lang->im->chatHasDismissed = '討論組已被解散';
$lang->im->needLogin        = '用戶沒有登錄。';
$lang->im->notExist         = '會話不存在。';
$lang->im->changeRenameTo   = '將會話名稱更改為';
$lang->im->multiChats       = '消息不屬於同一個會話。';
$lang->im->notInGroup       = '用戶不在此討論組內。';
$lang->im->notInChat        = '無法向與您無關的會話發送消息。';
$lang->im->notSameUser      = '無法作為他人發送消息。';
$lang->im->errorKey         = '<strong>密鑰</strong> 應該為數字或字母的組合，長度為32位。';
$lang->im->defaultKey       = '請勿使用預設<strong>密鑰</strong>。';
$lang->im->debugTips        = '<br>使用管理員賬號%s並訪問此頁面。';
$lang->im->noLogFile        = '沒有日誌檔案。';
$lang->im->noFopen          = '未啟用fopen函數，請按以下路逕自行查看日誌檔案：%s。';
$lang->im->owtIsDisabled    = '未啟用會議功能，無法進行會議。';
$lang->im->chatNameTooLong  = '會話名稱過長。';
$lang->im->adminCanInvite   = '本群中只有管理員能夠邀請成員';
$lang->im->userNotInGroup   = '您已不在此討論組內，無法進行相關操作。';
$lang->im->canNotDelOwner   = '不能移除群主。';

$lang->im->xxdServerTip             = '喧喧伺服器地址為完整的協議+地址+連接埠，示例：http://192.168.1.35 或 http://www.xxb.com ，不能使用127.0.0.1。';
$lang->im->iceServersTip            = '點對點傳輸時使用的 ICE 伺服器，如： stun:stun.l.google.com:19302，多個伺服器之間可用換行分隔，可選';
$lang->im->xxdServerEmpty           = '喧喧伺服器地址為空。';
$lang->im->xxdServerError           = '喧喧伺服器地址不能為 127.0.0.1。';
$lang->im->xxdSchemeError           = '伺服器地址應該以<strong>http://</strong>或<strong>https://</strong>開頭。';
$lang->im->xxdPortError             = '伺服器地址應該包含有效的連接埠號，預設為<strong>11443</strong>。';
$lang->im->xxdPollIntTip            = '輪詢間隔單位為秒，最小為 5 秒，預設為 60 秒，示例：60。';
$lang->im->xxdPollIntErr            = '輪詢間隔應為一個最小為 5 的整數。';
$lang->im->xxdFileSizeErr           = '檔案大小應大於等於 0。';
$lang->im->tokenLifetimeErr         = 'Token 有效期應為一個最小為 1 的整數。';
$lang->im->tokenAuthWindowErr       = 'Token 驗證窗口時間應為一個最小為 20 的整數。';
$lang->im->iceServersErr            = 'ICE 伺服器地址不合法';
$lang->im->errorSSLCrt              = 'SSL證書內容不能為空';
$lang->im->errorSSLKey              = 'SSL證書私鑰不能為空';
$lang->im->xxdAESTip                = '該設置僅針對 XXB 和 XXD 之間的通訊加密，不影響客戶端通訊加密。';
$lang->im->xxdFileEncryptTip        = '啟用後將加密存儲聊天產生的附件檔案，一旦啟用，無法關閉。';
$lang->im->xxdMessageEncryptTip     = '啟用後將加密存儲聊天消息，一旦啟用，無法關閉。';

$lang->im->errorClientVersionNotSupport = '客戶端版本 %s 過低，請升級到 5.0 及以上版本。https://xuanim.com';
$lang->im->operationNotSupportedOnArchivedChat = '不允許在已歸檔的討論組上執行此操作，請升級到 6.6 及以上版本。';

$lang->im->broadcast = new stdclass();
$lang->im->broadcast->createChat                 = '%s 創建了討論組 [%s](#/chats/groups/%s)。';
$lang->im->broadcast->changeChatOwnership        = '討論組 [%s](#/chats/groups/%s) 所有者更改為 %s。';
$lang->im->broadcast->changeChatOwnershipByAdmin = '系統管理員將討論組 [%s](#/chats/groups/%s) 所有者更改為 %s。';
$lang->im->broadcast->joinChat                   = '%s 加入了討論組。';
$lang->im->broadcast->leaveChat                  = '%s 退出了當前討論組。';
$lang->im->broadcast->renameChat                 = '%s 將討論組名稱更改為 [%s](#/chats/groups/%s)。';
$lang->im->broadcast->renamePrivate              = '會話名稱更改為 [%s](#/chats/recents/%s)。';
$lang->im->broadcast->inviteUser                 = '%s 邀請 %s 加入了討論組。';
$lang->im->broadcast->dismissChat                = '%s 解散了當前討論組。';
$lang->im->broadcast->archiveChat                = '%s 歸檔了討論組 %s，無法再發送消息，但仍然可以瀏覽消息記錄。';
$lang->im->broadcast->unarchiveChat              = '%s 取消歸檔討論組 %s。';
$lang->im->broadcast->mergeChat                  = '%s 討論組被合併到了當前討論組，歷史消息在消息記錄中顯示。';
$lang->im->broadcast->mergeChatWithMembers       = '%s 討論組被合併到了當前討論組，歷史消息在消息記錄中顯示。%s 加入了討論組。';
$lang->im->broadcast->chatMerged                 = '%s 討論組被合併到了 %s 討論組。';

$lang->im->broadcast->createConference           = '%s 發起了會議。';
$lang->im->broadcast->closeConference            = '%s 結束了會議。';
$lang->im->broadcast->createConferenceInvitation = '%s 邀請 %s 加入會議。若您未在會議中，可從右上角的會議入口加入。';
$lang->im->broadcast->conferenceInviteeOccupied  = '%s 線路正忙。';

$lang->im->conference = new stdclass();
$lang->im->conference->userBusy         = '對方線路正忙。';
$lang->im->conference->userOffline      = '對方不在綫。';
$lang->im->conference->defaultTopic     = '%s 發起的音視頻會議';
$lang->im->conference->botName          = '會議通知';

$lang->im->xxd = new stdclass();
$lang->im->xxd->os                  = '操作系統';
$lang->im->xxd->ip                  = '監聽IP';
$lang->im->xxd->chatPort            = '客戶端通訊連接埠';
$lang->im->xxd->commonPort          = '通用連接埠';
$lang->im->xxd->https               = 'HTTPS';
$lang->im->xxd->aes                 = '服務端通信 AES';
$lang->im->xxd->uploadFileSize      = '上傳檔案大小';
$lang->im->xxd->maxOnlineUser       = '最大在綫人數';
$lang->im->xxd->sslcrt              = '證書內容';
$lang->im->xxd->sslkey              = '證書私鑰';
$lang->im->xxd->max                 = '最大';
$lang->im->xxd->fileEncryption      = '檔案加密';
$lang->im->xxd->messageEncryption   = '消息加密';

$lang->im->httpsOptions['on']  = $lang->im->enabled;
$lang->im->httpsOptions['off'] = $lang->im->disabled;

$lang->im->aesOptions['on']  = $lang->im->enabled;
$lang->im->aesOptions['off'] = $lang->im->disabled;

$lang->im->fileEncryptOptions['on']  = $lang->im->enabled;
$lang->im->fileEncryptOptions['off'] = $lang->im->disabled;

$lang->im->messageEncryptOptions['on']  = $lang->im->enabled;
$lang->im->messageEncryptOptions['off'] = $lang->im->disabled;


$lang->im->osList['win_i386']      = 'Windows 32位';
$lang->im->osList['win_x86_64']    = 'Windows 64位';
$lang->im->osList['linux_i386']    = 'Linux 32位';
$lang->im->osList['linux_x86_64']  = 'Linux 64位';
$lang->im->osList['darwin_x86_64'] = 'macOS';

$lang->im->placeholder = new stdclass();
$lang->im->placeholder->xxd = new stdclass();
$lang->im->placeholder->xxd->ip             = '監聽的伺服器ip地址，沒有特殊需要直接填寫0.0.0.0';
$lang->im->placeholder->xxd->chatPort       = '與聊天客戶端通訊的連接埠';
$lang->im->placeholder->xxd->commonPort     = '通用連接埠，該連接埠用於客戶端登錄時驗證，以及檔案上傳下載使用';
$lang->im->placeholder->xxd->https          = '啟用https';
$lang->im->placeholder->xxd->uploadFileSize = '上傳檔案的大小';
$lang->im->placeholder->xxd->maxOnlineUser  = '最大在綫人數';
$lang->im->placeholder->xxd->sslcrt         = '請將證書內容複製到此處';
$lang->im->placeholder->xxd->sslkey         = '請將證書密鑰複製到此處';

$lang->im->notify = new stdclass();
$lang->im->notify->setReceiver = '沒有設置接收者類型，只能是用戶或者是某個討論組。';
$lang->im->notify->setGroup    = '應當設置接收討論組。';
$lang->im->notify->setUserList = '應當設置接收者用戶列表。';
$lang->im->notify->setSender   = '應當設置發送方信息。';
$lang->im->notify->setTitle    = '請提供通知信息的標題。';

$lang->im->xxdConfigNote = array();
$lang->im->xxdConfigNote['zh']['ip'] = '# 監聽的IP地址，不要使用127.0.0.1。';
$lang->im->xxdConfigNote['en']['ip'] = '# The ip listened. Do not use 127.0.0.1.';

$lang->im->xxdConfigNote['zh']['commonPort'] = '# 登錄和附件上傳介面(http)，確保防火牆開放此連接埠。';
$lang->im->xxdConfigNote['en']['commonPort'] = '# Port for user login and file uploaded(http)';

$lang->im->xxdConfigNote['zh']['chatPort'] = '# 聊天消息通訊連接埠(websocket)，確保防火牆開放此連接埠。';
$lang->im->xxdConfigNote['en']['chatPort'] = '# Port for chat(websocket).';

$lang->im->xxdConfigNote['zh']['https'] = '# HTTPS(on|off)。使用HTTPS可以保證消息全程加密。';
$lang->im->xxdConfigNote['en']['https'] = '# on|off. Use https to encryt all messages.';

$lang->im->xxdConfigNote['zh']['enableAES'] = '# 與後端伺服器通訊時的 AES 加密開關，1 為開啟 0 為關閉。';
$lang->im->xxdConfigNote['en']['enableAES'] = '# 0|1. This toggles server-side AES encryption with XXB.';

$lang->im->xxdConfigNote['zh']['enableClientAES'] = '# 是否啟用與客戶端通信時的 AES 加密。';
$lang->im->xxdConfigNote['en']['enableClientAES'] = '# Enable AES encryption between xxd and clients.';

$lang->im->xxdConfigNote['zh']['enableCompression'] = '# 是否啟用 websocket 和 http 通信壓縮。';
$lang->im->xxdConfigNote['en']['enableCompression'] = '# Enable compression for websocket and HTTP traffic.';

$lang->im->xxdConfigNote['zh']['uploadPath'] = '# 附件保存的目錄。預設存放在xxd/files/。';
$lang->im->xxdConfigNote['en']['uploadPath'] = '# Default upload path is xxd/files.';

$lang->im->xxdConfigNote['zh']['uploadFileSize'] = '# 上傳檔案的大小，以M為單位。';
$lang->im->xxdConfigNote['en']['uploadFileSize'] = '# The Max size for uploaded files(M).';

$lang->im->xxdConfigNote['zh']['pollingInterval'] = '# 輪詢時間，單位為秒，最小值為 5。';
$lang->im->xxdConfigNote['en']['pollingInterval'] = '# Interval of polling, should be a number equal to or greater than 5.';

$lang->im->xxdConfigNote['zh']['maxOnlineUser'] = '# 在綫用戶上限，0為無限制。';
$lang->im->xxdConfigNote['en']['maxOnlineUser'] = '# Max online users, 0 means no limit.';

$lang->im->xxdConfigNote['zh']['logPath'] = '# 程序運行日誌的保存路徑。';
$lang->im->xxdConfigNote['en']['logPath'] = '# Path of saved log files.';

$lang->im->xxdConfigNote['zh']['certPath'] = '# 證書的保存路徑。';
$lang->im->xxdConfigNote['en']['certPath'] = '# Path of saved certificate.';

$lang->im->xxdConfigNote['zh']['debug'] = '# Debug級別，可設置0|1|2';
$lang->im->xxdConfigNote['en']['debug'] = '# Debug level，0|1|2';

$lang->im->xxdConfigNote['zh']['thumbnail'] = '# 是否啟用圖片縮略圖';
$lang->im->xxdConfigNote['en']['thumbnail'] = '# Image thumbnail';

$lang->im->xxdConfigNote['zh']['syncConfig'] = '# 與後端伺服器同步配置信息，1 為開啟 0 為關閉。開啟後可能會丟失配置檔案的注視。';
$lang->im->xxdConfigNote['en']['syncConfig'] = '# Sync config with xxb，0|1. Sync config enable may lost config comment.';

$lang->im->xxdConfigNote['zh']['backend'] = "# xxd可以對接多個後台程序。每一個後台程序由入口檔案 + 私鑰組成。\n# 客戶端登錄時如果沒有指定後台程序，會預設登錄到第一個後台程序。";
$lang->im->xxdConfigNote['en']['backend'] = "# xxd can integrate with multi backends. Every backend has an entry and a key. \n# The client will login to the first backend if the user doesn't specify the backend.";

$lang->pinnedMessages = new stdclass();
$lang->pinnedMessages->limit = '置頂消息數量已達到上限';

$lang->im->IPInvalid     = '登錄 IP 不在規定網段內';
$lang->im->mobileLimited = '管理員限制了移動端的訪問';

$lang->im->bot = new stdclass();
$lang->im->bot->commonName  = '小喧喧';
$lang->im->bot->command     = '命令';
$lang->im->bot->description = '描述';
$lang->im->bot->name        = '機器人名稱';
$lang->im->bot->code        = '機器人代號';

$lang->im->bot->error = new stdclass();
$lang->im->bot->error->noSuchCommand = '該命令不存在，您可以發送 [help](xxc://sendContentToServerBySendbox/help) 查看命令列表。';
$lang->im->bot->error->noSuchBot     = '該機器人不存在。';
$lang->im->bot->error->badArguments  = '命令參數有誤。';
$lang->im->bot->error->unauthorized  = '您無權使用此命令。';

$lang->im->bot->show = new stdClass();
$lang->im->bot->show->commands = array('show', '查看應用');

$lang->im->bot->help = new stdclass();
$lang->im->bot->help->header             = array($lang->im->bot->command, $lang->im->bot->description, $lang->im->bot->code);
$lang->im->bot->help->commandLink        = '[%s](xxc://sendContentToChat/%s)';
$lang->im->bot->help->botCommandLink     = '[%s](xxc://sendContentToChat/%s@%s)';
$lang->im->bot->help->sendCommandLink    = '[%s](xxc://sendContentToServerBySendbox/%s)';
$lang->im->bot->help->sendBotCommandLink = '[%s](xxc://sendContentToServerBySendbox/%s@%s)';
$lang->im->bot->help->commands           = array('help', '幫助');
$lang->im->bot->help->welcome            = "歡迎使用{$lang->im->bot->commonName}機器人會話指令，預設您當前已進入內置應用中，您可以發送以下命令來控制我：";
$lang->im->bot->help->welcomeHeader      = array($lang->im->bot->command, $lang->im->bot->description);

$lang->im->bot->help->global = <<<EOT
## 全局命令

| 命令 | 描述 |
| ---- | ---- |
| 幫助 | 命令幫助界面 |
| 查看應用 | 顯示所有應用 |
| 應用名 | 切換到所輸入的應用中 |
| 退出 | 退出當前應用 |
\n\n
EOT;
$lang->im->bot->help->system = <<<EOT
## 系統命令

| 命令 | 描述 |
| ---- | ---- |
| [授權](xxc://sendContentToServerBySendbox/授權) | 查看授權 |
| [搜索用戶](xxc://sendContentToChat/搜索用戶) | 搜索用戶 |
| [伺服器](xxc://sendContentToServerBySendbox/伺服器) | 查看伺服器信息 |

EOT;

$lang->im->bot->exit = new stdclass();
$lang->im->bot->exit->commands = array('exit', '退出');
$lang->im->bot->exit->tips     = '已退出 %s 應用。';

$lang->im->bot->switch         = new stdclass();
$lang->im->bot->switch->tips   = '當前應用切換為 %s 。';

$lang->im->bot->welcome          = new stdclass();
$lang->im->bot->welcome->title   = '哈嘍~我是你的助手小喧喧';
$lang->im->bot->welcome->content = <<<EOT
我能夠發送實時通知消息，同時你可以發送命令控制我。
發送"幫助"命令能夠幫你快速學習，快來試試吧~
可點擊下方"查看詳情"瞭解更多使用說明。
EOT;
$lang->im->bot->welcome->link    = 'https://www.xuanim.com/book/xuanxuankehuduan/279.html';

$lang->im->bot->upgradeWelcome          = new stdclass();
$lang->im->bot->upgradeWelcome->title   = '哈嘍~我是你的助手小喧喧';
$lang->im->bot->upgradeWelcome->content = <<<EOT
目前檢測到當前客戶端版本過低，請升級到7.0及以上版本體驗會話命令的新功能吧~
可點擊下方"查看詳情"瞭解更多使用說明。
EOT;
$lang->im->bot->upgradeWelcome->link    = 'https://www.xuanim.com/dynamic.html';

$lang->im->userStatus = array('away' => '離開', 'busy' => '忙碌', 'meeting' => '會議中', 'offline' => '離線', 'online' => '在綫');

$lang->im->bot->license = new stdclass();
$lang->im->bot->license->title     = array('標題', '內容');
$lang->im->bot->license->noLicense = '無授權信息';

$lang->im->bot->searchUser = new stdclass();
$lang->im->bot->searchUser->notFound        = '未找到相關用戶';
$lang->im->bot->searchUser->keywordRequired = '請輸入關鍵字';

$lang->im->api = new stdclass();
$lang->im->api->notArray = '格式錯誤，JSON 必須為數組';

$lang->im->detachedConferenceUpgradeMessage = new stdclass();
$lang->im->detachedConferenceUpgradeMessage->title   = '會議更新';
$lang->im->detachedConferenceUpgradeMessage->content = '管理員已將會議機制更新，支持 v7.2.beta 及以上版本，為了不影響您的使用體驗，建議您重啟應用，我們將更新您的會議機制。更多詳情請移步喧喧官網查看。';

$lang->im->conferenceAppointment = new stdclass();
$lang->im->conferenceAppointment->firstNotifyTitle    = '會議創建';
$lang->im->conferenceAppointment->reminderNotifyTitle = '會議提醒';
$lang->im->conferenceAppointment->startNotifyTitle    = '會議開始';

$lang->im->conferenceEdit = new stdclass();
$lang->im->conferenceEdit->title        = '會議變更';
$lang->im->conferenceEdit->memberChange = '原定於**%s**的會議**%s**發生變動，您無需再參與該會議。';

$lang->im->conferenceCancel = new stdClass();
$lang->im->conferenceCancel->title           = '會議取消';
$lang->im->conferenceCancel->hasEndTimeBody  = '原定於**%s**的會議**%s**已經被取消，請知悉。';
$lang->im->conferenceCancel->noEndTimeBody   = '原定於**%s**開始的會議**%s**已經被取消，請知悉。';

$lang->im->conferenceEditFail = '會議編輯失敗。';

$lang->im->jitsiConferenceInviteLink = new stdClass();
$lang->im->jitsiConferenceInviteLink->title = '%s邀請您參與會議【%s】';
$lang->im->jitsiConferenceInviteLink->link = '會議連結';
$lang->im->jitsiConferenceInviteLink->password = '會議密碼';

$lang->im->jitsiConferenceInviteFailMessage = new stdClass();
$lang->im->jitsiConferenceInviteFailMessage->upgrade = '%s邀請您入會，但由於您版本過低無法入會，請及時升級到9.0及以上版本。';
$lang->im->jitsiConferenceInviteFailMessage->upgradeWithLink = '您收到一條會議邀請，由於您版本過低無法通過喧喧入會，可以點擊下方連結從瀏覽器入會，建議您及時升級到9.0及以上版本，方便您正常使用會議功能。';
