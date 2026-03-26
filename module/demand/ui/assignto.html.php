<?php
/**
 * The assignto file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     demand
 * @version     $Id: assignto.html.php 935 2024-04-29 10:14:24Z $
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->demand->assignTo));

formPanel
(
    set::submitBtnText($lang->demand->assignTo),
    formGroup
    (
        set::name('assignedTo'),
        set::label($lang->demand->assignedTo),
        set::width('1/3'),
        set::value($demand->assignedTo),
        set::items($users)
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows(6)
        )
    )
);
hr();
history();
