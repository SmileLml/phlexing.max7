<?php
$lang->zanode->common          = '執行節點';
$lang->zanode->instruction     = '說明';
$lang->zanode->browse          = '執行節點列表';
$lang->zanode->nodeList        = '執行節點列表';
$lang->zanode->create          = '創建執行節點';
$lang->zanode->edit            = '編輯執行節點';
$lang->zanode->editAction      = '編輯執行節點';
$lang->zanode->view            = '執行節點詳情';
$lang->zanode->initTitle       = '初始化執行節點';
$lang->zanode->suspend         = '休眠執行節點';
$lang->zanode->destroy         = '銷毀執行節點';
$lang->zanode->boot            = '啟動執行節點';
$lang->zanode->reboot          = '重啟執行節點';
$lang->zanode->shutdown        = '關閉執行節點';
$lang->zanode->resume          = '恢復執行節點';
$lang->zanode->suspendNode     = '休眠';
$lang->zanode->bootNode        = '啟動';
$lang->zanode->rebootNode      = '重啟';
$lang->zanode->shutdownNode    = '關閉';
$lang->zanode->resumeNode      = '恢復';
$lang->zanode->getVNC          = '遠程';
$lang->zanode->all             = '全部';
$lang->zanode->byQuery         = '搜索';
$lang->zanode->osName          = '操作系統';
$lang->zanode->osNamePhysics   = '操作系統';
$lang->zanode->image           = '鏡像';
$lang->zanode->imageName       = '鏡像名稱';
$lang->zanode->name            = '名稱';
$lang->zanode->start           = '創建後自動開啟';
$lang->zanode->hostName        = '所屬宿主機';
$lang->zanode->host            = $lang->zanode->hostName;
$lang->zanode->extranet        = 'IP/域名';
$lang->zanode->sshCommand      = 'SSH命令';
$lang->zanode->sshAddress      = 'SSH地址';
$lang->zanode->osArch          = '架構';
$lang->zanode->cpuCores        = 'CPU';
$lang->zanode->defaultUser     = '預設用戶';
$lang->zanode->defaultPwd      = '預設密碼';
$lang->zanode->memory          = '內存';
$lang->zanode->diskSize        = '硬碟';
$lang->zanode->desc            = '描述';
$lang->zanode->status          = '狀態';
$lang->zanode->mac             = 'MAC地址';
$lang->zanode->vnc             = 'VNC連接埠';
$lang->zanode->destroyAt       = '銷毀時間';
$lang->zanode->creater         = '創建人';
$lang->zanode->createdDate     = '創建日期';
$lang->zanode->confirmDelete   = "您確定銷毀執行節點嗎？";
$lang->zanode->confirmBoot     = "您確定啟動執行節點嗎？";
$lang->zanode->confirmReboot   = "您確定重啟執行節點嗎？";
$lang->zanode->confirmShutdown = "您確定關閉執行節點嗎？";
$lang->zanode->confirmSuspend  = "您確定休眠執行節點嗎？";
$lang->zanode->confirmResume   = "您確定恢復執行節點嗎？";
$lang->zanode->confirmRestore  = "執行節點將會還原至此快照狀態，您確定要繼續麼？";
$lang->zanode->actionSuccess   = '操作成功';
$lang->zanode->deleted         = "已刪除";
$lang->zanode->scriptPath      = "腳本目錄";
$lang->zanode->syncToZentao    = "同步腳本信息到禪道";
$lang->zanode->shell           = "shell命令";
$lang->zanode->automation      = "自動化設置";
$lang->zanode->install         = "安裝";
$lang->zanode->reinstall       = "重裝";
$lang->zanode->copy            = '複製';
$lang->zanode->copied          = '複製成功';
$lang->zanode->manual          = '手冊';
$lang->zanode->initializing    = '初始化中';
$lang->zanode->showPwd         = '顯示密碼';
$lang->zanode->hidePwd         = '隱藏密碼';
$lang->zanode->baseInfo        = '基礎信息';
$lang->zanode->cpuUnit         = '核';
$lang->zanode->IP              = 'IP/域名';

$lang->zanode->typeList['node']    = '虛擬機';
$lang->zanode->typeList['physics'] = '物理機';

$lang->automation = new stdClass();
$lang->automation->scriptPath = $lang->zanode->scriptPath;
$lang->automation->node       = $lang->zanode->common;

$lang->zanode->notFoundAgent  = '沒有發現Agent服務';
$lang->zanode->busy           = '節點正在%s, 請等待操作完成';
$lang->zanode->createVmFail   = '創建執行節點失敗';
$lang->zanode->noVncPort      = '無法獲取執行節點連接埠';
$lang->zanode->nameValid      = "名稱只能是字母、數字，'-'，'_'，'.'，且不能以符號開頭";
$lang->zanode->empty          = '暫時沒有執行節點';
$lang->zanode->runCaseConfirm = '系統檢測到選擇的用例存在自動化測試腳本，是否自動執行用例？';
$lang->zanode->netError       = '無法連接到物理機，請檢查網絡後重試。';

