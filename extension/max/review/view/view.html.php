<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $browseLink = $this->session->reviewList ? $this->session->reviewList : inlink('browse', "project=$review->project");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <div class="page-title">
      <span class="label label-id"><?php echo $review->id?></span>
      <span class="text"><?php echo $review->title;?></span>
      <?php if($review->deleted):?>
      <span class='label label-danger'><?php echo $lang->review->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class='cell'>
      <div class="detail scrollbar-hover">
      <?php
      if($review->category == 'PP')
      {
          $ganttType  = 'gantt';
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
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $review->files, 'fieldset' => 'true', 'object' => $review));?>
    </div>
    <div class='cell'>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->review->auditOpinion;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($auditOpinion) ? $auditOpinion : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
    </div>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php $params = "reviewID=$review->id"; ?>
        <?php common::printBack($browseLink);?>
        <div class='divider'></div>
        <?php
        common::printIcon('review', 'submit', $params, $review, 'button', 'play', '', 'iframe', true, '', $lang->review->submit);
        if(isset($pendingReviews[$review->id]) and !$review->deleted)
        {
            common::printIcon('review', 'assess', $params, $review, 'button', 'glasses');
        }
        common::printIcon('approval', 'progress', "approval=$approval->id", $review, 'button', 'list-alt', '', 'iframe', true);
        if(!$review->deleted)
        {
            common::printIcon('review', 'edit', $params, $review);
            common::printIcon('review', 'delete', $params, $review, 'button', 'trash', 'hiddenwin');
        }
        ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->review->basicInfo;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tbody>
              <tr>
                <th class='w-100px'><?php echo $lang->review->object;?></th>
                <td><?php echo zget($lang->baseline->objectList, $review->category);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->version;?></th>
                <td><?php echo $review->version;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->status;?></th>
                <td><?php echo zget($lang->review->statusList, $review->status);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->reviewedBy;?></th>
                <td><?php $reviewedBy = explode(',', str_replace(' ', '', $review->reviewedBy)); foreach($reviewedBy as $account) echo ' ' . zget($users, $account);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->reviewer;?></th>
                <td>
                <?php
                foreach($reviewerResult as $account => $resultList)
                {
                    $class = (in_array('', $resultList) || in_array('ignore', $resultList)) ? '' : 'text-gray';
                    echo "<span class='$class'>" . zget($users, $account) . "</span> &nbsp;";
                }
                ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->review->auditedBy;?></th>
                <td><?php echo zget($users, $review->auditedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->deadline;?></th>
                <td><?php echo helper::isZeroDate($review->deadline) ? '' : substr($review->deadline, 0, 19);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->createdBy;?></th>
                <td><?php echo zget($users, $review->createdBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->review->createdDate;?></th>
                <td><?php echo $review->createdDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    var mainHeight = $(window).height() - $('#footer').outerHeight() - $('#header').outerHeight() - 350;
    var sideHeight = mainHeight + 275;
    $('.scrollbar-hover').css('height', sideHeight);
    $('.scrollbar-hover').css('overflow-y', 'auto');
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
