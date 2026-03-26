<?php
/**
 * The view of cmcl module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     view
 * @version     $Id: track.html.php 4903 2020-09-04 09:32:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->cmclList !== false ? $app->session->cmclList : $this->createLink('cmcl', $browseMethod);?>
<?php js::set('sysurl', common::getSysUrl());?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $cmcl->id?></span>
      <span class="text" title='<?php echo $cmcl->contents;?>'><?php echo $cmcl->contents;?></span>
      <?php if($cmcl->deleted):?>
      <span class='label label-danger'><?php echo $lang->cmcl->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$cmcl->deleted):?>
        <?php
        common::printIcon('cmcl', 'edit', "cmclID=$cmcl->id", $cmcl);
        common::printIcon('cmcl', 'delete', "cmclID=$cmcl->id", $cmcl, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->cmcl->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->cmcl->id;?></th>
                  <td><?php echo $cmcl->id;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->cmcl->type;?></th>
                  <td><?php echo zget($lang->cmcl->typeList, $cmcl->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->cmcl->title;?></th>
                  <td><?php echo zget($lang->cmcl->titleList, $cmcl->title);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->cmcl->contents;?></th>
                  <td><?php echo $cmcl->contents;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->cmcl->createdBy;?></th>
                  <td><?php echo zget($users, $cmcl->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->cmcl->createdDate;?></th>
                  <td><?php echo $cmcl->createdDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
