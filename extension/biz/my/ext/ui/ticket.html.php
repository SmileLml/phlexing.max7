<?php
/**
 * The ticket view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'my/ui/header.html.php';

jsVar('feedbackLang', $this->lang->feedback->common);

featureBar
(
    set::current($browseType),
    set::linkParams("mode={$mode}&type={key}&param=&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"),
    li(searchToggle(set::module($app->rawMethod . 'Ticket'), set::open($browseType == 'bysearch')))
);

$cols = $this->loadModel('datatable')->getSetting('my');
if(!empty($cols['product'])) $cols['product']['map']   = $products;
$cols['feedback']['name'] = 'feedbackTip';
$tickets = initTableData($tickets, $cols, $this->ticket);
dtable
(
    set::cols($cols),
    set::data(array_values($tickets)),
    set::userMap($users),
    set::customCols(true),
    set::orderBy($orderBy),
    set::sortLink(createLink('my', $app->rawMethod, "mode={$mode}&type={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::onRenderCell(jsRaw('window.onRenderCell')),
    set::footPager(usePager())
);

pageJS(<<<JAVASCRIPT
/**
 * 来源列显示额外的内容。
 * Display extra content in the title column.
 *
 * @param  object result
 * @param  object info
 * @access public
 * @return object
 */
window.onRenderCell = function(result, {row, col})
{
    if(result && col.name == 'feedbackTip' && row.data.feedbackTip != '')
    {
        if(typeof result[0].props != 'undefined') result[0].props.className = 'overflow-hidden';
        result.push({html: '<span class="label primary-pale whitespace-nowrap w-auto">' + feedbackLang + '</span>'}); // 添加模块标签
    }
    return result;
}
JAVASCRIPT
);

render();
