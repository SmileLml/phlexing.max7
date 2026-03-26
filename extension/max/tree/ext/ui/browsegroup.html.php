<?php
/**
 * The browsegroup view file of tree module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     tree
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('rootID', $rootID);
jsVar('viewType', 'chart');

h:css('#treeEditor-tree-browsegroup .tree-item-inner>.toolbar {margin-left: 0;}');

$rootGroup = new stdclass();
$rootGroup->id = 0;
$rootGroup->name = $lang->chart->allGroup;

array_unshift($parentModules, $rootGroup);

$parentPath = array();
foreach($parentModules as $group)
{
    $parentPath[] = div
    (
        setClass('row flex-nowrap items-center'),
        a
        (
            setClass('tree-link text-clip'),
            set('href', helper::createLink('tree', 'browsegroup', "dimensionID=$dimensionID&groupID={$group->id}&type={$viewType}")),
            set('data-app', $app->tab),
            set::title($group->name),
            $group->name
        ),
        h::i
        (
            setClass('icon icon-angle-right muted align-middle'),
            setStyle('color', '#313C52')
        )
    );
}

/* Generate module rows. */
$maxOrder   = 0;
$moduleRows = array();
foreach($sons as $son)
{
    if($son->order > $maxOrder) $maxOrder = $son->order;

    $moduleRows[] = formRow
    (
        formGroup
        (
            inputGroup
            (
                setClass('row-module'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[id$son->id]"),
                    set::type('input'),
                    set::value($son->name),
                    set::placeholder($lang->tree->groupName)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[id$son->id]"),
                    set::type('input'),
                    set::value($son->short),
                    set::placeholder($lang->tree->short)
                ),
                input
                (
                    setClass('hidden'),
                    set::name("order[id$son->id]"),
                    set::value($son->order),
                    set::control('hidden')
                )
            ),
            batchActions
            (
                set::actionClass('action-group child-hidden')
            )
        )
    );
}

for($i = 0; $i < \tree::NEW_CHILD_COUNT; $i ++)
{
    $moduleRows[] = formRow
    (
        formGroup
        (
            inputGroup
            (
                setClass('row-module'),
                input
                (
                    setClass('col-module'),
                    set::name("modules[$i]"),
                    set::type('input'),
                    set::value(''),
                    set::placeholder($lang->tree->groupName)
                ),
                input
                (
                    setClass('col-short'),
                    set::name("shorts[$i]"),
                    set::type('input'),
                    set::placeholder($lang->tree->short)
                )
            ),
            batchActions
            (
                set::actionClass('action-group')
            )
        )
    );
}

div
(
    setClass('flex gap-x-4 mb-3'),
    backBtn
    (
        set::icon('back'),
        set::type('secondary'),
        set::url($gobackLink),
        $lang->goback
    ),
    div
    (
        setClass('entity-label flex items-center gap-x-2 text-lg font-bold'),
        $lang->chart->manageGroup
    )
);

div
(
    setClass('row gap-4 mt-2'),
    sidebar
    (
        set::toggleBtn(false),
        set::width(400),
        set::minWidth(350),
        set::maxWidth(550),
        panel
        (
            set::title($title),
            treeEditor
            (
                set('selected', $currentModuleID),
                set('type', $viewType),
                set('items', $tree),
                set('canEdit', hasPriv('tree', 'edit')),
                set('canDelete', hasPriv('tree', 'delete')),
                set::sortable(array('handle' => '.icon-move')),
                set::onSort(jsRaw('window.updateOrder'))
            )
        )
    ),
    div
    (
        setClass('flex-auto'),
        setID('modulePanel'),
        panel
        (
            setClass('pb-4'),
            set::title($lang->chart->manageGroup),
            div
            (
                setClass('flex'),
                div
                (
                    setClass('pr-2 tree-item-content row items-center'),
                    setStyle('max-width', '380px'),
                    setStyle('padding-bottom', '48px'),
                    $parentPath
                ),
                form
                (
                    setClass('flex-1 form-horz'),
                    set::url(helper::createLink('tree', 'manageChild', "root=$dimensionID&viewType=$viewType")),
                    set('data-app', $app->tab),
                    $moduleRows,
                    set::actionsClass('justify-start'),
                    set::submitBtnText($lang->save),
                    input
                    (
                        set::type('hidden'),
                        set::name('parentModuleID'),
                        set::value($currentModuleID),
                        set::control('hidden')
                    ),
                    input
                    (
                        set::type('hidden'),
                        set::name('maxOrder'),
                        set::value($maxOrder),
                        set::control('hidden')
                    )
                )
            )
        )
    )
);

render();
