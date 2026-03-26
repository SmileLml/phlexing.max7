<?php
/**
 * The track view file of opportunity module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     opportunity
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->opportunity->track));

$clickIsChange = jsCallback()->do(<<<'JS'
const currentValue = $(target).val();
if(currentValue == '') return true;
const $form = $(target).closest('form');
if(currentValue == '0')
{
    $form.find('.track').addClass('hidden');
    $form.find('.not-track').removeClass('hidden');
}
else
{
    $form.find('.not-track').addClass('hidden');
    $form.find('.track').removeClass('hidden');
}
JS
);

formPanel
(
    set::formID('form-opportunity-track'),
    on::change('[name=impact]', 'computeIndex'),
    on::change('[name=probability]', 'computeIndex'),
    formGroup
    (
        on::click($clickIsChange),
        set::label($lang->opportunity->isChange),
        radioList
        (
            set::name('isChange'),
            set::inline(true),
            set::items($lang->opportunity->isChangeList),
            set::value(0)
        )
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->name),
        set::name('name'),
        set::value($opportunity->name)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->type),
        set::name('type'),
        set::items($lang->opportunity->typeList),
        set::value($opportunity->type)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->strategy),
        set::name('strategy'),
        set::items($lang->opportunity->strategyList),
        set::value($opportunity->strategy)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->impact),
        set::control(array('control' => 'picker', 'required' => true)),
        set::name('impact'),
        set::items($lang->opportunity->impactList),
        set::value($opportunity->impact)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->chance),
        set::control(array('control' => 'picker', 'required' => true)),
        set::name('chance'),
        set::items($lang->opportunity->chanceList),
        set::value($opportunity->chance)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->ratio),
        set::name('ratio'),
        set::value($opportunity->ratio),
        set::readonly(true)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->pri),
        set::control(array('control' => 'priPicker', 'required' => true)),
        set::name('pri'),
        set::items($lang->opportunity->priList),
        set::value($opportunity->pri),
        set::readonly(true)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->lastCheckedBy),
        set::name('lastCheckedBy'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->opportunity->lastCheckedDate),
        set::name('lastCheckedDate'),
        set::control('datePicker'),
        set::value(helper::isZeroDate($opportunity->lastCheckedDate) ? helper::today() : $opportunity->lastCheckedDate)
    ),
    formGroup(setClass('track hidden'), set::label($lang->opportunity->desc), set::name('desc'), set::control('editor'), set::value($opportunity->desc)),
    formGroup(setClass('track hidden'), set::label($lang->opportunity->prevention), set::name('prevention'), set::control('editor'), set::value($opportunity->prevention)),
    formGroup(setClass('track hidden'), set::label($lang->opportunity->resolution), set::name('resolution'), set::control('editor'), set::value($opportunity->resolution)),
    formGroup(setClass('not-track'), set::label($lang->comment), set::name('comment'), set::control('editor'))
);
