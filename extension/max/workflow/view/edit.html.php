<?php
/**
 * The edit view file of workflow module of ZDOO.
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
<?php
js::set('currentModule', $flow->module);
js::set('positionModule', $flow->positionModule);
js::set('position', $flow->position);
js::set('flowApp', $flow->app);
js::set('flowBelong', $flow->belong);
js::set('belongList', $lang->workflow->belongList);
unset($apps['kanban'], $apps[$flow->module]);
?>
<form id='editForm' method='post' action='<?php echo inlink('edit', "id=$flow->id");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflow->name;?></th>
      <td class='required'>
        <div class='input-group'>
          <?php echo html::input('name', $flow->name, "class='form-control'" . ($flow->buildin ? "readonly='readonly'" : ''));?>
          <?php if(!$flow->buildin && $flow->type == 'flow'):?>
          <span class='input-group-addon fix-border br-0'><?php echo $lang->workflow->icon;?></span>
          <div class='input-group-btn'>
            <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
              <span class="control-icon"><i class="icon-<?php echo $flow->icon;?> icon>"></i></span> &nbsp;<span class="caret"></span>
              <input type='hidden' name='icon' value='<?php echo $flow->icon;?>'>
            </button>
            <div class='dropdown-menu pull-right drop-icon'>
              <?php
                foreach($config->workflow->icons as $icon) echo "<span class='icons' data-id='$icon'><i class='icon-$icon'></i></span>";
              ?>
            </div>
          </div>
          <?php endif;?>
        </div>
      </td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->module;?></th>
      <td><?php echo html::input('module', $flow->module, "class='form-control' readonly='readonly'");?></td>
      <td></td>
    </tr>
    <?php if(!$flow->buildin && $flow->type == 'flow'):?>
    <?php $required = $flow->status == 'normal' ? "class='required'" : '';?>
    <tr>
      <th><?php echo $lang->workflow->navigator;?></th>
      <td <?php echo $required;?>><?php echo html::select('navigator', $lang->workflow->navigators, $flow->navigator, "class='form-control'");?></td>
    </tr>
    <tr class='appTR hidden'>
      <th class='w-80px'><?php echo $lang->workflow->app;?></th>
      <td <?php echo $required;?>>
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
      <td <?php echo $required;?>>
        <div class='input-group'>
          <?php echo html::select('positionModule', $menus, $flow->positionModule, "class='form-control chosen'");?>
          <span class='input-group-addon fix-border'></span>
          <?php echo html::select('position', $lang->workflow->positionList, $flow->position, "class='form-control chosen'");?>
        </div>
      </td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->workflow->desc;?></th>
      <td><?php echo html::textarea('desc', $flow->desc, "rows='3' class='form-control'");?></td>
      <td></td>
    </tr>
  </table>
  <div class='form-actions text-center'>
    <?php echo html::hidden('parent', $flow->parent);?>
    <?php echo html::hidden('type', $flow->type);?>
    <?php echo html::submitButton();?>
  </div>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
