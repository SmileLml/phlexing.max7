<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<main>
  <div class="container">
    <div id="mainContent" class='main-content'>
      <div class='main-header'>
        <h2><?php echo $lang->traincourse->batchImport;?></h2>
      </div>
      <form method='post' class='form-ajax'>
        <div class='article-content'>
          <?php printf($lang->traincourse->batchImportTips1, $savePath);?>
          <ul>
            <li><?php echo $lang->traincourse->batchImportTips2;?></li>
            <li><?php echo $lang->traincourse->batchImportTips3;?></li>
          </ul>
        </div>
        <div class='text-center'>
          <?php echo html::submitButton($lang->import);?>
        </div>
      </form>
    </div>
  </div>
</main>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
