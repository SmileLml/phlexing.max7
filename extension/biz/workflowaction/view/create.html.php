<?php
/**
 * The create view file of workflowaction module of ZDOO.
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
<form id='ajaxForm' method='post' action='<?php echo inlink('create', "module=$flow->module");?>'>
  <table class='table table-form' id='actionTable'>
    <tr>
      <th class='w-120px'><?php echo $lang->workflowaction->name;?></th>
      <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->action;?></th>
      <td class='required'><?php echo html::input('action', '', "class='form-control' placeholder='{$lang->workflowaction->placeholder->code}'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->type;?></th>
      <?php if($flow->buildin):?>
      <?php unset($lang->workflowaction->typeList['batch']);?>
      <?php endif;?>
      <td><?php echo html::select('type', $lang->workflowaction->typeList, '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr class='hide'>
      <th><?php echo $lang->workflowaction->batchMode;?></th>
      <td><?php echo html::select('batchMode', $lang->workflowaction->batchModeList, '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->open;?></th>
      <td><?php echo html::select('open', $lang->workflowaction->openList, '', "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->position;?></th>
      <td>
        <?php
        $positionList = $lang->workflowaction->positionList;
        $position     = 'browseandview';
        if($flow->buildin)
        {
            unset($positionList['menu']);
            $position = 'view';
        }
        echo html::select('position', $positionList, $position, "class='form-control'");
        ?>
      </td>
      <td><a data-toggle='tooltip' title='<?php echo $lang->workflowaction->tips->position;?>'><i class='icon-help-circle icon-md'></i></a></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->show;?></th>
      <td><?php echo html::select('show', $lang->workflowaction->showList, '0', "class='form-control'");?></td>
      <td><a data-toggle='tooltip' title='<?php echo $lang->workflowaction->tips->show;?>'><i class='icon-help-circle icon-md'></i></a></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowaction->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows='3' class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'>
        <?php echo html::hidden('module', $flow->module);?>
        <?php echo html::submitButton();?>
      </td>
      <td></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
