<?php
/**
 * The create view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      LiuRuoGu <liuruogu@easysoft.com>
 * @package     workflow 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<div class="modal fade" id="coverModal">
  <div class="modal-dialog w-700px">
    <div class="modal-content">
      <div class='modal-header'>
        <?php echo html::closeButton();?>
        <strong class='modal-title'><?php echo $lang->workflowapproval->conflict[0];?></strong>
      </div>
      <div class='modal-body'>
        <p><?php echo $lang->workflowapproval->conflict[1];?></p>
        <p id='coverContent'></p>
        <p><?php echo $lang->workflowapproval->conflict[2];?></p>
        <p><?php echo $lang->workflowapproval->conflict[3];?></p>
        <p><strong class='text-danger'><?php echo $lang->workflowapproval->conflict[4];?></strong></p>
        <p class='form-actions text-center'>
          <?php echo baseHTML::commonButton($lang->workflow->cover, 'btn btn-danger', "id='coverButton' data-dismiss='modal'");?>
          <?php echo baseHTML::commonButton($lang->cancel, 'btn btn-default', "data-dismiss='modal'");?>
        </p>
      </div>
    </div>
  </div>
</div>
