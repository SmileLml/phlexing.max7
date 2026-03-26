<?php
/**
 * The issue module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     issue
 * @version     $Id: zh-cn.php 4729 2013-05-03 07:53:55Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->process->list                = '过程列表';
$lang->process->browse              = '瀑布模型过程列表';
$lang->process->scrumBrowse         = '敏捷模型过程列表';
$lang->process->agilePlusBrowse     = '融合敏捷模型过程列表';
$lang->process->waterfallPlusBrowse = '融合瀑布模型过程列表';

$lang->process->model = $lang->projectCommon . '模型';

$lang->process->modelList['scrum']     = '敏捷';
$lang->process->modelList['waterfall'] = '瀑布';

$lang->process->id           = 'ID';
$lang->process->selectAll    = '全选';
$lang->process->name         = '名称';
$lang->process->type         = '分类';
$lang->process->abbr         = '缩写';
$lang->process->assignedTo   = '指派给';
$lang->process->assignedBy   = '由谁指派';
$lang->process->assignedDate = '指派日期';
$lang->process->createdBy    = '由谁创建';
$lang->process->createdDate  = '创建日期';
$lang->process->editedBy     = '由谁编辑';
$lang->process->editedDate   = '编辑日期';
$lang->process->desc         = '描述';

$lang->process->create      = '新建';
$lang->process->view        = '查看详情';
$lang->process->batchCreate = '批量新建';
$lang->process->basicInfo   = '基本信息';
$lang->process->updateOrder = '排序';

$lang->process->createActivity = '创建活动';
$lang->process->activityList   = '活动列表';
$lang->process->edit           = '编辑';
$lang->process->delete         = '删除';
$lang->process->sort           = '排序';
$lang->process->search         = '搜索';
$lang->process->all            = '所有';

$lang->process->confirmDelete  = '您确定要执行删除操作吗？';
$lang->process->emptyTip       = '暂无';

$lang->process->classify['']            = '';
$lang->process->classify['support']     = '支持过程';
$lang->process->classify['project']     = $lang->projectCommon . '管理';
$lang->process->classify['engineering'] = '工程过程';

$lang->process->scrumClassify['']            = '';
$lang->process->scrumClassify['support']     = '支持过程';
$lang->process->scrumClassify['project']     = $lang->projectCommon . '管理';
$lang->process->scrumClassify['engineering'] = '工程过程';

$lang->process->agileplusClassify['']            = '';
$lang->process->agileplusClassify['support']     = '支持过程';
$lang->process->agileplusClassify['project']     = $lang->projectCommon . '管理';
$lang->process->agileplusClassify['engineering'] = '工程过程';

$lang->process->waterfallplusClassify['']            = '';
$lang->process->waterfallplusClassify['support']     = '支持过程';
$lang->process->waterfallplusClassify['project']     = $lang->projectCommon . '管理';
$lang->process->waterfallplusClassify['engineering'] = '工程过程';
