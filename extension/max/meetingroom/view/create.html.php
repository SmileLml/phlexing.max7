<?php
/**
 * The create of meetingroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     meetingroom
 * @version     $Id: create.html.php 4903 2021-06-10 13:53:59Z lyc $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->meetingroom->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->meetingroom->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->meetingroom->position;?></th>
            <td><?php echo html::input('position', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meetingroom->seats;?></th>
            <td><?php echo html::input('seats', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->meetingroom->equipment;?></th>
            <td>
              <div id='equipment' class='required'><?php echo html::checkbox('equipment', $lang->meetingroom->equipmentList);?></div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->meetingroom->openTime;?></th>
            <td>
              <div id='openTime' class='required'><?php echo html::checkbox('openTime', $lang->meetingroom->openTimeList);?></div>
            </td>
          </tr>
          <tr>
            <td colspan='2' class='form-actions text-center'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
