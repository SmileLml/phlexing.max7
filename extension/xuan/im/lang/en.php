<?php
$lang->im->settings = 'Xuanxuan Settings';
$lang->im->debug    = 'Debug';

$lang->im->version         = 'Version';
$lang->im->backendLang     = 'Server Language';
$lang->im->key             = 'Key';
$lang->im->systemGroup     = 'System';
$lang->im->url             = 'URL';
$lang->im->pollingInterval = 'Polling interval';
$lang->im->createKey       = 'New';
$lang->im->connector       = ', ';
$lang->im->viewDebug       = 'View Debug';
$lang->im->log             = 'Log';
$lang->im->xxdStatus       = 'XXD Status';
$lang->im->debugInfo       = 'Debug';
$lang->im->serverInfo      = 'Server Info';
$lang->im->errorInfo       = 'Error';
$lang->im->xxbConfigError  = 'XXB is error.';
$lang->im->id              = 'ID';
$lang->im->checkUpdate     = 'Check update';
$lang->im->xxcVersion      = 'XXC Version';
$lang->im->xxdVersion      = 'XXD Version';
$lang->im->xxbVersion      = 'XXB Version';
$lang->im->xxcDesc         = 'Upgrade description';
$lang->im->xxcReadme       = 'Update log';
$lang->im->strategy        = 'Upgrade strategy';
$lang->im->download        = 'Download';
$lang->im->notVersion      = 'The %s format is incorrect and must consist of digits and dots, such as "2.5.0" or "2.5"';
$lang->im->notempty        = 'Cannot be empty.';

$lang->im->strategies['force']    = 'Force';
$lang->im->strategies['optional'] = 'Optional';

$lang->im->disabled = 'Disabled';
$lang->im->enabled  = 'Enabled';

$lang->im->debugStatus[0] = $lang->im->disabled;
$lang->im->debugStatus[1] = $lang->im->enabled;

$lang->im->xxdServer       = 'Server';
$lang->im->createKey       = 'New';
$lang->im->downloadXXD     = 'Download XXD';
$lang->im->listenIP        = 'Listen IP';
$lang->im->chatPort        = 'Chat Port';
$lang->im->uploadFileSize  = 'File Size';
$lang->im->downloadPackage = 'Full Package';
$lang->im->downloadConfig  = 'Only Config';
$lang->im->changeSetting   = 'Change Settings';
$lang->im->downloadConf    = 'Download Config';
$lang->im->day             = 'd';
$lang->im->hours           = 'h';
$lang->im->minute          = 'm';
$lang->im->secs            = 's';
$lang->im->tokenLifetime   = 'Token Lifetime';
$lang->im->tokenAuthWindow = 'Token Auth Time Window';
$lang->im->iceServers      = 'ICE Servers';

$lang->im->logLevel        = 'Log Level';
$lang->im->logLevelSimple  = 'Simple';
$lang->im->logLevelDetail  = 'Detail';
$lang->im->logLevelOptions = array($lang->im->logLevelSimple, $lang->im->logLevelDetail);

$lang->im->mobileClient         = 'Mobile Client';
$lang->im->mobileOptions['on']  = $lang->im->enabled;
$lang->im->mobileOptions['off'] = $lang->im->disabled;

$lang->im->notAdmin         = 'You are not admin of chat.';
$lang->im->notGroupCreator  = 'You are not creator of chat.';
$lang->im->notSystemChat    = 'It is not a system chat.';
$lang->im->notGroupChat     = 'It is not a group chat.';
$lang->im->notPublic        = 'It is not a public chat.';
$lang->im->cantChat         = 'No rights to chat.';
$lang->im->chatHasDismissed = 'The chat group has been dismissed.';
$lang->im->needLogin        = 'You need login first.';
$lang->im->notExist         = 'Chat do not exist.';
$lang->im->changeRenameTo   = 'Rename chat to ';
$lang->im->multiChats       = 'Messages belong to different chats.';
$lang->im->notInGroup       = 'You are not in this chat group.';
$lang->im->notInChat        = 'Unable to send message to unrelated chats.';
$lang->im->notSameUser      = 'Unable to send message as someone else.';
$lang->im->errorKey         = 'The key should be a 32 byte string including letters or numbers.';
$lang->im->debugTips        = '<br>%s with administrator to get more information.';
$lang->im->noLogFile        = 'No log file.';
$lang->im->noFopen          = 'Function fopen disabled. Find the following file to see log : %s.';
$lang->im->defaultKey       = 'Do not use default <strong>key</strong>.';
$lang->im->owtIsDisabled    = 'Conference functionality is disabled.';
$lang->im->chatNameTooLong  = 'Chat name is too long.';
$lang->im->adminCanInvite   = 'Only administrators can invite members into this group.';
$lang->im->userNotInGroup   = 'You are no longer in this group and cannot do anything about it.';
$lang->im->canNotDelOwner   = 'Cannot remove group owners.';

