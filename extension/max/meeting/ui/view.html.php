<?php
/**
 * The view of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     view
 * @version     $Id
 * @link        https://www.zentao.net
 */
namespace zin;

/* 初始化主栏内容。Init sections in main column. */
$sections = array();
$sections[] = setting()
    ->title($lang->meeting->date)
    ->control('text')
    ->content($meeting->date . ' ' . $meeting->begin . ' - ' . $meeting->end);

$sections[] = setting()
    ->title($lang->meeting->participant)
    ->control('text')
    ->content($meeting->participantName);

$sections[] = setting()
    ->title($lang->meeting->minutes)
    ->control('html')
    ->content($meeting->minutes);

if($meeting->files)
{
    $sections[] = array
    (
        'control'    => 'fileList',
        'files'      => $meeting->files,
        'object'     => $meeting,
        'padding'    => false
    );
}

/* 初始化底部操作栏。Init bottom actions. */
$operateList = $this->loadModel('common')->buildOperateMenu($meeting);
$actions     = array();
if(!$meeting->deleted)
{
    $actions = $operateList['mainActions'];
    if(!empty($operateList['suffixActions'])) $actions = array_merge($actions, array(array('type' => 'divider')), $operateList['suffixActions']);
}

/* 初始化侧边栏标签页。Init sidebar tabs. */
$tabs = array();

/* 基本信息。Legend basic items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->meeting->legendBasicInfo)
    ->control('meetingBasicInfo');

/* 一生信息。Legend life items. */
$tabs[] = setting()
    ->group('basic')
    ->title($lang->meeting->legendLifeTime)
    ->control('meetingLifeInfo');

detail
(
    set::urlFormatter(array('{id}' => $meeting->id, '{from}' => $from)),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);
