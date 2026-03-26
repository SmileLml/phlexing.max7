<?php
/**
 * The create view file of workflowrule module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflowrule
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->workflowrule->create),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->workflowrule->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->workflowrule->typeList['regex']),
        set::control('textarea'),
        set::name('rule'),
        set::placeholder($lang->workflowrule->placeholder->regex),
        set::required(true)
    )
);

render();
