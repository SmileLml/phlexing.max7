<?php
/* Actions. */
$lang->kanban->noCard              = '暫無卡片';
$lang->kanban->create              = '創建看板';
$lang->kanban->createSpace         = '創建空間';
$lang->kanban->editSpace           = '設置空間';
$lang->kanban->closeSpace          = '關閉空間';
$lang->kanban->deleteSpace         = '刪除空間';
$lang->kanban->activateSpace       = '激活空間';
$lang->kanban->edit                = '編輯看板';
$lang->kanban->view                = '查看看板';
$lang->kanban->activate            = '激活看板';
$lang->kanban->close               = '關閉看板';
$lang->kanban->delete              = '刪除看板';
$lang->kanban->createRegion        = '新增區域';
$lang->kanban->editRegion          = '編輯區域';
$lang->kanban->sortRegion          = '區域排序';
$lang->kanban->sortGroup           = '泳道組排序';
$lang->kanban->deleteRegion        = '刪除區域';
$lang->kanban->createLane          = '創建泳道';
$lang->kanban->editLane            = '泳道設置';
$lang->kanban->sortLane            = '泳道排序';
$lang->kanban->laneHeight          = '泳道高度';
$lang->kanban->setLaneHeight       = '設置泳道高度';
$lang->kanban->columnWidth         = '列寬度';
$lang->kanban->setColumnWidth      = '設置列寬度';
$lang->kanban->deleteLane          = '刪除泳道';
$lang->kanban->createColumn        = '創建看板列';
$lang->kanban->emptyDesc           = '暫無描述';
$lang->kanban->editColumn          = '編輯看板列';
$lang->kanban->sortColumn          = '看板列排序';
$lang->kanban->deleteColumn        = '刪除看板列';
$lang->kanban->createCard          = '創建卡片';
$lang->kanban->editCard            = '編輯卡片';
$lang->kanban->finished            = '已完成';
$lang->kanban->finishCard          = '完成卡片';
$lang->kanban->activateCard        = '激活卡片';
$lang->kanban->viewCard            = '查看卡片';
$lang->kanban->archiveCard         = '歸檔卡片';
$lang->kanban->sortCard            = '卡片排序';
$lang->kanban->copyCard            = '複製卡片';
$lang->kanban->moveCard            = '移動卡片';
$lang->kanban->cardColor           = '卡片顏色';
$lang->kanban->setCardColor        = '設置卡片顏色';
$lang->kanban->deleteCard          = '刪除卡片';
$lang->kanban->removeCard          = '移除卡片';
$lang->kanban->assigntoCard        = '指派';
$lang->kanban->setting             = '設置';
$lang->kanban->settingSpace        = '設置空間';
$lang->kanban->settingKanban       = '設置看板';
$lang->kanban->activateSpace       = '激活空間';
$lang->kanban->closeSpace          = '關閉空間';
$lang->kanban->deleteSpace         = '刪除空間';
$lang->kanban->enableArchived      = '設置歸檔功能';
$lang->kanban->archive             = '歸檔功能';
$lang->kanban->performable         = '設置進度管理';
$lang->kanban->manageProgress      = '進度管理';
$lang->kanban->splitColumn         = '新增子看板列';
$lang->kanban->createColumnOnLeft  = '左側新增看板列';
$lang->kanban->createColumnOnRight = '右側新增看板列';
$lang->kanban->copyColumn          = '複製看板列';
$lang->kanban->archiveColumn       = '歸檔看板列';
$lang->kanban->spaceCommon         = '看板空間';
$lang->kanban->styleCommon         = '區域結構和樣式';
$lang->kanban->copy                = '複製';
$lang->kanban->custom              = '自定義';
$lang->kanban->archived            = '已歸檔';
$lang->kanban->viewArchivedCard    = '查看已歸檔卡片';
$lang->kanban->viewArchivedColumn  = '查看已歸檔列';
$lang->kanban->archivedColumn      = '已歸檔的看板列';
$lang->kanban->archivedCard        = '已歸檔的卡片';
$lang->kanban->restoreColumn       = '還原看板列';
$lang->kanban->restoreCard         = '還原卡片';
$lang->kanban->restore             = '還原';
$lang->kanban->child               = '子';
$lang->kanban->batchCreateCard     = '批量創建卡片';
$lang->kanban->import              = '導入功能';
$lang->kanban->importAB            = '導入';
$lang->kanban->showClosed          = '顯示已關閉';
$lang->kanban->importCard          = '轉入卡片';
$lang->kanban->importPlan          = '計劃';
$lang->kanban->importRelease       = '發佈';
$lang->kanban->importExecution     = $lang->execution->common;
$lang->kanban->importBuild         = '構建';
$lang->kanban->importTicket        = '工單';
$lang->kanban->allKanban           = '所有看板';
$lang->kanban->allProjects         = '所有' . $lang->projectCommon;
$lang->kanban->allProducts         = "所有{$lang->productCommon}";
$lang->kanban->mine                = '我負責';
$lang->kanban->alignment           = '列標題對齊方式';
$lang->kanban->allUsers            = '加載所有用戶';
$lang->kanban->colWidth            = '列寬';
$lang->kanban->minColWidth         = '最小列寬';
$lang->kanban->maxColWidth         = '最大列寬';

