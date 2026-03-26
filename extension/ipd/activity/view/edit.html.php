<?php
/**
 * The edit view of activity module of ZenTaoQC.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     activity
 * @version     $Id: edit.html.php 4903 2020-09-10 12:00:00Z xieqiyu@easycorp.ltd $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->activity->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-120px'><?php echo $lang->activity->process;?></th>
            <td><?php echo html::select('process', $process, $activity->process, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->activity->name;?></th>
            <td><?php echo html::input('name', $activity->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->activity->optional;?></th>
            <td class='checkbox'>
            <?php echo html::radio('optional', array_filter($lang->activity->optionalOptions), $activity->optional);?>
          </tr>
          <tr>
            <th><?php echo $lang->activity->tailorNorm;?></th>
            <td><?php echo html::input('tailorNorm', $activity->tailorNorm, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->activity->content;?></th>
            <td colspan='2'><?php echo html::textarea('content', $activity->content, 'class="form-control"');?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
