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
$lang->process->list                = 'Process';
$lang->process->browse              = 'Waterfull List';
$lang->process->scrumBrowse         = 'Scrum List';
$lang->process->agilePlusBrowse     = 'Agile Plus List';
$lang->process->waterfallPlusBrowse = 'Waterfall Plus List';

$lang->process->model = $lang->projectCommon . 'Model';

$lang->process->modelList['scrum']     = 'Scrum';
$lang->process->modelList['waterfall'] = 'Waterfall';

$lang->process->id           = 'ID';
$lang->process->selectAll    = 'Select All';
$lang->process->name         = 'Name';
$lang->process->type         = 'Type';
$lang->process->abbr         = 'Abbreviations';
$lang->process->assignedTo   = 'AssignedTo';
$lang->process->assignedBy   = 'AssignedBy';
$lang->process->assignedDate = 'Assigned';
$lang->process->createdBy    = 'CreatedBy';
$lang->process->createdDate  = 'Created';
$lang->process->editedBy     = 'EditedBy';
$lang->process->editedDate   = 'Edited';
$lang->process->desc         = 'Description';

$lang->process->create      = 'Create';
$lang->process->view        = 'View';
$lang->process->batchCreate = 'Batch Create';
$lang->process->basicInfo   = 'Basic Information';
$lang->process->updateOrder = 'Order';

$lang->process->createActivity = 'Create Activity';
$lang->process->activityList   = 'Activity List';
$lang->process->edit           = 'Edit';
$lang->process->delete         = 'Delete';
$lang->process->sort           = 'Sort';
$lang->process->search         = 'Search';
$lang->process->all            = 'All';

$lang->process->confirmDelete  = 'Do you want to delete it?';
$lang->process->emptyTip       = 'None';

$lang->process->classify['']            = '';
$lang->process->classify['support']     = 'Support Process';
$lang->process->classify['project']     = $lang->projectCommon . 'Management';
$lang->process->classify['engineering'] = 'Engineering Process';

$lang->process->scrumClassify['']            = '';
$lang->process->scrumClassify['support']     = 'Support Process';
$lang->process->scrumClassify['project']     = $lang->projectCommon . 'Management';
$lang->process->scrumClassify['engineering'] = 'Engineering Process';

$lang->process->agileplusClassify['']            = '';
$lang->process->agileplusClassify['support']     = 'Support Process';
$lang->process->agileplusClassify['project']     = $lang->projectCommon . 'Management';
$lang->process->agileplusClassify['engineering'] = 'Engineering Process';
