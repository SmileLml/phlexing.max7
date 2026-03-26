<?php
/**
 * The edit role view file of approval flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     approvalflow
 * @link        https://www.zentao.net
 */
namespace zin;
global $lang;

$fields = defineFieldList('approvalflow.editRole');

$fields->field('name')
    ->width('full')
    ->control('input')
    ->label($lang->approvalflow->role->name)
    ->value(data('role.name'))
    ->required();

$fields->field('code')
    ->width('full')
    ->label($lang->approvalflow->role->code)
    ->value(data('role.code'))
    ->control('input');

$fields->field('users')
    ->width('full')
    ->control('picker')
    ->multiple(true)
    ->label($lang->approvalflow->role->member)
    ->value(data('role.users'))
    ->items(data('users'));

$fields->field('desc')
    ->width('full')
    ->label($lang->approvalflow->role->desc)
    ->value(data('role.desc'))
    ->control('textarea');
