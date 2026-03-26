<?php
/**
 * The batch finish view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('batchFinishTip', $batchFinishTip);

formBatchPanel(set::title($lang->ticket->batchFinish), set::mode('edit'), set::items($this->config->ticket->form->batchFinish), set::data(array_values($tickets)));

render();
