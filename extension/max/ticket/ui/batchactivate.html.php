<?php
/**
 * The batch activate view file of ticket module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     ticket
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('batchActivateTip', $batchActivateTip);

$this->config->ticket->form->batchActivate['assignedTo']['items'] = $users;

formBatchPanel(set::title($lang->ticket->batchActivate), set::mode('edit'), set::items($this->config->ticket->form->batchActivate), set::data(array_values($tickets)));

render();
