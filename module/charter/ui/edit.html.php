<?php
/**
 * The edit file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('charter.edit');
$fields->autoLoad('level', '[data-name^=files],productCharterBox,type');

jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('browseType', zget($_SESSION, 'browseType', 'all'));
jsVar('vision', $config->vision);
formGridPanel
(
    set::title($lang->charter->edit),
    set::fields($fields),
    set::modeSwitcher(false),
    set::loadUrl($loadUrl)
);
