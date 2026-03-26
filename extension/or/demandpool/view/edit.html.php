<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->demandpool->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->demandpool->name;?></th>
            <td><?php echo html::input('name', $demandpool->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demandpool->owner;?></th>
            <td><?php echo html::select('owner[]', $users, $demandpool->owner, "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demandpool->reviewer;?></th>
            <td><?php echo html::select('reviewer[]', $users, $demandpool->reviewer, "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demandpool->products;?></th>
            <td><?php echo html::select('products[]', $products, $demandpool->products, "class='form-control picker-select' multiple data-placeholder='" . $lang->demandpool->bindProductsTips . "'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demandpool->desc;?></th>
            <td><?php echo html::textarea('desc', $demandpool->desc, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->demandpool->acl;?></th>
            <td><?php echo nl2br(html::radio('acl', $lang->demandpool->aclList, $demandpool->acl, '', 'block'));?></td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='2'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('hasReview', $lang->demandpool->hasReview);?>
<?php js::set('poolID', explode(',', $demandpool->id));?>
<?php js::set('oldOwners', explode(',', $demandpool->owner));?>
<?php js::set('oldReviewers', explode(',', $demandpool->reviewer));?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
