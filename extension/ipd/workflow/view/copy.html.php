<?php
/**
 * The copy view file of workflow module of ZDOO.
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
js::set('belongList', $lang->workflow->belongList);
unset($apps['kanban']);
?>
<form id='ajaxForm' method='post' action='<?php echo inlink('copy', "source=$source->module");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflow->source;?></th>
      <td><?php echo $source->name;?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->name;?></th>
      <td><?php echo html::input('name', '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->module;?></th>
      <td><?php echo html::input('module', '', "class='form-control' placeholder='{$lang->workflow->placeholder->module}'");?></td>
      <td></td>
    </tr>
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
    <?php if($source && !empty($this->config->openedApproval)):?>
    <tr>
      <th><?php echo $this->lang->workflow->approval;?></th>
      <td><?php echo html::radio('approval', $lang->workflowapproval->approvalList, $source->approval);?></td>
      <td></td>
    </tr>
    <tr class='approval'>
      <th><?php echo $this->lang->workflowapproval->approvalFlow;?></th>
      <td>
        <?php echo html::select('approvalFlow', array('') + $approvalFlows, $approvalFlow, "class='form-control chosen'");?>
      </td>
      <td></td>
    </tr>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->workflow->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='2' class='form-control'");?></td>
      <td></td>
    </tr>
  </table>
  <div class='form-actions text-center'>
    <?php echo html::hidden('type', $source->type);?>
    <?php echo html::submitButton();?>
  </div>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
