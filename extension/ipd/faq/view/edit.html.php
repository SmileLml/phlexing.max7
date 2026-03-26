<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('moduleID', $faq->module)?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->faq->edit;?></h2>
    </div>
  </div>
  <form class="main-form form-ajax" method='post' id='dataform'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->faq->product;?></th>
        <td><?php echo html::select('product', $products, $faq->product, "class='form-control chosen' onchange=loadProductModules(this.value)")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->faq->module;?></th>
        <td><?php echo html::select('module', '', '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->faq->question;?></th>
        <td colspan='2'><?php echo html::input('question', $faq->question, "class='form-control'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->faq->answer;?></th>
        <td colspan='2'><?php echo html::textarea('answer', $faq->answer, "class='form-control' rows=8")?></td>
      </tr>
      <tr>
          <td colspan='3' class='text-center form-actions'><?php echo html::submitButton();?></td>
        </tr>
    </table>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