/* Fields. */
$lang->kanban->space          = '所屬空間';
$lang->kanban->name           = '看板名稱';
$lang->kanban->archived       = '歸檔功能';
$lang->kanban->owner          = '負責人';
$lang->kanban->team           = '團隊';
$lang->kanban->desc           = '看板描述';
$lang->kanban->acl            = '訪問控制';
$lang->kanban->whitelist      = '白名單';
$lang->kanban->status         = '狀態';
$lang->kanban->createdBy      = '由誰創建';
$lang->kanban->createdDate    = '創建日期';
$lang->kanban->lastEditedBy   = '最後修改';
$lang->kanban->lastEditedDate = '最後修改日期';
$lang->kanban->closed         = '已關閉';
$lang->kanban->closedBy       = '由誰關閉';
$lang->kanban->closedDate     = '關閉日期';
$lang->kanban->empty          = '暫時沒有看板';
$lang->kanban->teamSumCount   = '共%s人';
$lang->kanban->cardsCount     = '共%s張卡片';
$lang->kanban->cardCount      = '卡片數量';
$lang->kanban->activatedBy    = '由誰激活';
$lang->kanban->activatedDate  = '激活日期';

$lang->kanban->createColumnOnLeft  = '在左側添加看板列';
$lang->kanban->createColumnOnRight = '在右側添加看板列';

$lang->kanban->accessDenied        = '您無權訪問該看板';
$lang->kanban->confirmDeleteSpace  = '您確認要刪除該空間嗎？';
$lang->kanban->confirmDeleteKanban = '您確認要刪除該看板嗎？';
$lang->kanban->cardCountTip        = '請輸入卡片數量';

$lang->kanban->selectedKanban  = '請選擇看板';
$lang->kanban->selectedProduct = "請選擇{$lang->productCommon}";
$lang->kanban->selectedProject = '請選擇' . $lang->projectCommon;
$lang->kanban->selectedLane    = '目標泳道';

$lang->kanban->aclGroup['open']    = '公開';
$lang->kanban->aclGroup['private'] = '私有';
$lang->kanban->aclGroup['extend']  = '繼承空間';

$lang->kanban->aclList['extend']  = '繼承空間訪問權限（能訪問當前空間，即可訪問）';
$lang->kanban->aclList['private'] = '私有（看板團隊成員、白名單、空間負責人可訪問）';

$lang->kanban->archiveList['1'] = '啟用';
$lang->kanban->archiveList['0'] = '不啟用';

$lang->kanban->enableList['1'] = '啟用';
$lang->kanban->enableList['0'] = '不啟用';

$lang->kanban->alignmentList['center'] = '居中';
$lang->kanban->alignmentList['left']   = '居左';

$lang->kanban->type = array();
$lang->kanban->type['all']         = "綜合看板";
$lang->kanban->type['epic']        = "{$lang->ERCommon}看板";
$lang->kanban->type['requirement'] = "{$lang->URCommon}看板";
$lang->kanban->type['parentStory'] = "父{$lang->SRCommon}看板";
$lang->kanban->type['story']       = "{$lang->SRCommon}看板";
$lang->kanban->type['task']        = "任務看板";
$lang->kanban->type['bug']         = "Bug看板";

$lang->kanban->group = new stdclass();

$lang->kanban->group->all = array();
$lang->kanban->group->story = array();
$lang->kanban->group->story['default']    = "預設分組";
$lang->kanban->group->story['pri']        = "需求優先順序";
$lang->kanban->group->story['category']   = "需求類別";
$lang->kanban->group->story['module']     = "需求模組";
$lang->kanban->group->story['source']     = "需求來源";
$lang->kanban->group->story['assignedTo'] = "指派人員";

