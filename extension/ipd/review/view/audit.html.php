<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<style>
#reviewRow {min-height: 380px !important;}
#reviewRow .side-col .cell{min-height: 368px !important;}
#mainContent {z-index: 0 !important;}
.review-result {white-space: nowrap;}
</style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a(inlink('browse', "project=$review->project"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $review->id?></span>
      <span class="text"><?php echo $review->title . $lang->arrow . $lang->review->audit;?></span>
    </div>
  </div>
</div>
<form class='form-ajax' method='post' id="assessForm">
  <div id='reviewRow' class='main-row fade split-row in'>
    <div class='side-col' data-min-width='550'>
      <div class='cell scrollbar-hover'>
      <div class="btn-toolbar">
      </div>
        <?php
        if($review->category == 'PP')
        {
            $ganttType  = 'gantt';
            $productID  = $review->product;
            $projectID  = $review->project;
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
                  echo "<div class='detail-content article-content'>$doc->content</div>";
              }
              elseif(isset($template) and (!isset($doc) or !$doc))
              {
                  echo "<div class='detail-title'>" . zget($lang->baseline->objectList, $review->category) . "</div>";
                  echo "<div class='detail-content article-content'>$template->content</div>";
              }
          }
      }
      ?>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $review->files, 'fieldset' => 'true'));?>
      <?php if(isset($doc)) echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true'));?>
      </div>
    </div>
    <div class='col-spliter' id="splitLine"></div>
    <div class='main-col' data-min-width='600' id="issueList">
      <?php if($cmcl):?>
      <div class='cell scrollbar-hover' id='reviewcl'>
        <div class="detail-title"><?php echo $lang->review->reviewcl;?></div>
        <div class="detail-content article-content">
          <table class='table reviewcl'>
            <thead>
              <tr>
                 <th class='text-center w-90px'><?php echo $lang->review->listCategory;?></th>
                 <th class='w-150px'><?php echo $lang->review->listItem;?></th>
                 <th><?php echo $lang->review->listTitle;?></th>
                 <th class='w-90px'><?php echo $lang->review->listResult;?></th>
                 <th><?php echo $lang->review->opinion;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($cmcl as $category => $list):?>
              <tr>
                 <td rowspan=<?php echo count($list);?> class='text-center'><strong><?php echo zget($typeList, $category);?></strong></td>
                 <?php $i = 0 ;?>
                 <?php foreach($list as $data):?>
                 <?php $i++ ;?>
                 <?php if($i != 1) echo "<tr>"?>
                 <td title='<?php echo zget($items, $data->title);?>'><?php echo zget($items, $data->title);?></td>
                 <td><?php echo html::a($this->createLink('cmcl', 'view', "id=$data->id"), $data->contents, '', "title=$data->contents");?></td>
                 <td><?php echo html::radio("issueResult[$data->id]", $lang->review->checkList, '1', "class='issueResult'", 'block');?></td>
                 <td class="issue-opintion">
                 <textarea name="<?php echo "issueOpinion[$data->id]";?>" id="<?php echo "issueOpinion$data->id";?>" rows="1" class="form-control opinion" readonly></textarea>
                   <div class="input-group opinionDate hidden" style="margin-top:5px;">
                     <span class="input-group-addon"><?php echo $lang->review->opinionDate;?></span>
                     <?php echo html::input("opinionDate[$data->id]", isset($data->opinionDate) ? $data->opinionDate : '', "class='form-control form-date'");?>
                   </div>
                 </td>
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
        <table class='table table-borderless'>
          <tr>
            <th class='w-80px'><?php echo $lang->review->auditResult;?></th>
            <td class='review-result'><?php echo html::radio('result', $lang->review->auditResultList, isset($result->result) ? $result->result : 'pass');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->review->auditedDate;?></th>
            <td><?php echo html::input('createdDate', helper::today(), 'class="form-control form-date"');?></td>
            <td>
              <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->review->consumed;?></span>
              <?php echo html::input('consumed', isset($result->consumed) ? $result->consumed : 0, "class='form-control'");?>
              <span class='input-group-addon'>h</span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='5'>
              <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->review->auditOpinion;?></span>
              <?php echo html::textarea('opinion', isset($result->opinion) ? $result->opinion : '', "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='5' class='text-center'>
            <?php echo html::hidden('mode', empty($result) ? 'new' : 'edit');?>
            <?php echo html::submitButton();?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</form>
<style>
.review-footer{margin-top: 10px; height: 165px;}
.review-footer table th{vertical-align: middle}
.reviewcl td{padding: 4px 10px !important;}
</style>
<?php js::set('stopSubmit', $lang->review->stopSubmit);?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
