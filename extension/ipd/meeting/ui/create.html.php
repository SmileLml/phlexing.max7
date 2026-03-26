<?php
/**
 * The create view file of meeting module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     meeting
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('meeting.create');

$fields->autoLoad('project', 'execution,participant');
$fields->autoLoad('execution', 'participant');
$fields->autoLoad('objectType', 'objectID');

$loadUrl = $this->createLink('meeting', 'create', "projectID={project}&executionID={execution}&from={$from}&objectType={objectType}");

formGridPanel
(
    set::title($lang->meeting->create),
    set::fields($fields),
    set::loadUrl($loadUrl)
);
