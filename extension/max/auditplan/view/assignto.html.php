<?php
/**
 * The assignto of nc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     nc
 * @version     $Id: assignto.html.php 4903 2021-05-27 09:32:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php";?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="center-block">
    <div class='main-header'>
      <h2>
        <span class='prefix label-id'><strong><?php echo $auditplan->id;?></strong></span>
        <?php echo "<span title='{$lang->auditplan->common}'>" . $lang->auditplan->common . '</span>';?>
        <?php if(!isonlybody()):?>
        <small> <?php echo $lang->arrow . $lang->auditplan->assignTo;?></small>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-100px'><?php echo $lang->auditplan->date;?></th>
            <td class='w-p25-f'><?php echo html::input('checkDate', helper::isZeroDate($auditplan->checkDate) ? '' : $auditplan->checkDate, "class='form-control form-date'");?></td>
            <td></td>
          </tr>
          <tr>
            <th class='w-100px'><?php echo $lang->auditplan->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, $auditplan->assignedTo, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->comment;?></th>
            <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <td class='text-center form-actions' colspan='3'><?php echo html::submitButton(); ?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php";?>

