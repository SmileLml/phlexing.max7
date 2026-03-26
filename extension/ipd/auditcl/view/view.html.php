<?php
/**
 * The view of auditcl module of ZenTaoPMS.
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
<?php $browseLink = $app->session->auditclList != false ? $app->session->auditclList : $this->createLink('auditcl', 'browse');?>
<?php js::set('sysurl', common::getSysUrl());?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $auditcl->id?></span>
      <span class="text" title='<?php echo $auditcl->title;?>'><?php echo $auditcl->title;?></span>
      <?php if($auditcl->deleted):?>
      <span class='label label-danger'><?php echo $lang->auditcl->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->auditcl->title;?></div>
        <div class="detail-content article-content"><?php echo $auditcl->title;?></div>
      </div>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$auditcl->deleted):?>
        <?php
        echo "<div class='divider'></div>";
        common::printIcon('auditcl', 'edit', "auditclID=$auditcl->id", $auditcl);
        common::printIcon('auditcl', 'delete', "auditclID=$auditcl->id", $auditcl, 'button', 'trash', 'hiddenwin');
        ?>  
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->auditcl->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='w-90px'><?php echo $lang->auditcl->practiceArea;?></th>
                  <td><?php echo $auditcl->practiceArea;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->auditcl->type;?></th>
                  <td><?php echo zget($lang->auditcl->typeList, $auditcl->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->auditcl->createdBy;?></th>
                  <td><?php echo zget($users, $auditcl->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->auditcl->createdDate;?></th>
                  <td><?php echo $auditcl->createdDate;?></td>
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
