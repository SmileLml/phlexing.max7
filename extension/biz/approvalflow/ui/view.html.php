<?php
/**
 * The view view file of approvalflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     approvalFlow
 * @link        https://www.zentao.net
 */

namespace zin;

$actions = !$flow->deleted ? $this->loadModel('common')->buildOperateMenu($flow) : array();
if(!empty($actions)) $actions = array_merge($actions['mainActions'], $actions['suffixActions']);
foreach($actions as $key => $action)
{
    if(isset($actions[$key]['url']))
    {
        $actions[$key]['url'] = str_replace('{id}', (string)$flow->id, $action['url']);
    }
}

$basicInfoItems = array();
$basicInfoItems[$lang->approvalflow->id]          = array('control' => 'text', 'text' => $flow->id);
$basicInfoItems[$lang->approvalflow->workflow]    = array('control' => 'text', 'text' => zget($workflows, $flow->workflow));
$basicInfoItems[$lang->approvalflow->createdBy]   = array('control' => 'text', 'text' => zget($users, $flow->createdBy));
$basicInfoItems[$lang->approvalflow->createdDate] = array('control' => 'text', 'text' => $flow->createdDate);

$basicInfo = datalist
(
    set::className('approvalflow-basic-info'),
    set::items($basicInfoItems)
);

$tabs = array();
$tabs[] = setting()
    ->group('basic')
    ->title($lang->approvalflow->basicInfo)
    ->children(wg($basicInfo));

$sections = array();
$sections[] = setting()
    ->title($lang->approvalflow->desc)
    ->control('html')
    ->content($flow->desc);

detail
(
    set::object($flow),
    set::sections($sections),
    set::tabs($tabs),
    set::actions(array_values($actions))
);
