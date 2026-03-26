<?php
/**
 * The view view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

include 'nav.html.php';
$members = array();
if(!empty($deploy->members))
{
    foreach(explode(',', $deploy->members) as $user) $members[] = zget($users, $user);
}

$actions = $deploy->deleted ? array() : $this->loadModel('common')->buildOperateMenu($deploy);

$productList = array();
if(!empty($deploy->products))
{
    foreach($deploy->products as $deployProduct)
    {
        $product = zget($products, $deployProduct->product);
        $release = zget($releases, $deployProduct->release);
        $productList[] = h::tr
        (

            h::td(zget($product, 'name', '')),
            h::td($release ? $release : '')
        );
    }
}

panel
(
    div
    (
        set::id('deployMenu'),
        $headers
    ),
    detailBody(setID('viewPage'), sectionList
    (
        section
        (
            setClass('flex-1 mr-4'),
            tableData
            (
                set::useTable(false),
                item
                (
                    set::trClass('border-b pb-4'),
                    set::name($lang->deploy->desc),
                    html($deploy->desc)
                )
            ),
            !empty($deploy->products) ? h::table
            (
                setClass('table table-fixed condensed'),
                h::tr
                (
                    set::className('text-left'),
                    h::th($lang->deploy->product),
                    h::th($lang->deploy->release)
                ),
                $productList
            ) : null
        ),
        section
        (
            setClass('w-1/3'),
            set::title($lang->deploy->lblBasic),
            tableData
            (
                item
                (
                    set::name($lang->deploy->owner),
                    zget($users, $deploy->owner)
                ),
                item
                (
                    set::name($lang->deploy->estimate),
                    substr($deploy->estimate, 0, 16)
                ),
                item
                (
                    set::name($lang->deploy->lblBeginEnd),
                    $deploy->begin ? substr($deploy->begin, 0, 16) . ' ~ ' . substr($deploy->end, 0, 16) : null
                ),
                item
                (
                    set::name($lang->deploy->members),
                    implode(' ', $members)
                ),
                item
                (
                    set::name($lang->deploy->status),
                    zget($lang->deploy->statusList, $deploy->status)
                ),
                item
                (
                    set::name($lang->deploy->createdBy),
                    zget($users, $deploy->createdBy) . $lang->at . $deploy->createdDate
                )
            )
        )
    ), history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'deploy', 'objectID' => $deploy->id)))
    ))
);
div
(
    setClass('detail-actions center sticky mt-4 bottom-4 z-10'),
    floatToolbar
    (
        set::object($deploy),
        to::prefix(backBtn(set::icon('back'), $lang->goback)),
        set::main(zget($actions, 'mainActions', array())),
        set::suffix(zget($actions, 'suffixActions', array()))
    )
);
