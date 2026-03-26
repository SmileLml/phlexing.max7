<?php
/**
 * The issue module lang file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id
 * @link        http://www.zentao.net
 */
$lang->issue->resolvedBy        = '解決者';
$lang->issue->project           = '所屬' . $lang->projectCommon;
$lang->issue->title             = '問題名稱';
$lang->issue->risk              = '關聯風險';
$lang->issue->desc              = '描述';
$lang->issue->pri               = '優先順序';
$lang->issue->priAB             = 'P';
$lang->issue->severity          = '嚴重程度';
$lang->issue->type              = '類型';
$lang->issue->execution         = '所屬' . $lang->execution->common;
$lang->issue->effectedArea      = '受影響的活動';
$lang->issue->activity          = '活動列表';
$lang->issue->deadline          = '計劃解決日期';
$lang->issue->resolution        = '解決方式';
$lang->issue->resolutionComment = '解決方案';
$lang->issue->resolvedDate      = '實際解決日期';
$lang->issue->noAssigned        = '未指派';
$lang->issue->status            = '結果';
$lang->issue->lib               = '問題庫';
$lang->issue->approver          = '審批人';
$lang->issue->createdBy         = '由誰創建';
$lang->issue->createdDate       = '創建日期';
$lang->issue->owner             = '提出人';
$lang->issue->editedBy          = '由誰編輯';
$lang->issue->editedDate        = '編輯日期';
$lang->issue->activateBy        = '由誰激活';
$lang->issue->activateDate      = '激活日期';
$lang->issue->closedBy          = '由誰關閉';
$lang->issue->closedDate        = '關閉日期';
$lang->issue->assignedTo        = '指派給';
$lang->issue->assignedBy        = '由誰指派';
$lang->issue->assignedDate      = '指派時間';
$lang->issue->id                = '編號';
$lang->issue->deleted           = '已刪除';
$lang->issue->files             = '附件';
$lang->issue->allLib            = '所有問題庫';

$lang->issue->browse           = '問題列表';
$lang->issue->view             = '問題詳情';
$lang->issue->close            = '關閉';
$lang->issue->cancel           = '取消';
$lang->issue->confirm          = '確認';
$lang->issue->resolve          = '解決';
$lang->issue->delete           = '刪除';
$lang->issue->search           = '搜索';
$lang->issue->basicInfo        = '基本信息';
$lang->issue->lifeTime         = '問題的一生';
$lang->issue->legendMisc       = '其他相關';
$lang->issue->linkedRisk       = '關聯的風險';
$lang->issue->activate         = '激活';
$lang->issue->assignTo         = '指派';
$lang->issue->create           = '新建問題';
$lang->issue->edit             = '編輯問題';
$lang->issue->export           = '導出數據';
$lang->issue->exportAction     = '導出問題';
$lang->issue->batchCreate      = '批量新建';
$lang->issue->effort           = '日誌';
$lang->issue->consumed         = '總計消耗';
$lang->issue->importToLib      = '導入問題庫';
$lang->issue->batchImportToLib = '批量導入問題庫';
$lang->issue->isExist          = '問題庫中已有此條記錄，請勿重複提交！';

$lang->issue->editAction      = '編輯問題';
$lang->issue->deleteAction    = '刪除問題';
$lang->issue->confirmAction   = '確認問題';
$lang->issue->assignToAction  = '指派問題';
$lang->issue->closeAction     = '關閉問題';
$lang->issue->cancelAction    = '取消問題';
$lang->issue->activateAction  = '激活問題';
$lang->issue->resolveAction   = '解決問題';

$lang->issue->importFromLib = '從問題庫中導入';
$lang->issue->import        = '導入';
$lang->issue->noIssueLib    = '現在還沒有問題庫，請先創建！';

$lang->issue->featureBar['browse']['all']      = '全部';
$lang->issue->featureBar['browse']['open']     = '開放';
$lang->issue->featureBar['browse']['assignto'] = '指派給我';
$lang->issue->featureBar['browse']['assignby'] = '由我指派';
$lang->issue->featureBar['browse']['closed']   = '已關閉';
$lang->issue->featureBar['browse']['resolved'] = '已解決';
$lang->issue->featureBar['browse']['canceled'] = '已取消';

$lang->issue->priList['0'] = '';
$lang->issue->priList['1'] = 1;
$lang->issue->priList['2'] = 2;
$lang->issue->priList['3'] = 3;
$lang->issue->priList['4'] = 4;

$lang->issue->severityList['0']  = '';
$lang->issue->severityList['1'] = '嚴重';
$lang->issue->severityList['2'] = '較嚴重';
$lang->issue->severityList['3'] = '較小';
$lang->issue->severityList['4'] = '建議';

$lang->issue->typeList[''] = '';
$lang->issue->typeList['design']       = '設計問題';
$lang->issue->typeList['code']         = '程序缺陷';
$lang->issue->typeList['performance']  = '性能問題';
$lang->issue->typeList['version']      = '版本控制';
$lang->issue->typeList['storyadd']     = '需求新增';
$lang->issue->typeList['storychanged'] = '需求修改';
$lang->issue->typeList['storyremoved'] = '需求刪除';
$lang->issue->typeList['data']         = '數據問題';

$lang->issue->resolutionList['resolved'] = '已解決';
$lang->issue->resolutionList['tostory']  = '轉需求';
$lang->issue->resolutionList['tobug']    = '轉BUG';
$lang->issue->resolutionList['torisk']   = '轉風險';
$lang->issue->resolutionList['totask']   = '轉任務';

$lang->issue->statusList['unconfirmed'] = '待確認';
$lang->issue->statusList['confirmed']   = '已確認';
$lang->issue->statusList['resolved']    = '已解決';
$lang->issue->statusList['canceled']    = '已取消';
$lang->issue->statusList['closed']      = '已關閉';
$lang->issue->statusList['active']      = '激活';

$lang->issue->resolveMethods = array();
$lang->issue->resolveMethods['resolved'] = '已解決';
$lang->issue->resolveMethods['totask']   = '轉任務';
$lang->issue->resolveMethods['tobug']    = '轉BUG';
$lang->issue->resolveMethods['tostory']  = '轉需求';
$lang->issue->resolveMethods['torisk']   = '轉風險';

$lang->issue->confirmDelete = '您確認刪除該問題？';
$lang->issue->typeEmpty     = 'ID：%s的類別不能為空。';
$lang->issue->titleEmpty    = 'ID：%s的標題不能為空。';
$lang->issue->severityEmpty = 'ID：%s的嚴重程度不能為空。';

$lang->issue->logComments = array();
$lang->issue->logComments['totask']  = "創建了任務：%s。";
$lang->issue->logComments['tostory'] = "創建了需求：%s。";
$lang->issue->logComments['tobug']   = "創建了BUG：%s。";
$lang->issue->logComments['torisk']  = "創建了風險：%s。";

$lang->issue->action = new stdclass();
$lang->issue->action->importfromissuelib = array('main' => '$date, 由 <strong>$actor</strong> 從問題庫 <strong>$extra</strong>導入。');
