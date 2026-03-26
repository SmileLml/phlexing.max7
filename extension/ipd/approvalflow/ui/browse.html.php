<?php
/**
 * The browse view file of approval flow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <sunguangming@easycorp.ltd>
 * @package     approvalflow
 * @link        https://www.zentao.net
 */
namespace zin;

include $app->getModuleRoot() . 'custom/ui/sidebar.html.php';

featureBar();

$canCreate  = common::hasPriv('approvalflow', 'create') && in_array($config->edition, array('max', 'ipd'));
$createItem = array('text' => $lang->approvalflow->create, 'url' => createLink('approvalflow', 'create'));
toolbar($canCreate ? item(set($createItem + array('class' => 'btn primary', 'icon' => 'plus', 'data-toggle' => 'modal'))) : null);

foreach($flows as $flow) $flow->workflow = zget($workflows, $flow->workflow, '');

$cols = $config->approvalflow->dtable->browse->fieldList;
$data = initTableData($flows, $cols, $this->approvalflow);

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::checkable(false),
    set::footPager(usePager())
);
