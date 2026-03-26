<?php
/**
 * The import notice view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

$labelWidth = '160px';
formPanel
(
    set::title($lang->convert->confluence->import),
    set::headingClass('justify-start'),
    set::bodyClass('px-0'),
    set::submitBtnText($lang->convert->jira->next),
    set::backUrl(inlink('index')),
    to::heading
    (
        span
        (
            setClass('flex items-center text-gray'),
            icon('exclamation text-warning mr-1'),
            span($lang->convert->jira->importNotice)
        )
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('1.'),
        set::labelWidth($labelWidth),
        $lang->convert->confluence->importSteps[1]
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('2.'),
        set::labelWidth($labelWidth),
        $lang->convert->confluence->importSteps[2]
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('3.'),
        set::labelWidth($labelWidth),
        html(sprintf($lang->convert->confluence->importSteps[3], $app->getTmpRoot()))
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('4.'),
        set::labelWidth($labelWidth),
        $lang->convert->confluence->importSteps[4]
    ),
    formGroup
    (
        setStyle(array('align-items' => 'center')),
        set::label('5.'),
        set::labelWidth($labelWidth),
        $lang->convert->confluence->importSteps[5]
    ),
    formGroup
    (
        set::label($lang->convert->confluence->domain),
        set::labelWidth($labelWidth),
        set::required(true),
        input
        (
            setClass('w-72'),
            set::name('confluenceDomain'),
            set::value(zget($confluenceApi, 'domain', ''))
        )
    ),
    formGroup
    (
        set::label($lang->convert->confluence->admin),
        set::labelWidth($labelWidth),
        set::required(true),
        input
        (
            setClass('w-72'),
            set::name('confluenceAdmin'),
            set::value(zget($confluenceApi, 'admin', ''))
        )
    ),
    formGroup
    (
        set::label($lang->convert->confluence->token),
        set::labelWidth($labelWidth),
        set::required(true),
        input
        (
            setClass('w-72'),
            set::name('confluenceToken'),
            set::value(zget($confluenceApi, 'token', ''))
        )
    )
);

render();
