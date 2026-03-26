<?php
/**
* The close file of demand module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Qiyu Xie<xieqiyu@easycorp.ltd>
* @package     Demand
* @link        https://www.zentao.net
*/
namespace zin;

modalHeader();
formPanel
(
    formGroup
    (
        setID('closedReason'),
        set::name('closedReason'),
        set::label($lang->demand->closedReason),
        set::width('1/3'),
        set::value(''),
        set::items($lang->demand->reasonList),
        on::change()->call('setDemand')
    ),
    formGroup
    (
        set::hidden(true),
        setID('duplicateDemandBox'),
        set::required(true),
        set::label($lang->demand->duplicateDemand),
        set::width('1/3'),
        set::value(''),
        picker
        (
            set::placeholder($lang->demand->duplicateTip),
            set::name('duplicateDemand'),
            set::items($demands)
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('comment')
    )
);
hr();
history();
