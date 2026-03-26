<?php
/**
 * The edit chapter view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: editchapter.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $this->createLink('traincourse', 'manageCourse', "courseID={$chapter->course}")?>
<style>
.edui-default{max-width:100%;}
</style>
<?php js::set('initType', $chapter->type);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
  </div>
</div>

<div class="main-row split-row" id="mainRow">
  <?php include './side.html.php';?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-book text-muted"></i>
          <?php echo $chapter->name . " <i class='icon-angle-right'></i> " . $lang->traincourse->editChapter;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
          </div>
        </nav>
      </div>
      <div class='panel-body'>
        <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='ajaxForm'>
          <table class='table table-form'>
            <tbody>
              <?php $class = $chapter->type == 'chapter' ? "class='hidden'" : '';?>
              <tr>
                <th><?php echo $lang->traincourse->type;?></th>
                <td><?php echo html::select('type', $lang->traincourse->typeList, $chapter->type, "class='form-control' onchange='toggleContentBox(this.value)'");?></td>
                <td></td>
              </tr>
              <tr>
                <th><?php echo $lang->traincourse->parentChapter;?></th>
                <td><?php echo html::select('parent', $optionMenu, $chapter->parent,  "class='form-control chosen'");?></td>
                <td></td>
              </tr>
              <tr>
                <th><?php echo $lang->traincourse->chapterName;?></th>
                <td colspan="2"><?php echo html::input('name', $chapter->name, "class='form-control' eutocomplete='off'");?></td>
              </tr>
              <tr id='contentBox'>
                <th><?php echo $lang->traincourse->chapterDesc;?></th>
                <td colspan='2'><?php echo html::textarea('desc', htmlSpecialString($chapter->desc), "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
              </tr>
              <?php if(empty($chapter->files)):?>
              <tr <?php echo $class?>>
                <th><?php echo $lang->traincourse->file;?></th>
                <td colspan='2'><?php echo $this->fetch('file', 'ajaxUploadLargeFile', 'module=traincourse');?></td>
              </tr>
              <?php else:?>
              <tr>
                <th><?php echo $lang->file->common?></th>
                <td colspan='2'><?php echo $this->fetch('file', 'printFiles', array('files' => $chapter->files, 'fieldset' => 'false'));?></td>
              </tr>
              <?php endif;?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan='3' class='text-center form-actions'> <?php echo html::submitButton();?> </td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
