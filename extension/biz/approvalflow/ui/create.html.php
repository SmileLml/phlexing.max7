<?php
/**
 * The create view file of approval flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     approvalflow
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = defineFieldList('approvalflow.create');

$fields->field('name')
    ->width('1/2')
    ->control('input')
    ->label($lang->approvalflow->name)
    ->required();

$fields->field('workflow')
    ->width('1/2')
    ->control('picker')
    ->label($lang->approvalflow->workflow)
    ->items($workflows)
    ->value($workflow);

$fields->field('workflowNotice')
    ->width('full')
    ->control('static')
    ->label('')
    ->value($lang->approvalflow->warningList['workflow']);

$fields->field('desc')
    ->width('full')
    ->control('textarea')
    ->label($lang->approvalflow->desc);

formPanel
(
    set::title($lang->approvalflow->create),
    set::fields($fields)
);
