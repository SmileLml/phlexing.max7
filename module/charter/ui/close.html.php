<?php
/**
 * The close file of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     charter
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('charter.close');

jsVar('browseType', $_SESSION['browseType']);
jsVar('vision', $config->vision);
modalHeader();
formPanel
(
    set::size('normal'),
    set::fields($fields)
);
history();
