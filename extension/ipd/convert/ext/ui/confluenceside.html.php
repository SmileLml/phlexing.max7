<?php
/**
 * The side view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

$currentMethod = $app->rawMethod;

if(!empty($_SESSION['confluenceUser']))
{
    $icon = icon('check', setClass('success rounded-full p-1 mx-2'));
}
elseif($currentMethod == 'initconfluenceuser')
{
    $icon = icon('ellipsis-v', setClass('secondary rounded-full rotate-90 p-1 mx-2'));
}
else
{
    $icon = icon('ellipsis-v', setClass('gray-200 rounded-full rotate-90 text-white p-1 mx-2'));
}
$sideBar[] = a(set::href(!empty($_SESSION['confluenceUser']) ? inlink('initConfluenceUser') : 'javascript:;'), div(setClass('h-10 border content-center mb-4'), $icon, span(setClass('text-black'), $lang->convert->confluence->importUser)));

$sideBar[] = a(set::href('javascript:;'), div(setClass('h-10 border content-center mb-4'), $currentMethod == 'importconfluence' ? icon('ellipsis-v', setClass('secondary rounded-full rotate-90 p-1 mx-2')) : icon('ellipsis-v', setClass('gray-200 rounded-full rotate-90 text-white p-1 mx-2')), span(setClass('text-black'), $lang->convert->confluence->importData)));

featureBar();
toolbar
(
    $currentMethod != 'initconfluenceuser' ? item(set(array('text' => $lang->convert->jira->back, 'class' => 'default', 'url' => $currentMethod == 'initconfluenceuser' ? inlink('mapConfluence2Zentao') : inlink('initConfluenceUser')))) : null,
    $currentMethod != 'importconfluence' ? item(set(array('text' => $lang->convert->jira->next, 'class' => 'primary', 'data-on' => 'click', 'data-call' => 'next', 'data-params' => 'event'))) : null
);
