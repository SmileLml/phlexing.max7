<?php
/**
 * The unlinkUR view file of roadmap module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     roadmap
 * @version     $Id: unlinkur.html.php 4129 2024-07-15 15:05:14Z xieqiyu $
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->roadmap->unlinkURAB), set::entityID($story->id), set::entityText($story->title));

formPanel
(
    formGroup(setID('unlinkReason'), set::name('unlinkReason'), set::label($lang->roadmap->unlinkReason), set::items($lang->roadmap->unlinkReasonList), set::width('1/3')),
    formGroup
    (
        set::label($lang->comment),
        set::control('editor'),
        set::name('comment')
    ),
    affected
    (
        set::tasks($story->tasks),
        set::executions($story->executions),
        set::teams(isset($story->teams) ? $story->teams : array()),
        set::bugs(isset($story->bugs ) ? $story->bugs : array()),
        set::cases(isset($story->cases) ? $story->cases : array()),
        set::stories(empty($story->stories) ? array() : $story->stories)

    )
);
hr();
history();
