<?php
/* Fields. */
$lang->program->id             = '編號';
$lang->program->name           = '項目集名稱';
$lang->program->template       = '項目集模板';
$lang->program->category       = '項目集類型';
$lang->program->desc           = '項目集描述';
$lang->program->status         = '狀態';
$lang->program->PM             = '負責人';
$lang->program->budget         = '預算';
$lang->program->budgetUnit     = '預算單位';
$lang->program->invested       = '已投入';
$lang->program->begin          = '計劃開始';
$lang->program->end            = '計劃完成';
$lang->program->realBeganAB    = '實際開始';
$lang->program->realEndAB      = '實際完成';
$lang->program->stage          = '階段';
$lang->program->type           = '類型';
$lang->program->pri            = '優先順序';
$lang->program->parent         = '父項目集';
$lang->program->exchangeRate   = '匯率';
$lang->program->openedBy       = '創建者';
$lang->program->openedDate     = '創建時間';
$lang->program->closedBy       = '由誰關閉';
$lang->program->closedDate     = '關閉日期';
$lang->program->canceledBy     = '由誰取消';
$lang->program->canceledDate   = '取消日期';
$lang->program->lastEditedDate = '最後編輯';
$lang->program->suspendedDate  = '暫停日期';
$lang->program->vision         = '界面';
$lang->program->team           = '團隊';
$lang->program->order          = '排序';
$lang->program->days           = '可用工作日';
$lang->program->acl            = '訪問控制';
$lang->program->groups         = '權限組';
$lang->program->users          = '用戶';
$lang->program->whitelist      = '白名單';
$lang->program->deleted        = '已刪除';
$lang->program->lifetime       = $lang->projectCommon . '周期';
$lang->program->output         = '輸出';
$lang->program->auth           = '權限控制';
$lang->program->path           = '路徑';
$lang->program->grade          = '層級';
$lang->program->realBegan      = '實際開始';
$lang->program->realEnd        = '實際完成';
$lang->program->version        = '版本';
$lang->program->parentVersion  = '父版本';
$lang->program->planDuration   = '計劃周期天數';
$lang->program->realDuration   = '實際周期天數';
$lang->program->openedVersion  = '創建版本';
$lang->program->lastEditedBy   = '最後編輯人';
$lang->program->lastEditedDate = '最後編輯日期';
$lang->program->childProgram   = '子項目集';
$lang->program->ignore         = '忽略';
$lang->program->other          = '更多狀態操作';

/* Actions. */
$lang->program->common                  = '項目集';
$lang->program->index                   = '項目集主頁';
$lang->program->create                  = '添加項目集';
$lang->program->createGuide             = "選擇{$lang->projectCommon}模板";
$lang->program->edit                    = '編輯項目集';
$lang->program->browse                  = '項目集列表';
$lang->program->kanbanAction            = '項目集看板';
$lang->program->view                    = '項目集詳情';
$lang->program->copy                    = '複製項目集';
$lang->program->product                 = "項目集{$lang->productCommon}列表";
$lang->program->project                 = "項目集{$lang->projectCommon}列表";
$lang->program->all                     = '所有項目集';
$lang->program->start                   = '啟動項目集';
$lang->program->finish                  = '完成項目集';
$lang->program->suspend                 = '掛起項目集';
$lang->program->delete                  = '刪除項目集';
$lang->program->close                   = '關閉項目集';
$lang->program->activate                = '激活項目集';
$lang->program->export                  = '導出';
$lang->program->stakeholder             = '干係人列表';
$lang->program->createStakeholder       = '添加干係人';
$lang->program->unlinkStakeholder       = '移除干係人';
$lang->program->batchUnlinkStakeholders = '批量移除干係人';
$lang->program->unlink                  = '移除';
$lang->program->updateOrder             = '排序';
$lang->program->unbindWhitelist         = '移除白名單';
$lang->program->importStakeholder       = '從父項目集導入';
$lang->program->manageMembers           = '項目集團隊';
$lang->program->confirmChangePRJUint    = "是否同步更新該項目集下子項目集和{$lang->projectCommon}的預算的單位？若確認更新,請填寫今日匯率。";
$lang->program->exRateNotNegative       = '『匯率』不能是負數。';
$lang->program->changePRJUnit           = "更新{$lang->projectCommon}預算單位";
$lang->program->showNotCurrentProjects  = "顯示非當前項目集的{$lang->projectCommon}信息";

