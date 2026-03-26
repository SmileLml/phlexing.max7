<?php
/**
 * The book view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

$nodeTree = $this->review->buildBookTree($book, $review, $docID);
panel
(
    setID('bookTree'),
    treeEditor(set::items($nodeTree), set::canSplit(false))
);

render();
