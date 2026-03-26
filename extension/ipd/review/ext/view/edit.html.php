<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->review->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <?php if($this->session->hasProduct and $project->model != 'ipd'):?>
          <tr>
            <th><?php echo $lang->review->product;?></th>
            <td><?php echo html::select('product', $products, $review->product, "class='form-control chosen'");?></td>
          </tr>
          <?php else:?>
          <?php echo html::hidden('product', $review->product);?>
          <?php endif;?>
          <tr>
            <th class='w-120px'><?php echo $lang->review->object;?></th>
            <td><?php echo html::select('object', $lang->baseline->objectList, $review->category, "class='form-control chosen' disabled");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->review->title;?></th>
            <td><?php echo html::input('title', $review->title, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->review->deadline;?></th>
            <td><?php echo html::input('deadline', helper::isZeroDate($review->deadline) ? '' : $review->deadline, "class='form-date form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->review->comment;?></th>
            <td colspan='2'><?php echo html::textarea('comment', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->files;?></th>
            <td colspan='2'>
                <?php echo $this->fetch('file', 'printFiles', array('files' => $review->files, 'fieldset' => 'false', 'object' => $review, 'method' => 'edit'));?>
                <?php echo $this->fetch('file', 'buildoldform');?>
            </td>
          </tr>
          <tr>
            <td colspan='3' class='form-actions text-center'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

<?php js::set('stageEndDate',   $stageEndDate);?>
<?php js::set('stageBeginDate', $stageBeginDate);?>
<script>
$(function()
{
    $('#deadline').datetimepicker('setStartDate', stageBeginDate);
    $('#deadline').datetimepicker('setEndDate', stageEndDate);
});
</script>
