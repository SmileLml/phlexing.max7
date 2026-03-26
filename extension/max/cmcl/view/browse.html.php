<?php
/**
 * The browse view file of cmcl module of QuCheng.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.qcmmi.com
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php
      if($browseType != 'all')
      {
          echo zget($lang->cmcl->typeList, $browseType);
          echo html::a(inlink($method, "type=$type&browseType=all"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      else
      {
          echo $lang->cmcl->browseByType;
      }
      ?>
    </div>
  </div>
  <div class="toolbar">
    <div class="btn-toolbar pull-right">
      <?php common::printLink('cmcl', 'batchCreate', "type=$type", "<i class='icon icon-plus'></i>" . $lang->cmcl->batchCreate, '', "class='btn btn-primary'");?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-table main-row">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <ul class='tree'>
      <?php unset($lang->cmcl->typeList['']);?>
      <?php foreach($lang->cmcl->typeList as $key => $value):?>
      <?php $active = $browseType == $key ? 'active' : '';?>
      <li class="<?php echo $active;?>"><?php echo html::a($this->createLink('cmcl', $method, "type=$type&browseType=$key"), $value);?></li>
      <?php endforeach;?>
      </ul>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($cmcls)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
        <?php if(common::hasPriv('cmcl', 'create')):?>
        <?php echo html::a($this->createLink('cmcl', 'batchCreate', "type=$type"), "<i class='icon icon-plus'></i> " . $lang->cmcl->batchCreate, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <table class="table has-sort-head" id='cmclList'>
      <?php $vars = "type=$type&browseType=$browseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <thead>
        <tr>
          <th class='text-left w-120px'><?php echo $lang->cmcl->type;?></th>
          <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->cmcl->id);?></th>
          <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->cmcl->title);?></th>
          <th class='w-200px'><?php common::printOrderLink('contents', $orderBy, $vars, $lang->cmcl->contents);?></th>
          <th class='w-120px'><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->cmcl->createdBy);?></th>
          <th class='w-120px'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->cmcl->createdDate);?></th>
          <th class='w-100px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($lang->cmcl->typeList as $key => $value):?>
        <?php if(!isset($cmcls[$key])) continue;?>
        <?php $i = 0;?>
        <?php foreach($cmcls[$key] as $cmcl):?>
        <tr>
          <?php if($i == 0):?>
          <td rowspan=<?php echo count($cmcls[$key]);?>><?php echo $value;?></td>
          <?php endif;?>
          <td><?php echo $cmcl->id;?></td>
          <td><?php echo html::a($this->createLink('cmcl', 'view', "cmclID=$cmcl->id"), zget($lang->cmcl->titleList, $cmcl->title));?></td>
          <td><?php echo $cmcl->contents;?></td>
          <td><?php echo zget($users, $cmcl->createdBy);?></td>
          <td><?php echo substr($cmcl->createdDate, 0, 11);?></td>
          <td class='c-actions'>
            <?php
            $params = "cmclID=$cmcl->id";
            common::printIcon('cmcl', 'edit', $params, $cmcl, "list");
            common::printIcon('cmcl', 'delete', $params, $cmcl, "list", 'trash', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
