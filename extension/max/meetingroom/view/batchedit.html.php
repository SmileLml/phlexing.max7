<?php
/**
 * The batchedit of meetingroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meetingroom
 * @version     $Id: batchedit.html.php 4903 2021-06-10 13:53:59Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->meetingroom->batchEdit;?></h2>
  </div>
  <form class="load-indicator main-form" target='hiddenwin' method='post' enctype='multipart/form-data' id='dataform'>
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
        <?php foreach($rooms as $room):?>
        <tr>
          <?php echo html::hidden("roomIDList[$room->id]", $room->id);?>
          <td><?php echo $room->id;?></td>
          <td><?php echo html::input("name[$room->id]", $room->name, "class='form-control'");?></td>
          <td><?php echo html::input("position[$room->id]", $room->position, "class='form-control'");?></td>
          <td><?php echo html::input("seats[$room->id]", $room->seats, "class='form-control'");?></td>
          <td><?php echo html::select("equipment[$room->id][]", $lang->meetingroom->equipmentList, $room->equipment,  "class='form-control chosen' multiple");?></td>
          <td><?php echo html::select("openTime[$room->id][]", $lang->meetingroom->openTimeList, $room->openTime,  "class='form-control chosen' multiple");?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::submitButton() .  html::linkButton($lang->goback, $this->createLink('meetingroom', 'browse'), 'self', '', 'btn btn-wide');?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
