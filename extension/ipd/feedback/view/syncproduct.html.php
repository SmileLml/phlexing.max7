<?php
/**
 * The syncproduct file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     feedback
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php js::set('browseLink', $browseLink);?>
<?php js::set('syncConfirm', $lang->feedback->syncConfirm);?>
<?php js::set('feedbackCount', $feedbackCount);?>
<?php js::set('feedbackModuleCount', $feedbackModuleCount);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->feedback->syncProduct;?></h2>
  </div>
  <form class='form-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
    <table class='table table-form'>
      <tr id='ownerBox'>
        <th><?php echo $lang->feedback->syncProductTo;?></th>
        <td><?php echo html::select('syncLevel', $lang->feedback->syncLeveList, '', "class='form-control picker-select'");?></td>
      </tr>
      <tr>
        <th></th>
        <td><i class="icon icon-exclamation-sign text-primary"></i> <?php echo $lang->feedback->syncWarning;?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::hidden('needMerge', 'no');?>
          <?php echo html::submitButton($lang->confirm);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
