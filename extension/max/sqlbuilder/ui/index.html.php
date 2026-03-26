<?php
/**
 * The index view file of sqlbuilder module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xinzhi Qi <qixinzhi@chandao.com>
 * @package     sqlbuilder
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui();

h::css(<<<CSS
#builderPanel>.panel-body {padding: 0 !important;}
CSS
);

h::js(<<<JS
window.sqlBuilderChange = function()
{
    const builder = $('#sqlBuilder').data('sqlbuilder');
    window.parent.sqlBuilderChange?.(builder);
}
JS
);

form
(
    set::actions(array()),
    sqlBuilder
    (
        set::data($data),
        set::tableList($tableList),
        set::steps(array('table', 'field', 'func', 'where', 'group')),
        set::url(createLink('sqlbuilder', 'index', "objectID={$objectID}&objectType={$objectType}")),
        set::afterUpdate('sqlBuilderChange()')
    )
);

render('pagebase');
