<?php
/**
 * The export view file of flow module of ZenTaoPms.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     flow
 * @link        https://www.zentao.net
 */
if(empty($allExportFields))
{
    if(commonModel::hasPriv('flow.workflowfield', 'setExport'))
    {
        $designLink = baseHTML::a($this->createLink('workflowfield', 'setExport', "module={$module}"), $lang->flow->setExport, "target='_parent'");
    }
    else
    {
        $designLink = $lang->flow->setExport;
    }
    echo "<div class='alert'>" . sprintf($lang->flow->tips->emptyExportFields, $designLink) . '</div>';
}
else
{
    include $app->getModuleRoot() . 'file/ui/export.html.php';
}

