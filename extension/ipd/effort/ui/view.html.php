<?php
/**
 * The view file of effort module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     effort
 * @link        https://www.zentao.net
 */
namespace zin;
$confirmDelete = $this->lang->effort->confirmDelete;
if($effort->objectType == 'task' and $task->consumed - $effort->consumed == 0) $confirmDelete = $lang->task->confirmDeleteLastEstimate;
jsVar('confirmDelete', $confirmDelete);

$actions = $this->loadModel('common')->buildOperateMenu($effort);
detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($effort->id),
            set::level(1),
            span($lang->effort->view)
        ),
        $effort->deleted ? span(setClass('label danger'), $lang->effort->deleted) : null
    )
);

panel
(
    tableData
    (
        item(set::name($lang->effort->account), zget($users, $effort->account)),
        item(set::name($lang->effort->date), date(DT_DATE1, strtotime($effort->date))),
        item(set::name($lang->effort->consumed), !empty($effort->consumed) ? $effort->consumed . ' ' . $lang->effort->hour : ''),
        $effort->objectType == 'task' ? item(set::name($lang->effort->left), !empty($effort->left) ? $effort->left . ' ' . $lang->effort->hour : '') : null,
        item(set::name($lang->effort->objectType), zget($lang->effort->objectTypeList, $effort->objectType, ''), $work ? a(set::href($this->createLink($effort->objectType == 'case' ? 'testcase' : $effort->objectType, 'view', "objectID={$effort->objectID}")), ' #' . $effort->objectID . ' ' . $work[$effort->objectID]) : ''),
        item(set::name($lang->effort->work), empty($effort->work) ? $lang->noData : html($effort->work))
    ),
    h::hr(setClass('mt-4')),
    history(set::objectID($effort->id)),
    !isInModal() ? div
    (
        setClass('flex center'),
        floatToolbar
        (
            set::object($effort),
            isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), setClass('ghost text-white'), $lang->goback)),
            set::suffix($actions['suffixActions'])
        )
    ) : null
);
