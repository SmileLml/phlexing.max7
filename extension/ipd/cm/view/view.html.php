<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
.checkbox-primary {display: inline-block; line-height: 20px;}
.histories .btn-mini>.icon {position: static; top: -2px; left: -2px;}
.histories-list>li {position: static;}
</style>
<div id="mainMenu" class="clearfix">
   <div class="btn-toolbar pull-left">
     <?php echo html::a(inlink('browse', "project=$baseline->project"), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
     <div class="divider"></div>
     <div class="page-title">
       <span class="label label-id"><?php echo $baseline->id?></span>
       <span class="text" title="<?php echo $baseline->title;?>"><?php echo $baseline->title;?></span>
     </div>
   </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
       <?php if($baseline->category == 'PP') include $app->getModuleRoot() . 'programplan/view/gantt.html.php';?>
       <?php
       if(isset($bookID) and $bookID)
       {
           echo '<div class="tab-pane active" id="book">';
           echo '<ul data-name="docsTree" data-ride="tree" data-initial-state="preserve" class="tree no-margin" data-idx="0">';
           include '../../review/view/book.html.php';
           echo '</ul>';
           echo '</div>';
       }
       else
       {
          if(isset($doc) and $doc)
          {
              echo "<div class='detail-title'>" . $doc->title . "</div>";
              echo "<div class='detail-content article-content'>$doc->content</div>";
          }
          elseif(isset($template) and (!isset($doc) or !$doc))
          {
              echo "<div class='detail-title'>" . zget($lang->baseline->objectList, $baseline->category) . "</div>";
              echo "<div class='detail-content article-content'>$template->content</div>";
          }
       }
       ?>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class="cell">
      <div class='detail-title'><?php echo $lang->cm->basicInfo;?></div>
      <div class='detail-content'>
        <table class='table-data'>
          <tr>
            <th class='w-120px'><?php echo $lang->cm->object;?></th>
            <td><?php echo zget($lang->baseline->objectList, $baseline->category);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->title;?></th>
            <td><?php echo $baseline->title;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->version;?></th>
            <td><?php echo $baseline->version;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->createdBy;?></th>
            <td><?php echo zget($users, $baseline->createdBy);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->cm->createdDate;?></th>
            <td><?php echo $baseline->createdDate;?></td>
          </tr>
        </table>
      </div>
    </div>
    <div class='cell'>
    <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
