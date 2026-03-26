<?php
/**
 * The stage module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: zh-tw.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
/* Actions. */
$lang->stage->browse      = '瀑布階段列表';
$lang->stage->create      = '新建';
$lang->stage->batchCreate = '批量新建';
$lang->stage->edit        = '編輯';
$lang->stage->delete      = '刪除';
$lang->stage->view        = '階段詳情';
$lang->stage->plusBrowse  = '融合瀑布階段列表';

/* Fields. */
$lang->stage->id      = 'ID';
$lang->stage->name    = '階段名稱';
$lang->stage->type    = '階段類型';
$lang->stage->percent = '工作量占比';
$lang->stage->setType = '階段類型';

$lang->stage->typeList['mix']     = '綜合';
$lang->stage->typeList['request'] = '需求';
$lang->stage->typeList['design']  = '設計';
$lang->stage->typeList['dev']     = '開發';
$lang->stage->typeList['qa']      = '測試';
$lang->stage->typeList['release'] = '發佈';
$lang->stage->typeList['review']  = '總結評審';
$lang->stage->typeList['other']   = '其他';

$lang->stage->ipdTypeList['concept']   = '概念';
$lang->stage->ipdTypeList['plan']      = '計劃';
$lang->stage->ipdTypeList['develop']   = '開發';
$lang->stage->ipdTypeList['qualify']   = '驗證';
$lang->stage->ipdTypeList['launch']    = '發佈';
$lang->stage->ipdTypeList['lifecycle'] = '生命周期';

$lang->stage->viewList      = '瀏覽列表';
$lang->stage->noStage       = '暫時沒有階段';
$lang->stage->confirmDelete = '您確定要執行刪除操作嗎？';

$lang->stage->error              = new stdclass();
$lang->stage->error->percentOver = '工作量占比累計不應當超過100%';
$lang->stage->error->notNum      = '工作量占比應當是數字';
