<?php
/**
 * The edit view file of workflowaction module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowaction
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('edit', "id=$action->id");?>'>
  <table class='table table-form' id='actionTable'>
    <tr class='nameTR'>
      <th class='w-100px'><?php echo $lang->workflowaction->name;?></th>
      <td class='required'><?php echo html::input('name', $action->name, "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <?php if($action->buildin):?>
    <tr>
      <th><?php echo $lang->workflowaction->extensionType;?></th>
      <td><?php echo html::select('extensionType', $lang->workflowaction->extensionTypeList, $action->extensionType, "class='form-control'");?></td>
      <td></td>
    </tr>
    <?php else:?>
    <?php if(!in_array($action->action, $config->workflowaction->noDisableActions)):?>
    <tr class='statusTR'>
      <th><?php echo $lang->workflowaction->status;?></th>
      <td><?php echo html::radio('status', $lang->workflowaction->statusList, $action->status);?></td>
      <td></td>
    </tr>
    <?php endif;?>
    <?php if(!$action->virtual):?>
    <?php if(!in_array($action->action, $config->workflowaction->defaultActions)):?>
    <tr>
      <th><?php echo $lang->workflowaction->open;?></th>
      <td>
        <?php if($action->action == 'view') unset($lang->workflowaction->openList['none']);?>
        <?php echo html::select('open', $lang->workflowaction->openList, $action->open, "class='form-control'");?>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->position;?></th>
      <td>
        <?php
        $positionList = $lang->workflowaction->positionList;
        if($flow->buildin)
        {
            unset($positionList['menu']);
        }
        echo html::select('position', $positionList, $action->action == 'browse' ? '' : $action->position, "class='form-control'");
        ?>
      </td>
      <td class='w-40px'><a data-toggle='tooltip' title='<?php echo $lang->workflowaction->tips->position;?>'><i class='icon-help-circle icon-md'></i></a></td>
    </tr>
    <?php endif;?>
    <?php if(!in_array($action->action, $config->workflowaction->noShowActions)):?>
    <tr>
      <th><?php echo $lang->workflowaction->show;?></th>
      <td><?php echo html::select('show', $lang->workflowaction->showList, $action->show, "class='form-control'");?></td>
      <td class='w-40px'>
        <a data-toggle='tooltip' title='<?php echo $lang->workflowaction->tips->show;?>'><i class='icon-help-circle icon-md'></i></a>
      </td>
    </tr>
    <?php endif;?>
    <?php endif;?>
    <?php endif;?>
    <tr>
      <th><?php echo $lang->workflowaction->desc;?></th>
      <td><?php echo html::textarea('desc', $action->desc, "rows='3' class='form-control'");?></td>
      <td></td>
    </tr>
    <tr class='submitTR'>
      <th></th>
      <td class='form-actions'>
        <?php echo html::hidden('module', $action->module);?>
        <?php echo html::submitButton();?>
      </td>
      <td></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
