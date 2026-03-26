<?php
/**
 * The manage course view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: managecourse.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php $browseLink = $this->createLink('traincourse', 'admin')?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
  </div>
</div>

<div class="main-row split-row" id="mainRow">
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-folder text-muted"></i>
          <?php echo $course->name;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php commonModel::printLink('traincourse', 'manageChapter', "courseID={$course->id}&nodeID=0", "<i class='icon icon-plus'></i>" . $lang->traincourse->createChapter, '', "class='btn'");?>
          </div>
        </nav>
      </div>
      <div class='panel-body'>
        <?php if(empty($chapter)):?>
        <div class="table-empty-tip">
          <p>
            <span class="text-muted"><?php echo $lang->traincourse->addCatalogTip;?></span>
            <?php echo html::a(helper::createLink('traincourse', 'manageChapter', "courseID={$course->id}&nodeID=0"), "<i class='icon icon-plus'></i>" . $lang->traincourse->createChapter, '', "class='btn btn-info'");?>
          <p>
        </div>
        <?php else:?>
        <div class='courses'><?php echo $chapter;?></div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
