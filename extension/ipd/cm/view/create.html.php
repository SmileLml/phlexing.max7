<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->cm->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->cm->object;?></th>
            <td><?php echo html::select('category', $lang->baseline->objectList, isset($review->category) ? $review->category : '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->from;?></th>
            <td><?php echo html::select('from', '', '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->version;?></th>
            <td><?php echo html::input('version', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->title;?></th>
            <td><?php echo html::input('title', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td><?php echo html::hidden('product', isset($review->product) ? $review->product : 0)?></td>
            <td class='form-actions text-center'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
$('#category').change(function()
{
    var category = $(this).val();
    var link     = createLink('cm', 'ajaxGetReviews', "category=" + category + "&projectID=" + projectID);
    $.post(link, function(data)
    {
        $('#from').replaceWith(data);
        $('#from_chosen').remove();
        $('#from').chosen();
    })
})

function getProduct(reviewID)
{
    var link = createLink('cm', 'ajaxGetProduct', "reviewID=" + reviewID);
    $.post(link, function(data)
    {
        $('#product').val(data);
    })
}
$('#category').change();
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
