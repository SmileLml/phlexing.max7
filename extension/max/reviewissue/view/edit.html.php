<?php include $app->getModuleRoot() . 'common/view/header.html.php'?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->reviewissue->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method="post" enctype="multipart/form-data" id="dataform">
      <table class="table table-form">
        <tbody>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->review;?></th>
            <td><?php echo zget($reviewList, $issue->review, '');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->injection;?></th>
            <td><?php echo html::select('injection', $stages, $issue->injection, 'class="form-control chosen"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->title;?></th>
            <td><?php echo html::input('title', $issue->title, 'class="form-control"');?></td>
            <td></td>
          </tr>
          <tr>
            <th class="w-120px"><?php echo $lang->reviewissue->opinion;?></th>
            <td><?php echo html::input('opinion', $issue->opinion, 'class="form-control"');?></td>
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
<?php include $app->getModuleRoot() . 'common/view/footer.html.php'?>
