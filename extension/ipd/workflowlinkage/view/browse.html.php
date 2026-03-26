<?php
/**
 * The browse view file of workflowlinkage module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlinkage
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php js::set('action', $action->id);?>
<div id='createLinkage'>
  <?php extCommonModel::printLink('workflowlinkage', 'create', "action={$action->id}&ui={$ui}", "<i class='icon-plus'> </i>" . $lang->workflowlinkage->create, "class='btn btn-primary loadInModal iframe'");?>
</div>
<div class='panel main-table'>
  <table class='table table-fixed' id='linkageTable'>
    <thead>
      <tr>
        <th><?php echo $lang->workflowlinkage->source;?></th>
        <th><?php echo $lang->workflowlinkage->target;?></th>
        <th class='w-80px text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $linkages = zget($action->linkages, $ui, array());?>
      <?php foreach($linkages as $key => $linkage):?>
      <?php $sources = zget($linkage, 'sources', array());?>
      <?php $targets = zget($linkage, 'targets', array());?>
      <tr>
        <td>
          <?php
          foreach($sources as $source)
          {
              if(is_array($source)) $source = (object)$source;
              if(!isset($fields[$source->field])) continue;

              $field = $fields[$source->field];
              echo $field->name . zget($config->workflowlinkage->operatorList, $source->operator);
              if($field->control == 'multi-select' or $field->control == 'checkbox')
              {
                  $values = explode(',', $source->value);
                  foreach($values as $value) echo zget($field->options, $value) . ' ' ;
              }
              else
              {
                  echo zget($field->options, $source->value);
              }
          }
          ?>
        </td>
        <td>
          <?php
          foreach($targets as $target)
          {
              if(is_array($target)) $target = (object)$target;
              if(!isset($fields[$target->field])) continue;

              $field = $fields[$target->field];
              echo $field->name . "[{$lang->workflowlinkage->statusList[$target->status]}]&nbsp;&nbsp;&nbsp;&nbsp;";
          }
          ?>
        </td>
        <td class='actions'>
          <?php extCommonModel::printLink('workflowlinkage', 'edit',   "action=$action->id&key=$key&ui={$ui}", $lang->edit, "class='edit loadInModal iframe'");?>
          <?php if(common::hasPriv('workflowlinkage', 'delete')) echo html::a('###', $lang->delete, '', "class='deleterLinkage' data-href='" . inlink('delete', "action=$action->id&key=$key&ui={$ui}") . "'");?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<div class='text-center'>
  <?php extCommonModel::printLink('workflowlayout', 'admin', "module={$action->module}&action={$action->action}&mode=view&ui={$ui}", $lang->goback, "class='btn btn-wide loadInModal iframe'");?>
</div>
<script>
$(document).off('click', '.deleterLinkage').on('click', '.deleterLinkage', function()
{
    if(confirm('<?php echo $lang->confirmDelete?>'))
    {
        let $deleter = $(this);
        $deleter.text('<?php echo $lang->deleteing?>');
        $.getJSON($deleter.attr('data-href'), function(response)
        {
             if(response.result == 'success') $('#triggerModal').load(response.locate, function(){ $.zui.ajustModalPosition(); });
        });
    }
});
</script>
<?php include '../../common/view/footer.modal.html.php';?>
