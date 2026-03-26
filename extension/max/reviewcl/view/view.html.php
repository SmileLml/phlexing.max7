<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($this->createLink('reviewcl', $method, "type={$reviewcl->type}object="), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <?php endif;?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $reviewcl->id;?></span>
      <span class="text" title="<?php echo $reviewcl->title;?>"><?php echo $reviewcl->title;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions text-center' style="margin-top:20px">
      <div class="btn-toolbar">
        <?php common::printBack($this->session->reviewcl);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$reviewcl->deleted):?>
        <?php
        common::printIcon('reviewcl', 'edit',   "reviewclID=$reviewcl->id", $reviewcl, 'button', 'edit');
        common::printIcon('reviewcl', 'delete', "reviewclID=$reviewcl->id", $reviewcl, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class="detail">
        <div class='detail-title'><?php echo $lang->reviewcl->basicInfo;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th><?php echo $lang->reviewcl->id;?></th>
              <td><?php echo $reviewcl->id;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->title;?></th>
              <td><?php echo $reviewcl->title;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->object;?></th>
              <td><?php echo zget($lang->baseline->objectList, $reviewcl->object);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->category;?></th>
              <td><?php echo zget($lang->reviewcl->{$reviewcl->type . 'CategoryList'}, $reviewcl->category);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->createdBy;?></th>
              <td><?php echo zget($users, $reviewcl->createdBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->createdDate;?></th>
              <td><?php echo substr($reviewcl->createdDate, 0, 11);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->editedBy;?></th>
              <td><?php echo zget($users, $reviewcl->editedBy);?></td>
            </tr>
            <tr>
              <th><?php echo $lang->reviewcl->editedDate;?></th>
              <td><?php echo substr($reviewcl->editedDate, 0, 11);?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
