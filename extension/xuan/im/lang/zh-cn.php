<?php
$lang->im->settings = '喧喧设置';
$lang->im->debug    = '调试功能';

$lang->im->version         = '版本';
$lang->im->backendLang     = '服务器端语言';
$lang->im->key             = '密钥';
$lang->im->systemGroup     = '系统';
$lang->im->url             = '访问地址';
$lang->im->pollingInterval = '轮询间隔';
$lang->im->createKey       = '重新生成密钥';
$lang->im->connector       = '、';
$lang->im->viewDebug       = '查看调试信息';
$lang->im->log             = '日志';
$lang->im->xxdStatus       = 'XXD状态';
$lang->im->debugInfo       = '调试信息';
$lang->im->serverInfo      = '服务器信息';
$lang->im->errorInfo       = '错误提示';
$lang->im->xxbConfigError  = 'XXB参数设置不正确。';
$lang->im->disabled = '不启用';
$lang->im->enabled  = '启用';

$lang->im->debugStatus[0] = $lang->im->disabled;
$lang->im->debugStatus[1] = $lang->im->enabled;

$lang->im->xxdServer       = '喧喧服务器';
$lang->im->createKey       = '重新生成密钥';
$lang->im->downloadXXD     = '下载XXD服务端';
$lang->im->listenIP        = '监听IP';
$lang->im->chatPort        = '客户端通讯端口';
$lang->im->uploadFileSize  = '上传文件大小';
$lang->im->downloadPackage = '下载完整包';
$lang->im->downloadConfig  = '只下载配置文件';
$lang->im->changeSetting   = '修改配置';
$lang->im->downloadConf    = '下载配置';
$lang->im->tokenLifetime   = 'Token 有效期';
$lang->im->tokenAuthWindow = 'Token 验证窗口时间';
$lang->im->iceServers      = 'ICE 服务器';

$lang->im->logLevel        = '日志级别';
$lang->im->logLevelSimple  = '简单';
$lang->im->logLevelDetail  = '详细';
$lang->im->logLevelOptions = array($lang->im->logLevelSimple, $lang->im->logLevelDetail);

$lang->im->mobileClient         = '手机客户端';
$lang->im->mobileOptions['on']  = $lang->im->enabled;
$lang->im->mobileOptions['off'] = $lang->im->disabled;

$lang->im->day    = '天';
$lang->im->hours  = '小时';
$lang->im->minute = '分钟';
$lang->im->secs   = '秒';

$lang->im->notAdmin         = '不是系统管理员。';
$lang->im->notGroupCreator  = '不是群组创建人';
$lang->im->notSystemChat    = '不是系统会话。';
$lang->im->notGroupChat     = '不是多人会话。';
$lang->im->notPublic        = '不是公开会话。';
$lang->im->cantChat         = '没有发言权限。';
$lang->im->chatHasDismissed = '讨论组已被解散';
$lang->im->needLogin        = '用户没有登录。';
$lang->im->notExist         = '会话不存在。';
$lang->im->changeRenameTo   = '将会话名称更改为';
$lang->im->multiChats       = '消息不属于同一个会话。';
$lang->im->notInGroup       = '用户不在此讨论组内。';
$lang->im->notInChat        = '无法向与您无关的会话发送消息。';
$lang->im->notSameUser      = '无法作为他人发送消息。';
$lang->im->errorKey         = '<strong>密钥</strong> 应该为数字或字母的组合，长度为32位。';
$lang->im->defaultKey       = '请勿使用默认<strong>密钥</strong>。';
$lang->im->debugTips        = '<br>使用管理员账号%s并访问此页面。';
$lang->im->noLogFile        = '没有日志文件。';
$lang->im->noFopen          = '未启用fopen函数，请按以下路径自行查看日志文件：%s。';
$lang->im->owtIsDisabled    = '未启用会议功能，无法进行会议。';
$lang->im->chatNameTooLong  = '会话名称过长。';
$lang->im->adminCanInvite   = '本群中只有管理员能够邀请成员';
$lang->im->userNotInGroup   = '您已不在此讨论组内，无法进行相关操作。';
$lang->im->canNotDelOwner   = '不能移除群主。';

