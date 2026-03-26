<?php
/**
 * The edit view file of workflowrule module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowrule
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('edit', "id={$rule->id}");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-100px'><?php echo $lang->workflowrule->name;?></th>
      <td><?php echo html::input('name', $rule->name, "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowrule->typeList['regex'];?></th>
      <td>
        <?php echo html::textarea('rule', $rule->rule, "rows='5' class='form-control'");?>
        <span class='text-warning'><?php echo $lang->workflowrule->tip->delimiter;?></span>
      </td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'><?php echo html::submitButton();?></td>
      <td></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
