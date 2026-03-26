<?php
/**
 * The batchcreate of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@cnezsoft.com>
 * @package     risk
 * @version     $Id: batchcreate
 * @link        http://www.zentao.net
 */
namespace zin;
$items = array();

/* Field of id. */
$items[] = array
(
    'name'    => 'id',
    'label'   => $lang->risk->id,
    'control' => 'index',
    'width'   => '32px'
);

/* Field of execution. */
$items[] = array
(
    'name'      => 'execution',
    'label'     => $lang->risk->execution,
    'control'   => array('control' => 'picker'),
    'items'     => $executions,
    'value'     => isset($executionID) ? $executionID : '',
    'width'     => '200px',
    'className' => empty($project->multiple) ? 'hidden' : ''
);

/* Field of name. */
$items[] = array
(
    'name'     => 'name',
    'label'    => $lang->risk->name,
    'control'  => 'input',
    'width'    => '250px',
    'required' => true
);

/* Field of source. */
$items[] = array
(
    'name'    => 'source',
    'label'   => $lang->risk->source,
    'control' => array('control' => 'picker'),
    'items'   => $lang->risk->sourceList,
    'width'   => '100px',
);

/* Field of category. */
$items[] = array
(
    'name'    => 'category',
    'label'   => $lang->risk->category,
    'control' => array('control' => 'picker'),
    'items'   => $lang->risk->categoryList,
    'width'   => '100px',
);

/* Field of strategy. */
$items[] = array
(
    'name'    => 'strategy',
    'label'   => $lang->risk->strategy,
    'control' => array('control' => 'picker'),
    'items'   => $lang->risk->strategyList,
    'width'   => '100px',
);

/* Hidden field of impact. */
$items[] = array
(
    'name'     => 'impact',
    'hidden'   => true,
    'required' => true,
    'label'    => $lang->risk->impact,
    'control'  => array('control' => 'picker'),
    'items'    => $lang->risk->impactList,
    'value'    => 3,
    'width'    => '50px',
);
/* Hidden field of probability. */
$items[] = array
(
    'name'     => 'probability',
    'hidden'   => true,
    'required' => true,
    'label'    => $lang->risk->probability,
    'control'  => array('control' => 'picker'),
    'items'    => $lang->risk->probabilityList,
    'value'    => 3,
    'width'    => '50px',
);
/* Hidden field of rate. */
$items[] = array
(
    'name'    => 'rate',
    'hidden'  => true,
    'label'   => $lang->risk->rate,
    'control' => 'input',
    'value'   => 9,
    'width'   => '50px',
);
/* Hidden field of pri. */
$items[] = array
(
    'name'     => 'pri',
    'hidden'   => true,
    'required' => true,
    'label'    => $lang->risk->pri,
    'control'  => array('control' => 'picker'),
    'items'    => $lang->risk->priList,
    'value'    => 'middle',
    'width'    => '100px',
);

formBatchPanel(set::title($lang->risk->batchCreate), set::pasteField('name'), set::items($items));

render();
