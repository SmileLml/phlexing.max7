<?php
/**
 * The release view file of workflow module of ZDOO.
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
<?php if(!empty($errors)):?>
<div class='alert alert-warning'>
  <?php foreach($errors as $error):?>
  <p><?php echo $error;?></p>
  <?php endforeach;?>
</div>
<?php else:?>
<?php
js::set('currentModule', $flow->module);
js::set('positionModule', $flow->positionModule);
js::set('position', $flow->position);
js::set('flowApp', $flow->app);
js::set('flowBelong', $flow->belong);
js::set('belongList', $lang->workflow->belongList);
unset($apps['kanban'], $apps[$flow->module]);
?>
<form id='releaseForm' method='post' action='<?php echo inlink('release', "id=$flow->id");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflow->navigator;?></th>
      <td class='required'><?php echo html::select('navigator', $lang->workflow->navigators, $flow->navigator, "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr class='appTR hidden'>
      <th class='w-80px'><?php echo $lang->workflow->app;?></th>
      <td class='required'>
        <div class='input-group'>
          <?php echo html::select('app', $apps, $flow->app, "class='form-control chosen'");?>
          <div class='input-group-addon belongBox hidden'>
            <?php echo html::checkbox('belong', array('' => ''), '', "class='form-control'", 'inline');?>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->position;?></th>
      <td class='required'>
        <div class='input-group'>
          <?php echo html::select('positionModule', $menus, $flow->positionModule, "class='form-control chosen'");?>
          <span class='input-group-addon fix-border'></span>
          <?php echo html::select('position', $lang->workflow->positionList, $flow->position, "class='form-control chosen'");?>
        </div>
      </td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'>
        <?php
        echo html::hidden('parent', $flow->parent);
        echo html::hidden('type', $flow->type);
        echo html::submitButton();
        ?>
      </td>
      <td></td>
    </tr>
  </table>
</form>
<?php endif;?>
<?php include '../../common/view/footer.modal.html.php';?>
