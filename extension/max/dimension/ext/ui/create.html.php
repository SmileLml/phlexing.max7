<?php
/**
 * The create view file of demension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     dimension
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

$biPath = $this->app->getModuleExtPath('bi', 'ui');
include $biPath['common'] . 'aclbox.html.php';

formPanel
(
    formGroup
    (
        set::label($lang->dimension->name),
        set::required(true),
        input(set::name('name'))
    ),
    formGroup
    (
        set::label($lang->dimension->code),
        set::required(true),
        input
        (
            set::name('code'),
            set::placeholder($lang->dimension->codeHolder)
        )
    ),
    formGroup
    (
        set::label($lang->dimension->desc),
        textarea(set::name('desc'))
    ),
    $fnAclBox($lang->dimension->aclList, 'open'),
    set::submitBtnText($lang->save)
);

render();
