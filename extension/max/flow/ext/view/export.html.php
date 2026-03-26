<?php
/**
 * The export view file of flow module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
chdir('../../view');
if(empty($fields))
{
    include $app->getModuleRoot() . 'common/view/header.lite.html.php';
    if(commonModel::hasPriv('flow.workflowfield', 'setExport'))
    {
        $designLink = baseHTML::a($this->createLink('workflowfield', 'setExport', "module={$module}"), $lang->flow->setExport, "target='_parent'");
    }
    else
    {
        $designLink = $lang->flow->setExport;
    }
    echo "<div class='alert' style='margin-bottom:0px;padding:20px'>" . sprintf($lang->flow->tips->emptyExportFields, $designLink) . '</div>';
}
else
{
    include $app->getModuleRoot() . 'file/view/export.html.php';
}
?>
<?php js::set('moduleName', 'flow');?>
<script>
$(function()
{
    $('#exportType').closest('tr').hide();
})
</script>
