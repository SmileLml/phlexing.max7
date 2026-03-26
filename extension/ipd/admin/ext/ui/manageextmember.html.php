<?php
/**
 * The license view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->admin->authorUser),
    set::entityText($lang->admin->extensionList[$extCode])
);

jsVar('licenseCount', zget($extProperties, 'user', 0));
jsVar('extGrantCountError', $lang->admin->extGrantCountError);

/* zin: Define the sidebar in main content. */
div
(
    setClass('alert'),
    sprintf($lang->admin->extGrantCountNotice, zget($extProperties, 'user', 0), count($extAuthors))
);
div
(
    setClass('w-full flex mt-3'),
    cell
    (
        setClass('w-1/5'),
        moduleMenu(set(array(
            'modules'       => $deptTree,
            'activeKey'     => $deptID,
            'closeLink'     => $this->createLink('admin', 'manageExtMember', "extCode={$extCode}"),
            'showDisplay'   => false,
            'app'           => $app->tab,
            'toggleSidebar' => false
        )))
    ),
    cell
    (
        setClass('w-4/5'),
        formPanel
        (
            setID('manageExtMember'),
            set::submitBtnText($lang->save),
            set::formClass('border-0'),
            $authorUsers ? formRow
            (
                set::className('group-user-row'),
                formGroup
                (
                    set::className('items-center row-label'),
                    setStyle(array('align-items' => 'center')),
                    set::label($lang->admin->authorUser),
                    set::width('1/10'),
                    checkbox
                    (
                        set::id('allInsideChecker'),
                        set::name('allInsideChecker'),
                        set::className('check-all'),
                        set::checked(true)
                    )
                ),
                formGroup
                (
                    checkList
                    (
                        setClass('flex-wrap w-full h-full group-user-box'),
                        set::name('members[]'),
                        set::items($authorUsers),
                        set::value(implode(',', array_keys($authorUsers))),
                        set::inline(true)
                    )
                )
            ) : null,
            $authorUsers ? h::hr() : null,
            !empty($noAuthorUsers) ? formRow
            (
                set::className('group-user-row'),
                formGroup
                (
                    set::className('items-center row-label'),
                    setStyle(array('align-items' => 'center')),
                    set::label($lang->admin->noAuthorUser),
                    set::width('1/10'),
                    checkbox
                    (
                        set::id('allOtherChecker'),
                        set::name('allOtherChecker'),
                        set::className('check-all')
                    )
                ),
                formGroup
                (
                    checkList
                    (
                        setClass('flex-wrap w-full h-full group-user-box'),
                        set::name('members[]'),
                        set::items($noAuthorUsers),
                        set::inline(true)
                    ),
                    formHidden('foo', '')
                )
            ) : null
        )
    )
);
