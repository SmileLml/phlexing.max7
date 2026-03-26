<?php
$lang->demandpool->create   = '創建需求池';
$lang->demandpool->browse   = '需求池列表';
$lang->demandpool->edit     = '編輯';
$lang->demandpool->view     = '需求池概況';
$lang->demandpool->delete   = '刪除';
$lang->demandpool->close    = '關閉';
$lang->demandpool->activate = '激活';
$lang->demandpool->comment  = '備註';
$lang->demandpool->track    = '需求池矩陣';
$lang->demandpool->demand   = '需求';

$lang->demandpool->id           = '編號';
$lang->demandpool->idAB         = 'ID';
$lang->demandpool->code         = '序號';
$lang->demandpool->category     = '需求分類';
$lang->demandpool->owner        = '負責人';
$lang->demandpool->pri          = '優先順序';
$lang->demandpool->dept         = '所屬部門';
$lang->demandpool->status       = '狀態';
$lang->demandpool->name         = '需求池名稱';
$lang->demandpool->createdBy    = '由誰創建';
$lang->demandpool->createdDate  = '創建時間';
$lang->demandpool->desc         = '描述';
$lang->demandpool->num          = '需求數量';
$lang->demandpool->assignedTo   = '指派給';
$lang->demandpool->reviewer     = '評審人';
$lang->demandpool->myDemand     = '我負責';
$lang->demandpool->other        = '其他';
$lang->demandpool->doneDemands  = '已關閉';
$lang->demandpool->info         = '信息';
$lang->demandpool->basicInfo    = '基本信息';
$lang->demandpool->activateAB   = '激活';
$lang->demandpool->closeAB      = '關閉';
$lang->demandpool->deleted      = '已刪除';
$lang->demandpool->products     = '綁定產品';
$lang->demandpool->all          = '全部需求池';

$lang->demandpool->noData           = '暫無數據，';
$lang->demandpool->needDistribute   = '待分發';
$lang->demandpool->closedDemandpool = '已關閉的需求池';
$lang->demandpool->hasReview        = '當前人員有待評審的需求，無法刪除。';

$lang->demandpool->accessDenied = "您無權訪問該需求池！";

$lang->demandpool->statusList[''] = '';
$lang->demandpool->statusList['normal'] = '正常';
$lang->demandpool->statusList['closed'] = '已關閉';

$lang->demandpool->labelList = array();
$lang->demandpool->labelList['all']    = '全部';
$lang->demandpool->labelList['mine']   = '我負責';
$lang->demandpool->labelList['normal'] = '正常';
$lang->demandpool->labelList['closed'] = '已關閉';

$lang->demandpool->colList = array();
$lang->demandpool->colList['draft']       = '草稿';
$lang->demandpool->colList['reviewing']   = '評審中';
$lang->demandpool->colList['wait']        = '待分發';
$lang->demandpool->colList['willCharter'] = '待立項';
$lang->demandpool->colList['inCharter']   = 'Charter立項';

$lang->demandpool->acl = '訪問控制';

$lang->demandpool->aclList['open']    = '公開（有需求池視圖權限，即可訪問）';
$lang->demandpool->aclList['private'] = '私有（需求池負責人、評審人可以訪問）';

$lang->demandpool->confirmDelete    = '您確認刪除該需求池嗎？';
$lang->demandpool->hasDemand        = '需求池中已有需求，不能刪除，請先轉移或刪除需求後再操作。';
$lang->demandpool->bindProductsTips = '綁定產品後，需求只能分發到綁定的產品中';
