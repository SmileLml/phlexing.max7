<?php
/**
 * The edit view file of workflowgroup module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@chandao.com>
 * @package     workflowgroup
 * @link        https://www.zentao.net
 */
namespace zin;

$fields = defineFieldList('workflowgroup.edit');
$fields->field('name')->control(['control' => 'input', 'class' => 'w-1/2'])->value($group->name)->required();
if($group->type == 'project')
{
    $fields->field('projectModel')->control(['control' => 'checkBtnGroup', 'class' => 'w-1/2'])->items($lang->workflowgroup->projectModelList)->value($group->projectModel)->required()->disabled();
    $fields->field('projectType')->control(['control' => 'checkBtnGroup', 'class' => 'w-1/2'])->items($lang->workflowgroup->projectTypeList)->value($group->projectType)->required()->disabled();
}
$fields->field('desc')->control(['control' => 'textarea', 'rows' => 3])->value($group->desc);

formPanel
(
    set::labelWidth($group->type == 'product' ? '80px' : '120px'),
    set::title($lang->workflowgroup->edit),
    set::fields($fields),
    set::submitBtnText($lang->save)
);

render();