$lang->kanban->group->epic        = $lang->kanban->group->story;
$lang->kanban->group->requirement = $lang->kanban->group->story;
$lang->kanban->group->parentStory = $lang->kanban->group->story;

$lang->kanban->group->task = array();
$lang->kanban->group->task['default']    = "預設分組";
$lang->kanban->group->task['pri']        = "任務優先順序";
$lang->kanban->group->task['type']       = "任務類型";
$lang->kanban->group->task['module']     = "任務所屬模組";
$lang->kanban->group->task['assignedTo'] = "指派人員";
$lang->kanban->group->task['story']      = "{$lang->SRCommon}";

$lang->kanban->group->bug = array();
$lang->kanban->group->bug['default']    = "預設分組";
$lang->kanban->group->bug['pri']        = "Bug優先順序";
$lang->kanban->group->bug['severity']   = "Bug嚴重程度";
$lang->kanban->group->bug['module']     = "Bug模組";
$lang->kanban->group->bug['type']       = "Bug類型";
$lang->kanban->group->bug['assignedTo'] = "指派人員";

$lang->kanban->WIP                = 'WIP';
$lang->kanban->setWIP             = '在製品設置';
$lang->kanban->WIPStatus          = '在製品狀態';
$lang->kanban->WIPStage           = '在製品階段';
$lang->kanban->WIPType            = '在製品類型';
$lang->kanban->WIPCount           = '在製品數量';
$lang->kanban->noLimit            = '不限制∞';
$lang->kanban->setLane            = '泳道設置';
$lang->kanban->editLaneName       = '修改泳道名';
$lang->kanban->laneName           = '泳道名稱';
$lang->kanban->editLaneColor      = '設置背景色';
$lang->kanban->laneColor          = '泳道顏色';
$lang->kanban->setColumn          = '看板列設置';
$lang->kanban->columnName         = '看板列名稱';
$lang->kanban->columnColor        = '看板列顏色';
$lang->kanban->moveUp             = '泳道上移';
$lang->kanban->moveDown           = '泳道下移';
$lang->kanban->laneGroup          = '泳道分組';
$lang->kanban->cardsSort          = '卡片排序';
$lang->kanban->more               = '更多';
$lang->kanban->moreAction         = '更多操作';
$lang->kanban->noGroup            = '無';
$lang->kanban->limitExceeded      = '超出在製品限制';
$lang->kanban->fullScreen         = '全屏';
$lang->kanban->my                 = '我的看板';
$lang->kanban->other              = '其他';
$lang->kanban->showWIP            = '顯示在製品限制';
$lang->kanban->copyTitle          = '請選擇一個看板來複制';
$lang->kanban->copyNoKanban       = '沒有可用的看板來複制';
$lang->kanban->cancelCopy         = '取消複製';
$lang->kanban->copyContent        = '複製內容';

$lang->kanban->error = new stdclass();
$lang->kanban->error->mustBeInt         = '在製品數量必須是正整數。';
$lang->kanban->error->parentLimitNote   = '父列的在製品數量不能小於子列在製品數量之和';
$lang->kanban->error->childLimitNote    = '子列在製品數量之和不能大於父列的在製品數量';
$lang->kanban->error->importObjNotEmpty = '請至少選擇一個導入對象';
$lang->kanban->error->childLimitEmpty   = '請先設置子列的在製品數量後再操作';

$lang->kanban->orderList['id_asc']          = '需求ID正序';
$lang->kanban->orderList['id_desc']         = '需求ID倒序';
$lang->kanban->orderList['pri_asc']         = '需求優先順序正序';
$lang->kanban->orderList['pri_desc']        = '需求優先順序倒序';
$lang->kanban->orderList['stage_asc']       = '需求階段正序';
$lang->kanban->orderList['stage_desc']      = '需求階段倒序';
$lang->kanban->orderList['assignedTo_desc'] = '指派給';

$lang->kanban->importList = array();
$lang->kanban->importList['on']  = '啟用導入功能（可以導入自己有權查看的內容作為卡片）';
$lang->kanban->importList['off'] = '不啟用導入功能';

