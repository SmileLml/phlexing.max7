<?php
/**
 * The batch edit view of ticket module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     ticket
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->ticket->common . $lang->hyphen . $lang->ticket->batchEdit;?>
    </h2>
  </div>
  <form id='batchEditForm' class='main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit')?>">
    <div class="table-responsive">
      <table class='table table-form table-fixed with-border'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='required w-200px'><?php echo $lang->ticket->title?></th>
            <th class='required w-200px'><?php echo $lang->ticket->product?></th>
            <th class='required w-200px'><?php echo $lang->ticket->module?></th>
            <th class='w-100px'><?php echo $lang->ticket->pri?></th>
            <th class='w-200px'><?php echo $lang->ticket->type?></th>
            <th class='w-150px'><?php echo $lang->ticket->assignedTo;?></th>
            <?php
            $extendFields = $this->ticket->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tickets as $ticket):?>
          <tr>
            <td><?php echo $ticket->id;?></td>
            <td title='<?php echo $ticket->title;?>'><?php echo html::input("titles[$ticket->id]", $ticket->title, "class='form-control' autocomplete='off'");?></td>
            <td style='overflow:visible'><?php echo html::select("products[$ticket->id]", $products, $ticket->product, "class='form-control chosen' onchange='changeModule(this.value, $ticket->id)'")?></td>
            <td style='overflow:visible'><?php echo html::select("modules[$ticket->id]",  !isset($modules[$ticket->product]) ? array() : $modules[$ticket->product], $ticket->module, "class='form-control chosen'")?></td>
            <td style='overflow:visible'><?php echo html::select("pris[$ticket->id]", $this->lang->ticket->priList, $ticket->pri, "class='form-control chosen'")?></td>
            <td style='overflow:visible'><?php echo html::select("types[$ticket->id]", $this->lang->ticket->typeList, $ticket->type, "class='form-control chosen'")?></td>
            <td style='overflow:visible'><?php echo html::select("assignedTos[$ticket->id]", $users, $ticket->assignedTo, "class='form-control chosen'");?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $ticket, $extendField->field . "[{$ticket->id}]") . "</td>";?>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='7' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php if($batchEditTip): ?>
<script>
$(function()
{
  alert('<?php echo $batchEditTip;?>');
})
</script>
<?php endif; ?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
