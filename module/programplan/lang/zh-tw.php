<?php
/**
 * The programplan module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: zh-tw.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->programplan->common        = $lang->projectCommon . '階段';
$lang->programplan->browse        = '瀏覽甘特圖';
$lang->programplan->gantt         = '甘特圖';
$lang->programplan->ganttEdit     = '甘特圖編輯';
$lang->programplan->list          = '階段列表';
$lang->programplan->create        = '設置階段';
$lang->programplan->edit          = '編輯階段';
$lang->programplan->delete        = '刪除階段';
$lang->programplan->close         = '關閉階段';
$lang->programplan->activate      = '激活階段';
$lang->programplan->createSubPlan = '創建子階段';
$lang->programplan->subPlanManage = '子階段的管理方法';
$lang->programplan->submit        = '提交評審';
$lang->programplan->idAB          = '序號';

$lang->programplan->parent           = '父階段';
$lang->programplan->emptyParent      = '無';
$lang->programplan->name             = '階段名稱';
$lang->programplan->code             = '代號';
$lang->programplan->status           = '階段進度';
$lang->programplan->PM               = '階段負責人';
$lang->programplan->PMAB             = '負責人';
$lang->programplan->acl              = '訪問控制';
$lang->programplan->subStageName     = '子階段名稱';
$lang->programplan->percent          = '工作量占比';
$lang->programplan->percentAB        = '工作量占比';
$lang->programplan->planPercent      = '工作量';
$lang->programplan->attribute        = '階段類型';
$lang->programplan->milestone        = '里程碑';
$lang->programplan->taskProgress     = '任務進度';
$lang->programplan->task             = '任務';
$lang->programplan->begin            = '計劃開始';
$lang->programplan->end              = '計劃完成';
$lang->programplan->realBegan        = '實際開始';
$lang->programplan->realEnd          = '實際完成';
$lang->programplan->ac               = '實際花費';
$lang->programplan->sv               = '進度偏差率';
$lang->programplan->cv               = '成本偏差率';
$lang->programplan->planDateRange    = '計划起始日期';
$lang->programplan->realDateRange    = '實際起始日期';
$lang->programplan->output           = '輸出';
$lang->programplan->openedBy         = '由誰創建';
$lang->programplan->openedDate       = '創建日期';
$lang->programplan->editedBy         = '由誰編輯';
$lang->programplan->editedDate       = '編輯日期';
$lang->programplan->duration         = '可用工日';
$lang->programplan->estimate         = '工時';
$lang->programplan->consumed         = '消耗工時';
$lang->programplan->version          = '版本號';
$lang->programplan->full             = '全屏';
$lang->programplan->today            = '今天';
$lang->programplan->exporting        = '導出';
$lang->programplan->exportFail       = '導出失敗';
$lang->programplan->hideCriticalPath = '隱藏關鍵路徑';
$lang->programplan->showCriticalPath = '顯示關鍵路徑';
$lang->programplan->delay            = '是否延期';
$lang->programplan->delayDays        = '延期天數';
$lang->programplan->settingGantt     = '設置甘特圖';
$lang->programplan->viewSetting      = '顯示設置';
$lang->programplan->desc             = '描述';
$lang->programplan->wait             = '待提交';
$lang->programplan->enabled          = '啟用階段';
$lang->programplan->point            = '評審點';
$lang->programplan->progress         = '進度';

$lang->programplan->relation            = '維護任務關係';
$lang->programplan->setTaskRelation     = '維護任務關係';
$lang->programplan->viewTaskRelation    = '瀏覽任務關係';
$lang->programplan->createRelation      = '添加任務關係';
$lang->programplan->editRelation        = '維護任務關係';
$lang->programplan->batchEditRelation   = '批量維護任務關係';
$lang->programplan->deleteRelation      = '刪除任務關係';
$lang->programplan->batchDeleteRelation = '批量刪除任務關係';

$lang->programplan->errorBegin       = "階段的開始時間不能小於所屬{$lang->projectCommon}的開始時間%s";
$lang->programplan->errorEnd         = "階段的結束時間不能大於所屬{$lang->projectCommon}的結束時間%s";
$lang->programplan->emptyBegin       = '『計劃開始』日期不能為空';
$lang->programplan->emptyEnd         = '『計劃完成』日期不能為空';
$lang->programplan->checkBegin       = '『計劃開始』應當為合法的日期';
$lang->programplan->checkEnd         = '『計劃完成』應當為合法的日期';
$lang->programplan->methodTip        = "您可以在該階段下選擇繼續創建階段或創建{$lang->executionCommon}/看板進行工作。{$lang->executionCommon}/看板不支持繼續拆分。";
$lang->programplan->cropStageTip     = "已經開始了的階段不能再裁剪";
$lang->programplan->childEnabledTip  = "子階段啟用狀態跟隨父階段";
$lang->programplan->reviewedPointTip = "該評審點已提交評審不能再操作";
$lang->programplan->typeTip          = "第一層級僅支持創建階段，同一階段下可以創建階段或創建迭代/看板。迭代/看板不支持繼續拆分。";

$lang->programplan->milestoneList[1] = '是';
$lang->programplan->milestoneList[0] = '否';

$lang->programplan->delayList = array();
$lang->programplan->delayList[1] = '是';
$lang->programplan->delayList[0] = '否';

$lang->programplan->enabledList = array();
$lang->programplan->enabledList['on']  = '啟用';
$lang->programplan->enabledList['off'] = '停用';

$lang->programplan->typeList = array();
$lang->programplan->typeList['stage']     = '階段';
$lang->programplan->typeList['agileplus'] = $lang->executionCommon . '/看板';

$lang->programplan->noData            = '暫無數據。';
$lang->programplan->children          = '二級計劃';
$lang->programplan->childrenAB        = '子';
$lang->programplan->confirmDelete     = '確定要刪除當前計劃嗎？';
$lang->programplan->confirmChangeAttr = '修改後子階段的類型將根據父階段類型同步調整為“%s”，是否保存？';
$lang->programplan->noticeChangeAttr  = '修改後子階段的類型將根據父階段類型同步調整為“%s”';
$lang->programplan->workloadTips      = '子階段工作量占比按百分百的比例進行拆分';

$lang->programplan->stageCustom['date'] = '顯示日期';
$lang->programplan->stageCustom['task'] = '顯示任務';

$lang->programplan->ganttCustom['PM']           ='負責人';
$lang->programplan->ganttCustom['deadline']     ='計劃完成';
$lang->programplan->ganttCustom['status']       ='狀態';
$lang->programplan->ganttCustom['realBegan']    ='實際開始';
$lang->programplan->ganttCustom['realEnd']      ='實際完成';
$lang->programplan->ganttCustom['progress']     ='工作量占比';
$lang->programplan->ganttCustom['taskProgress'] ='任務進度';
$lang->programplan->ganttCustom['estimate']     ='工時';
$lang->programplan->ganttCustom['consumed']     ='消耗工時';
$lang->programplan->ganttCustom['delay']        = '是否延期';
$lang->programplan->ganttCustom['delayDays']    = '延期天數';

$lang->programplan->error                  = new stdclass();
$lang->programplan->error->percentNumber   = '"工作量占比"必須為數字';
$lang->programplan->error->planFinishSmall = '"計劃完成時間"必須大於"計劃開始時間"';
$lang->programplan->error->percentOver     = '相同父階段的子階段工作量占比之和不超過100%';
$lang->programplan->error->createdTask     = '已分解任務，不可添加子階段';
$lang->programplan->error->parentWorkload  = '子階段的工作量之和不能大於父階段的工作量:%s';
$lang->programplan->error->letterParent    = "子階段計劃開始不能小於父階段的計劃開始時間 %s";
$lang->programplan->error->greaterParent   = "子階段計劃完成不能超過父階段的計劃完成時間 %s";
$lang->programplan->error->sameName        = '階段名稱不能相同！';
$lang->programplan->error->sameCode        = '階段代號不能相同！';
$lang->programplan->error->taskDrag        = '%s的任務不可以拖動';
$lang->programplan->error->planDrag        = '%s的階段不可以拖動';
$lang->programplan->error->notStage        = $lang->executionCommon . '/看板不支持創建子階段';
$lang->programplan->error->sameType        = '父階段類型為"%s"，階段類型需與父階段一致';
$lang->programplan->error->emptyParentName = "包含子階段，階段名稱不能為空。";

$lang->programplan->ganttBrowseType['gantt']       = '按階段分組';
$lang->programplan->ganttBrowseType['assignedTo']  = '按指派給分組';

$lang->programplan->reviewColorList['draft']     = '#FC913F';
$lang->programplan->reviewColorList['reviewing'] = '#CD6F27';
$lang->programplan->reviewColorList['pass']      = '#0DBB7D';
$lang->programplan->reviewColorList['fail']      = '#FB2B2B';
