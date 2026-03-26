<?php
/**
 * The create file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Huchang Tang<tanghucheng@easycorp.ltd>
 * @package     demand
 * @link        https://www.zentao.net
 */
namespace zin;

$forceReview  = $this->demand->checkForceReview();

$fields = useFields('demand.create');
$fields->field('needNotReview')->value($forceReview ? 0 : 1);
if(!$forceReview) $fields->field('reviewer')->hidden(true);

$fields->autoLoad('pool', 'product,parent,reviewer,assignedTo');

formGridPanel
(
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    to::heading(div
    (
        setClass('panel-title text-lg'),
        $lang->demand->create,
        !$forceReview ? checkbox(setID('needNotReview'), set::rootClass('text-base font-medium'), set::value(1), set::text($lang->demand->needNotReview), set::checked($needReview), on::change('toggleReviewer(e.target)')) : null
    )),
    set::actions(array
    (
        array('text' => $lang->save,              'data-status' => 'active',  'class' => 'primary',   'btnType' => 'submit'),
        array('text' => $lang->demand->saveDraft, 'data-status' => 'draft', 'class' => 'secondary', 'btnType' => 'submit'),
        isInModal() ? null : array('text' => $lang->goback, 'back' => true)
    )),
    set::fields($fields),
    set::loadUrl($loadUrl),
    on::change('[name="undetermined"]', 'toggleProductDropdown')
);
