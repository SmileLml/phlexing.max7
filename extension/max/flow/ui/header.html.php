<?php
/**
 * The header view file of flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@chandao.com>
 * @package     flow
 * @link        https://www.zentao.net
 */
namespace zin;

global $app;
if(!empty($action->module) && !empty($action->method)) html($app->control->appendExtendCssAndJS($action->module, $action->method));
if(!empty($flowAction->module) && !empty($flowAction->method)) html($app->control->appendExtendCssAndJS($flowAction->module, $flowAction->method));
