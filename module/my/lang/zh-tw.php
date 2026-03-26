<?php
global $config;

/* 方法列表。*/
$lang->my->index           = '首頁';
$lang->my->data            = '我的數據';
$lang->my->todo            = '我的待辦';
$lang->my->todoAction      = '日程列表';
$lang->my->calendar        = '日程';
$lang->my->work            = '待處理';
$lang->my->contribute      = '貢獻';
$lang->my->task            = '我的任務';
$lang->my->bug             = '我的Bug';
$lang->my->myTestTask      = '我的測試單';
$lang->my->myTestCase      = '我的用例';
$lang->my->story           = "我的{$lang->SRCommon}";
$lang->my->doc             = "我的文檔";
$lang->my->createProgram   = '添加' . $lang->projectCommon;
$lang->my->project         = '我的' . $lang->projectCommon;
$lang->my->execution       = "我的{$lang->execution->common}";
$lang->my->audit           = '審批';
$lang->my->issue           = '我的問題';
$lang->my->risk            = '我的風險';
$lang->my->profile         = '我的檔案';
$lang->my->dynamic         = '我的動態';
$lang->my->team            = '團隊';
$lang->my->editProfile     = '修改檔案';
$lang->my->changePassword  = '修改密碼';
$lang->my->preference      = '個性化設置';
$lang->my->unbind          = '解除ZDOO綁定';
$lang->my->manageContacts  = '維護聯繫人';
$lang->my->createContacts  = '創建聯繫人';
$lang->my->deleteContacts  = '刪除聯繫人';
$lang->my->viewContacts    = '查看聯繫人';
$lang->my->shareContacts   = '公共聯繫人';
$lang->my->limited         = '受限操作(只能編輯與自己相關的內容)';
$lang->my->score           = '我的積分';
$lang->my->scoreRule       = '積分規則';
$lang->my->noTodo          = '暫時沒有待辦。';
$lang->my->noData          = "暫時沒有%s。";
$lang->my->storyChanged    = "需求變更";
$lang->my->hours           = '工時/天';
$lang->my->uploadAvatar    = '更換頭像';
$lang->my->epic            = "我的{$lang->ERCommon}";
$lang->my->requirement     = "我的{$lang->URCommon}";
$lang->my->testtask        = '我的測試單';
$lang->my->testcase        = '我的用例';
$lang->my->storyConcept    = $config->URAndSR ? '預設需求概念組合' : '預設需求概念';
$lang->my->pri             = '優先順序';
$lang->my->alert           = '後續您可以點擊右上方的頭像，選擇“個性化設置”修改信息。';
$lang->my->assignedToMe    = '指派給我';
$lang->my->byQuery         = '搜索';
$lang->my->contactHolder   = '聯繫人';
$lang->my->contactList     = '聯繫人列表';
$lang->my->myContact       = '我的聯繫人';
$lang->my->publicContact   = '公共聯繫人';
$lang->my->manageSelf      = '僅可維護自己創建的聯繫人';
$lang->my->adminView       = '系統管理員具備刪除公共聯繫人權限';

$lang->my->indexAction      = '地盤儀表盤';
$lang->my->calendarAction   = '我的日程';
$lang->my->workAction       = '我的待處理';
$lang->my->contributeAction = '我的貢獻';
$lang->my->profileAction    = '個人檔案';
$lang->my->dynamicAction    = '動態';

$lang->my->myExecutions = "我參與的階段/衝刺/迭代";
$lang->my->name         = '名稱';
$lang->my->code         = '代號';
$lang->my->projects     = '所屬' . $lang->projectCommon;
$lang->my->executions   = "所屬{$lang->executionCommon}";

$lang->my->taskMenu = new stdclass();
$lang->my->taskMenu->assignedToMe = '指派給我';
$lang->my->taskMenu->openedByMe   = '由我創建';
$lang->my->taskMenu->finishedByMe = '由我完成';
$lang->my->taskMenu->closedByMe   = '由我關閉';
$lang->my->taskMenu->canceledByMe = '由我取消';
$lang->my->taskMenu->assignedByMe = '由我指派';

$lang->my->storyMenu = new stdclass();
$lang->my->storyMenu->assignedToMe = '指派給我';
$lang->my->storyMenu->reviewByMe   = '待我評審';
$lang->my->storyMenu->openedByMe   = '由我創建';
$lang->my->storyMenu->reviewedByMe = '由我評審';
$lang->my->storyMenu->closedByMe   = '由我關閉';
$lang->my->storyMenu->assignedByMe = '由我指派';

$lang->my->auditField = new stdclass();
$lang->my->auditField->title  = '評審標題';
$lang->my->auditField->time   = '提交時間';
$lang->my->auditField->type   = '評審對象';
$lang->my->auditField->result = '評審結果';
$lang->my->auditField->status = '狀態';

