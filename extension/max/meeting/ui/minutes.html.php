<?php
/**
 * The minutes of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@chandao.com>
 * @package     minutes
 * @version     $Id: minutes.html.php 4903 2024-08-13 19:32:59Z lyc $
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    formGroup
    (
        setClass('minutesBox'),
        set::label($lang->meeting->minutes),
        set::name('minutes'),
        set::items($meeting->minutes),
        set::value($meeting->minutes),
        set::control(array('control' => 'editor', 'rows' => 6))
    ),
    formGroup
    (
        set::label($lang->meeting->files),
        $meeting->files ? fileList
        (
            set::files($meeting->files),
            set::fieldset(false),
            set::showDelete(false),
            set::showEdit(false),
            set::object($meeting)
        ) : null,
        fileSelector()
    ),
    set::submitBtnText($lang->save)
);

hr();
history();
