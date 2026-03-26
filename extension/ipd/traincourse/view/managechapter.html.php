<?php
/**
 * The manage chapter view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: managechapter.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('path', $node ? explode(',', $node->path) : array(0));?>
<?php $browseLink = $this->createLink('traincourse', 'manageCourse', "courseID=$courseID")?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $course->id?></span>
      <span class="text" title='<?php echo $course->name;?>'><?php echo $course->name;?></span>
    </div>
  </div>
</div>
<div class="main-row split-row" id="mainRow">
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-folder mtext-muted"></i>
          <?php echo $course->name . " <i class='icon-angle-right'></i> " . ($node ? $node->name : $lang->traincourse->manageChapter);?>
        </div>
      </div>
      <div class='panel-body'>
        <form class='load-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='ajaxForm'>
          <table class='table table-form'>
            <thead>
              <tr class='text-center'>
                <th class='w-p10'><?php echo $lang->traincourse->type;?></th>
                <th><?php echo $lang->traincourse->chapterName;?></th>
                <th class='w-p30'><?php echo $lang->traincourse->chapterDesc;?></th>
                <th class='w-80px'><?php echo $lang->actions; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php $maxID = 0;?>
              <?php foreach($children as $child):?>
              <?php $maxID = $maxID < $child->id ? $child->id : $maxID;?>
              <tr class='text-center text-middle'>
                <td><?php echo html::select("type[$child->id]", $lang->traincourse->typeList, $child->type, "class='form-control'");?></td>
                <td><?php echo html::input("name[$child->id]",  $child->name, "class='form-control'");?></td>
                <td><?php echo html::input("desc[$child->id]",  $child->desc, "class='form-control'");?></td>
                <td>
                  <?php echo html::hidden("order[$child->id]", $child->order, "class='order'");?>
                  <?php echo html::hidden("mode[$child->id]", 'update');?>
                  <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
                </td>
              </tr>
              <?php endforeach;?>
              <?php for($i = 0; $i < 5; $i ++):?>
              <tr class='text-center text-middle node'>
                <td><?php echo html::select("type[]", $lang->traincourse->typeList, '', "class='form-control'");?></td>
                <td><?php echo html::input("name[]", '', "class='form-control'");?></td>
                <td><?php echo html::input("desc[]", '', "class='form-control'");?></td>
                <td>
                  <?php echo html::hidden("order[]", '', "class='order'");?>
                  <?php echo html::hidden("mode[]", 'new');?>
                  <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
                </td>
              </tr>
              <?php endfor;?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan='4' class='text-center form-actions'>
                  <?php echo html::submitButton() . html::backButton();?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
<?php js::set('maxID', $maxID)?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
