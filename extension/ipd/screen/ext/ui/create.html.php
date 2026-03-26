<?php
/**
 * The create view file of screen module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     screen
 * @link        https://www.zentao.net
 */
namespace zin;

$biPath = $this->app->getModuleExtPath('bi', 'ui');
include $biPath['common'] . 'aclbox.html.php';

formPanel
(
    setID('createForm'),
    set::title($title),
    set::submitBtnText($lang->screen->next),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->screen->name),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->screen->desc),
            textarea
            (
                set::name('desc'),
                set::rows('5')
            )
        )
    ),
    $fnAclBox($lang->screen->aclList, 'open')
);

render();
