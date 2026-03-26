<?php
/**
 * The create view of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     process
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div class="main-content" id="mainContent">
  <div class="main-header">
    <h2><?php echo $lang->process->create;?></h2>
  </div>
  <form method="post" class="main-form form-ajax" enctype="multipart/form-data" id="processForm">
    <table class="table table-form">
      <tbody>
        <tr>
          <th><?php echo $lang->process->name;?></th>
          <td class="required"><?php echo html::input('name', '', 'class="form-control"');?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->process->type;?></th>
          <td><?php echo html::select('type', $lang->process->$classify, '', 'class="form-control chosen"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->process->abbr;?></th>
          <td><?php echo html::input('abbr', '', 'class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->process->desc;?></th>
          <td colspan="2"><?php echo html::textarea('desc', '', 'row="3"');?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          <?php echo html::hidden('model', $model);?>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
