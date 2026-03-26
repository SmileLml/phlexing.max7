<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->reviewcl->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->reviewcl->object;?></th>
            <td><?php echo html::select('object', $lang->baseline->objectList, $reviewcl->object, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->reviewcl->category;?></th>
            <td><?php echo html::select('category', $lang->reviewcl->{$reviewcl->type . 'CategoryList'}, $reviewcl->category, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->reviewcl->title;?></th>
            <td><?php echo html::input('title', $reviewcl->title, "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
