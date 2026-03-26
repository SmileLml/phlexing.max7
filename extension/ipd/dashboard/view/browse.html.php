<?php
/**
 * The index view file of dashboard of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo $moduleName;
      if($moduleID)
      {
          $removeLink = $browseType == 'bymodule' ? inlink('browse', "browseType=all&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("bugModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('dashboard', 'create', "dimensionID=$dimensionID", '<i class="icon icon-plus"></i> ' . $lang->dashboard->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class='main-table main-row'>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->dashboard->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browsegroup', "rootID=$dimensionID&moduleID=$moduleID&from=dashboard", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class='main-col'>
    <table class="table" id='dashboardList'>
      <thead>
        <tr>
          <th class="w-id"><?php echo $lang->dashboard->id;?></th>
          <th class="w-250px"><?php echo $lang->dashboard->name;?></th>
          <th class="c-desc"><?php echo $lang->dashboard->desc;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($dashboards as $dashboard):?>
        <tr>
          <td><?php echo html::a(inlink('view', "id=$dashboard->id"), $dashboard->id);?></td>
          <td><?php echo html::a(inlink('view', "id=$dashboard->id"), $dashboard->name);?></td>
          <td title='<?php echo $dashboard->desc;?>'><?php echo $dashboard->desc;?></td>
          <td class='c-actions'>
            <?php common::printIcon('dashboard', 'design', "id=$dashboard->id", $dashboard, 'list', 'backend');?>
            <?php common::printIcon('dashboard', 'edit', "id=$dashboard->id", $dashboard, 'list');?>
            <?php common::printIcon('dashboard', 'delete', "id=$dashboard->id", $dashboard, 'list', 'trash', 'hiddenwin');?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<?php js::set('moduleID', $moduleID);?>
<script>
$('#module' + moduleID).closest('li').addClass('active');
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
