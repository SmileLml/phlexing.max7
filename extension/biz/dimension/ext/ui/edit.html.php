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

$biPath = $this->app->getModuleExtPath('bi', 'ui');
include $biPath['common'] . 'aclbox.html.php';

modalHeader
(
    to::suffix(div($dimension->createdBy == 'system' ? $lang->dimension->builtinTip : '', setClass('pl-1 text-gray-500')))
);

formPanel
(
    formGroup
    (
        set::label($lang->dimension->name),
        set::required(true),
        input
        (
            set::name('name'),
            set::value($dimension->name)
        )
    ),
    formGroup
    (
        set::label($lang->dimension->code),
        set::required(true),
        input
        (
            set::name('code'),
            set::value($dimension->code),
            set::placeholder($lang->dimension->codeHolder)
        )
    ),
    formGroup
    (
        set::label($lang->dimension->desc),
        textarea(set::name('desc'), set::value($dimension->desc))
    ),
    $dimension->createdBy == 'system' ? null : $fnAclBox($lang->dimension->aclList, $dimension->acl, $dimension->whitelist),
    set::submitBtnText($lang->save)
);

render();
