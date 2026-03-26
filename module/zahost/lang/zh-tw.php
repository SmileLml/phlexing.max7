<?php
$lang->zahost->id             = 'ID';
$lang->zahost->common         = '宿主機';
$lang->zahost->browse         = '宿主機列表';
$lang->zahost->create         = '添加宿主機';
$lang->zahost->view           = '宿主機詳情';
$lang->zahost->initTitle      = '初始化宿主機';
$lang->zahost->edit           = '編輯';
$lang->zahost->editAction     = '編輯宿主機';
$lang->zahost->delete         = '刪除';
$lang->zahost->cancel         = "取消下載";
$lang->zahost->deleteAction   = '刪除宿主機';
$lang->zahost->byQuery        = '搜索';
$lang->zahost->all            = '全部主機';
$lang->zahost->browseNode     = '執行節點列表';
$lang->zahost->deleted        = "已刪除";
$lang->zahost->copy           = '複製';
$lang->zahost->copied         = '複製成功';
$lang->zahost->baseInfo       = '基礎信息';

$lang->zahost->name        = '名稱';
$lang->zahost->IP          = 'IP/域名';
$lang->zahost->extranet    = 'IP/域名';
$lang->zahost->memory      = '內存';
$lang->zahost->cpuCores    = 'CPU';
$lang->zahost->diskSize    = '硬碟容量';
$lang->zahost->desc        = '描述';
$lang->zahost->type        = '類型';
$lang->zahost->status      = '狀態';

$lang->zahost->createdBy    = '由誰創建';
$lang->zahost->createdDate  = '創建時間';
$lang->zahost->editedBy     = '由誰修改';
$lang->zahost->editedDate   = '最後修改時間';
$lang->zahost->registerDate = '最後註冊時間';

$lang->zahost->memorySize    = $lang->zahost->memory;
$lang->zahost->cpuCoreNum    = $lang->zahost->cpuCores;
$lang->zahost->os            = '操作系統';
$lang->zahost->imageName     = '鏡像檔案';
$lang->zahost->browseImage   = '鏡像列表';
$lang->zahost->downloadImage = '下載鏡像';

$lang->zahost->createZanode        = '創建執行節點';
$lang->zahost->initNotice          = '保存成功，請您初始化宿主機或返回列表。';
$lang->zahost->createZanodeNotice  = '初始化成功，您現在可以創建執行節點了。';
$lang->zahost->downloadImageNotice = '初始化成功，請下載鏡像用於創建執行節點。';
$lang->zahost->undeletedNotice     = "宿主機下存在執行節點無法刪除。";
$lang->zahost->uninitNotice        = '請先初始化宿主機';
$lang->zahost->netError            = '無法連接到宿主機，請檢查網絡後重試。';

$lang->zahost->init = new stdclass;
$lang->zahost->init->statusTitle = "服務狀態";
$lang->zahost->init->checkStatus = "檢測服務狀態";
$lang->zahost->init->not_install = "未安裝";
$lang->zahost->init->not_available = "已安裝，未啟動";
$lang->zahost->init->ready = "已就緒";
$lang->zahost->init->next = "下一步";

$lang->zahost->init->initFailNotice    = "服務未就緒，在宿主機上執行安裝服務命令或<a href='https://github.com/easysoft/zenagent/' target='_blank'>查看幫助</a>.";
$lang->zahost->init->initSuccessNotice = "服務已就緒，您可以在%s後%s。";

$lang->zahost->init->serviceStatus = array();
$lang->zahost->init->serviceStatus['kvm']        = 'not_install';
$lang->zahost->init->serviceStatus['nginx']      = 'not_install';
$lang->zahost->init->serviceStatus['novnc']      = 'not_install';
$lang->zahost->init->serviceStatus['websockify'] = 'not_install';

$lang->zahost->init->title       = "初始化宿主機";
$lang->zahost->init->descTitle   = "請根據引導完成宿主機上的初始化: ";
$lang->zahost->init->initDesc    = "- 在宿主機上執行命令：%s %s <br>- 點擊檢測服務狀態。";
$lang->zahost->init->statusTitle = "服務狀態";

$lang->zahost->image = new stdclass;
$lang->zahost->image->browseImage   = '鏡像列表';
$lang->zahost->image->createImage   = '創建鏡像';
$lang->zahost->image->choseImage    = '選擇鏡像';
$lang->zahost->image->downloadImage = $lang->zahost->downloadImage;
$lang->zahost->image->startDowload  = '開始下載';

$lang->zahost->image->common     = '鏡像';
$lang->zahost->image->name       = '名稱';
$lang->zahost->image->desc       = '描述';
$lang->zahost->image->path       = '檔案路徑';
$lang->zahost->image->memory     = $lang->zahost->memory;
$lang->zahost->image->disk       = $lang->zahost->diskSize;
$lang->zahost->image->os         = $lang->zahost->os;
$lang->zahost->image->imageName  = $lang->zahost->imageName;
$lang->zahost->image->progress   = '下載進度';

