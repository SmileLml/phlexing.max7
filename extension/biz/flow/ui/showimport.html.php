<?php
/**
 * The show import view file of flow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;
include 'header.html.php';

$jsRoot = $this->app->getWebRoot() . "js/";
h::import($jsRoot . 'md5.js');

jsVar('moduleName', $flow->module);

$items = $this->flowZen->buildBatchFormItems($fields);

formBatchPanel(set::title($lang->flow->showImport), set::mode('edit'), set::items($items), set::data(array_values($dataList)));
