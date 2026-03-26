<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->reviewissue->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method="post" enctype="multipart/form-data" id="dataform">
      <table class="table table-form">
        <tbody>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->review;?></th>
            <td><?php echo html::select('review', $reviewList, '', 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->injection;?></th>
            <td><?php echo html::select('injection', '', '', 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->listType;?></th>
            <td><?php echo html::select('category', $category, '', 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->checklist;?></th>
            <td><?php echo html::select('listID', '', '', 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->opinion;?></th>
            <td><?php echo html::input('opinion', '', 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"></th>
            <td colspan="2" class="form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
    $('#review').change(function()
    {
        var reviewID = $(this).val();
        var link = createLink('reviewissue', 'ajaxGetCategory','reviewID=' + reviewID);
        $.post(link, function(data)
        {
            $('#category').replaceWith(data);
            $('#category_chosen').remove();
            $('#category').chosen();
            $('#category').change();
        })

        var link = createLink('reviewissue', 'ajaxGetInjection','reviewID=' + reviewID);
        $.post(link, function(data)
        {
            $('#injection').replaceWith(data);
            $('#injection_chosen').remove();
            $('#injection').chosen();
        })

    });

    function findCheck()
    {
        var reviewID = $("#review").val();
        var category = $("#category").val();

        var link = createLink('reviewissue', 'ajaxGetCheck','reviewID=' + reviewID + '&category=' + category);
        $.post(link, function(data)
        {
            $('#listID').replaceWith(data);
            $('#listID_chosen').remove();
            $('#listID').chosen();
        })
    }

    $('#review').change();
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