$lang->im->xxdServerTip             = 'XXD server address contains protocol and host and port, such as http://192.168.1.35 or http://www.backend.com, that can not be 127.0.0.1.';
$lang->im->iceServersTip            = 'The iceServer used for point-to-point transmission, such as: stun:stun.l.google.com:19302, multiple servers can be separated by a newline, optional';
$lang->im->xxdServerEmpty           = 'XXD server address is empty.';
$lang->im->xxdServerError           = 'XXD server address can not be 127.0.0.1.';
$lang->im->xxdSchemeError           = 'Server address should started with http:// or https://.';
$lang->im->xxdPortError             = 'Server address should contain valid port and the default is <strong>11443</strong>.';
$lang->im->xxdPollIntTip            = 'Polling interval should be a number equal to or greater than 5, the default value is 60 for 60 seconds.';
$lang->im->xxdPollIntErr            = 'Polling interval should be a number equal to or greater than 5.';
$lang->im->xxdFileSizeErr           = 'File size should be equal to or greater than 0.';
$lang->im->tokenLifetimeErr         = 'Token lifetime should be equal to or greater than 1.';
$lang->im->tokenAuthWindowErr       = 'Token auth time window should be equal to or greater than 20.';
$lang->im->iceServersErr            = 'iceServer address is invalid';
$lang->im->errorSSLCrt              = 'SSL certificate cannot be empty';
$lang->im->errorSSLKey              = 'SSL key cannot be empty';
$lang->im->xxdAESTip                = 'This only affects server-side AES encryption between XXB and XXD.';
$lang->im->xxdFileEncryptTip        = 'Encrypt files uploaded in chats. Cannot disable once enabled.';
$lang->im->xxdMessageEncryptTip     = 'Encrypt chat messages in chats. Cannot disable once enabled.';

$lang->im->errorClientVersionNotSupport = 'The client version (%s) is too low, Please upgrade version to at least 6.0.https://xuanim.com';
$lang->im->operationNotSupportedOnArchivedChat = 'This operation is not allowed on archived discussion groups, please upgrade to version 6.6 and above.';

$lang->im->broadcast = new stdclass();
$lang->im->broadcast->createChat                 = '%s created the group [%s](#/chats/groups/%s).';
$lang->im->broadcast->changeChatOwnership        = 'Group [%s](#/chats/groups/%s) owner changed to %s.';
$lang->im->broadcast->changeChatOwnershipByAdmin = 'System Administrator changed owner of group [%s](#/chats/groups/%s) to %s.';
$lang->im->broadcast->joinChat                   = '%s joined.';
$lang->im->broadcast->leaveChat                  = '%s quited.';
$lang->im->broadcast->renameChat                 = '%s renamed the group to [%s](#/chats/groups/%s).';
$lang->im->broadcast->renamePrivate              = 'Chat is renamed to [%s](#/chats/recents/%s)。';
$lang->im->broadcast->inviteUser                 = '%s invited %s to join.';
$lang->im->broadcast->dismissChat                = '%s dismissed the group.';
$lang->im->broadcast->archiveChat                = '%s archive the group %s.';
$lang->im->broadcast->unarchiveChat              = '%s unarchive the group %s.';
$lang->im->broadcast->mergeChat                  = 'Chat %s has been merged into this chat, message history can be viewed from the history of this chat.';
$lang->im->broadcast->mergeChatWithMembers       = 'Chat %s has been merged into this chat, message history can be viewed from the history of this chat. %s joined.';
$lang->im->broadcast->chatMerged                 = 'Chat %s has been merged into %s.';

$lang->im->broadcast->createConference           = '%s started a conference.';
$lang->im->broadcast->closeConference            = '%s closed the conference.';
$lang->im->broadcast->createConferenceInvitation = '%s invited %s to join the conference. If you are not in the conference, you can join it in the upper right corner.';
$lang->im->broadcast->conferenceInviteeOccupied  = '%s is busy, try again later.';

$lang->im->conference = new stdclass();
$lang->im->conference->userBusy         = 'Line busy, try again later.';
$lang->im->conference->userOffline      = 'User is offline.';
$lang->im->conference->defaultTopic     = 'The conference initiated by %s';
$lang->im->conference->botName          = 'Conference notice';

