<?php
/**
 * The create view file of issue module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     issue
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = useFields('issue');
$fields->field('execution')->value(data('executionID') ? data('executionID') : 0);
$fields->field('pri')->value(3);
$fields->field('owner')->items($from == 'stakeholder' ? array_merge($teamMembers, $owners) : $teamMembers)->value($owner ? $owner : $app->user->account);

formGridPanel
(
    set::title($lang->issue->create),
    set::modeSwitcher(false),
    set::fields($fields)
);
