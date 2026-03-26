<?php
/**
 * The performable file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yue Ma <mayue@easycorp.ltd>
 * @package     kanban
 * @version     $Id: performable.html.php 935 2022-01-1 14:20:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.checkbox-primary{display: inline-block;width:100px;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><i class='icon icon-cog-outline'></i> <?php echo $lang->execution->ganttSetting;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->execution->gantt->format;?></th>
          <td><?php echo html::radio('zooming', $lang->execution->gantt->zooming, $zooming ? $zooming : 'day');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->customField;?></th>
          <td><?php echo html::checkbox('ganttFields', $customFields, $showFields);?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
