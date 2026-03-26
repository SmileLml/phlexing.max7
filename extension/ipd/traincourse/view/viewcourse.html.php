<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('viewType', $type);?>
<?php $browseLink = $this->createLink('traincourse', 'browse', "");?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <ul class='breadcrumb'><?php foreach($position as $item) echo strpos($item, '</a>') ? "<li>$item</li>" : "<li title='$item'>$item</li>";?></ul>
  </div>
</div>
<div class="main-row main-info">
  <div class="main-col col-8">
    <div class="cell clearfix">
      <div class="item item-img">
        <?php if(!empty($file)):?>
        <img src='<?php echo $file->webPath;?>' controls='controls' width='100%'/>
        <?php else:?>
        <div class="hl-default image"><i class="icon icon-picture"></i></div>
        <?php endif?>
      </div>
      <div class="item content">
        <div class="title text-ellipsis" title='<?php echo$course->name?>'><?php echo $course->name;?></div>
        <div class="detail-content">
          <table class='table table-data'>
            <tr>
              <th class='w-70px'><?php echo $lang->traincourse->desc;?></th>
              <td>
                <div id='descBox' class="" title='<?php echo $course->desc;?>'><?php echo $course->desc;?></div>
              </td>
            </tr>
          </table>
        </div>
        <div class="action">
          <?php
          if(!empty($chapterList) and commonModel::hasPriv('traincourse', 'viewchapter'))
          {
              $study = $lang->traincourse->continueStudy;
              if(empty($activeChapterID))
              {
                  $firstChapter    = reset($chapterList);
                  $activeChapterID = $firstChapter->id;
                  $study           = $lang->traincourse->beginStudy;
              }
              echo html::a($this->createLink('traincourse', 'viewchapter', "chapterID=$activeChapterID"), $study, '',  "class='btn btn-primary'");
          }
          ?>
        </div>
      </div>
    </div>
    <div class='main-actions text-center'>
      <div class="btn-toolbar">
        <?php $title = $lang->goback;?>
        <?php echo html::a($browseLink, '<i class="icon-goback icon-back"></i> ' . $lang->goback, '', "id='back' class='btn' title={$title}");?>
        <?php if($type == 'admin'):?>
        <div class='divider'></div>
        <?php
        echo "<div class='divider'></div>";
        //commonModel::printLink('traincourse', 'manageCourse', "courseID=$course->id", "<i class='icon icon-manage'></i>" . $lang->traincourse->manageCourse, '', "class='btn btn-link'");
        //commonModel::printLink('traincourse', 'editCourse',   "courseID=$course->id", "<i class='icon icon-common-edit icon-edit'></i>", '', "class='btn btn-link' iframe  data-toggle='modal' title='{$lang->traincourse->editCourse}'", true, true);
        commonModel::printLink('traincourse', 'deleteCourse', "courseID=$course->id", "<i class='icon icon-common-edit icon-trash'></i>", 'hiddenwin', "class='btn btn-link'");
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <ul class="nav nav-tabs">
        <li class="active"><a data-tab href="#tabChapter"><?php echo $lang->traincourse->chapter;?></a></li>
        <li id='descTab' class='hidden'><a data-tab href="#tabDesc"><?php echo $lang->traincourse->desc;?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tabChapter">
          <div class="detail">
            <div class='courses detail-content article-content'><?php echo $catalog;?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function() { $('.courses .actions .sort').hide(); })
if(viewType == 'browse')
{
    $('#subNavbar li').removeClass('active');
    $('#subNavbar').find('ul li').each(function()
    {
        var tab = $(this).attr('data-id');
        if(tab == 'coursebrowse') $(this).addClass('active');
    });
}
var descHeight = $('#descBox').height();
if(descHeight > 70)
{
    $('#descBox').addClass('course-desc');
    $('#moreDescBox').removeClass('hidden');
}
$('#moreDescLink').on('click', function()
{
    $('#descTab').removeClass('hidden');
    $('#descTab a').click();
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
