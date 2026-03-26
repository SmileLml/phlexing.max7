<?php
/**
 * The cancel of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun LI <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: cancel.html.php 4903 2020 09:21:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php";?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
 <div class='main-header'>
    <h2>
      <span class='prefix label-id'><strong><?php echo $risk->id;?></strong></span>
      <?php echo "<span title='$risk->name'>" . $risk->name . '</span>';?>
    </h2>
  </div>
  <form class='load-indicator main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='w-120px'><?php echo $lang->risk->cancelBy;?></th>
          <td><?php echo html::select('cancelBy', $users, $this->app->user->account, "class='form-control chosen'");?></td>
          <td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->risk->cancelDate;?></th>
          <td><?php echo html::input('cancelDate', helper::today(),  "class='form-control form-date'");?></td>
          <td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->risk->cancelReason;?></th>
          <td><?php echo html::select('cancelReason', $lang->risk->cancelReasonList, '', "class='form-control chosen'");?></td>
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
<?php include $app->getModuleRoot() . "common/view/footer.html.php";?>

