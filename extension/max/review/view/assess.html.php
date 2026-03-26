<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<style>
.checkbox-primary {display: inline-block; line-height: 20px;}
.review-result {white-space: nowrap;}
.gantt_container {overflow-x: auto!important;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php $browseLink = $app->session->reviewList != false ? $app->session->reviewList : inlink('browse', "project=$review->project");?>
    <?php echo html::backButton("<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "btn btn-secondary");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $review->id?></span>
      <span class="text"><?php echo $review->title . $lang->arrow . zget($lang->baseline->objectList, $review->category);?></span>
    </div>
  </div>
</div>
<form class='form-ajax' method='post' id="assessForm">
  <div id='reviewRow' class='main-row fade split-row in'>
    <div class='side-col' data-min-width='550'>
      <div class='cell scrollbar-hover'>
      <?php
      if($review->category == 'PP')
      {
          $ganttType  = $type;
          $productID  = $review->product;
          include $app->getModuleRoot() . 'programplan/view/gantt.html.php';
      }
      ?>
      <?php
      if(isset($bookID) and $bookID)
      {
          echo '<div class="tab-pane active" id="book">';
          echo '<ul data-name="docsTree" data-ride="tree" data-initial-state="preserve" class="tree no-margin" data-idx="0">';
          include './book.html.php';
          echo '</ul>';
          echo '</div>';
      }
      else
      {
          if($review->category != 'PP')
          {
              if(isset($doc) and $doc)
              {
                  echo "<div class='detail-title'>" . $doc->title . "</div>";
                  if($doc->contentType == 'markdown')
                  {
                      echo "<div class='detail-content article-content'><textarea id='markdownContent'>$doc->content</textarea></div>";
                  }
                  else
                  {
                      echo "<div class='detail-content article-content'>$doc->content</div>";
                  }
              }
              elseif(isset($template) and (!isset($doc) or !$doc))
              {
                  echo "<div class='detail-title'>" . zget($lang->baseline->objectList, $review->category) . "</div>";
                  if($template->type == 'markdown')
                  {
                      echo "<div class='detail-content article-content'><textarea id='markdownContent'>$template->content</textarea></div>";
                  }
                  else
                  {
                      echo "<div class='detail-content article-content'>$template->content</div>";
                  }
              }
          }
      }
      ?>
      <?php if(isset($doc) && $doc) echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true'));?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $review->files, 'fieldset' => 'true'));?>
      </div>
    </div>
    <div class='col-spliter' id="splitLine"></div>
    <div class='main-col' data-min-width='600' id="issueList">
      <?php if(!empty($reviewcl)):?>
      <div class='cell scrollbar-hover' id='reviewcl'>
        <div class="detail-title"><?php echo $lang->review->reviewcl;?></div>
        <div class="detail-content article-content">
          <table class='table reviewcl'>
            <thead>
              <tr>
                 <th class='text-center'><?php echo $lang->review->listCategory;?></th>
                 <th><?php echo $lang->review->listTitle;?></th>
                 <th><?php echo $lang->review->listResult;?></th>
                 <th><?php echo $lang->review->opinion;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($reviewcl as $category => $list):?>
              <tr>
                 <td rowspan=<?php echo count($list);?> class='text-center'><strong><?php echo zget($categoryList, $category);?></strong></td>
                 <?php $i = 0 ;?>
                 <?php foreach($list as $data):?>
                 <?php $i++ ;?>
                 <?php if($i != 1) echo "<tr>"?>
                 <td><?php echo html::a($this->createLink('reviewcl', 'view', "id=$data->id", '', true), $data->title, '', "title='$data->title' data-toggle='modal' class='iframe'");?></td>
                 <td><?php echo html::radio("issueResult[$data->id]", $lang->review->checkList, '1', "class='issueResult'", 'block');?></td>
                 <td><textarea name="<?php echo "issueOpinion[$data->id]";?>" id="<?php echo "issueOpinion$data->id";?>" rows="1" class="form-control opinion" readonly></textarea></td>
                 <?php if($i != 1) echo "</tr>"?>
                 <?php endforeach;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php $class = empty($reviewcl) ? '' : 'review-footer';?>
      <div class='cell <?php echo $class;?>'>
        <table class='table table-form'>
          <?php if($setReviewer):?>
          <?php $reviewer = strpos($setReviewer, 'pending-') !== false ? substr($setReviewer, 8) : $setReviewer;?>
          <tr>
            <th class='w-120px'><?php echo $lang->review->setReviewer;?></th>
            <td class='review-result'><?php echo html::select('setReviewer', $users, $reviewer, "class='form-control chosen'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-120px'><?php echo $lang->review->result;?></th>
            <td class='review-result'><?php echo html::radio('result', $lang->review->resultList, isset($result->result) ? $result->result : 'pass');?></td>
          </tr>
          <tr>
            <th class='text-nowrap'><?php echo $lang->review->reviewedDate;?></th>
            <td><?php echo html::input('createdDate', helper::today(), 'class="form-control form-date"');?></td>
            <td>
              <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->review->consumed;?></span>
              <?php echo html::input('consumed', isset($result->consumed) ? $result->consumed : 0, "class='form-control w-120px'");?>
              <span class='input-group-addon'>h</span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='5'>
              <div class='input-group'>
              <span class='input-group-addon w-120px'><?php echo $lang->review->finalOpinion;?></span>
              <?php echo html::textarea('opinion', isset($result->opinion) ? $result->opinion : '', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->review->files;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildoldform', 'fileCount=1&percent=0.85');?></td>
          </tr>
          <tr>
            <td colspan='5' class='text-center'>
            <?php echo html::submitButton();?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</form>
<style>
.review-footer{margin-top: 10px; height: 260px;}
.review-footer table th{vertical-align: middle}
.reviewcl td{padding: 4px 10px !important;}
</style>
<?php js::set('stopSubmit', $lang->review->stopSubmit);?>
<script>
$(function()
{
    var mainHeight = $(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - 370;
    var sideHeight = mainHeight + 275;
    $('.main-col #reviewcl').css('height', mainHeight);
    $('.side-col .cell').css('height', sideHeight);
    $('table.reviewcl .issueResult, table.reviewcl').change(function()
    {
        var result = $(this).val();
        if(result == 1)
        {
            $(this).closest('tr').find('.opinion').attr('readonly', true);
        }
        else
        {
            $(this).closest('tr').find('.opinion').attr('readonly', false);
        }
    })
})
</script>
<?php if((!empty($doc->contentType) and $doc->contentType == 'markdown') or (!empty($template->type) and $template->type == 'markdown')):?>
<?php css::import($jsRoot . "markdown/simplemde.min.css");?>
<?php js::import($jsRoot . 'markdown/simplemde.min.js'); ?>
<script>
$(function()
{
    var simplemde = new SimpleMDE({element: $("#markdownContent")[0],toolbar:false, status: false});
    simplemde.value($('#markdownContent').html());
    simplemde.togglePreview();

    $('#content .CodeMirror .editor-preview a').attr('target', '_blank');
})
</script>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
