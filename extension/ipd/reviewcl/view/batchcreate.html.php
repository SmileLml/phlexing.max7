<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='reviewclForm' enctype='multipart/form-data'>
    <table class='table table-form'>
      <thead>
        <tr class='text-center'>
          <th class='w-50px'>  <?php echo $lang->reviewcl->id;?></th>
          <th class='w-200px'> <?php echo $lang->reviewcl->object;?></th>
          <th class='w-200px'> <?php echo $lang->reviewcl->category;?></th>
          <th class='required'><?php echo $lang->reviewcl->title;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("objects[$i]",   $lang->baseline->objectList, $object, "class='form-control chosen'");?></td>
          <td><?php echo html::select("categories[$i]", $lang->reviewcl->{$type . 'CategoryList'}, '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("titles[$i]", '', "class='form-control'");?></td>
        </tr>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='4' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton(); ?></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