$lang->zanode->createImage        = '導出鏡像';
$lang->zanode->createImaging      = '正在導出鏡像';
$lang->zanode->pending            = '等待導出鏡像';
$lang->zanode->createImageNotice  = '系統將基于當前執行節點導出鏡像，該過程需要關閉該執行節點，確定要繼續麼？';
$lang->zanode->createImageSuccess = '鏡像導出成功，您可以使用此鏡像創建執行節點。';
$lang->zanode->createImageFail    = '鏡像導出失敗';
$lang->zanode->createImageButton  = '去創建';

$lang->zanode->snapshotName          = '快照名稱';
$lang->zanode->browseSnapshot        = '快照列表';
$lang->zanode->createSnapshot        = '創建快照';
$lang->zanode->editSnapshot          = '編輯快照';
$lang->zanode->restoreSnapshot       = '還原到此快照';
$lang->zanode->deleteSnapshot        = '刪除快照';
$lang->zanode->snapshotEmpty         = '無快照';
$lang->zanode->confirmDeleteSnapshot = "快照被刪除後無法從資源回收筒中還原，您確定繼續麼？";

$lang->zanode->snapshot = new stdClass();
$lang->zanode->snapshot->statusList['creating']          = '創建中';
$lang->zanode->snapshot->statusList['inprogress']        = '創建中';
$lang->zanode->snapshot->statusList['completed']         = '可使用';
$lang->zanode->snapshot->statusList['failed']            = '創建失敗';
$lang->zanode->snapshot->statusList['restoring']         = '還原中';
$lang->zanode->snapshot->statusList['restore_failed']    = '還原失敗';
$lang->zanode->snapshot->statusList['restore_completed'] = '可使用';

$lang->zanode->snapshot->defaultSnapName = '初始快照';
$lang->zanode->snapshot->defaultSnapUser = '系統';

$lang->zanode->imageNameEmpty  = '名稱不能為空';
$lang->zanode->snapStatusError = '快照不可用';
$lang->zanode->snapRestoring   = '快照正在還原中';

$lang->zanode->runTimeout = '自動執行失敗，請檢查宿主機和執行節點狀態';

$lang->zanode->apiError['-10100']     = '執行節點不存在';
$lang->zanode->apiError['fail']       = '執行失敗，請檢查宿主機和執行節點狀態';
$lang->zanode->apiError['notRunning'] = '請檢查執行節點狀態';

$lang->zanode->publicList[0] = '不共享';
$lang->zanode->publicList[1] = '共享';

$lang->zanode->statusList['created']       = '已創建';
$lang->zanode->statusList['launch']        = '啟動中';
$lang->zanode->statusList['ready']         = '就緒';
$lang->zanode->statusList['running']       = '運行中';
$lang->zanode->statusList['suspend']       = '休眠';
$lang->zanode->statusList['offline']       = '離線';
$lang->zanode->statusList['destroy']       = '已銷毀';
$lang->zanode->statusList['shutoff']       = '已關機';
$lang->zanode->statusList['destroy_fail']  = '銷毀失敗';
$lang->zanode->statusList['wait']          = '初始化中';
$lang->zanode->statusList['online']        = '在綫';
$lang->zanode->statusList['restoring']     = '還原中';
$lang->zanode->statusList['creating_snap'] = '創建快照中';
$lang->zanode->statusList['creating_img']  = '導出鏡像中';

$lang->zanode->initNotice = "保存成功，請初始化執行節點或返回列表。";
$lang->zanode->initButton = "去初始化";

$lang->zanode->init = new stdClass();
$lang->zanode->init->statusTitle   = "服務狀態";
$lang->zanode->init->checkStatus   = "檢測服務狀態";
$lang->zanode->init->not_install   = "未安裝";
$lang->zanode->init->unknown       = "未知";
$lang->zanode->init->not_available = "已安裝，未啟動";
$lang->zanode->init->ready         = "已就緒";
$lang->zanode->init->next          = "下一步";
$lang->zanode->init->button        = "去設置";

$lang->zanode->init->initSuccessNoticeTitle  = "服務已就緒，還需兩步即可在執行節點上執行自動化測試：<br/>1、根據%s配置自動化測試運行環境。<br/>2、進行%s";
$lang->zanode->init->initFailNotice          = "服務未就緒，在執行節點上執行安裝服務命令或<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看幫助</a>.";
$lang->zanode->init->initFailNoticeOnPhysics = "服務還沒有安裝，請在執行節點執行以下命令後檢測服務狀態。<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看幫助</a>";