$lang->my->auditField->oaTitle['attend']   = '%s的考勤申請：%s';
$lang->my->auditField->oaTitle['leave']    = '%s的請假申請：%s';
$lang->my->auditField->oaTitle['makeup']   = '%s的補班申請：%s';
$lang->my->auditField->oaTitle['overtime'] = '%s的加班申請：%s';
$lang->my->auditField->oaTitle['lieu']     = '%s的調休申請：%s';

$lang->my->form = new stdclass();
$lang->my->form->lblBasic   = '基本信息';
$lang->my->form->lblContact = '聯繫信息';
$lang->my->form->lblAccount = '帳號信息';

$lang->my->programLink   = '項目集預設着陸頁';
$lang->my->productLink   = $lang->productCommon .'預設着陸頁';
$lang->my->projectLink   = $lang->projectCommon . '預設着陸頁';
$lang->my->executionLink = '執行預設着陸頁';
$lang->my->docLink       = '文檔預設着陸頁';

$lang->my->programLinkList = array();
$lang->my->programLinkList['program-browse']  = '項目集列表/可以查看所有的項目集';
$lang->my->programLinkList['program-kanban']  = '項目集看板/可以可視化的查看到所有項目集的進展情況';
$lang->my->programLinkList['program-project'] = "最近一個項目集的{$lang->projectCommon}列表/可以查看當前項目集下所有{$lang->projectCommon}";

$lang->my->productLinkList = array();
$lang->my->productLinkList['product-all']       = "{$lang->productCommon}列表/可以查看所有{$lang->productCommon}";
$lang->my->productLinkList['product-kanban']    = "{$lang->productCommon}看板/以可視化的方式查看到所有{$lang->productCommon}的整體情況";
$lang->my->productLinkList['product-index']     = "所有{$lang->productCommon}儀表盤/可以查看所有{$lang->productCommon}的統計,概況，總覽等";
$lang->my->productLinkList['product-dashboard'] = "最近一個{$lang->productCommon}儀表盤/可以查看最近查看過的一個{$lang->productCommon}儀表盤";
$lang->my->productLinkList['product-browse']    = "最近一個{$lang->productCommon}的需求列表/可以進入最近查看過的一個{$lang->productCommon}下的研發需求列表";

$lang->my->projectLinkList = array();
$lang->my->projectLinkList['project-browse']    = "{$lang->projectCommon}列表/可以查看所有{$lang->projectCommon}";
$lang->my->projectLinkList['project-kanban']    = "{$lang->projectCommon}看板/以可視化的查看到所有{$lang->projectCommon}的整體情況";
$lang->my->projectLinkList['project-execution'] = "最近一個{$lang->projectCommon}執行列表/可以查看{$lang->projectCommon}下所有的執行列表";
$lang->my->projectLinkList['project-index']     = "最近一個{$lang->projectCommon}儀表盤/可以進入最近查看過的一個{$lang->projectCommon}的儀表盤";

$lang->my->executionLinkList = array();
$lang->my->executionLinkList['execution-all']             = '執行列表/可以查看所有執行';
$lang->my->executionLinkList['execution-executionkanban'] = '執行看板/以可視化的方式查看所有執行的整體情況';
$lang->my->executionLinkList['execution-task']            = '最近一個執行的任務列表/可以查看最近創建的一個執行下的任務';

$lang->my->docLinkList = array();
$lang->my->docLinkList['doc-lastViewedSpaceHome'] = '最近一個瀏覽的空間首頁';
$lang->my->docLinkList['doc-lastViewedSpace']     = '最近一個瀏覽的空間';
$lang->my->docLinkList['doc-lastViewedLib']       = '最近一個瀏覽的庫';

$lang->my->confirmReview['pass'] = '您確定要執行通過操作嗎？';
$lang->my->guideChangeTheme = <<<EOT
<p class='theme-title'>全新<span style='color: #0c60e1'>“青春藍”</span>主題上線了！</p>
<div>
  <p>只需一步，您就可以體驗全新的“青春藍”主題了，趕緊去設置吧！</p>
  <p>滑鼠經過<span style='color: #0c60e1'>【頭像-主題-青春藍】</span>，點擊青春藍，設置成功！</p>
</div>
EOT;

$lang->my->featureBar['todo']['all']       = '指派給我';
$lang->my->featureBar['todo']['undone']    = '未完成';
$lang->my->featureBar['todo']['future']    = '待定';
$lang->my->featureBar['todo']['today']     = '今日';
$lang->my->featureBar['todo']['thisWeek']  = '本週';
$lang->my->featureBar['todo']['thisMonth'] = '本月';
$lang->my->featureBar['todo']['more']      = '更多';

$lang->my->moreSelects['todo']['more']['thisYear']        = '本年';
$lang->my->moreSelects['todo']['more']['assignedToOther'] = '指派他人';
$lang->my->moreSelects['todo']['more']['cycle']           = '周期';