$lang->im->xxdServerTip             = '喧喧服务器地址为完整的协议+地址+端口，示例：http://192.168.1.35 或 http://www.xxb.com ，不能使用127.0.0.1。';
$lang->im->iceServersTip            = '点对点传输时使用的 ICE 服务器，如： stun:stun.l.google.com:19302，多个服务器之间可用换行分隔，可选';
$lang->im->xxdServerEmpty           = '喧喧服务器地址为空。';
$lang->im->xxdServerError           = '喧喧服务器地址不能为 127.0.0.1。';
$lang->im->xxdSchemeError           = '服务器地址应该以<strong>http://</strong>或<strong>https://</strong>开头。';
$lang->im->xxdPortError             = '服务器地址应该包含有效的端口号，默认为<strong>11443</strong>。';
$lang->im->xxdPollIntTip            = '轮询间隔单位为秒，最小为 5 秒，默认为 60 秒，示例：60。';
$lang->im->xxdPollIntErr            = '轮询间隔应为一个最小为 5 的整数。';
$lang->im->xxdFileSizeErr           = '文件大小应大于等于 0。';
$lang->im->tokenLifetimeErr         = 'Token 有效期应为一个最小为 1 的整数。';
$lang->im->tokenAuthWindowErr       = 'Token 验证窗口时间应为一个最小为 20 的整数。';
$lang->im->iceServersErr            = 'ICE 服务器地址不合法';
$lang->im->errorSSLCrt              = 'SSL证书内容不能为空';
$lang->im->errorSSLKey              = 'SSL证书私钥不能为空';
$lang->im->xxdAESTip                = '该设置仅针对 XXB 和 XXD 之间的通讯加密，不影响客户端通讯加密。';
$lang->im->xxdFileEncryptTip        = '启用后将加密存储聊天产生的附件文件，一旦启用，无法关闭。';
$lang->im->xxdMessageEncryptTip     = '启用后将加密存储聊天消息，一旦启用，无法关闭。';

$lang->im->errorClientVersionNotSupport = '客户端版本 %s 过低，请升级到 5.0 及以上版本。https://xuanim.com';
$lang->im->operationNotSupportedOnArchivedChat = '不允许在已归档的讨论组上执行此操作，请升级到 6.6 及以上版本。';

$lang->im->broadcast = new stdclass();
$lang->im->broadcast->createChat                 = '%s 创建了讨论组 [%s](#/chats/groups/%s)。';
$lang->im->broadcast->changeChatOwnership        = '讨论组 [%s](#/chats/groups/%s) 所有者更改为 %s。';
$lang->im->broadcast->changeChatOwnershipByAdmin = '系统管理员将讨论组 [%s](#/chats/groups/%s) 所有者更改为 %s。';
$lang->im->broadcast->joinChat                   = '%s 加入了讨论组。';
$lang->im->broadcast->leaveChat                  = '%s 退出了当前讨论组。';
$lang->im->broadcast->renameChat                 = '%s 将讨论组名称更改为 [%s](#/chats/groups/%s)。';
$lang->im->broadcast->renamePrivate              = '会话名称更改为 [%s](#/chats/recents/%s)。';
$lang->im->broadcast->inviteUser                 = '%s 邀请 %s 加入了讨论组。';
$lang->im->broadcast->dismissChat                = '%s 解散了当前讨论组。';
$lang->im->broadcast->archiveChat                = '%s 归档了讨论组 %s，无法再发送消息，但仍然可以浏览消息记录。';
$lang->im->broadcast->unarchiveChat              = '%s 取消归档讨论组 %s。';
$lang->im->broadcast->mergeChat                  = '%s 讨论组被合并到了当前讨论组，历史消息在消息记录中显示。';
$lang->im->broadcast->mergeChatWithMembers       = '%s 讨论组被合并到了当前讨论组，历史消息在消息记录中显示。%s 加入了讨论组。';
$lang->im->broadcast->chatMerged                 = '%s 讨论组被合并到了 %s 讨论组。';

$lang->im->broadcast->createConference           = '%s 发起了会议。';
$lang->im->broadcast->closeConference            = '%s 结束了会议。';
$lang->im->broadcast->createConferenceInvitation = '%s 邀请 %s 加入会议。若您未在会议中，可从右上角的会议入口加入。';
$lang->im->broadcast->conferenceInviteeOccupied  = '%s 线路正忙。';

$lang->im->conference = new stdclass();
$lang->im->conference->userBusy         = '对方线路正忙。';
$lang->im->conference->userOffline      = '对方不在线。';
$lang->im->conference->defaultTopic     = '%s 发起的音视频会议';
$lang->im->conference->botName          = '会议通知';

