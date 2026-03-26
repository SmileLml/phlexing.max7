<?php
/**
 * The create file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Huchang Tang<tanghucheng@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('currencySymbol', $lang->project->currencySymbol);
jsVar('browseType', $this->session->browseType);
jsVar('vision', $config->vision);

$fields = useFields('charter.create');
$fields->autoLoad('level', '[data-name^=files],productCharterBox,type');

formGridPanel
(
    set::title($lang->charter->create),
    set::fields($fields),
    set::modeSwitcher(false),
    set::loadUrl($loadUrl)
);
