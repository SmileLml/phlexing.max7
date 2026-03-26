<?php
/**
 * The track file of risk module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     risk
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->risk->track));

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
    set::formID('form-risk-track'),
    on::change('[name=impact]', 'computeIndex'),
    on::change('[name=probability]', 'computeIndex'),
    formGroup
    (
        on::click($clickIsChange),
        set::label($lang->risk->isChange),
        radioList
        (
            set::name('isChange'),
            set::inline(true),
            set::items($lang->risk->isChangeList),
            set::value(0)
        )
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->name),
        set::name('name'),
        set::value($risk->name)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->category),
        set::name('category'),
        set::items($lang->risk->categoryList),
        set::value($risk->category)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->strategy),
        set::name('strategy'),
        set::items($lang->risk->strategyList),
        set::value($risk->strategy)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->impact),
        set::name('impact'),
        set::items($lang->risk->impactList),
        set::required(true),
        set::value($risk->impact)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->probability),
        set::name('probability'),
        set::items($lang->risk->probabilityList),
        set::required(true),
        set::value($risk->probability)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->rate),
        set::name('rate'),
        set::value($risk->rate),
        set::readonly(true)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->pri),
        set::control('priPicker'),
        set::name('pri'),
        set::items($lang->risk->priList),
        set::value($risk->pri),
        set::required(true),
        set::readonly(true)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->trackedBy),
        set::name('trackedBy'),
        set::items($users),
        set::value($app->user->account)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->trackedDate),
        set::name('trackedDate'),
        set::control('datePicker'),
        set::value($risk->trackedDate == '0000-00-00' || empty($risk->trackedDate) ? helper::today() : $risk->trackedDate)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->prevention),
        set::name('prevention'),
        set::control('editor'),
        set::value($risk->prevention),
        set::rows(6)
    ),
    formGroup
    (
        setClass('track hidden'),
        set::label($lang->risk->resolution),
        set::name('resolution'),
        set::control('editor'),
        set::value($risk->resolution),
        set::rows(6)
    ),
    formGroup
    (
        setClass('not-track'),
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::value(''),
        set::rows(6)
    )
);