$lang->program->progress         = "{$lang->projectCommon}進度";
$lang->program->progressAB       = '進度';
$lang->program->children         = '添加子項目集';
$lang->program->allInvest        = '項目集總投入';
$lang->program->teamCount        = '總人數';
$lang->program->longTime         = '長期';
$lang->program->moreProgram      = '更多項目集';
$lang->program->stakeholderType  = '干係人類型';
$lang->program->parentBudget     = '所屬項目集剩餘預算：';
$lang->program->isStakeholderKey = '關鍵干係人';
$lang->program->summary          = "本頁共 %d 個頂級項目集，%d 個獨立{$lang->projectCommon}。";

$lang->program->stakeholderTypeList['inside']  = '內部';
$lang->program->stakeholderTypeList['outside'] = '外部';

$lang->program->noProgram          = '暫時沒有項目集';
$lang->program->showClosed         = '顯示已關閉';
$lang->program->tips               = "選擇了父項目集，則可關聯該父項目集下的{$lang->productCommon}。如果{$lang->projectCommon}未選擇任何項目集，則系統會預設創建一個和該{$lang->projectCommon}同名的{$lang->productCommon}並關聯該{$lang->projectCommon}。";
$lang->program->confirmBatchUnlink = "您確定要批量移除這些干係人嗎？";

$lang->program->beginLessThanParent  = '項目集的開始日期小於了父項目集的開始日期：';
$lang->program->endGreatThanParent   = '項目集的完成日期大於了父項目集的完成日期：';
$lang->program->dateExceedParent     = '項目集的起止日期已超出父項目集的起止日期';
$lang->program->beginGreatEqualChild = "項目集的開始日期大於了子項目集或{$lang->projectCommon}的最小開始日期：";
$lang->program->endLessThanChild     = "項目集的完成日期小於了子項目集或{$lang->projectCommon}的最大完成日期：";

$lang->program->dateExceedChild    = "項目集的起止日期已不包含子項目集或{$lang->projectCommon}的日期範圍";
$lang->program->closeErrorMessage  = "存在子項目集或{$lang->projectCommon}為未關閉狀態";
$lang->program->hasChildren        = "該項目集有子項目集或{$lang->projectCommon}存在，不能刪除。";
$lang->program->hasProduct         = "該項目集有{$lang->productCommon}存在，不能刪除。";
$lang->program->confirmDelete      = '您確定要刪除“%s”項目集嗎？';
$lang->program->confirmUnlink      = '您確定要移除干係人嗎？';
$lang->program->readjustTime       = '重新調整項目集起止時間';
$lang->program->accessDenied       = '你無權訪問該項目集';
$lang->program->beyondParentBudget = '已超出所屬項目集的剩餘預算';
$lang->program->checkedProjects    = '已選擇%s項';
$lang->program->budgetOverrun      = '項目集的預算超出了父項目集的剩餘預算：';

/* ToolBar. */
$lang->program->createProduct    = '添加產品';
$lang->program->createProject    = '添加項目';

/* DTable columns of product view page. */
$lang->program->totalUnclosedStories = '需求未關閉';
$lang->program->closedStoryRate      = '需求完成率';
$lang->program->testCaseCoverage     = '用例覆蓋率';
$lang->program->totalActivatedBugs   = 'Bug激活';
$lang->program->fixedRate            = '修復率';
$lang->program->feedback             = '反饋';

$lang->program->tip = new stdclass();
$lang->program->tip->closed     = '該項目集已是關閉狀態，無須關閉。';
$lang->program->tip->notSuspend = '該項目集已關閉，不可進行掛起操作。';
$lang->program->tip->suspended  = '該項目集已是掛起狀態，無須掛起。';
$lang->program->tip->actived    = '該項目集已是激活狀態，無須激活。';
$lang->program->tip->notCreate  = '該項目集已關閉，不可進行添加子項目集的操作。';

$lang->program->endList[31]  = '一個月';
$lang->program->endList[93]  = '三個月';
$lang->program->endList[186] = '半年';
$lang->program->endList[365] = '一年';
$lang->program->endList[999] = '長期';

$lang->program->aclList['open']    = "公開（有項目集視圖權限，即可訪問）";
$lang->program->aclList['private'] = "私有（項目集負責人和干係人可訪問，干係人可後續維護）";