$lang->zahost->image->statusList['notDownloaded'] = '可下載';
$lang->zahost->image->statusList['created']       = '下載中';
$lang->zahost->image->statusList['canceled']      = '可下載';
$lang->zahost->image->statusList['inprogress']    = '下載中';
$lang->zahost->image->statusList['pending']       = '排隊下載中';
$lang->zahost->image->statusList['completed']     = '可使用';
$lang->zahost->image->statusList['failed']        = '下載失敗';

$lang->zahost->image->imageEmpty           = '無鏡像';
$lang->zahost->image->downloadImageFail    = '創建下載鏡像任務失敗';
$lang->zahost->image->downloadImageSuccess = '創建下載鏡像任務成功';
$lang->zahost->image->cancelDownloadFail    = '取消下載鏡像任務失敗';
$lang->zahost->image->cancelDownloadSuccess = '取消下載鏡像任務成功';

$lang->zahost->empty         = '暫時沒有宿主機';

$lang->zahost->statusList['wait']    = '待初始化';
$lang->zahost->statusList['ready']   = '就緒';
$lang->zahost->statusList['online']  = '在綫';
$lang->zahost->statusList['offline'] = '離線';
$lang->zahost->statusList['busy']    = '繁忙';

$lang->zahost->vsoft = '虛擬化軟件';
$lang->zahost->softwareList['kvm'] = 'KVM';

$lang->zahost->unitList['GB'] = 'GB';
$lang->zahost->unitList['TB'] = 'TB';

$lang->zahost->cpuUnit = '核';

$lang->zahost->zaHostType                 = '主機類型';
$lang->zahost->zaHostTypeList['physical'] = '實體主機';

$lang->zahost->confirmDelete           = '是否刪除該宿主機記錄？';
$lang->zahost->cancelDelete            = '是否取消該下載任務？';

$lang->zahost->notice = new stdclass();
$lang->zahost->notice->ip              = '『%s』格式不正確！';
$lang->zahost->notice->registerCommand = '宿主機註冊命令：./zagent-host -t host -s http://%s:%s -i %s -p 8086 -secret %s';
$lang->zahost->notice->loading         = '加載中...';
$lang->zahost->notice->noImage         = '無可用的鏡像檔案';

$lang->zahost->tips = '宿主機包括實體主機、K8s集群、雲伺服器以及雲容器實例，主要用於創建虛擬機或容器實例。宿主機推薦安裝的操作系統為Ubuntu或CentOS的LTS版本。';

$lang->zahost->automation = new stdclass();
$lang->zahost->automation->title = '自動化測試解決方案';
$lang->zahost->automation->abstract      = '簡介';
$lang->zahost->automation->abstractSpec  = '禪道自動化測試解決方案實現了對測試用例，測試腳本、腳本執行、測試結果以及測試環境的集中化管理，在降低測試管理成本的同時提高了測試執行的效率。通過解決方案你可以更容易地建立起適配當前項目管理和研發流程的自動化測試體系，借助自動化技術減少測試工作的投入。';
$lang->zahost->automation->framework     = '架構';
$lang->zahost->automation->frameworkSpec = '基于KVM虛擬化軟件的解決方案架構：';

$lang->zahost->automation->feature1           = '1、核心概念';
$lang->zahost->automation->feature1Spec       = "宿主機包括實體主機、K8s集群、雲伺服器以及雲容器實例，主要用於創建虛擬機或容器實例。宿主機推薦安裝的操作系統為Ubuntu或CentOS的LTS版本。<br/> 執行節點是由宿主機創建的虛擬機或容器實例，是執行測試任務的測試環境。";
$lang->zahost->automation->feature2           = '2、應用介紹';
$lang->zahost->automation->feature2ZenAgent   = 'ZAgent是禪道開源的軟件自動化測試調度平台，它借助虛擬化技術，為用戶提供了一個分散式、集中管理的的測試環境。';
$lang->zahost->automation->feature2ZTF        = 'ZTF是禪道開源的自動化測試管理框架，它幫助用戶將測試腳本統一管理。ZTF與禪道深度整合，每一個腳本都可以和測試管理系統裡面的一個用例進行關聯，腳本裡面的步驟信息和管理系統裡面的用例信息可以互相同步。';
$lang->zahost->automation->feature2KVM        = 'KVM(for Kernel-based Virtual Machine)是x86硬件上Linux的完整虛擬化解決方案，包含虛擬化擴展(Intel VT或AMD-V)。';
$lang->zahost->automation->feature2Nginx      = 'Nginx是一個高性能的HTTP和反向代理web伺服器，同時也提供了IMAP/POP3/SMTP服務。';
$lang->zahost->automation->feature2noVNC      = 'noVNC是一個HTML VNC客戶端JavaScript類庫和構建在該類庫上的應用程序。 noVNC在任何主流瀏覽器(包括移動瀏覽器(iOS和Android)上運行良好。';
$lang->zahost->automation->feature2Websockify = 'Websockify只是將WebSockets流量轉換為正常的socket流量。Websockify接受WebSockets握手，解析它，然後開始在客戶端和目標之間雙向轉發流量。';
$lang->zahost->automation->support            = '支持';
$lang->zahost->automation->supportSpec        = '您可以訪問禪道官網獲取幫助手冊：';
$lang->zahost->automation->groupTitle         = "歡迎掃瞄二維碼<br/>獲取幫助";
