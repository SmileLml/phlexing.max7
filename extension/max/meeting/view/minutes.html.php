<?php
/**
 * The minutes of meeting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     minutes
 * @version     $Id: minutes.html.php 4903 2020-09-04 09:32:59Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php";?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
 <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $meeting->id;?></strong></span>
      <?php echo "<span title='$meeting->name'>" . $meeting->name . '</span>';?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' enctype='multipart/form-data' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th><?php echo $lang->meeting->minutes;?></th>
          <td colspan='2'><?php echo html::textarea('minutes', $meeting->minutes, "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->meeting->files;?></th>
          <td colspan='2'>
            <?php echo $this->fetch('file', 'printFiles', array('files' => $meeting->files, 'fieldset' => 'false', 'object' => $meeting, 'method' => 'edit'));?>
            <?php echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85&filesName=minutesFiles&labelsName=minutesFiles');?>
          </td>
        </tr>
        <tr>
          <td class='text-center form-actions' colspan='3'><?php echo html::submitButton(); ?></td>
        </tr>
      </tbody>
    </table>
  </form>
  <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php";?>
