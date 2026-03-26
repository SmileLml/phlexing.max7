<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $review->id;?></span>
        <span><?php echo $review->title;?></span>

        <small><?php echo $lang->arrow . $lang->review->submit;?></small>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->review->reviewer;?></th>
          <td colspan='2' id='reviewerBox'></td>
        </tr>
        <tr>
          <th><?php echo $lang->comment;?></th>
          <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control'");?></td>
        </tr>
        <tr>
          <td class='text-center' colspan='3'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
  </div>
</div>
<?php js::set('projectID', $review->project);?>
<?php js::set('type', $review->category);?>
<script>
$(function()
{
    var link = createLink('review', 'ajaxGetNodes', "project=" + projectID + '&object=' + type);
    $('#reviewerBox').load(link, function(){$(this).find('select').chosen()});
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
