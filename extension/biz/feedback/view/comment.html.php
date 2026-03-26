<?php
/**
 * The comment view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $title;?></h2>
  </div>
  <form method='post' target='hiddenwin' enctype='multipart/form-data'>
    <table class='table table-form'>
      <?php if($type == 'replied'):?>
      <tr>
        <th><?php echo $lang->feedback->faq;?></th>
        <td><?php echo html::select('faq', $faqs, '',  "class='form-control chosen'");?></td>
      </tr>
      <?php endif;?>
      <tr class='hide'>
        <th><?php echo $lang->feedback->status;?></th>
        <td><?php echo html::hidden('status', $type);?></td>
      </tr>
      <?php $this->printExtendFields($feedback, 'table', '', '', $app->rawModule, $app->rawMethod);?>
      <tr>
        <th><?php echo $title;?></th>
        <td colspan='2'><?php echo html::textarea('comment', '',"rows='5' class='w-p100'");?></td>
      </tr>
      <?php if($type == 'asked'):?>
      <tr>
        <th><?php echo $lang->feedback->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildoldform');?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