$lang->im->xxd = new stdclass();
$lang->im->xxd->os                  = '操作系统';
$lang->im->xxd->ip                  = '监听IP';
$lang->im->xxd->chatPort            = '客户端通讯端口';
$lang->im->xxd->commonPort          = '通用端口';
$lang->im->xxd->https               = 'HTTPS';
$lang->im->xxd->aes                 = '服务端通信 AES';
$lang->im->xxd->uploadFileSize      = '上传文件大小';
$lang->im->xxd->maxOnlineUser       = '最大在线人数';
$lang->im->xxd->sslcrt              = '证书内容';
$lang->im->xxd->sslkey              = '证书私钥';
$lang->im->xxd->max                 = '最大';
$lang->im->xxd->fileEncryption      = '文件加密';
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
$lang->im->placeholder->xxd->ip             = '监听的服务器ip地址，没有特殊需要直接填写0.0.0.0';
$lang->im->placeholder->xxd->chatPort       = '与聊天客户端通讯的端口';
$lang->im->placeholder->xxd->commonPort     = '通用端口，该端口用于客户端登录时验证，以及文件上传下载使用';
$lang->im->placeholder->xxd->https          = '启用https';
$lang->im->placeholder->xxd->uploadFileSize = '上传文件的大小';
$lang->im->placeholder->xxd->maxOnlineUser  = '最大在线人数';
$lang->im->placeholder->xxd->sslcrt         = '请将证书内容复制到此处';
$lang->im->placeholder->xxd->sslkey         = '请将证书密钥复制到此处';

$lang->im->notify = new stdclass();
$lang->im->notify->setReceiver = '没有设置接收者类型，只能是用户或者是某个讨论组。';
$lang->im->notify->setGroup    = '应当设置接收讨论组。';
$lang->im->notify->setUserList = '应当设置接收者用户列表。';
$lang->im->notify->setSender   = '应当设置发送方信息。';
$lang->im->notify->setTitle    = '请提供通知信息的标题。';

$lang->im->xxdConfigNote = array();
$lang->im->xxdConfigNote['zh']['ip'] = '# 监听的IP地址，不要使用127.0.0.1。';
$lang->im->xxdConfigNote['en']['ip'] = '# The ip listened. Do not use 127.0.0.1.';

$lang->im->xxdConfigNote['zh']['commonPort'] = '# 登录和附件上传接口(http)，确保防火墙开放此端口。';
$lang->im->xxdConfigNote['en']['commonPort'] = '# Port for user login and file uploaded(http)';

$lang->im->xxdConfigNote['zh']['chatPort'] = '# 聊天消息通讯端口(websocket)，确保防火墙开放此端口。';
$lang->im->xxdConfigNote['en']['chatPort'] = '# Port for chat(websocket).';

$lang->im->xxdConfigNote['zh']['https'] = '# HTTPS(on|off)。使用HTTPS可以保证消息全程加密。';
$lang->im->xxdConfigNote['en']['https'] = '# on|off. Use https to encryt all messages.';

$lang->im->xxdConfigNote['zh']['enableAES'] = '# 与后端服务器通讯时的 AES 加密开关，1 为开启 0 为关闭。';
$lang->im->xxdConfigNote['en']['enableAES'] = '# 0|1. This toggles server-side AES encryption with XXB.';

$lang->im->xxdConfigNote['zh']['enableClientAES'] = '# 是否启用与客户端通信时的 AES 加密。';
$lang->im->xxdConfigNote['en']['enableClientAES'] = '# Enable AES encryption between xxd and clients.';

$lang->im->xxdConfigNote['zh']['enableCompression'] = '# 是否启用 websocket 和 http 通信压缩。';
$lang->im->xxdConfigNote['en']['enableCompression'] = '# Enable compression for websocket and HTTP traffic.';

$lang->im->xxdConfigNote['zh']['uploadPath'] = '# 附件保存的目录。默认存放在xxd/files/。';
$lang->im->xxdConfigNote['en']['uploadPath'] = '# Default upload path is xxd/files.';

$lang->im->xxdConfigNote['zh']['uploadFileSize'] = '# 上传文件的大小，以M为单位。';
$lang->im->xxdConfigNote['en']['uploadFileSize'] = '# The Max size for uploaded files(M).';

$lang->im->xxdConfigNote['zh']['pollingInterval'] = '# 轮询时间，单位为秒，最小值为 5。';
$lang->im->xxdConfigNote['en']['pollingInterval'] = '# Interval of polling, should be a number equal to or greater than 5.';