$lang->program->subAclList['open']    = "全部公開（有項目集視圖權限，即可訪問）";
$lang->program->subAclList['program'] = "項目集內公開（所有上級項目集負責人和干係人、本項目集負責人和干係人可訪問）";
$lang->program->subAclList['private'] = "私有（本項目集負責人和干係人可訪問，干係人可後續維護）";

$lang->program->subAcls['open']    = '全部公開';
$lang->program->subAcls['program'] = '項目集內公開';
$lang->program->subAcls['private'] = '私有';

$lang->program->authList['extend'] = "繼承 (取{$lang->projectCommon}權限與組織權限的並集)";
$lang->program->authList['reset']  = "重新定義 (只取{$lang->projectCommon}權限)";

$lang->program->statusList['wait']      = '未開始';
$lang->program->statusList['doing']     = '進行中';
$lang->program->statusList['suspended'] = '已掛起';
$lang->program->statusList['closed']    = '已關閉';

$lang->program->featureBar['browse']['all']       = '全部';
$lang->program->featureBar['browse']['unclosed']  = '未關閉';
$lang->program->featureBar['browse']['wait']      = '未開始';
$lang->program->featureBar['browse']['doing']     = '進行中';
$lang->program->featureBar['browse']['suspended'] = '已掛起';
$lang->program->featureBar['browse']['delayed']   = '已延期';
$lang->program->featureBar['browse']['closed']    = '已關閉';

$lang->program->featureBar['product']['all']      = '全部' . $lang->productCommon;
$lang->program->featureBar['product']['noclosed'] = '未關閉';
$lang->program->featureBar['product']['closed']   = '結束';

$lang->program->featureBar['project']['all']       = '全部';
$lang->program->featureBar['project']['unclosed']  = '未關閉';
$lang->program->featureBar['project']['wait']      = '未開始';
$lang->program->featureBar['project']['doing']     = '進行中';
$lang->program->featureBar['project']['suspended'] = '已掛起';
$lang->program->featureBar['project']['delayed']   = '已延期';
$lang->program->featureBar['project']['closed']    = '已關閉';

$lang->program->featureBar['productview']['all']      = '全部';
$lang->program->featureBar['productview']['unclosed'] = '未關閉';
$lang->program->featureBar['productview']['wait']     = '未開始';
$lang->program->featureBar['productview']['doing']    = '進行中';
$lang->program->featureBar['productview']['more']     = $lang->more;

$lang->program->moreSelects['suspended'] = '已掛起';
$lang->program->moreSelects['closed']    = '已關閉';

$lang->program->kanban = new stdclass();
$lang->program->kanban->common             = '項目集看板';
$lang->program->kanban->typeList['my']     = '我參與的項目集';
$lang->program->kanban->typeList['others'] = '其他項目集';

$lang->program->kanban->openProducts    = "未關閉的{$lang->productCommon}";
$lang->program->kanban->unexpiredPlans  = '未過期的計劃';
$lang->program->kanban->waitingProjects = '未開始的' . $lang->projectCommon;
$lang->program->kanban->doingProjects   = '進行中的' . $lang->projectCommon;
$lang->program->kanban->doingExecutions = '進行中的執行';
$lang->program->kanban->normalReleases  = '正常的發佈';

$lang->program->kanban->laneColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#FFC20E', '#00A78E', '#7FBB00', '#424BAC', '#C0E9FF', '#EC2761');

$lang->program->defaultProgram    = '預設項目集';
$lang->program->manDay            = '人天';
$lang->program->createdDate       = '創建時間';
$lang->program->totalStories      = '需求總數';
$lang->program->project           = '項目';
$lang->program->totalBugs         = 'Bug總數';
$lang->program->latestReleaseDate = '最新發佈時間';
$lang->program->latestRelease     = '最新發佈';
$lang->program->manageLine        = '維護產品綫';
$lang->program->checkedProducts   = '共選中&nbsp;<strong>%total%</strong>&nbsp;個產品。';

$lang->program->programBudget   = '項目集預算';
$lang->program->projectBudget   = '項目預算';
$lang->program->sumSubBudget    = '子集預算和';
$lang->program->exceededBudget  = '已超出預算';
$lang->program->remainingBudget = '項目集剩餘';
