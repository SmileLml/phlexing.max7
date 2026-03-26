<?php
$lang->im->common           = '聊天';
$lang->im->turnon           = '是否打開';
$lang->im->help             = '使用說明';
$lang->im->settings         = '伺服器設置';
$lang->im->xxdServer        = '禪道伺服器';
$lang->im->downloadXXD      = '下載聊天服務端';
$lang->im->zentaoIntegrate  = '禪道整合';
$lang->im->zentaoClient     = '新增禪道客戶端！';
$lang->im->getChatUsers     = '獲取用戶';
$lang->im->getChatGroups    = '獲取討論組';
$lang->im->notifyMSG        = '消息通知';
$lang->im->sendNotification = '向通知中心推送通知消息';
$lang->im->sendChatMessage  = '向指定的討論組推送通知消息';

$lang->im->createBug   = '創建 Bug';
$lang->im->createDoc   = '創建文檔';
$lang->im->createStory = '創建需求';
$lang->im->createTask  = '創建任務';
$lang->im->createTodo  = '創建待辦';

$lang->im->xxdIsHttps = '啟用HTTPS';

$lang->im->turnonList = array();
$lang->im->turnonList[1] = '是';
$lang->im->turnonList[0] = '否';

$lang->im->xxClientConfirm = '與禪道深度整合，支持成員溝通，小組討論，檔案傳輸，任務指派，更加方便的項目管理，更加流暢的團隊協作！點擊界面右上角用戶下拉菜單中下載禪道客戶端。';
$lang->im->xxServerConfirm = '與禪道深度整合，支持成員溝通，小組討論，檔案傳輸，任務指派，更加方便的項目管理，更加流暢的團隊協作！進入後台-客戶端進行下載配置。';

$lang->im->xxdServerTip    = '禪道伺服器地址為完整的協議+地址+連接埠，示例：http://192.168.1.35 或 http://pms.zentao.com ，不能使用127.0.0.1。';
$lang->im->xxdServerEmpty  = '禪道伺服器地址為空。';
$lang->im->xxdServerError  = '禪道伺服器地址不能為 127.0.0.1。';

if(!isset($lang->im->xxd)) $lang->im->xxd = new stdclass();
$lang->im->xxd->aes  = '服務端通信 AES';
$lang->im->xxdAESTip = '該設置僅針對 XXB 和 XXD 之間的通訊加密，不影響客戶端通訊加密。';
$lang->im->aesOptions['on']  = '開啟';
$lang->im->aesOptions['off'] = '關閉';

if(!isset($lang->im->bot)) $lang->im->bot = new stdclass();
$lang->im->bot->commonName = '阿道';
$lang->im->bot->welcome->title = '哈嘍~我是你的助手阿道';
$lang->im->bot->upgradeWelcome->title = '哈嘍~我是你的助手阿道';

$lang->im->bot->zentaoBot = new stdclass();
$lang->im->bot->zentaoBot->name = '禪道';
$lang->im->bot->zentaoBot->pageSearchRegex = '/(pageID|recPerPage|頁碼|每頁數量|頁碼|每頁數量)=(\d+)/';

$lang->im->bot->zentaoBot->commands = new stdclass();
$lang->im->bot->zentaoBot->commands->view = new stdclass();
$lang->im->bot->zentaoBot->commands->view->description = '查看任務';
$lang->im->bot->zentaoBot->commands->view->alias       = array('查看', '搜索', '查詢', '篩選');
$lang->im->bot->zentaoBot->commands->start = new stdclass();
$lang->im->bot->zentaoBot->commands->start->description = '開始任務';
$lang->im->bot->zentaoBot->commands->start->alias       = array('開始', '開始任務');
$lang->im->bot->zentaoBot->commands->close = new stdclass();
$lang->im->bot->zentaoBot->commands->close->description = '關閉任務';
$lang->im->bot->zentaoBot->commands->close->alias       = array('關閉', '關閉任務');
$lang->im->bot->zentaoBot->commands->finish = new stdclass();
$lang->im->bot->zentaoBot->commands->finish->description = '完成任務';
$lang->im->bot->zentaoBot->commands->finish->alias       = array('完成', '完成任務');

$lang->im->bot->zentaoBot->condKeywords = array();
$lang->im->bot->zentaoBot->condKeywords['task']            = array('任務', 'task');
$lang->im->bot->zentaoBot->condKeywords['pri']             = array('優先順序', 'pri');
$lang->im->bot->zentaoBot->condKeywords['status']          = array('狀態', 'status');
$lang->im->bot->zentaoBot->condKeywords['assignTo']        = array('指派人', '指派給', 'assignto', 'user');
$lang->im->bot->zentaoBot->condKeywords['id']              = array('編號', 'id');
$lang->im->bot->zentaoBot->condKeywords['taskName']        = array('任務名', '任務名稱', 'taskname');
$lang->im->bot->zentaoBot->condKeywords['comment']         = array('備註', 'comment');
$lang->im->bot->zentaoBot->condKeywords['left']            = array('預計剩餘', 'left');
$lang->im->bot->zentaoBot->condKeywords['consumed']        = array('總計消耗', 'consumed');
$lang->im->bot->zentaoBot->condKeywords['realStarted']     = array('實際開始', 'realStarted');
$lang->im->bot->zentaoBot->condKeywords['pageID']          = array('pageID', '頁碼', '頁碼');
$lang->im->bot->zentaoBot->condKeywords['recPerPage']      = array('recPerPage', '每頁數量', '每頁數量');
$lang->im->bot->zentaoBot->condKeywords['finishedDate']    = array('實際完成', 'finishedDate');
$lang->im->bot->zentaoBot->condKeywords['currentConsumed'] = array('本次消耗', 'currentConsumed');

