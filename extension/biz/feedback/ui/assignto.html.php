<?php
/**
 * The complete file of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     task
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($lang->feedback->assign)
);

formPanel
(
    set::submitBtnText($lang->feedback->assign),
    formGroup
    (
        set::width('1/3'),
        set::name('assignedTo'),
        set::label($lang->feedback->assignedTo),
        set::value($feedback->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->bug->mailto),
        mailto(set::items($users), set::value($feedback->mailto))
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    ),
    formHidden('status', $feedback->status)
);
hr();
history();
