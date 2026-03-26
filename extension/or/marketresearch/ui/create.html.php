<?php
/**
 * The create view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('marketresearch.create');

$fields->autoload('newMarket', 'market,marketName');

$handleLongTimeChange = jsCallback()->do(<<<'JS'
    const endPicker  = $element.find('[name=end]').closest('[data-zui-datepicker]').zui('datePicker');
    const isLongTime = $element.find('[name=longTime]').prop('checked');
    endPicker.render({disabled: isLongTime});
    if(isLongTime) endPicker.$.setValue('');
    $element.find('[name=days]').attr('disabled', isLongTime ? 'disabled' : null);
JS
);

formGridPanel
(
    on::change('[name=longTime]', $handleLongTimeChange),
    set::modeSwitcher(false),
    set::title($lang->marketresearch->create),
    set::fields($fields),
    set::loadUrl($loadUrl)
);
