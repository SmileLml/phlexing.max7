<?php
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Hucheng Tang <tanghucheng@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

modalHeader(set::title(sprintf($lang->story->confirmRetractTip, $lang->$objectType->common)));

foreach($stories as $story)
{
    div
    (
        set::className('panal-body border border-gray-300'),
        div
        (
            set::className('bg-gray-400 p-4'),
            div
            (
                set::className('label label-id'),
                $story->id
            ),
            $story->title
        ),
        div
        (
            set::className('bg-gray-100 p-4'),
            p("[{$lang->story->legendSpec}]"),
            $story->spec,
            p("[{$lang->story->legendVerify}]"),
            $story->verify
        )
    );
}

formPanel(formGroup
(
    set::name('confirm'),
    set::value('1'),
    set::hidden(true)
), set::submitBtnText($lang->confirm));

render();
