<?php
/**
 * The create view file of workflowhook module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowhook
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<form id='createLinkageForm' method='post' action='<?php echo inlink('create', "action={$action->id}&ui=$ui");?>'>
  <table class='table table-form' id='linkageTable'>
    <tr>
      <td class='w-80px'></td>
      <td></td>
      <td class='w-80px'></td>
      <td></td>
      <td class='w-120px'></td>
    </tr>
    <?php $sourceIndex = 1;?>
    <tr>
      <th class='source' rowspan='1'><?php echo $lang->workflowlinkage->source;?></th>
      <td><?php echo html::select("source[$sourceIndex]", $fields, '', "id='source$sourceIndex' class='form-control chosen'");?></td>
      <td><?php echo html::select("operator[$sourceIndex]", $config->workflowlinkage->operatorList, '', "id='operator$sourceIndex' class='form-control'");?></td>
      <td class='value required'><?php echo html::input("value[$sourceIndex]", '', "id='value$sourceIndex' class='form-control'");?></td>
      <td></td>
    </tr>
    <?php $sourceIndex++;?>
    <tr class='errorTR'>
      <th></th>
      <td colspan='4'><div id='source'></div></td>
    </tr>

    <?php $targetIndex = 1;?>
    <tr>
      <th class='target' rowspan='1'><?php echo $lang->workflowlinkage->target;?></th>
      <td><?php echo html::select("target[$targetIndex]", $fields, '', "id='target$targetIndex' class='form-control chosen'");?></td>
      <td class='text-center'><?php echo $lang->workflowlinkage->status;?></td>
      <td><?php echo html::select("status[$targetIndex]", $lang->workflowlinkage->statusList, '', "id='status$targetIndex' class='form-control'");?></td>
      <td>
        <a href='javascript:;' class='btn addTarget'><i class='icon icon-plus'></i></a>
        <a href='javascript:;' class='btn delTarget'><i class='icon icon-close'></i></a>
      </td>
    </tr>
    <?php $targetIndex++;?>

    <tr class='errorTR'>
      <th></th>
      <td colspan='4'><div id='target'></div></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions' colspan='4'>
        <?php
        echo html::hidden('ui', $ui);
        echo html::submitButton();
        extCommonModel::printLink('workflowlinkage', 'browse', "action={$action->id}&ui={$ui}", $lang->goback, "class='btn btn-wide loadInModal iframe'");
        ?>
      </td>
    <tr>
  </table>
</form>

<?php
echo html::select('fieldTemplate', $fields, '', "class='form-control hidden'");

$th        = "<th class='target' rowspan='ROWSPAN'>{$lang->workflowlinkage->target}</th>";
$target    = html::select('target[KEY]', $fields, '', "id='targetKEY' class='form-control chosen'");
$status    = html::select('status[KEY]', $lang->workflowlinkage->statusList, '', "id='statusKEY' class='form-control'");
$targetRow = <<<EOT
  <tr>
    <td>{$target}</td>
    <td class='text-center'>{$lang->workflowlinkage->status}</td>
    <td>{$status}</td>
    <td>
      <a href="javascript:;" class="btn addTarget"><i class="icon icon-plus"></i></a>
      <a href="javascript:;" class="btn delTarget"><i class="icon icon-close"></i></a>
    </td>
  </tr>
EOT;
js::set('th', $th);
js::set('targetRow', $targetRow);
js::set('sourceIndex', $sourceIndex);
js::set('targetIndex', $targetIndex);
?>
<?php include '../../common/view/footer.modal.html.php';?>