$lang->im->xxd = new stdclass();
$lang->im->xxd->os                  = 'OS';
$lang->im->xxd->ip                  = 'Listen IP';
$lang->im->xxd->chatPort            = 'Chat Port';
$lang->im->xxd->commonPort          = 'Common Port';
$lang->im->xxd->https               = 'HTTPS';
$lang->im->xxd->aes                 = 'Server-side AES';
$lang->im->xxd->uploadFileSize      = 'File Size';
$lang->im->xxd->maxOnlineUser       = 'Max Online User Counts';
$lang->im->xxd->sslcrt              = 'SSL Crt';
$lang->im->xxd->sslkey              = 'SSL Key';
$lang->im->xxd->max                 = 'Max';
$lang->im->xxd->fileEncryption      = 'File Encryption';
$lang->im->xxd->messageEncryption   = 'Chat Message Encryption';

$lang->im->httpsOptions['on']  = $lang->im->enabled;
$lang->im->httpsOptions['off'] = $lang->im->disabled;

$lang->im->aesOptions['on']  = $lang->im->enabled;
$lang->im->aesOptions['off'] = $lang->im->disabled;

$lang->im->fileEncryptOptions['on']  = $lang->im->enabled;
$lang->im->fileEncryptOptions['off'] = $lang->im->disabled;

$lang->im->messageEncryptOptions['on']  = $lang->im->enabled;
$lang->im->messageEncryptOptions['off'] = $lang->im->disabled;

$lang->im->placeholder = new stdclass();
$lang->im->placeholder->xxd = new stdclass();
$lang->im->placeholder->xxd->ip             = 'Listen to the server IP address, Default 0.0.0.0';
$lang->im->placeholder->xxd->chatPort       = 'The port on which the chat client communicates';
$lang->im->placeholder->xxd->commonPort     = 'Generic port used for client login verification and for file upload and download';
$lang->im->placeholder->xxd->https          = 'HTTPS';
$lang->im->placeholder->xxd->uploadFileSize = 'Upload file size';
$lang->im->placeholder->xxd->maxOnlineUser  = 'Maximum number of user online';
$lang->im->placeholder->xxd->sslcrt         = 'Copy the certificate content here';
$lang->im->placeholder->xxd->sslkey         = 'Copy the certificate key here';

$lang->im->notify = new stdclass();
$lang->im->notify->setReceiver = 'Not set receiver type, it must be users or chat group.';
$lang->im->notify->setGroup    = 'Should set chat group.';
$lang->im->notify->setUserList = 'Should set user list.';
$lang->im->notify->setSender   = 'Should set sender info.';
$lang->im->notify->setTitle    = 'Notification title not set.';

$lang->im->osList['win_i386']      = 'Windows_i386';
$lang->im->osList['win_x86_64']    = 'Windows_x86_64';
$lang->im->osList['linux_i386']    = 'Linux_i386';
$lang->im->osList['linux_x86_64']  = 'Linux_x86_64';
$lang->im->osList['darwin_x86_64'] = 'macOS';

$lang->pinnedMessages = new stdclass();
$lang->pinnedMessages->limit = 'The number of pinned messages has reached the limit';

$lang->im->IPInvalid     = 'Login IP is not in the specified network segment';
$lang->im->mobileLimited = 'The administrator has restricted the access of the mobile terminal';

$lang->im->bot = new stdclass();
$lang->im->bot->commonName  = 'Xuan Bot';
$lang->im->bot->command     = 'Command';
$lang->im->bot->description = 'Description';
$lang->im->bot->code        = 'Bot Name';
$lang->im->bot->code        = 'Bot Code';

$lang->im->bot->error = new stdclass();
$lang->im->bot->error->noSuchCommand = 'There\'s no such command, send [/help](xxc://sendContentToServerBySendbox/%2fhelp) to get list of available commands.';
$lang->im->bot->error->noSuchBot     = 'There\'s no such bot.';
$lang->im->bot->error->badArguments  = 'Bad arguments.';
$lang->im->bot->error->unauthorized  = 'Unauthorized.';

$lang->im->bot->show = new stdClass();
$lang->im->bot->show->commands = array('show', 'list');

$lang->im->bot->help = new stdclass();
$lang->im->bot->help->header             = array($lang->im->bot->command, $lang->im->bot->description, $lang->im->bot->code);
$lang->im->bot->help->commandLink        = '[%s](xxc://sendContentToChat/%s)';
$lang->im->bot->help->botCommandLink     = '[%s](xxc://sendContentToChat/%s@%s)';
$lang->im->bot->help->sendCommandLink    = '[%s](xxc://sendContentToServerBySendbox/%s)';
$lang->im->bot->help->sendBotCommandLink = '[%s](xxc://sendContentToServerBySendbox/%s@%s)';
$lang->im->bot->help->commands           = array('help');
$lang->im->bot->help->welcome            = "Welcome to the {$lang->im->bot->commonName} bot session command. By default you are currently in the built-in application and you can send the following command to control me.";
$lang->im->bot->help->welcomeHeader      = array($lang->im->bot->command, $lang->im->bot->description);

