<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<main id="main">
  <div class="container">
    <div id="mainContent" class='main-content load-indicator'>
      <div class='main-header'>
        <h2><?php echo $lang->story->submit;?></h2>
      </div>
      <form class='main-form' method='post' target='hiddenwin'>
        <table class="table table-form">
          <tr>
            <th><?php echo $lang->story->reviewRange;?></th>
            <td colspan="2">
              <?php echo html::select('reviewRange', $lang->exportTypeList, 'all', "class='form-control'");?>
            </td>
          </tr>
          <tr>
            <th></th>
            <td><?php echo html::submitButton('', '', 'btn btn-primary');?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</main>
