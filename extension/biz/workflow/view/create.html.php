<?php
/**
 * The create view file of workflow module of ZDOO.
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
js::set('currentModule', '');
js::set('createTips', $lang->workflow->tips->create);
js::set('notNow', $lang->workflow->notNow);
js::set('toDesign', $lang->workflow->toDesign);
js::set('belongList', $lang->workflow->belongList);
unset($apps['kanban']);
?>
<form id="<?php echo $type == 'table' ? 'ajaxForm' : 'createForm';?>" method='post' action='<?php echo inlink('create', "type=$type&parent=$parent&app=$currentApp");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflow->name;?></th>
      <td class='required'>
        <div class='input-group'>
          <?php echo html::input('name', '', "class='form-control'");?>
          <?php if($type == 'flow'):?>
          <span class='input-group-addon fix-border br-0'><?php echo $lang->workflow->icon;?></span>
          <div class='input-group-btn'>
            <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
              <span class="control-icon"><i class="icon-flow icon>"></i></span> &nbsp;<span class="caret"></span>
              <input type='hidden' name='icon' value='flow'>
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
      <td class='required'><?php echo html::input('module', '', "class='form-control' placeholder='{$lang->workflow->placeholder->module}'");?></td>
      <td></td>
    </tr>
    <?php if($type == 'flow'):?>
    <tr>
      <th><?php echo $lang->workflow->navigator;?></th>
      <td><?php echo html::select('navigator', $lang->workflow->navigators, '', "class='form-control'");?></td>
    </tr>
    <tr class='appTR hidden'>
      <th class='w-80px'><?php echo $lang->workflow->app;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::select('app', $apps, '', "class='form-control chosen'");?>
          <div class='input-group-addon belongBox hidden'>
            <?php echo html::checkbox('belong', array('' => ''), '', "class='form-control'", 'inline');?>
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->position;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::select('positionModule', array(), '', "class='form-control chosen'");?>
          <span class='input-group-addon fix-border'></span>
          <?php echo html::select('position', $lang->workflow->positionList, '', "class='form-control chosen'");?>
        </div>
      </td>
    </tr>
    <?php endif;?>
    <?php if($type == 'flow' && !empty($this->config->openedApproval)):?>
    <tr>
      <th><?php echo $this->lang->workflow->approval;?></th>
      <td><?php echo html::radio('approval', $lang->workflowapproval->approvalList, 'disabled');?></td>
    </tr>
    <tr class='approval'>
      <th><?php echo $this->lang->workflowapproval->approvalFlow;?></th>
      <td><?php echo html::select('approvalFlow', array('') + $approvalFlows, '', "class='form-control chosen'");?></td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->workflow->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='3' class='form-control'");?></td>
    </tr>
    <?php if($type == 'flow' && !empty($this->config->openedApproval)):?>
    <tr><th></th><td class='text-important'><?php echo $this->lang->workflowapproval->openLater;?></td></tr>
    <?php endif;?>
  </table>
  <div class='form-actions text-center'>
    <?php echo html::hidden('parent', $parent);?>
    <?php echo html::hidden('type', $type);?>
    <?php echo html::submitButton();?>
  </div>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