$lang->im->bot->help->global   = <<<EOT
## Global Commands

| Command | Description |
| ---- | ---- |
| help | Command help screen |
| show | Show all bots |
| Bot Code | Switch bot |
| exit | Exit current bot  |
\n\n
EOT;
$lang->im->bot->help->system   = <<<EOT
## System Commands

| Command | Description |
| ---- | ---- |
| [license](xxc://sendContentToServerBySendbox/license) | Show license |
| searchUser username | Search user |
| [server](xxc://sendContentToServerBySendbox/server) | Show server info |

EOT;

$lang->im->bot->exit = new stdclass();
$lang->im->bot->exit->commands = array('exit');
$lang->im->bot->exit->tips     = 'Exit %s bot.';

$lang->im->bot->switch         = new stdclass();
$lang->im->bot->switch->tips   = 'Switch to %s bot.';

$lang->im->bot->welcome          = new stdclass();
$lang->im->bot->welcome->title   = 'Hi! I\'m your assistant.';
$lang->im->bot->welcome->content = <<<EOT
I can send real-time notification messages, and you can send commands to control me.
Sending "help" commands can help you learn quickly, so try it out!
You can click "View Details" below to learn more about how to use it.
EOT;
$lang->im->bot->welcome->link    = 'https://www.xuanim.com/book/xuanxuankehuduan/279.html';

$lang->im->bot->upgradeWelcome          = new stdclass();
$lang->im->bot->upgradeWelcome->title   = 'Hi! I\'m your assistant.';
$lang->im->bot->upgradeWelcome->content = <<<EOT
The current client version is detected to be too low, please upgrade to version 7.0 and above to experience the new features of session command.
You can click "View Details" below to learn more about how to use it.
EOT;
$lang->im->bot->upgradeWelcome->link    = 'https://www.xuanim.com/dynamic.html';

$lang->im->userStatus = array('away' => 'Away', 'busy' => 'Busy', 'meeting' => 'Meeting', 'offline' => 'Offline', 'online' => 'Online');

$lang->im->bot->license = new stdclass();
$lang->im->bot->license->title     = array('Title', 'Content');
$lang->im->bot->license->noLicense = 'No license data.';

$lang->im->bot->searchUser = new stdclass();
$lang->im->bot->searchUser->notFound        = 'User not found.';
$lang->im->bot->searchUser->keywordRequired = 'Keyword required.';

$lang->im->api = new stdclass();
$lang->im->api->notArray = 'Format error, JSON must be an array';

$lang->im->detachedConferenceUpgradeMessage = new stdclass();
$lang->im->detachedConferenceUpgradeMessage->title   = 'Conference Updates';
$lang->im->detachedConferenceUpgradeMessage->content = 'The administrator has updated the meeting mechanism to support v7.2.beta and above, in order not to affect your experience, we recommend that you restart the app and we will update your meeting mechanism, please check the website for details.';

$lang->im->conferenceAppointment = new stdclass();
$lang->im->conferenceAppointment->firstNotifyTitle    = 'Conference create';
$lang->im->conferenceAppointment->reminderNotifyTitle = 'Conference reminder';
$lang->im->conferenceAppointment->startNotifyTitle    = 'Conference start';

$lang->im->conferenceEdit = new stdclass();
$lang->im->conferenceEdit->title        = 'Conference change';
$lang->im->conferenceEdit->memberChange = 'The conference **%s** scheduled **%s** has been change, you do not need to participate in this meeting.';

$lang->im->conferenceCancel = new stdClass();
$lang->im->conferenceCancel->title          = 'Conference Canceled';
$lang->im->conferenceCancel->hasEndTimeBody = 'The conference **%s** scheduled **%s** has been canceled, please be aware.';
$lang->im->conferenceCancel->noEndTimeBody  = 'The conference **%s** scheduled **%s** has been canceled, please be aware.';

$lang->im->conferenceEditFail = 'Conference edit failed.';

$lang->im->jitsiConferenceInviteLink = new stdClass();
$lang->im->jitsiConferenceInviteLink->title = '%s invites you to participate in the conference [%s]';
$lang->im->jitsiConferenceInviteLink->link = 'Conference link';
$lang->im->jitsiConferenceInviteLink->password = 'Conference password';

$lang->im->jitsiConferenceInviteFailMessage = new stdClass();
$lang->im->jitsiConferenceInviteFailMessage->upgrade = '%s invites you to join the conference, but you are unable to join because your version is too low. Please upgrade to version 9.0 or above in time.';
$lang->im->jitsiConferenceInviteFailMessage->upgradeWithLink = 'You have received a conference invitation. Since your version is too low, you cannot join the meeting through Noon. You can click the link below to join the conference through the browser. It is recommended that you upgrade to version 9.0 or above in time to facilitate your normal use of the conference function.';
