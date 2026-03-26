<?php
/**
 * The batch finish view of ticket module of ZenTaoPMS.
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
      <?php echo $lang->ticket->common . $lang->hyphen . $lang->ticket->batchFinish;?>
    </h2>
  </div>
  <form id='batchEditForm' class='main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchFinish')?>">
    <div class="table-responsive">
      <table class='table table-form table-fixed with-border'>
        <thead>
          <tr>
            <th class='w-50px'><?php echo $lang->idAB;?></th>
            <th class='required w-300px'><?php echo $lang->ticket->title?></th>
            <th class='w-100px'><?php echo $lang->ticket->currentConsumed?></th>
            <th class='required w-200px'><?php echo $lang->ticket->resolvedDate?></th>
            <th class='required w-300px'><?php echo $lang->ticket->resolution?></th>
            <th class='w-300px'><?php echo $lang->comment?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tickets as $ticket):?>
          <tr>
            <td><?php echo $ticket->id;?></td>
            <td title='<?php echo $ticket->title;?>'><?php echo html::input("titles[$ticket->id]", $ticket->title, "class='form-control' autocomplete='off' readonly");?></td>
            <td><?php echo html::number("currentConsumeds[$ticket->id]", 0, "class='form-control' autocomplete='off' min='0.00' step='0.01'");?></td>
            <td><?php echo html::input("resolvedDates[$ticket->id]", helper::today(), "class='form-control form-date'");?></td>
            <td><?php echo html::input("resolutions[$ticket->id]", '', "class='form-control' autocomplete='off'");?></td>
            <td><?php echo html::input("comments[$ticket->id]", '', "class='form-control' autocomplete='off'");?></td>
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
<?php if($batchFinishTip): ?>
<script>
$(function()
{
    alert('<?php echo $batchFinishTip;?>');
})
</script>
<?php endif; ?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
