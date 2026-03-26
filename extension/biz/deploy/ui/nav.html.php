<?php
/**
 * The nav view file of deploy module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     deploy
 * @link        https://www.zentao.net
 */
namespace zin;

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($deploy->id),
            set::level(1),
            set::text($deploy->name)
        )
    )
);

$headers = null;
if(!isInModal())
{
    $navs = array();
    foreach($config->deploy->view->navs as $nav)
    {
        if(!hasPriv('deploy', $nav)) continue;

        $navs[] = li
        (
            setClass('nav-item'),
            a
            (
                $lang->deploy->$nav,
                set::href(inLink($nav, "deployID={$deploy->id}")),
                set('data-app', $app->tab),
                $app->rawMethod == $nav ? setClass('active') : null
            )
        );
    }
    $headers = nav
    (
        setClass('flex-auto'),
        $navs
    );
}
