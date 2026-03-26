<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('browseType', $this->session->courseManageBrowseType);?>
<?php js::set('categoryID', $categoryID);?>
<?php js::set('hasDownloadingCourses', !empty($_SESSION['hasDownloadingCourses']) ? $this->session->hasDownloadingCourses : false);?>
<div id='mainMenu' class='clearfix'>
  <div id='sidebarHeader'>
    <div class='title' title="<?php echo empty($category->name) ? '' : $category->name;?>">
      <?php echo empty($category->name) ? $lang->traincourse->all : $category->name;?>
      <?php if($categoryID) echo html::a(inlink('admin', "browseType=bymodule&param=0"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <div class='btn-toolbar browse-tabs pull-left'>
    <?php
    foreach($lang->traincourse->featureBar['admin'] as $type => $label)
    {
        echo html::a($this->inlink('admin', "browseType=$type&categoryID=$categoryID"), "<span class='text'>$label</span>", "", "id='{$type}Tab' class='btn btn-link'");
    }
    ?>
  </div>

  <div class="btn-toolbar pull-right">
    <div class='btn-group'><button type='button' class='btn btn-link' data-toggle='dropdown' id='importAction'><i class='icon icon-import muted'></i><span class='text'><?php echo $lang->import;?></span><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right' id='importActionMenu'>
        <?php
        $batchImportDisabled = common::hasPriv('traincourse', 'batchImport') ? '' : "disabled='disabled'";
        $cloudImportDisabled = (common::hasPriv('traincourse', 'cloudImport') && !helper::isIntranet()) ? '' : "disabled='disabled'";
        ?>
        <li <?php echo common::hasPriv('traincourse', 'batchImport') ? '' : "class='disabled'";?>><?php echo html::a($this->inLink('batchImport'), $lang->traincourse->batchImport, '', "data-width='800' class='iframe text-left' $batchImportDisabled");?></li>
        <li <?php echo (common::hasPriv('traincourse', 'cloudImport') && !helper::isIntranet()) ? '' : "class='disabled'";?>><?php echo html::a($this->inLink('cloudImport'), $lang->traincourse->cloudImport, '', "class='text-left' $cloudImportDisabled");?></li>
      </ul>
    </div>
    <?php commonModel::printLink('traincourse', 'uploadCourse', '', "<i class='icon icon-import'></i> " . $lang->traincourse->uploadCourse, '', "data-width='60%' class='btn btn-primary iframe'");?>
    <?php //commonModel::printLink('traincourse', 'createCourse', '', "<i class='icon icon-plus'></i>" . $lang->traincourse->createCourse, '', "class='btn btn-primary'");?>
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
      <div class="text-center">
          <?php
          if(commonModel::hasPriv('traincourse', 'browseCategory'))
          {
              $browseLink  = $this->createLink('traincourse', 'browseCategory', "type=trainskill&currentModuleID=0&rootID=0&originalType=traincourse");
              //echo html::a($browseLink, $lang->traincourse->manageCategory, '', "class='btn btn-info btn-wide'");
          }
          ?>
          <hr class="space-sm" />
      </div>
    </div>
  </div>
  <?php if(empty($courses)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->traincourse->noCourses;?></span>
      <?php if(commonModel::hasPriv('traincourse', 'uploadCourse') && helper::isIntranet()):?>
      <?php echo html::a($this->createLink('traincourse', 'uploadCourse'), "<i class='icon icon-plus'></i> " . $lang->traincourse->uploadCourse, '', "class='btn btn-info iframe'");?>
      <?php endif;?>
      <?php if(commonModel::hasPriv('traincourse', 'cloudImport') && !helper::isIntranet()):?>
      <?php echo html::a($this->createLink('traincourse', 'cloudImport'), "<i class='icon icon-plus'></i> " . $lang->traincourse->cloudImport, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <div class='main-col'>
    <form class='main-table' method='post' data-ride='table' id='traincourseForm'>
      <table class='table table-fixed has-sort-head' id='courseList'>
        <thead>
          <tr class='text-center'>
            <?php $vars = "browseType=$browseType&categoryID=$categoryID&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
            <th class='c-id'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->traincourse->id);?></th>
            <th class='text-left'> <?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->traincourse->name);?></th>
            <th class='w-80px'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->traincourse->status);?></th>
            <th class='c-importedStatus'><?php commonModel::printOrderLink('importedStatus', $orderBy, $vars, $lang->traincourse->importedStatus);?></th>
            <th class='c-actions-2'><?php echo $lang->actions;?></th>
         </tr>
        </thead>
        <tbody>
          <?php foreach($courses as $course):?>
          <tr class='text-center'>
            <?php $idLink = in_array($course->importedStatus, array('wait', 'doing')) ? '<sapn>' . sprintf('%03d', $course->id) .'</span>' : html::a(inlink('viewcourse', "courseID=$course->id&type=admin"), sprintf('%03d', $course->id));?>
            <td><?php echo $idLink;?></td>
            <?php $viewLink = in_array($course->importedStatus, array('wait', 'doing')) ? "<span>{$course->name}</span>" : html::a(inlink('viewcourse', "courseID=$course->id&type=admin"), $course->name);?>
            <td class='c-name text-left' title="<?php echo $course->name?>"> <?php echo $viewLink;?></td>
            <td><?php echo zget($lang->traincourse->statusList, $course->status);?></td>
            <td><?php echo zget($lang->traincourse->importedStatusList, $course->importedStatus);?></td>
            <td class='c-actions'>
              <?php
              //common::printIcon('traincourse', 'editCourse', "courseID=$course->id", $course, 'list', 'edit', '', "'btn-primary iframe' data-toggle='modal'", true, '', $lang->traincourse->editCourse);
              //common::printIcon('traincourse', 'manageCourse', "courseID=$course->id", $course, 'list', 'plus');

              $icon    = $course->status == 'offline' ? 'arrow-up' : 'arrow-down';
              $title   = $course->status == 'offline' ? $lang->traincourse->upCourse : $lang->traincourse->offCourse;
              $status  = $course->status == 'offline' ? 'online' : 'offline';
              $disable = in_array($course->importedStatus, array('wait', 'doing')) ? "disabled='disabled'" : '';
              common::printIcon('traincourse', 'changeStatus', "courseID=$course->id&status=$status", $course, 'list', $icon, '', '', false, $disable, $title);

              common::printIcon('traincourse', 'deleteCourse', "courseID=$course->id", $course, 'list', 'trash', 'hiddenwin', '', false, $disable);
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </form>
  </div>
  <?php endif;?>
</div>
<script>
$('#module' + categoryID).closest('li').addClass('active');
$('#' + browseType + 'Tab').addClass('btn-active-text').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