$lang->kanban->importObjectList = array();
$lang->kanban->importObjectList['plans']      = '計劃';
$lang->kanban->importObjectList['releases']   = '發佈';
$lang->kanban->importObjectList['builds']     = '構建';
$lang->kanban->importObjectList['executions'] = $lang->execution->common;
if($this->config->edition != 'open') $lang->kanban->importObjectList['tickets'] = '工單';
$lang->kanban->importObjectList['cards']      = '其他看板卡片';

$lang->kanban->showWIPList[1] = '顯示';
$lang->kanban->showWIPList[0] = '不顯示';

$lang->kanban->defaultColumn = array();
$lang->kanban->defaultColumn['wait']   = '未開始';
$lang->kanban->defaultColumn['doing']  = '進行中';
$lang->kanban->defaultColumn['done']   = '已完成';
$lang->kanban->defaultColumn['closed'] = '已關閉';

$lang->kanban->laneTypeList = array();
$lang->kanban->laneTypeList['parentStory'] = "父" . $lang->SRCommon;
$lang->kanban->laneTypeList['story']       = $lang->SRCommon;
$lang->kanban->laneTypeList['bug']         = 'Bug';
$lang->kanban->laneTypeList['task']        = '任務';

$lang->kanban->ERURColumn = array();
$lang->kanban->ERURColumn['wait']       = '未開始';
$lang->kanban->ERURColumn['planned']    = '已計劃';
$lang->kanban->ERURColumn['projected']  = '已立項';
$lang->kanban->ERURColumn['developing'] = '研發中';
$lang->kanban->ERURColumn['delivering'] = '交付中';
$lang->kanban->ERURColumn['delivered']  = '已交付';
$lang->kanban->ERURColumn['closed']     = '已關閉';

$lang->kanban->storyColumn = array();
$lang->kanban->storyColumn['backlog']    = 'Backlog';
$lang->kanban->storyColumn['ready']      = '準備好';
$lang->kanban->storyColumn['design']     = '設計';
$lang->kanban->storyColumn['designing']  = '設計中';
$lang->kanban->storyColumn['designed']   = '設計完畢';
$lang->kanban->storyColumn['develop']    = '開發';
$lang->kanban->storyColumn['developing'] = '進行中';
$lang->kanban->storyColumn['developed']  = '完成';
$lang->kanban->storyColumn['test']       = '測試';
$lang->kanban->storyColumn['testing']    = '進行中';
$lang->kanban->storyColumn['tested']     = '完成';
$lang->kanban->storyColumn['verified']   = '已驗收';
$lang->kanban->storyColumn['rejected']   = '驗收失敗';
$lang->kanban->storyColumn['released']   = '已發佈';
$lang->kanban->storyColumn['closed']     = '已關閉';

$lang->kanban->bugColumn = array();
$lang->kanban->bugColumn['unconfirmed'] = '待確認';
$lang->kanban->bugColumn['confirmed']   = '已確認';
$lang->kanban->bugColumn['resolving']   = '解決中';
$lang->kanban->bugColumn['fixing']      = '進行中';
$lang->kanban->bugColumn['fixed']       = '完成';
$lang->kanban->bugColumn['test']        = '測試';
$lang->kanban->bugColumn['testing']     = '測試中';
$lang->kanban->bugColumn['tested']      = '測試完畢';
$lang->kanban->bugColumn['closed']      = '已關閉';

$lang->kanban->taskColumn = array();
$lang->kanban->taskColumn['wait']       = '未開始';
$lang->kanban->taskColumn['develop']    = '開發';
$lang->kanban->taskColumn['developing'] = '研發中';
$lang->kanban->taskColumn['developed']  = '研發完畢';
$lang->kanban->taskColumn['pause']      = '已暫停';
$lang->kanban->taskColumn['canceled']   = '已取消';
$lang->kanban->taskColumn['closed']     = '已關閉';

$lang->kanban->copyContentList = array();
$lang->kanban->copyContentList['basicInfo'] = '基本信息';
$lang->kanban->copyContentList['region']    = '列和泳道';

