<?php
/**
 * The batchcreate of meetingroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meetingroom
 * @version     $Id: batchcreate.html.php 4903 2021-06-10 13:55:00Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->meetingroom->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->meetingroom->id;?></th>
          <th class='required'><?php echo $lang->meetingroom->name;?></th>
          <th class='w-160px required'><?php echo $lang->meetingroom->position;?></th>
          <th class='w-80px required'><?php echo $lang->meetingroom->seats;?></th>
          <th class='w-280px required'><?php echo $lang->meetingroom->equipment;?></th>
          <th class='w-280px required'><?php echo $lang->meetingroom->openTime;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
          <td><?php echo html::input("position[$i]", '', "class='form-control'");?></td>
          <td><?php echo html::input("seats[$i]", '', "class='form-control'");?></td>
          <td><?php echo html::select("equipment[$i][]", $lang->meetingroom->equipmentList, '',  "class='form-control chosen' multiple");?></td>
          <td><?php echo html::select("openTime[$i][]", $lang->meetingroom->openTimeList, '',  "class='form-control chosen' multiple");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