$lang->im->bot->zentaoBot->success        = '指令執行完成';
$lang->im->bot->zentaoBot->tasksFound     = '為您匹配到 %d 項任務';
$lang->im->bot->zentaoBot->prevPage       = '上一頁';
$lang->im->bot->zentaoBot->nextPage       = '下一頁';
$lang->im->bot->zentaoBot->effortRecorded = '任務 #%d 已完成工時信息填寫';

$lang->im->bot->zentaoBot->finishCommand = '完成';
$lang->im->bot->zentaoBot->closeCommand  = '關閉';
$lang->im->bot->zentaoBot->startCommand  = '開始';
$lang->im->bot->zentaoBot->viewCommand   = '查看';

$lang->im->bot->zentaoBot->errors = new stdclass();
$lang->im->bot->zentaoBot->errors->emptyResult     = '未查詢到相關匹配信息';
$lang->im->bot->zentaoBot->errors->invalidCommand  = '無法識別該指令';
$lang->im->bot->zentaoBot->errors->invalidStatus   = '檢測到該任務為%s狀態，無法實現指令操作';
$lang->im->bot->zentaoBot->errors->unauthorized    = '您無權操作此任務';
$lang->im->bot->zentaoBot->errors->taskIDRequired  = '請輸入任務編號';
$lang->im->bot->zentaoBot->errors->taskNotFound    = '任務不存在';

$lang->im->bot->zentaoBot->finish = new stdclass();
$lang->im->bot->zentaoBot->finish->tip             = '完成任務指令需要填入工時與記錄起始時間，請點擊下方入口';
$lang->im->bot->zentaoBot->finish->tipLinkTitle    = '工時記錄';
$lang->im->bot->zentaoBot->finish->done            = '任務 #%d 已完成，完成時間：%s，消耗：%.1f 小時';
$lang->im->bot->zentaoBot->finish->bugTip          = '檢測到任務 #%d 關聯相關 Bug，您可以點擊以下連結進行處理';
$lang->im->bot->zentaoBot->finish->bugTipLinkTitle = '關聯 Bug 處理';

$lang->im->bot->zentaoBot->start = new stdclass();
$lang->im->bot->zentaoBot->start->tip                = '點擊連結開始任務 #%d';
$lang->im->bot->zentaoBot->start->tipLinkTitle       = '開始任務';
$lang->im->bot->zentaoBot->start->finishWithZeroLeft = '剩餘工時為 0，任務將被標記為"已完成"';

$lang->im->bot->zentaoBot->help = <<<EOT
### 1. 任務查詢指令

指令：`查看 任務 條件a 值a1，值a2 條件b 值b1，值b2···條件n 值n1，值n2`
示例：`查看 任務 阿道 P1 進行中` 顯示指派給阿道、優先順序為P1且狀態為進行中的任務

| 命令 | 描述 |
| ---- | ---- |
| 查看 任務 			| 顯示當前用戶名下所有未關閉的任務 |
| 查看 任務 名稱關鍵字	| 顯示匹配到名稱關鍵字的任務 |
| 查看 任務 指派人	| 顯示指派人為輸入值的任務 |
| 查看 任務 優先順序	| 顯示優先順序為輸入值的任務 |
| 查看 任務 狀態 		| 顯示狀態為輸入值的任務 |
| 查看 任務 ID		| 顯示ID為輸入值的任務 |

### 2. 任務編輯指令

任務編輯指令支持對任務進行狀態變更。

| 命令 | 描述 |
| ---- | ---- |
| 開始 任務 #ID 	| 開始任務並記錄其消耗/剩餘工時 |
| 完成 任務 #ID	| 完成任務並記錄其消耗/剩餘工時 |
| 關閉 任務 #ID	| 關閉任務並記錄其消耗/剩餘工時 |
EOT;

$lang->im->bot->upgradeWelcome->link = 'https://www.zentao.net/downloads.html';
$lang->im->detachedConferenceUpgradeMessage->content = '管理員已將會議機制更新，支持 v7.2.beta 及以上版本，為了不影響您的使用體驗，建議您重啟應用，我們將更新您的會議機制。更多詳情請移步禪道官網查看。';

$lang->im->jitsiConferenceInviteFailMessage->upgradeWithLink = '您收到一條會議邀請，由於您版本過低無法通過大桌面入會，可以點擊下方連結從瀏覽器入會，建議您及時升級到9.0及以上版本，方便您正常使用會議功能。';