$lang->im->xxdConfigNote['zh']['maxOnlineUser'] = '# 在线用户上限，0为无限制。';
$lang->im->xxdConfigNote['en']['maxOnlineUser'] = '# Max online users, 0 means no limit.';

$lang->im->xxdConfigNote['zh']['logPath'] = '# 程序运行日志的保存路径。';
$lang->im->xxdConfigNote['en']['logPath'] = '# Path of saved log files.';

$lang->im->xxdConfigNote['zh']['certPath'] = '# 证书的保存路径。';
$lang->im->xxdConfigNote['en']['certPath'] = '# Path of saved certificate.';

$lang->im->xxdConfigNote['zh']['debug'] = '# Debug级别，可设置0|1|2';
$lang->im->xxdConfigNote['en']['debug'] = '# Debug level，0|1|2';

$lang->im->xxdConfigNote['zh']['thumbnail'] = '# 是否启用图片缩略图';
$lang->im->xxdConfigNote['en']['thumbnail'] = '# Image thumbnail';

$lang->im->xxdConfigNote['zh']['syncConfig'] = '# 与后端服务器同步配置信息，1 为开启 0 为关闭。开启后可能会丢失配置文件的注视。';
$lang->im->xxdConfigNote['en']['syncConfig'] = '# Sync config with xxb，0|1. Sync config enable may lost config comment.';

$lang->im->xxdConfigNote['zh']['backend'] = "# xxd可以对接多个后台程序。每一个后台程序由入口文件 + 私钥组成。\n# 客户端登录时如果没有指定后台程序，会默认登录到第一个后台程序。";
$lang->im->xxdConfigNote['en']['backend'] = "# xxd can integrate with multi backends. Every backend has an entry and a key. \n# The client will login to the first backend if the user doesn't specify the backend.";

$lang->pinnedMessages = new stdclass();
$lang->pinnedMessages->limit = '置顶消息数量已达到上限';

$lang->im->IPInvalid     = '登录 IP 不在规定网段内';
$lang->im->mobileLimited = '管理员限制了移动端的访问';

$lang->im->bot = new stdclass();
$lang->im->bot->commonName  = '小喧喧';
$lang->im->bot->command     = '命令';
$lang->im->bot->description = '描述';
$lang->im->bot->name        = '机器人名称';
$lang->im->bot->code        = '机器人代号';

$lang->im->bot->error = new stdclass();
$lang->im->bot->error->noSuchCommand = '该命令不存在，您可以发送 [help](xxc://sendContentToServerBySendbox/help) 查看命令列表。';
$lang->im->bot->error->noSuchBot     = '该机器人不存在。';
$lang->im->bot->error->badArguments  = '命令参数有误。';
$lang->im->bot->error->unauthorized  = '您无权使用此命令。';

$lang->im->bot->show = new stdClass();
$lang->im->bot->show->commands = array('show', '查看应用');

$lang->im->bot->help = new stdclass();
$lang->im->bot->help->header             = array($lang->im->bot->command, $lang->im->bot->description, $lang->im->bot->code);
$lang->im->bot->help->commandLink        = '[%s](xxc://sendContentToChat/%s)';
$lang->im->bot->help->botCommandLink     = '[%s](xxc://sendContentToChat/%s@%s)';
$lang->im->bot->help->sendCommandLink    = '[%s](xxc://sendContentToServerBySendbox/%s)';
$lang->im->bot->help->sendBotCommandLink = '[%s](xxc://sendContentToServerBySendbox/%s@%s)';
$lang->im->bot->help->commands           = array('help', '帮助');
$lang->im->bot->help->welcome            = "欢迎使用{$lang->im->bot->commonName}机器人会话指令，默认您当前已进入内置应用中，您可以发送以下命令来控制我：";
$lang->im->bot->help->welcomeHeader      = array($lang->im->bot->command, $lang->im->bot->description);

$lang->im->bot->help->global = <<<EOT
## 全局命令

| 命令 | 描述 |
| ---- | ---- |
| 帮助 | 命令帮助界面 |
| 查看应用 | 显示所有应用 |
| 应用名 | 切换到所输入的应用中 |
| 退出 | 退出当前应用 |
\n\n
EOT;
$lang->im->bot->help->system = <<<EOT
## 系统命令

