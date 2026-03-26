<?php
/**
 * The edit view file of marketresearch module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     marketresearch
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('marketresearch.edit');

formGridPanel
(
    on::change('[name=longTime]')->do('const $endPicker = $("[name=end]").zui("datePicker"); $endPicker.render({disabled: $(target).prop("checked")}); if($(target).prop("checked")){ $endPicker.$.setValue(""); $("[name=days]").attr("disabled", "disabled");} else{ $("[name=days]").removeAttr("disabled");}'),
    set::modeSwitcher(false),
    set::title($lang->marketresearch->edit),
    set::fields($fields)
);
