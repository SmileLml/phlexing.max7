<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
#main{min-width: 780px !important; padding: unset;}
#main>.container {padding: unset;}
</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $title;?></h2>
    </div>
  </div>
  <form id='createForm' method='post' class='form-ajax' action='<?php echo inlink('create', "dimensionID=$dimensionID");?>'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->screen->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->screen->desc;?></th>
        <td><?php echo html::textarea('desc', '', "class='form-control' rows='5'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton($lang->screen->next);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
