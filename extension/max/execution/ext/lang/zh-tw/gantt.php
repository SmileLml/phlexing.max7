<?php
$lang->execution->createRelation      = '添加任務關係';
$lang->execution->editRelation        = '維護任務關係';
$lang->execution->batchEditRelation   = '批量維護任務關係';
$lang->execution->maintainRelation    = '維護任務關係';
$lang->execution->deleteRelation      = '刪除任務關係';
$lang->execution->batchDeleteRelation = '批量刪除任務關係';
$lang->execution->viewRelation        = '瀏覽任務關係';
$lang->execution->maintain            = '維護';

$lang->execution->featureBar['relation']['all'] = '全部';

$lang->execution->relation = new stdClass();
$lang->execution->relation->typeList = array();
$lang->execution->relation->typeList['FS'] = '完成開始（FS）';
$lang->execution->relation->typeList['FF'] = '完成完成（FF）';
$lang->execution->relation->typeList['SF'] = '開始完成（SF）';
$lang->execution->relation->typeList['SS'] = '開始開始（SS）';

$lang->execution->relation->typeHintList = array();
$lang->execution->relation->typeHintList['FS'] = '前置任務計劃完成不能晚于後置任務計劃開始';
$lang->execution->relation->typeHintList['FF'] = '前置任務計劃完成不能晚于後置任務計劃完成';
$lang->execution->relation->typeHintList['SF'] = '前置任務計劃開始不能晚于後置任務計劃完成';
$lang->execution->relation->typeHintList['SS'] = '前置任務計劃開始不能晚于後置任務計劃開始';

$lang->execution->ganttchart   = '甘特圖';
$lang->execution->ganttSetting = '顯示設置';
$lang->execution->ganttEdit    = '甘特圖編輯';

$lang->execution->gantt->common    = '甘特圖';
$lang->execution->gantt->id        = '編號';
$lang->execution->gantt->pretask   = '前置任務';
$lang->execution->gantt->condition = '條件動作';
$lang->execution->gantt->task      = '後置任務';
$lang->execution->gantt->action    = '動作';
$lang->execution->gantt->type      = '關係類型';

$lang->execution->gantt->createRelationOfTasks    = '創建任務關係';
$lang->execution->gantt->newCreateRelationOfTasks = '新增任務關係';
$lang->execution->gantt->editRelationOfTasks      = '維護任務關係';
$lang->execution->gantt->relationOfTasks          = '查看任務關係';
$lang->execution->gantt->relation                 = '任務關係';
$lang->execution->gantt->showCriticalPath         = '顯示關鍵路徑';
$lang->execution->gantt->hideCriticalPath         = '隱藏關鍵路徑';
$lang->execution->gantt->fullScreen               = '全屏';

$lang->execution->gantt->zooming['day']   = '天';
$lang->execution->gantt->zooming['week']  = '周';
$lang->execution->gantt->zooming['month'] = '月';

$lang->execution->gantt->assignTo  = '指派給';
$lang->execution->gantt->duration  = '可用工日';
$lang->execution->gantt->comp      = '進度';
$lang->execution->gantt->startDate = '開始日期';
$lang->execution->gantt->endDate   = '結束日期';
$lang->execution->gantt->days      = ' 天';
$lang->execution->gantt->format    = '查看格式';

$lang->execution->gantt->preTaskStatus['']      = '';
$lang->execution->gantt->preTaskStatus['end']   = '完成後';
$lang->execution->gantt->preTaskStatus['begin'] = '開始後';

$lang->execution->gantt->taskActions[''] = '';
$lang->execution->gantt->taskActions['begin'] = '才能開始';
$lang->execution->gantt->taskActions['end']   = '才能完成';

$lang->execution->gantt->browseType['type']       = '按任務類型分組';
$lang->execution->gantt->browseType['module']     = '按模組分組';
$lang->execution->gantt->browseType['assignedTo'] = '按指派給分組';
$lang->execution->gantt->browseType['story']      = "按{$lang->SRCommon}分組";

