<?php
/**
 * The batch edit view of task module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     task
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php
unset($lang->feedback->statusList['replied']);
unset($lang->feedback->statusList['asked']);
unset($lang->feedback->statusList['tobug']);
unset($lang->feedback->statusList['tostory']);
unset($lang->feedback->statusList['commenting']);
?>
<?php js::set('noChangeIDList', $noChangeIDList);?>
<?php js::set('batchEditTip', sprintf($lang->feedback->batchEditTip, $noChangeIDList));?>
<?php js::set('browseType', $browseType);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->feedback->common . $lang->hyphen . $lang->feedback->batchEdit;?>
    </h2>
  </div>
  <form id='ajaxForm' class='main-form' method='post' action="<?php echo inLink('batchEdit', "browseType={$browseType}")?>">
    <div class="table-responsive">
      <table class='table table-form table-fixed with-border'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='required w-300px'><?php echo $lang->feedback->product?></th>
            <th class='w-300px'><?php echo $lang->feedback->module?></th>
            <th class='required'><?php echo $lang->feedback->title?></th>
            <th class='w-150px'><?php echo $lang->feedback->assignedTo;?></th>
            <?php
            $extendFields = $this->feedback->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($feedbacks as $feedback):?>
          <tr>
            <td><?php echo $feedback->id;?></td>
            <td style='overflow:visible'><?php echo html::select("products[$feedback->id]", $products, $feedback->product, "id='product{$feedback->id}' class='form-control chosen' onchange='changeModule(this.value, $feedback->id)'")?></td>
            <td style='overflow:visible'><?php echo html::select("module[$feedback->id]",  zget($moduleList, $feedback->product, ''), $feedback->module, "id='module{$feedback->id}' class='form-control chosen'")?></td>
            <td title='<?php echo $feedback->title;?>'><?php echo html::input("titles[$feedback->id]", $feedback->title, "id='title{$feedback->id}' class='form-control' autocomplete='off'");?></td>
            <td style='overflow:visible'><?php echo html::select("assignedTos[$feedback->id]", $users, $feedback->assignedTo, "id='assignedTo{$feedback->id}' class='form-control chosen'");?></td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $feedback, $extendField->field . "[{$feedback->id}]") . "</td>";?>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<script>
$(function()
{
    $('select[id^=status]').change(function()
    {
        $(this).parent().find('div.closedReason').addClass('hidden');
        if($(this).val() == 'closed')
        {
            $(this).parent().find('div.closedReason').removeClass('hidden');
        }
    })
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
