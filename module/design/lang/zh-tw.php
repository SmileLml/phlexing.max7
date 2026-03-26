<?php
/**
 * The zh-tw file of design module.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: zh-tw.php 4729 2020-09-01 07:53:55Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
/* 欄位列表. */
$lang->design->id            = '編號';
$lang->design->name          = '設計名稱';
$lang->design->story         = '需求';
$lang->design->type          = '設計類型';
$lang->design->ditto         = '同上';
$lang->design->submission    = '相關提交';
$lang->design->version       = '版本號';
$lang->design->assignedTo    = '指派給';
$lang->design->actions       = '操作';
$lang->design->byQuery       = '搜索';
$lang->design->products      = "所屬{$lang->productCommon}";
$lang->design->story         = '相關需求';
$lang->design->file          = '附件';
$lang->design->desc          = '設計描述';
$lang->design->range         = '影響範圍';
$lang->design->product       = "所屬{$lang->productCommon}";
$lang->design->basicInfo     = '基礎信息';
$lang->design->commitBy      = '由誰提交';
$lang->design->commitDate    = '提交時間';
$lang->design->affectedStory = "影響{$lang->SRCommon}";
$lang->design->affectedTasks = '影響任務';
$lang->design->reviewObject  = '評審對象';
$lang->design->createdBy     = '由誰創建';
$lang->design->createdByAB   = '創建者';
$lang->design->createdDate   = '創建日期';
$lang->design->basicInfo     = '基本信息';
$lang->design->noAssigned    = '未指派';
$lang->design->comment       = '註釋';
$lang->design->more          = '更多';
$lang->design->project       = '所屬項目';

/* 動作列表. */
$lang->design->common             = '設計';
$lang->design->create             = '創建設計';
$lang->design->batchCreate        = '批量創建';
$lang->design->edit               = '變更';
$lang->design->delete             = '刪除';
$lang->design->view               = '設計詳情';
$lang->design->browse             = '設計列表';
$lang->design->viewCommit         = '查看提交';
$lang->design->linkCommit         = '關聯提交';
$lang->design->unlinkCommit       = '取消關聯';
$lang->design->submit             = '提交評審';
$lang->design->assignTo           = '指派';
$lang->design->assignAction       = '指派';
$lang->design->revision           = '查看關聯代碼';
$lang->design->confirmStoryChange = '確認';

$lang->design->browseAction = '設計列表';

/* 欄位取值. */
$lang->design->typeList         = array();
$lang->design->typeList['']     = '';
$lang->design->typeList['HLDS'] = '概要設計';
$lang->design->typeList['DDS']  = '詳細設計';
$lang->design->typeList['DBDS'] = '資料庫設計';
$lang->design->typeList['ADS']  = '介面設計';

$lang->design->plusTypeList = $lang->design->typeList;

$lang->design->rangeList           = array();
$lang->design->rangeList['all']    = '全部記錄';
$lang->design->rangeList['assign'] = '選中記錄';

/* 提示信息. */
$lang->design->errorSelection = '還沒有選中記錄!';
$lang->design->noDesign       = '暫時沒有記錄';
$lang->design->noCommit       = '暫時沒有提交記錄';
$lang->design->confirmDelete  = '您確定要刪除這個設計嗎？';
$lang->design->confirmUnlink  = '您確定要移除這個提交嗎？';
$lang->design->errorDate      = '開始日期不能大於結束日期';
$lang->design->deleted        = '已刪除';