$lang->execution->gantt->confirmDelete      = '確認要刪除此任務關係嗎？';
$lang->execution->gantt->confirmBatchDelete = '您確認要批量刪除這些任務關係嗎？';
$lang->execution->gantt->tmpNotWrite        = '不可寫';

$lang->execution->gantt->showList[0] = '不顯示';
$lang->execution->gantt->showList[1] = '顯示';

$lang->execution->gantt->warning                 = new stdclass();
$lang->execution->gantt->warning->noEditSame     = "前置任務與後置任務不能相同！";
$lang->execution->gantt->warning->noEditRepeat   = "編號%s與任務關係【%s】重複！";
$lang->execution->gantt->warning->noEditContrary = "所選任務關係與已有任務關係之間有矛盾！";
$lang->execution->gantt->warning->noRepeat       = "編號%s任務關係已存在！";
$lang->execution->gantt->warning->noContrary     = "編號%s與任務關係【%s】之間有矛盾！";
$lang->execution->gantt->warning->noNewSame      = "新增的編號%s前後任務不能相同！";
$lang->execution->gantt->warning->noNewRepeat    = "新增的編號%s與新增的編號%s任務關係之間重複！";
$lang->execution->gantt->warning->noNewContrary  = "新增的編號%s與新增的編號%s任務關係之間有矛盾！";
$lang->execution->gantt->warning->noCreateLink   = "所選任務之間的任務關係已存在，無法重複創建！";
$lang->execution->gantt->warning->hasConflict    = "前後置任務所在的路徑上存在依賴關係衝突，請重新建立任務依賴關係。";

$lang->execution->error = new stdClass();
$lang->execution->error->wrongGanttRelation       = '只能為任務創建依賴關係。';
$lang->execution->error->wrongGanttRelationSource = '您選擇的第一個對象不是任務。';
$lang->execution->error->wrongGanttRelationTarget = '您選擇的第二個對象不是任務。';
$lang->execution->error->parentTaskRelation       = '為了簡化任務關係複雜度，父級任務不再支持任務關係。';
$lang->execution->error->preTaskIsParent          = '前置任務為父級任務，不支持建立任務關係。';
$lang->execution->error->afterTaskIsParent        = '後置任務為父級任務，不支持建立任務關係。';
$lang->execution->error->closedLoop               = '該任務關係與已有任務關係之間有矛盾。';
$lang->execution->error->multiplePreTask          = '後置任務不允許有多個前置任務。';
$lang->execution->error->wrongTaskStatus          = '已完成、已取消和已關閉的任務不能建立任務關係。';
$lang->execution->error->wrongKanbanTasks         = '看板下的任務不能建立任務關係。';

$lang->execution->ganttCustom['id']         = '編號ID';
$lang->execution->ganttCustom['branch']     = '分支';
$lang->execution->ganttCustom['assignedTo'] = '指派給';
$lang->execution->ganttCustom['progress']   = '進度';
$lang->execution->ganttCustom['begin']      = '計劃開始';
$lang->execution->ganttCustom['realBegan']  = '實際開始';
$lang->execution->ganttCustom['deadline']   = '計劃完成';
$lang->execution->ganttCustom['realEnd']    = '實際完成';
$lang->execution->ganttCustom['duration']   = '可用工日';
$lang->execution->ganttCustom['estimate']   = '預計工時';
$lang->execution->ganttCustom['consumed']   = '消耗工時';
$lang->execution->ganttCustom['left']       = '剩餘工時';
$lang->execution->ganttCustom['delay']      = '是否延期';
$lang->execution->ganttCustom['delayDays']  = '延期天數';
$lang->execution->ganttCustom['openedBy']   = '創建者';
$lang->execution->ganttCustom['finishedBy'] = '完成者';