| 命令 | 描述 |
| ---- | ---- |
| [授权](xxc://sendContentToServerBySendbox/授权) | 查看授权 |
| [搜索用户](xxc://sendContentToChat/搜索用户) | 搜索用户 |
| [服务器](xxc://sendContentToServerBySendbox/服务器) | 查看服务器信息 |

EOT;

$lang->im->bot->exit = new stdclass();
$lang->im->bot->exit->commands = array('exit', '退出');
$lang->im->bot->exit->tips     = '已退出 %s 应用。';

$lang->im->bot->switch         = new stdclass();
$lang->im->bot->switch->tips   = '当前应用切换为 %s 。';

$lang->im->bot->welcome          = new stdclass();
$lang->im->bot->welcome->title   = '哈喽~我是你的助手小喧喧';
$lang->im->bot->welcome->content = <<<EOT
我能够发送实时通知消息，同时你可以发送命令控制我。
发送"帮助"命令能够帮你快速学习，快来试试吧~
可点击下方"查看详情"了解更多使用说明。
EOT;
$lang->im->bot->welcome->link    = 'https://www.xuanim.com/book/xuanxuankehuduan/279.html';

$lang->im->bot->upgradeWelcome          = new stdclass();
$lang->im->bot->upgradeWelcome->title   = '哈喽~我是你的助手小喧喧';
$lang->im->bot->upgradeWelcome->content = <<<EOT
目前检测到当前客户端版本过低，请升级到7.0及以上版本体验会话命令的新功能吧~
可点击下方"查看详情"了解更多使用说明。
EOT;
$lang->im->bot->upgradeWelcome->link    = 'https://www.xuanim.com/dynamic.html';

$lang->im->userStatus = array('away' => '离开', 'busy' => '忙碌', 'meeting' => '会议中', 'offline' => '离线', 'online' => '在线');

$lang->im->bot->license = new stdclass();
$lang->im->bot->license->title     = array('标题', '内容');
$lang->im->bot->license->noLicense = '无授权信息';

$lang->im->bot->searchUser = new stdclass();
$lang->im->bot->searchUser->notFound        = '未找到相关用户';
$lang->im->bot->searchUser->keywordRequired = '请输入关键字';

$lang->im->api = new stdclass();
$lang->im->api->notArray = '格式错误，JSON 必须为数组';

$lang->im->detachedConferenceUpgradeMessage = new stdclass();
$lang->im->detachedConferenceUpgradeMessage->title   = '会议更新';
$lang->im->detachedConferenceUpgradeMessage->content = '管理员已将会议机制更新，支持 v7.2.beta 及以上版本，为了不影响您的使用体验，建议您重启应用，我们将更新您的会议机制。更多详情请移步喧喧官网查看。';

$lang->im->conferenceAppointment = new stdclass();
$lang->im->conferenceAppointment->firstNotifyTitle    = '会议创建';
$lang->im->conferenceAppointment->reminderNotifyTitle = '会议提醒';
$lang->im->conferenceAppointment->startNotifyTitle    = '会议开始';

$lang->im->conferenceEdit = new stdclass();
$lang->im->conferenceEdit->title        = '会议变更';
$lang->im->conferenceEdit->memberChange = '原定于**%s**的会议**%s**发生变动，您无需再参与该会议。';

$lang->im->conferenceCancel = new stdClass();
$lang->im->conferenceCancel->title           = '会议取消';
$lang->im->conferenceCancel->hasEndTimeBody  = '原定于**%s**的会议**%s**已经被取消，请知悉。';
$lang->im->conferenceCancel->noEndTimeBody   = '原定于**%s**开始的会议**%s**已经被取消，请知悉。';

$lang->im->conferenceEditFail = '会议编辑失败。';

$lang->im->jitsiConferenceInviteLink = new stdClass();
$lang->im->jitsiConferenceInviteLink->title = '%s邀请您参与会议【%s】';
$lang->im->jitsiConferenceInviteLink->link = '会议链接';
$lang->im->jitsiConferenceInviteLink->password = '会议密码';

$lang->im->jitsiConferenceInviteFailMessage = new stdClass();
$lang->im->jitsiConferenceInviteFailMessage->upgrade = '%s邀请您入会，但由于您版本过低无法入会，请及时升级到9.0及以上版本。';
$lang->im->jitsiConferenceInviteFailMessage->upgradeWithLink = '您收到一条会议邀请，由于您版本过低无法通过喧喧入会，可以点击下方链接从浏览器入会，建议您及时升级到9.0及以上版本，方便您正常使用会议功能。';
