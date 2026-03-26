<?php
/**
 * The issue module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禪道軟件（青島）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->process->list                = '過程列表';
$lang->process->browse              = '瀑布模型過程列表';
$lang->process->scrumBrowse         = '敏捷模型過程列表';
$lang->process->agilePlusBrowse     = '融合敏捷模型過程列表';
$lang->process->waterfallPlusBrowse = '融合瀑布模型過程列表';

$lang->process->model = $lang->projectCommon . '模型';

$lang->process->modelList['scrum']     = '敏捷';
$lang->process->modelList['waterfall'] = '瀑布';

$lang->process->id           = 'ID';
$lang->process->selectAll    = '全選';
$lang->process->name         = '名稱';
$lang->process->type         = '分類';
$lang->process->abbr         = '縮寫';
$lang->process->assignedTo   = '指派給';
$lang->process->assignedBy   = '由誰指派';
$lang->process->assignedDate = '指派日期';
$lang->process->createdBy    = '由誰創建';
$lang->process->createdDate  = '創建日期';
$lang->process->editedBy     = '由誰編輯';
$lang->process->editedDate   = '編輯日期';
$lang->process->desc         = '描述';

$lang->process->create      = '新建';
$lang->process->view        = '查看詳情';
$lang->process->batchCreate = '批量新建';
$lang->process->basicInfo   = '基本信息';
$lang->process->updateOrder = '排序';

$lang->process->createActivity = '創建活動';
$lang->process->activityList   = '活動列表';
$lang->process->edit           = '編輯';
$lang->process->delete         = '刪除';
$lang->process->sort           = '排序';
$lang->process->search         = '搜索';
$lang->process->all            = '所有';

$lang->process->confirmDelete  = '您確定要執行刪除操作嗎？';
$lang->process->emptyTip       = '暫無';

$lang->process->classify['']            = '';
$lang->process->classify['support']     = '支持過程';
$lang->process->classify['project']     = $lang->projectCommon . '管理';
$lang->process->classify['engineering'] = '工程過程';

$lang->process->scrumClassify['']            = '';
$lang->process->scrumClassify['support']     = '支持過程';
$lang->process->scrumClassify['project']     = $lang->projectCommon . '管理';
$lang->process->scrumClassify['engineering'] = '工程過程';

$lang->process->agileplusClassify['']            = '';
$lang->process->agileplusClassify['support']     = '支持過程';
$lang->process->agileplusClassify['project']     = $lang->projectCommon . '管理';
$lang->process->agileplusClassify['engineering'] = '工程過程';

$lang->process->waterfallplusClassify['']            = '';
$lang->process->waterfallplusClassify['support']     = '支持過程';
$lang->process->waterfallplusClassify['project']     = $lang->projectCommon . '管理';
$lang->process->waterfallplusClassify['engineering'] = '工程過程';