$lang->kanbanspace = new stdclass();
$lang->kanbanspace->common         = '看板空間';
$lang->kanbanspace->name           = '空間名稱';
$lang->kanbanspace->owner          = '負責人';
$lang->kanbanspace->team           = '團隊';
$lang->kanbanspace->desc           = '空間描述';
$lang->kanbanspace->acl            = '訪問控制';
$lang->kanbanspace->whitelist      = '白名單';
$lang->kanbanspace->status         = '狀態';
$lang->kanbanspace->createdBy      = '由誰創建';
$lang->kanbanspace->createdDate    = '創建日期';
$lang->kanbanspace->lastEditedBy   = '最後修改';
$lang->kanbanspace->lastEditedDate = '最後修改日期';
$lang->kanbanspace->closedBy       = '由誰關閉';
$lang->kanbanspace->closedDate     = '關閉日期';
$lang->kanbanspace->type           = '空間類型';
$lang->kanbanspace->activatedBy    = '由誰激活';
$lang->kanbanspace->activatedDate  = '激活日期';

$lang->kanbanspace->empty = '暫時沒有空間';

$lang->kanbanspace->aclList['open']    = '公開（有看板空間視圖權限即可訪問）';
$lang->kanbanspace->aclList['private'] = '私有（只有看板空間負責人、團隊成員、白名單可訪問）';

$lang->kanban->featureBar['space']['involved']    = '我參與的';
$lang->kanban->featureBar['space']['cooperation'] = '協作空間';
$lang->kanban->featureBar['space']['public']      = '公共空間';
$lang->kanban->featureBar['space']['private']     = '私人空間';

$lang->kanbanspace->typeList['cooperation'] = '協作空間';
$lang->kanbanspace->typeList['public']      = '公共空間';
$lang->kanbanspace->typeList['private']     = '私人空間';

$lang->kanbancolumn = new stdclass();
$lang->kanbancolumn->name       = $lang->kanban->columnName;
$lang->kanbancolumn->limit      = $lang->kanban->WIPCount;
$lang->kanbancolumn->color      = '看板列顏色';
$lang->kanbancolumn->childName  = '子列名稱';
$lang->kanbancolumn->childColor = '子狀態顏色';
$lang->kanbancolumn->empty      = '暫時沒有看板列';
$lang->kanbancolumn->common     = '看板列';

$lang->kanbancolumn->confirmArchive     = '您確認歸檔該列嗎？歸檔列後，該列及列上的卡片將被隱藏，您可以在區域-已歸檔中查看已歸檔的列。';
$lang->kanbancolumn->confirmDelete      = '您確認刪除該列嗎？刪除列後不可恢復，列上的卡片也會消失。';
$lang->kanbancolumn->confirmDeleteChild = '您確認刪除該列嗎？刪除列後，該列中所有卡片會被移動到兄弟列或父列。';
$lang->kanbancolumn->confirmRestore     = '您確定要還原該看板列嗎？還原後，該看板列將回到之前的位置。';
$lang->kanbancolumn->fixedTip           = '固定列寬不能小於264px。';
$lang->kanbancolumn->autoTip            = '最小列寬不能小於264px。';

$lang->kanbanlane = new stdclass();
$lang->kanbanlane->name         = $lang->kanban->laneName;
$lang->kanbanlane->common       = '泳道';
$lang->kanbanlane->default      = '預設泳道';
$lang->kanbanlane->column       = '泳道看板列';
$lang->kanbanlane->otherlane    = '選擇共享看板列的泳道';
$lang->kanbanlane->color        = '泳道顏色';
$lang->kanbanlane->WIPType      = '泳道在製品類型';
$lang->kanbanlane->heightByCard = '自適應（根據卡片高度自適應）';

$lang->kanbanlane->confirmDelete    = '您確認刪除該泳道嗎？刪除泳道後，該泳道中所有數據（列、卡片）也會被刪除。';
$lang->kanbanlane->confirmDeleteTip = '您確認刪除該泳道嗎？刪除泳道後，該泳道中所有的%s將被隱藏。';

$lang->kanbanlane->modeList['sameAsOther'] = '與其他泳道使用相同看板列';
$lang->kanbanlane->modeList['independent'] = '採用獨立的看板列';

$lang->kanbanlane->heightTypeList['auto']   = "自適應<span class='tip'>（根據卡片高度和泳道名稱高度自適應）</span>";
$lang->kanbanlane->heightTypeList['custom'] = "自定義<span class='tip'>（根據卡片數量自定義泳道高度）</span>";

$lang->kanbancolumn->fluidBoardList['0'] = "固定列寬";
$lang->kanbancolumn->fluidBoardList['1'] = "自適應列寬";