$lang->my->featureBar['audit']['all']         = '全部';
$lang->my->featureBar['audit']['demand']      = '需求池需求';
$lang->my->featureBar['audit']['story']       = $lang->SRCommon;
$lang->my->featureBar['audit']['requirement'] = $lang->URCommon;
$lang->my->featureBar['audit']['epic']        = $lang->ERCommon;
$lang->my->featureBar['audit']['testcase']    = '用例';
if(in_array($config->edition, array('max', 'ipd')) and (helper::hasFeature('waterfall') or helper::hasFeature('waterfallplus'))) $lang->my->featureBar['audit']['project'] = $lang->projectCommon;
if($config->edition != 'open') $lang->my->featureBar['audit']['feedback'] = '反饋';
if($config->edition != 'open' and helper::hasFeature('OA')) $lang->my->featureBar['audit']['oa'] = '辦公';

$lang->my->featureBar['project']['doing']      = '進行中';
$lang->my->featureBar['project']['wait']       = '未開始';
$lang->my->featureBar['project']['suspended']  = '已掛起';
$lang->my->featureBar['project']['delayed']    = '已延期';
$lang->my->featureBar['project']['closed']     = '已關閉';
$lang->my->featureBar['project']['openedbyme'] = '由我創建';

$lang->my->featureBar['execution']['undone']  = '未完成';
$lang->my->featureBar['execution']['done']    = '已完成';
$lang->my->featureBar['execution']['delayed'] = '已延期';

$lang->my->featureBar['dynamic']['all']       = '全部';
$lang->my->featureBar['dynamic']['today']     = '今天';
$lang->my->featureBar['dynamic']['yesterday'] = '昨天';
$lang->my->featureBar['dynamic']['thisWeek']  = '本週';
$lang->my->featureBar['dynamic']['lastWeek']  = '上周';
$lang->my->featureBar['dynamic']['thisMonth'] = '本月';
$lang->my->featureBar['dynamic']['lastMonth'] = '上月';

$lang->my->featureBar['work']['task']['assignedTo']     = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testcase']['assigntome'] = $lang->my->assignedToMe;
$lang->my->featureBar['work']['testtask']['assignedTo'] = '由我負責';

$lang->my->featureBar['work']['epic'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['epic']['reviewBy'] = '待我評審';

$lang->my->featureBar['work']['requirement'] = $lang->my->featureBar['work']['task'];
$lang->my->featureBar['work']['requirement']['reviewBy'] = '待我評審';

$lang->my->featureBar['work']['story'] = $lang->my->featureBar['work']['requirement'];
$lang->my->featureBar['work']['bug']   = $lang->my->featureBar['work']['task'];

$lang->my->featureBar['contribute']['task']['openedBy']   = '由我創建';
$lang->my->featureBar['contribute']['task']['finishedBy'] = '由我完成';
$lang->my->featureBar['contribute']['task']['myInvolved'] = '由我參與';
$lang->my->featureBar['contribute']['task']['closedBy']   = '由我關閉';
$lang->my->featureBar['contribute']['task']['canceledBy'] = '由我取消';
$lang->my->featureBar['contribute']['task']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['epic']['openedBy']   = '由我創建';
$lang->my->featureBar['contribute']['epic']['reviewedBy'] = '我評審過';
$lang->my->featureBar['contribute']['epic']['closedBy']   = '由我關閉';
$lang->my->featureBar['contribute']['epic']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['requirement']['openedBy']   = '由我創建';
$lang->my->featureBar['contribute']['requirement']['reviewedBy'] = '我評審過';
$lang->my->featureBar['contribute']['requirement']['closedBy']   = '由我關閉';
$lang->my->featureBar['contribute']['requirement']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['bug']['openedBy']   = '由我創建';
$lang->my->featureBar['contribute']['bug']['resolvedBy'] = '由我解決';
$lang->my->featureBar['contribute']['bug']['closedBy']   = '由我關閉';
$lang->my->featureBar['contribute']['bug']['assignedBy'] = '由我指派';

$lang->my->featureBar['contribute']['story'] = $lang->my->featureBar['contribute']['requirement'];

$lang->my->featureBar['contribute']['testcase']['openedbyme'] = '我建的用例';

$lang->my->featureBar['contribute']['testtask']['done'] = '已測測試單';

$lang->my->featureBar['contribute']['audit']['reviewedbyme'] = '由我評審';
$lang->my->featureBar['contribute']['audit']['createdbyme']  = '由我發起';

$lang->my->featureBar['contribute']['doc']['openedbyme'] = '由我創建';
$lang->my->featureBar['contribute']['doc']['editedbyme'] = '由我編輯';

$lang->my->featureBar['score']['all'] = '我的積分';

$lang->my->reviewResultList['pass'] = '通過';
$lang->my->reviewResultList['fail'] = '不通過';
