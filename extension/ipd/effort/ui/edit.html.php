<?php
/**
 * The edit view file of effort module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     effort
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('objectType', $effort->objectType);
jsVar('noticeFinish', $lang->effort->noticeFinish);

!isInModal() ? h::css(".modal-header {min-width: 1200px;}") : null;

modalHeader(set::entityText($objectName), set::title($lang->effort->edit), set::entityID($effort->objectID));

formPanel
(
    setID('editEffortForm'),
    set::submitBtnText($lang->save),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    $effort->objectType == 'task' ? formGroup
    (
        set::hidden(empty($project->hasProduct) || $config->vision == 'or'),
        set::width('1/2'),
        set::label($lang->effort->product),
        set::name('product'),
        set::multiple(true),
        set::items($products),
        set::value($effort->product)
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($config->vision == 'or' ? $lang->stage->common : $lang->effort->execution),
        set::name('execution'),
        set::control(array('control' => 'picker', 'items' => $executions, 'value' => $effort->execution, 'popWidth' => 'auto', 'maxItemsCount' => 50))
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->effort->date),
        datePicker
        (
            set::name('date'),
            set::value($effort->date),
            set::maxDate(helper::today())
        ),
        set::required(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->effort->consumed),
        set::name('consumed'),
        set::value($effort->consumed),
        set::required(true)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->effort->left),
        set::name('left'),
        set::value($effort->left),
        set::hidden($effort->objectType != 'task'),
        set::readonly($recentDateID !== $effort->id || ($effort->objectType == 'task' && !empty($task->team) && $effort->left == 0))
    ),
    formGroup
    (
        set::label($lang->effort->work),
        set::name('work'),
        set::value($effort->work)
    ),
    formHidden('objectType', $effort->objectType),
    formHidden('objectID', $effort->objectID)
);
