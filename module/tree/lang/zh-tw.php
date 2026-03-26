<?php
/**
 * The tree module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: zh-tw.php 4836 2013-06-19 05:39:40Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->tree                       = new stdclass();
$lang->tree->common               = '模組維護';
$lang->tree->edit                 = '編輯模組';
$lang->tree->delete               = '刪除模組';
$lang->tree->browse               = '通用模組維護';
$lang->tree->browseTask           = '任務模組維護';
$lang->tree->manage               = '維護模組';
$lang->tree->fix                  = '修正數據';
$lang->tree->manageProduct        = "維護{$lang->productCommon}視圖模組";
$lang->tree->manageProject        = "維護{$lang->projectCommon}視圖模組";
$lang->tree->manageExecution      = "維護{$lang->execution->common}視圖模組";
$lang->tree->manageLine           = "維護產品綫";
$lang->tree->manageBug            = '維護測試視圖模組';
$lang->tree->manageCase           = '維護用例視圖模組';
$lang->tree->manageCaselib        = '維護用例庫模組';
$lang->tree->manageCustomDoc      = '維護文檔庫分類';
$lang->tree->manageApiChild       = '維護介面庫目錄';
$lang->tree->manageGroup          = '維護分組';
$lang->tree->updateOrder          = '更新排序';
$lang->tree->manageChild          = '維護子模組';
$lang->tree->manageStoryChild     = '維護子模組';
$lang->tree->manageLineChild      = "維護產品綫";
$lang->tree->manageBugChild       = '維護Bug子模組';
$lang->tree->manageCaseChild      = '維護用例子模組';
$lang->tree->manageCaselibChild   = '維護用例庫子模組';
$lang->tree->manageDashboard      = '維護儀表盤模組';
$lang->tree->manageDashboardChild = '維護儀表盤子模組';
$lang->tree->manageProjectChild   = "維護{$lang->projectCommon}子模組";
$lang->tree->manageTaskChild      = "維護{$lang->execution->common}子模組";
$lang->tree->syncFromProduct      = '複製模組';
$lang->tree->dragAndSort          = "拖放排序";
$lang->tree->sort                 = "排序";
$lang->tree->addChild             = "增加子模組";
$lang->tree->confirmDelete        = '該模組及其子模組都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDeleteMenu    = '該目錄及其子目錄都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDelCategory   = '該分類及其子分類都會被刪除，您確定刪除嗎？';
$lang->tree->confirmDeleteLine    = "您確定刪除該產品綫嗎？";
$lang->tree->confirmDeleteGroup   = '該分組及其子分組都會被刪除，您確定刪除嗎？';
$lang->tree->confirmRoot          = "模組的所屬{$lang->productCommon}修改，會關聯修改該模組下的{$lang->SRCommon}、Bug、用例的所屬{$lang->productCommon}，以及{$lang->executionCommon}和{$lang->productCommon}的關聯關係。該操作比較危險，請謹慎操作。是否確認修改？";
$lang->tree->confirmRoot4Doc      = "修改所屬文檔庫，會同時修改該分類下文檔的關聯關係。該操作比較危險，請謹慎操作。是否確認修改？";
$lang->tree->noSubmodule          = "當前模組下沒有可複製的子模組！";
$lang->tree->successSave          = '成功保存';
$lang->tree->successFixed         = '成功修正數據！';
$lang->tree->repeatName           = '模組名“%s”已經存在！';
$lang->tree->repeatDirName        = '目錄名“%s”已經存在！';
$lang->tree->shouldNotBlank       = '模組名不能為空格！';
$lang->tree->syncProductModule    = "同步{$lang->productCommon}模組";
$lang->tree->host                 = '主機';
$lang->tree->editHost             = '編輯主機分組';
$lang->tree->deleteHost           = '刪除主機分組';
$lang->tree->manageHostChild      = '維護主機子分組';
$lang->tree->groupMaintenance     = '維護主機分組';
$lang->tree->groupName            = '分組名稱';
$lang->tree->parentGroup          = '上級分組';
$lang->tree->childGroup           = '子分組';
$lang->tree->confirmDeleteHost    = '該分組及子分組都會被刪除，您確定刪除嗎？';

$lang->tree->module       = '模組';
$lang->tree->name         = '模組名稱';
$lang->tree->wordName     = '名稱';
$lang->tree->line         = "產品綫名稱";
$lang->tree->cate         = '分類名稱';
$lang->tree->dir          = '目錄名稱';
$lang->tree->root         = "所屬{$lang->productCommon}";
$lang->tree->branch       = '平台/分支';
$lang->tree->path         = '路徑';
$lang->tree->type         = '類型';
$lang->tree->parent       = '上級模組';
$lang->tree->parentCate   = '所屬父級';
$lang->tree->child        = '子模組';
$lang->tree->parentGroup  = '上級分組';
$lang->tree->childGroup   = '子分組';
$lang->tree->subCategory  = '子分類';
$lang->tree->editCategory = '編輯分類';
$lang->tree->delCategory  = '刪除分類';
$lang->tree->lineChild    = "子產品綫";
$lang->tree->owner        = '負責人';
$lang->tree->order        = '排序';
$lang->tree->short        = '簡稱';
$lang->tree->all          = '所有模組';
$lang->tree->executionDoc = "{$lang->executionCommon}文檔";
$lang->tree->product      = "所屬{$lang->productCommon}";
$lang->tree->editDir      = "編輯目錄";

$lang->tree->emptyHistory = "暫時沒有歷史記錄。";

$lang->module = new stdclass();
$lang->module->action = new stdclass();
$lang->module->action->created = array('main' => "\$date, 由 <strong>\$actor</strong> 創建了 <strong>\$extra</strong>。");
$lang->module->action->moved   = array('main' => "\$date, 由 <strong>\$actor</strong> 移動了 <strong>\$extra</strong>。");
$lang->module->action->deleted = array('main' => "\$date, 由 <strong>\$actor</strong> 刪除了 <strong>\$extra</strong>。");
