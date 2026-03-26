<?php
/**
 * The setCharterInfo view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie<xieqiyu@chandao.com>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items['key']              = array('name' => 'key',              'control' => 'hidden',  'hidden' => true);
$items['level']            = array('name' => 'level',            'label' => $lang->custom->charter->level,            'control' => 'input',  'width' => '120px');
$items['projectApproval']  = array('name' => 'projectApproval',  'label' => $lang->custom->charter->projectApproval,  'control' => 'hidden', 'width' => '120px');
$items['completeApproval'] = array('name' => 'completeApproval', 'label' => $lang->custom->charter->completeApproval, 'control' => 'hidden', 'width' => '120px');
$items['cancelApproval']   = array('name' => 'cancelApproval',   'label' => $lang->custom->charter->cancelApproval,   'control' => 'hidden', 'width' => '120px');

formBatchPanel
(
    set::title($title),
    set::items($items),
    set::onRenderRow(jsRaw('renderRowData')),
    set::data(array_values(json_decode($config->custom->charterFiles, true))),
    set::minRows(1),
    set::actions(array('submit', array('text' => $lang->custom->restore, 'class' => 'btn-wide ajax-submit', 'data-confirm' => $lang->custom->confirmRestore, 'url' => inlink('resetCharterInfo'))))
);
