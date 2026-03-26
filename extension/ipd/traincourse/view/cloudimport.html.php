<?php
/**
 * The cloudImport view file of traincourse module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package     traincourse
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='unimportedCourseList'>
  <form class="main-table table-course" data-ride="table" method="post" target='hiddenwin' id='unimportedCoursesForm' action="<?php echo $this->createLink('traincourse', 'cloudImport');?>">
    <div class='table-header hl-primary text-primary strong'>
      <?php echo $lang->traincourse->unimportedCourse;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr>
          <th class='c-id text-left'>
            <?php if($courses):?>
            <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
          </th>
          <th class='text-left'><?php echo $lang->traincourse->courseName;?></th>
          <th class='c-category'><?php echo $lang->traincourse->courseCategory;?></th>
          <th class='c-number text-center'><?php echo $lang->traincourse->chapterCount;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $unimportedCount = 0;?>
        <?php foreach($courses as $course):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('courses', array($course->code => '')) . html::hidden('courseCodes[]', $course->code);?>
          </td>
          <td title='<?php echo $course->name;?>' class='text-left c-name'><?php echo $course->name;?></td>
          <td><?php echo $course->category;?></td>
          <td class='text-center'><?php echo count($course->contents);?></td>
        </tr>
        <?php $unimportedCount ++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($unimportedCount):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#importModal"><?php echo $lang->import?></button>
      </div>
      <?php endif;?>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('admin'), $lang->goback, '', "class='btn'");?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>

<div class="modal fade" id='importModal'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-icon"><i class="icon-info-sign icon-lg"></i></div>
        <div class="modal-text"><?php echo $lang->traincourse->importTip;?></div>
        <div class="modal-button"><button type="button" id='submitBtn' class="btn btn-wide btn-primary"><?php echo $lang->confirm;?></button></div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