$lang->kanbanlane->error = new stdclass();
$lang->kanbanlane->error->mustBeInt      = '卡片數量必須是 3~100 的正整數。';
$lang->kanbanlane->error->hasExist       = '該泳道已存在';
$lang->kanbanlane->error->emptyOtherLane = '共享看板列的泳道不能為空！';

$lang->kanbanregion = new stdclass();
$lang->kanbanregion->name    = '區域名稱';
$lang->kanbanregion->default = '預設區域';
$lang->kanbanregion->style   = '區域樣式';
$lang->kanbanregion->all     = '所有區域';

$lang->kanbanregion->confirmDelete = '您確認刪除該區域嗎？刪除該區域後，該區域中所有數據將會被刪除。';

$lang->kanbancard = new stdclass();
$lang->kanbancard->create  = '創建卡片';
$lang->kanbancard->edit    = '編輯卡片';
$lang->kanbancard->view    = '查看卡片';
$lang->kanbancard->archive = '歸檔';
$lang->kanbancard->assign  = '指派';
$lang->kanbancard->copy    = '複製';
$lang->kanbancard->delete  = '刪除';

$lang->kanbancard->name            = '卡片名稱';
$lang->kanbancard->legendBasicInfo = '基本信息';
$lang->kanbancard->legendLifeTime  = '卡片的一生';
$lang->kanbancard->legendDesc      = '卡片描述';
$lang->kanbancard->space           = '所屬空間';
$lang->kanbancard->region          = '所屬區域';
$lang->kanbancard->kanban          = '所屬看板';
$lang->kanbancard->lane            = '所屬泳道';
$lang->kanbancard->column          = '所屬看板列';
$lang->kanbancard->assignedTo      = '指派給';
$lang->kanbancard->beginAndEnd     = '起止日期';
$lang->kanbancard->begin           = '預計開始';
$lang->kanbancard->end             = '截止日期';
$lang->kanbancard->pri             = '優先順序';
$lang->kanbancard->status          = '狀態';
$lang->kanbancard->progress        = '進度';
$lang->kanbancard->desc            = '描述';
$lang->kanbancard->estimate        = '預計';
$lang->kanbancard->createdBy       = '由誰創建';
$lang->kanbancard->createdDate     = '由誰創建';
$lang->kanbancard->lastEditedBy    = '由誰編輯';
$lang->kanbancard->lastEditedDate  = '最後編輯時間';
$lang->kanbancard->archivedBy      = '由誰歸檔';
$lang->kanbancard->archivedDate    = '歸檔時間';
$lang->kanbancard->lblHour         = 'h';
$lang->kanbancard->noAssigned      = '未指派';
$lang->kanbancard->deadlineAB      = '截止';
$lang->kanbancard->beginAB         = '開始';
$lang->kanbancard->to              = '~';
$lang->kanbancard->archived        = '已歸檔';
$lang->kanbancard->empty           = '暫時沒有卡片';
$lang->kanbancard->ditto           = '同上';

$lang->kanbancard->confirmArchive    = '您確認歸檔該卡片嗎？歸檔卡片後，該卡片將從列中隱藏，您可以在區域-已歸檔中查看。';
$lang->kanbancard->confirmDelete     = '您確認刪除該卡片嗎？刪除卡片後，該卡片將從看板中刪除，您只能通過系統資源回收筒查看。';
$lang->kanbancard->confirmRestore    = '您確定要還原該卡片嗎？';
$lang->kanbancard->confirmRestoreTip = '該卡片所屬的看板列已被歸檔或刪除，請先還原“%s”看板列。';
$lang->kanbancard->confirmRemove     = '您確認移除該卡片嗎？移除卡片後，源數據不會受到影響，卡片將從看板中移除且無法恢復。';

$lang->kanbancard->priList[1] = 1;
$lang->kanbancard->priList[2] = 2;
$lang->kanbancard->priList[3] = 3;
$lang->kanbancard->priList[4] = 4;

$lang->kanbancard->colorList['#fff']    = '預設';
$lang->kanbancard->colorList['#937c5a'] = '阻塞';
$lang->kanbancard->colorList['#fc5959'] = '警告';
$lang->kanbancard->colorList['#ff9f46'] = '加急';

$lang->kanbancard->error = new stdclass();
$lang->kanbancard->error->recordMinus     = '預計不能為負數!';
$lang->kanbancard->error->endSmall        = '"截止日期"不能小於"預計開始"!';
$lang->kanbancard->error->progressIllegal = '請輸入正確的進度';