$lang->zanode->init->serviceStatus = array(
    "ZenAgent" => 'not_install',
    "ZTF"      => 'not_install',
);
$lang->zanode->init->title          = "初始化執行節點";
$lang->zanode->init->descTitle      = "請根據引導完成執行節點上的初始化: ";
$lang->zanode->init->initDesc       = "- 在執行節點上執行命令：%s %s  <br>- 點擊檢測服務狀態。";$lang->zanode->init->statusTitle    = "服務狀態";

$lang->zanode->tips           = '執行節點是由宿主機創建的虛擬機或容器實例，是執行測試任務的測試環境，在執行節點配置自動化測試環境後可以自動執行腳本，結果可以在禪道對應用例執行結果中查看。';
$lang->zanode->scriptTips     = '填寫執行節點上自動化測試腳本所在的目錄。';
$lang->zanode->shellTips      = '在執行節點上運行自動化測試腳本前，可以執行自定義的shell命令。';
$lang->zanode->automationTips = "在執行節點上執行測試任務前，需要設置{$lang->productCommon}對應的執行節點，自動化測試腳本的目錄以及需要執行的自定義Shell命令。";
$lang->zanode->nameUnique     = $lang->zanode->name . '已存在';

$lang->zanode->instructionPage = new stdClass();
$lang->zanode->instructionPage->title            = "禪道自動化測試解決方案";
$lang->zanode->instructionPage->desc             = "禪道自動化測試解決方案實現了對測試用例，測試腳本、腳本執行、測試結果以及測試環境的集中化管理，在降低測試管理成本的同時提高了測試執行的效率。通過解決方案你可以更容易地建立起適配當前項目管理和研發流程的自動化測試體系，借助自動化技術減少測試工作的投入。";
$lang->zanode->instructionPage->imageInstruction = '架構圖如下:';
$lang->zanode->instructionPage->image            = 'static/svg/zanode_instruction.svg';
$lang->zanode->instructionPage->concept          = '1、核心概念';
$lang->zanode->instructionPage->conceptDesc      = '宿主機包括實體主機、K8s集群、雲伺服器以及雲容器實例，主要用於創建虛擬機或容器實例。宿主機推薦安裝的操作系統為Ubuntu或CentOS的LTS版本。
執行節點是由宿主機創建的虛擬機或容器實例，是執行測試任務的測試環境。';
$lang->zanode->instructionPage->appIntroduction  = '2、應用介紹';
$lang->zanode->instructionPage->ZAgentDesc       = 'ZAgent是禪道開源的軟件自動化測試調度平台，它借助虛擬化技術，為用戶提供了一個分散式、集中管理的的測試環境。';
$lang->zanode->instructionPage->ZAgentUrl        = 'https://github.com/easysoft/zagent/blob/main/guide/deploy/index.md';
$lang->zanode->instructionPage->ZTFDesc          = 'ZTF是禪道開源的自動化測試管理框架，它幫助用戶將測試腳本統一管理。ZTF與禪道深度整合，每一個腳本都可以和測試管理系統裡面的一個用例進行關聯，腳本裡面的步驟信息和管理系統裡面的用例信息可以互相同步。';
$lang->zanode->instructionPage->ZTFUrl           = 'https://ztf.im/';
$lang->zanode->instructionPage->KVMDesc          = 'KVM(for Kernel-based Virtual Machine)是x86硬件上Linux的完整虛擬化解決方案，包含虛擬化擴展(Intel VT或AMD-V)。';
$lang->zanode->instructionPage->KVMUrl           = 'https://www.linux-kvm.org/page/Documents';
$lang->zanode->instructionPage->NginxDesc        = 'Nginx是一個高性能的HTTP和反向代理web伺服器，同時也提供了IMAP/POP3/SMTP服務。';
$lang->zanode->instructionPage->NginxUrl         = 'http://nginx.org/en/docs/';
$lang->zanode->instructionPage->noVNCDesc        = 'noVNC是一個HTML VNC客戶端JavaScript類庫和構建在該類庫上的應用程序。 noVNC在任何主流瀏覽器(包括移動瀏覽器(iOS和Android)上運行良好。';
$lang->zanode->instructionPage->noVNCUrl         = 'https://novnc.com/info.html';
$lang->zanode->instructionPage->WebsockifyDesc   = 'Websockify只是將WebSockets流量轉換為正常的socket流量。Websockify接受WebSockets握手，解析它，然後開始在客戶端和目標之間雙向轉發流量。';
$lang->zanode->instructionPage->WebsockifyUrl    = 'https://github.com/novnc/websockify';
