<?php
/**
 * The browse view file of dimension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     dimension
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('canNotDelete',  $lang->dimension->canNotDelete);
jsVar('confirmDelete', $lang->dimension->confirmDelete);

$dimensions = initTableData($dimensions, $config->dimension->dtable->fieldList, $this->dimension);

featureBar();

toolBar
(
    hasPriv('dimension', 'create') ? item
    (
        set(array
        (
            'icon' => 'plus',
            'text' => $lang->dimension->create,
            'class' => 'primary pull-right',
            'url' => createLink('dimension', 'create'),
            'data-toggle' => 'modal',
        ))
    ) : null
);

foreach($dimensions as $dimension)
{
    foreach($dimension->actions as $key => $action)
    {
        if($action['name'] == 'delete') $dimension->actions[$key]['data-params'] = $dimension->id;
    }
}

dtable
(
    set::cols($config->dimension->dtable->fieldList),
    set::data($dimensions)
);

render();
