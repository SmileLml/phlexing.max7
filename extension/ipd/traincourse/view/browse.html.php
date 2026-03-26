<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('categoryID', $categoryID);?>
<?php js::set('browseType', $this->session->courseBrowseType);?>
<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class='title' title="<?php echo empty($category->name) ? '' : $category->name;?>">
      <?php echo empty($category->name) ? $lang->traincourse->allCourses : $category->name;?>
      <?php if($categoryID) echo html::a(inlink('browse', "browseType=byModule&categoryID=0"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <div class='btn-toolbar browse-tabs pull-left'>
    <?php
    foreach ($lang->traincourse->featureBar['browse'] as $key => $label)
    {
        echo html::a($this->inlink('browse', "browseType=$key"), "<span class='text'>$label</span>", '', "id='{$key}Tab' class='btn btn-link'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id="bysearchTab"><i class="icon icon-search muted"></i> <?php echo $lang->traincourse->byQuery;?></a>
  </div>
</div>
<div class='main-row'>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->traincourse->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
    </div>
  </div>
  <div id="mainContent">
    <div class="cell <?php if($browseType == 'bySearch') echo 'show';?>" id="queryBox" data-module='traincourse' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=traincourse');?>"></div>
    <?php if(empty($courses)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted">
        <?php
        if($browseType == 'all' or $browseType == 'bySearch') echo $lang->traincourse->noCourses;
        if($browseType == 'wait')  echo $lang->traincourse->noNotStartCourses;
        if($browseType == 'doing') echo $lang->traincourse->noInProgressCourses;
        if($browseType == 'done')  echo $lang->traincourse->noFinishedCourses;
        ?>
        </span>
      </p>
    </div>
    <?php else:?>
    <div class='main-col'>
      <div class='row'>
        <?php $i = 1;?>
        <?php foreach ($courses as $courseID => $course):?>
        <?php $file = empty($course->files) ? '' : reset($course->files);?>
        <?php $viewLink = $this->createLink('traincourse', 'viewcourse', "id=$course->id");?>
        <div class='col-md-3' data-id='<?php echo $courseID?>'>
          <a href='<?php echo $viewLink?>'/>
            <div class='course'>
              <?php if(!empty($file)):?>
              <div class='top img'>
                <img src='<?php echo $file->webPath;?>' controls='controls' width='100%'/>
              </div>
              <?php else:?>
              <div class='top img bg<?php echo $i?>'>
              </div>
              <?php $i++;?>
              <?php if($i == 13) $i = 1;?>
              <?php endif?>
              <div class='medium'>
                <div class='course-status'>
                  <?php $title = zget($lang->traincourse->progressList, $course->progress);?>
                  <span class='label status-<?php echo $course->progress?>' title='<?php echo $title;?>'><?php echo $title;?></span>
                </div>
                <span class='estimate-info label'>
                  <?php $course->doneCount == 0 ? printf($lang->traincourse->allVideo, $course->articleCount) : printf($lang->traincourse->chapterInfo, $course->doneCount, $course->articleCount); ?>
                </span>
              </div>
              <div class='bottom'>
                <div class='course-title text-ellipsis' title='<?php echo $course->name?>'><?php echo $course->name;?></div>
                <div class='course-desc' title="<?php echo $course->desc;?>">
                <?php echo empty($course->desc) ? $lang->traincourse->noDesc : $course->desc;?>
                </div>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach;?>
      </div>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </div>
    <?php endif;?>
  </div>
</div>
<script>
$('#module' + categoryID).closest('li').addClass('active');
$('#' + browseType + 'Tab').addClass('btn-active-text').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
