<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $browseLink = $this->createLink('traincourse', 'viewcourse', "courseID=$course->id");?>
<?php
js::set('chapterID', $chapter->id);
js::set('courseID', $course->id);
js::set('fullscreen', $lang->traincourse->fullscreen);
js::set('retrack', $lang->traincourse->retrack);
js::set('viewType', $browseType);
?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '',  "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $chapter->id?></span>
      <span class="text" title="<?php echo $chapter->name;?>"><i class='icon icon-book'></i> <?php echo $chapter->name;?></span>
    </div>
  </div>
  <?php if($file->extension == 'pdf'):?>
  <div class='pull-right'>
  <?php echo "<a id='pdfFullscreen'><i class='icon icon-fullscreen' title='{$lang->traincourse->fullscreen}'></i> {$lang->traincourse->fullscreen}</a>";?>
  </div>
  <?php endif;?>
</div>
<div class="main-row split-row">
  <div class="main-col col-8">
    <div class="main-col" data-min-width="400">
      <div class="cell">
        <div class="detail">
          <?php if($file):?>
          <?php if($file->extension == 'pdf'):?>
          <?php include './viewpdf.html.php';?>
          <?php else:?>
          <?php include './video.html.php';?>
          <?php endif;?>
          <?php endif;?>
          <div class="detail-content article-content">
            <br>
            <?php if(!empty($chapter->video) && !$file):?>
            <?php $video  = json_decode($chapter->video);?>
            <?php $width  = isset($video->width) ? $video->width : '800';?>
            <?php $height = isset($video->height) ? $video->height : '450';?>
            <video id='videoPlayer' controls='controls' width='<?php echo $width;?>' height='<?php echo $height;?>'>
            <source src='<?php echo $video->link;?>' />
            </video>
            <?php endif;?>
            <p><span class="text-limit" data-limit-size="80"><?php echo $chapter->desc;?></span><a class="text-primary text-limit-toggle small" data-text-expand="<?php echo $lang->expand;?>"  data-text-collapse="<?php echo $lang->collapse;?>"></a></p>
          </div>
          <div class="operate">
            <?php
            $disabled = $status == 'done' ? 'disabled' : '';
            echo html::commonButton($lang->traincourse->finish, "id='finishButton'", "btn btn-secondary btn-sm $disabled");
            ?>
            <?php
            $disabled = $nextChapterID == 'end' || empty($nextChapterID) ? 'disabled' : '';
            echo html::a($this->createLink('traincourse', 'viewchapter', "chapterID=$nextChapterID"), $lang->traincourse->next, '', "class='btn btn-success btn-sm pull-right $disabled'");
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->traincourse->course . $lang->traincourse->chapter;?></div>
        <div class='detail-content courses'><?php echo $catalog;?></div>
      </div>
    </div>
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->traincourse->relatedInfo;?></div>
        <div class="detail-content">
          <table class='table table-data'>
            <tr>
              <th><?php echo $lang->traincourse->course;?></th>
              <td><?php echo $course->name;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->traincourse->teacher;?></th>
              <td><?php echo $course->teacher;?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
if(viewType == 'browse')
{
    $('#subNavbar li').removeClass('active');
    $('#subNavbar').find('ul li').each(function()
    {
        var tab = $(this).attr('data-id');
        if(tab == 'coursebrowse') $(this).addClass('active');
    });
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
