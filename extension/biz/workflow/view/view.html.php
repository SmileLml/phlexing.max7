<?php
/**
 * The detail view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflow 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php if(!helper::isAjaxRequest()):?>
<div class='modal' id='ajaxModal' ref="<?php echo $this->app->getURI();?>">
  <div class='modal-dialog' style='width: 750px'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span><span class='sr-only'>X</span></button>
        <h4 class='modal-title'><?php echo $lang->workflow->view;?></h4>
      </div>
      <div class='modal-body'>
<?php endif;?>
        <div class='panel'>
          <table class='table table-form'>
            <?php if($flow->type == 'flow'):?>
            <tr>
              <th class='w-80px'><?php echo $lang->workflow->app;?></th>
              <td><?php echo zget($apps, $flow->app, '');?></td>
              <td class='w-40px'></td>
            </tr>
            <?php endif;?>
            <tr>
              <th class='w-80px'><?php echo $lang->workflow->name;?></th>
              <td><?php echo $flow->name;?></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->workflow->desc;?></th>
              <td><?php echo $flow->desc;?></td>
              <td></td>
            </tr>
          </table>
        </div>
    <?php $actions = $this->loadModel('action')->getList('workflow', $flow->id);?>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
<?php if(!helper::isAjaxRequest()):?>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function()
{
    $('.clearfix').find('.panel').remove();
    $('#ajaxModal').modal('show', 'fit');
})
</script>
<?php endif;?>
<?php include '../../common/view/footer.modal.html.php';?>
