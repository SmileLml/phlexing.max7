<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
<div id="mainContent" class="main-row fade">
  <div class='side-col col-lg' id='sidebar'>
    <?php include './blockreportlist.html.php';?>
  </div>
  <div class="main-col">
    <div class="container panel">
    <div class='panel-body'>
      <h1 class='text-center'><?php echo $report->name;?></h1>
      <?php echo htmlspecialchars_decode($report->content);?>
   </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
