<?php
/**
 * The distribute file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     demand
 * @version     $Id: distribute.html.php 935 2024-05-16 10:26:24Z $
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->demand->distribute));

$fields = defineFieldList('demand.distribute');

$fields->field('productRoadmapBox')
    ->width('full')
    ->control(array
    (
        'control'           => 'productRoadmapBox',
        'preProducts'       => $preProducts,
        'products'          => $products,
        'branchGroups'      => $branchGroups,
        'roadmapPlanGroups' => $roadmapPlanGroups,
        'storyGrades'       => $storyGrades
    ));

$fields->field('comment')
    ->width('full')
    ->control('editor');

formGridPanel
(
    set::formClass('distributeForm'),
    set::submitBtnText($lang->demand->distribute),
    set::fields($fields),
    set::modeSwitcher(false)
);

history();
