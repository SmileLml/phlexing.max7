<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
  js::set('search', $search);
  js::set('moduleID', $moduleID);
  js::set('firstModuleID', isset($firstModule->id) ? $firstModule->id : 0);
  js::set('secondModuleID', isset($secondModule->id) ? $secondModule->id : 0);
  js::set('updatePracticeTip', $lang->practice->updatePracticeTip);
?>
<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class='title' title="<?php echo empty($moduleItem) ? '' : $moduleItem->name;?>">
      <?php echo empty($moduleItem->name) ? $lang->practice->allPractice : $moduleItem->name;?>
      <?php if($moduleItem):?>
        <a href="<?php echo helper::createLink('traincourse', 'practicebrowse', 'moduleID=0');?>" class="text-muted"><i class="icon icon-sm icon-close"></i></a>
      <?php endif;?>
    </div>
  </div>
  <div class="pull-left">
    <ul class='breadcrumb'>
      <li><span class='breadcrumb-title'><?php echo html::a(inlink('practice'), $lang->traincourse->practiceAction, '_self', "title='{$lang->traincourse->practiceAction}'");?></span></li>
      <?php if($firstModule):?>
      <li><span class='breadcrumb-title'><?php echo html::a(inlink('practicebrowse', "moduleID={$firstModule->id}"), $firstModule->name, '_self', "title='{$firstModule->name}'");?></span></li>
      <?php endif;?>
      <?php if($secondModule):?>
      <li><span class='breadcrumb-title'><?php echo html::a(inlink('practicebrowse', "moduleID={$secondModule->id}"), $secondModule->name, '_self', "title='{$secondModule->name}'");?></span></li>
      <?php endif;?>
    </ul>
  </div>
  <div class='browse-tools'>
    <div class="pull-right flex">
      <?php if(!helper::isIntranet() and common::hasPriv('traincourse', 'updatePractice')):?>
      <div class="btn btn-link update-practice">
        <a id="update-practice"><?php echo $lang->practice->updatePractice;?></a>
      </div>
      <?php endif;?>
      <form method='post' action="<?php echo helper::createLink('traincourse', 'practicebrowse', "moduleID={$moduleID}&brwoseType=search");?>">
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-search"></i></span>
          <?php echo html::input('search', $search, "placeholder='{$lang->practice->searchPlaceholder}' class='search-for form-control' maxlength='20'");?>
          <span class="input-group-btn">
            <?php echo html::submitButton($lang->practice->search, $misc = '', $class = 'btn btn-secondary search-btn');?>
          </span>
        </div>
      </form>
    </div>
  </div>
</div>
<div class='main-row'>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
    </div>
  </div>
  <div id="mainContent">
    <div class='main-col panel practices-panel'>
    <?php foreach ($practices as $practice):?>
    <?php $viewLink = $this->createLink('traincourse', 'practiceview', "id={$practice->id}");?>
      <a <?php echo common::hasPriv('traincourse', 'practiceview') ? "href='{$viewLink}'" : '';?> title="<?php echo strip_tags($practice->title);?>">
        <div class="practice">
          <div class="flex">
              <div class="practice-title"><?php echo $practice->title;?></div>
              <?php if(!empty($practice->labels)):?>
              <div class="labels">
                <?php foreach(explode(',', $practice->labels) as $labelKey => $label):?>
                <div class="label-item <?php echo $config->practice->labelClassList[$labelKey % 3];?>"><?php echo $label;?></div>
                <?php endforeach;?>
              </div>
              <?php endif;?>
          </div>
          <?php if(!empty($practice->summary)):?>
            <div class="practice-intro" title='<?php echo strip_tags($practice->summary);?>'><?php echo $practice->summary;?></div>
          <?php endif;?>
          <div class="practice-contributor"><?php echo sprintf($lang->practice->contributor, $practice->contributor);?></div>
        </div>
      </a>
    <?php endforeach;?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
