<?php
/**
 * The retract view file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Deqing Chai <chaideqing@cnezsoft.com>
 * @package     story
 * @version     $Id: close.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
namespace zin;

div
(
    setClass('flex items-center pb-3'),
    div($lang->demand->retract),
    entityLabel
    (
        set::level(1),
        setClass('pl-2'),
        set::text($story->title),
        set::entityID($story->id),
        set::reverse(true)
    )
);

$fields = defineFieldList('demand.retract');

$fields->field('retractedReason')
    ->items($lang->demand->retractedReasonList)
    ->tip($lang->demand->retractedReasonTips)->tipClass('text-warning')
    ->control(array('control' => 'picker', 'className' => 'w-48'));

$fields->field('comment')
    ->control('editor')
    ->rows(6);

$fields->field('status')->value('closed')->hidden(true);

$fields->field('affected')
    ->control(array
    (
        'control'    => 'affected',
        'tasks'      => $story->tasks,
        'executions' => $story->executions,
        'teams'      => isset($story->teams) ? $story->teams : array(),
        'bugs'       => isset($story->bugs) ? $story->bugs : array(),
        'cases'      => isset($story->cases) ? $story->cases : array(),
        'stories'    => empty($story->stories) ? array() : $story->stories
    ));

formPanel
(
    set::fields($fields),
    set::submitBtnText($lang->save)
);

hr();
history();
