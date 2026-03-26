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

$fields = useFields('risk.create');
$fields->field('impact')->value(3);
$fields->field('probability')->value(3);
$fields->field('rate')->value(9);
$fields->field('pri')->value('middle');
if(isset($executionID)) $fields->field('execution')->value($executionID);

formGridPanel
(
    on::change('[name=impact]', 'computeIndex'),
    on::change('[name=probability]', 'computeIndex'),
    set::title($lang->risk->create),
    set::modeSwitcher(false),
    set::fields($fields)
);
