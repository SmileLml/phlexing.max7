<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2>
        <span class='label label-id'><?php echo $demand->id;?></span>
        <span title='<?php echo $demand->title;?>'><?php echo $demand->title;?></span>
      </h2>
    </div>
    <form class="load-indicator main-form" method='post' target='hiddenwin' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='thWidth'><?php echo $lang->demand->closedReason;?></th>
            <td class='w-p25-f'><?php echo html::select('closedReason', $this->lang->demand->reasonList, '', 'class="form-control chosen" onchange="setDemand(this.value)"');?></td>
          </tr>
          <tr id='duplicateDemandBox' style='display:none'>
            <th><?php echo $lang->demand->duplicateDemand;?></th>
            <td><?php echo html::select('duplicateDemand', array('' => '') + $demands, '', "class='form-control' placeholder='{$lang->demand->duplicateTip}'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->comment;?></th>
            <td colspan='3'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='4'><?php echo html::submitButton($lang->demand->close);?></td>
          </tr>
        </tbody>
      </table>
    </form>
    <hr/>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
<script>
$(function()
{
    $('#duplicateDemand').picker(
    {
        disableEmptySearch : true,
        dropWidth : 'auto'
    });
});
function setDemand(reason)
{
    if(reason == 'duplicate')
    {
        $('#duplicateDemandBox').show();
    }
    else
    {
        $('#duplicateDemandBox').hide();
    }
}
</script>
