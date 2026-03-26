<?php
/**
 * The groupitem file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateGroupItem = function($key, $value, $itemCount, $options, $errors) use ($pivotState)
{
    $hasError = isset($errors[$key]);
    $canAdd   = (($this->config->edition == 'biz' && $itemCount < 3) ||
        (in_array($this->config->edition, array('max', 'ipd')) && $itemCount < 5));
    return div
    (
        setClass("pl-1 group-line"),
        set('data-key', $key),
        div
        (
            setClass('flex'),
            div
            (
                setClass('picker-group'),
                picker
                (
                    setID("picker_group$key"),
                    setClass(array('has-error' => $hasError)),
                    set::name($key),
                    set::value($value),
                    set::items($options['fields']),
                    on::change()->do('changeSettingGroup(event)')
                ),
                span
                (
                    setClass('text-danger', array('hidden' => !$hasError)),
                    $this->lang->pivot->emptyGroupError
                )
            ),
            toolbar
            (
                setClass('pl-2 gap-1'),
                btn
                (
                    setClass('ghost size-sm squre group-add', array('hidden' => !$canAdd)),
                    set::icon('plus'),
                    on::click()->do('addSettingGroup(event)')
                ),
                btn
                (
                    setClass('ghost size-sm squre group-delete', array('hidden' => $itemCount == 1)),
                    set::icon('close'),
                    on::click()->do('deleteSettingGroup(event)')
                )
            )
        )
    );
};
