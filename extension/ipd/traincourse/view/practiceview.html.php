<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/markdown.html.php';?>
<?php
  js::set('practiceID', $practice->id);
  js::set('additionalHTML', $additionalHTML);
  js::set('firstModuleID', isset($firstModule->id) ? $firstModule->id : 0);
  js::set('secondModuleID', isset($secondModule->id) ? $secondModule->id : 0);
?>
<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class='title'>
      <?php echo $lang->practice->all;?>
    </div>
  </div>
  <ul class='breadcrumb'>
    <li><span class='breadcrumb-title'><?php echo html::a(inlink('practice'), $lang->traincourse->practiceAction, '_self', "title='{$lang->traincourse->practiceAction}'");?></span></li>
    <li><span class='breadcrumb-title'><?php echo html::a(inlink('practicebrowse', "moduleID={$firstModule->id}"), $firstModule->name, '_self', "title='{$firstModule->name}'");?></span></li>
    <li><span class='breadcrumb-title'><?php echo html::a(inlink('practicebrowse', "moduleID={$secondModule->id}"), $secondModule->name, '_self', "title='{$secondModule->name}'");?></span></li>
    <li><span class='breadcrumb-title' title='<?php echo $practice->title;?>'><?php echo $practice->title;?></span></li>
  </ul>
</div>
<div id='mainContent' class='main-row fade'>
  <div class='side-col' id='sidebar'>
    <div class='sidebar-toggle'><i class='icon icon-angle-left'></i></div>
    <div class='cell'>
      <?php echo $moduleTree;?>
    </div>
  </div>
  <div class='main-row fade in'>
    <div id='contentBox' class='main-col'>
      <div class='contenthtml'><?php echo "<textarea id='markdownContent'></textarea>";?></div>
    </div>
  </div>
</div>
<?php css::import($jsRoot . 'markdown/simplemde.min.css');?>
<?php js::import($jsRoot . 'markdown/simplemde.min.js'); ?>
<?php js::set('markdownText', htmlspecialchars_decode($practice->content));?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
