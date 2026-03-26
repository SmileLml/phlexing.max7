<?php
/**
 * The admin ui view file of workflowlayout module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowlayout
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php
if(!isset($emptyCustomFields))
{
    $canAddUI = common::hasPriv('workflowlayout', 'addUI');
    if($action->type == 'batch') $canAddUI = false;
    if(!in_array($action->method, array('edit', 'operate', 'view'))) $canAddUI = false;
    if($canAddUI) $modalActions = html::a(inlink('addUI', "module={$action->module}&action={$action->action}"), '<i class="icon-plus"></i> ' . $lang->workflowlayout->addUI, '', "class='btn btn-primary loadInModal iframe'");
}
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php if(isset($emptyCustomFields)):?>
<div class='alert alert-warning'>
  <p class='clearfix'><?php echo sprintf($lang->workflowlayout->error->emptyCustomFields, $this->createLink('workflowfield', 'browse', "module={$module}"));?></p>
</div>
<?php else:?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('action', $action->action);?>
<?php js::set('method', $action->method);?>
<?php js::set('mode', $mode);?>

<ul id='layoutTabs' class="nav nav-tabs">
  <?php foreach($uiPairs as $uiID => $uiName):?>
  <?php $active = $uiID == $ui ? 'active' : '';?>
  <li class='<?php echo $active;?>' title='<?php echo $uiName;?>'><?php echo html::a(inlink('admin', "module={$action->module}&action={$action->action}&mode=view&ui={$uiID}"), $uiName, '', "class='loadInModal iframe'")?></li>
  <?php endforeach;?>
</ul>

<?php if($mode == 'edit'):?>
<form id='adminLayoutForm' method='post' action='<?php echo inlink('admin', "module=$action->module&action=$action->action&mode=$mode&ui=$ui");?>'>
<?php endif;?>
  <div id='layoutPanel' class='panel'>
    <table id='fixedEnabled' class='table table-layout'>
      <tr class='fixed-enabled head'>
        <?php $cols = $action->method == 'view' ? 4 : 6;?>
        <?php if($action->buildin == '1' && $action->method == 'edit' && $action->layout == 'side') $cols = 7;?>
        <td colspan="<?php echo $cols;?>"><i class='icon-check'></i> <span class='title'><span class='title-bar'><strong><?php echo $lang->workflow->mainTable . $lang->hyphen . $flow->name;?></strong></span></span></td>
      <tr>
    </table>
    <table id='fixedRequired' class='table table-layout'></table>

    <?php include 'fields.html.php';?>
  </div>

  <?php
  if($action->method != 'browse' && $action->type == 'single')
  {
      if($subTables) include 'subtables.html.php';
      if($prevModules) include 'prevmodules.html.php';
  }
  ?>

  <?php /* Form actions. */ ?>
  <?php if($mode == 'edit'):?>
  <div class='form-actions text-center'>
    <div class='btn-group'>
      <?php echo baseHTML::commonButton($lang->selectAll, 'btn btn-default no-margin', "id='allchecker'");?>
      <?php echo baseHTML::commonButton($lang->selectReverse, 'btn btn-default', "id='reversechecker'");?>
    </div>
    <?php echo html::submitButton();?>
  </div>
  <?php endif;?>

<?php if($mode == 'edit'):?>
</form>
<?php endif;?>

<?php /* Page actions. */ ?>
<?php if($mode == 'view'):?>
<?php
$canSetLinkage = $this->loadModel('workflowaction')->isClickable($action, 'browseLinkage');
if($action->type == 'batch') $canSetLinkage = false;
if(!in_array($action->method, array('edit', 'operate', 'create'))) $canSetLinkage = false;
?>
<div class='form-actions text-center'>
  <?php extCommonModel::printLink('workflowlayout', 'admin', "module=$action->module&action=$action->action&mode=edit&ui={$ui}", $lang->workflowlayout->design, "class='btn loadInModal iframe'");?>
  <?php if($canSetLinkage) extCommonModel::printLink('workflowlinkage', 'browse', "action={$action->id}&ui={$ui}", $lang->workflowaction->linkage, "class='btn loadInModal iframe'");?>
  <?php if($ui):?>
  <?php extCommonModel::printLink('workflowlayout', 'editUI', "ui={$ui}", $lang->edit, "class='btn loadInModal iframe'");?>
  <?php if(common::hasPriv('workflowlayout', 'deleteUI')) echo html::a('###', $lang->delete, '', "class='btn deleterUI' data-href='" . inlink('deleteUI', "ui={$ui}") . "'");?>
  <?php endif;?>
  <?php if($action->method == 'view') extCommonModel::printLink('workflowlayout', 'block', "module=$action->module", $lang->workflowlayout->block, "class='btn loadInModal iframe'");?>
</div>
<?php endif;?>

<?php endif;?>
<script>
$(document).off('click', '.deleterUI').on('click', '.deleterUI', function()
{
    if(confirm('<?php echo $lang->workflowlayout->tips->confirmDelete?>'))
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
