<?php
/**
 * The browse view of zoutput module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     zoutput
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('orderBy', $orderBy);?>
<div id="mainContent" class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'><?php include '../../process/view/menu.html.php';?></div>
  </div>
  <div class="main-col">
    <div class="main-header clearfix">
      <div class="btn-toolbar pull-left">
        <strong><?php echo $lang->zoutput->browse?></strong>
        <a class="querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i><?php echo $lang->zoutput->search;?></a>
      </div>
      <div class="btn-toolbar pull-right">
        <div class='btn-group dropdown'>
          <?php common::printLink('zoutput', 'create', "", "<i class='icon icon-plus'></i>" . $lang->zoutput->create, '', "class='btn btn-primary'");?>
          <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
          <ul class='dropdown-menu pull-right'>
            <li><?php echo html::a($this->createLink('zoutput', 'create'), $lang->zoutput->create);?></li>
            <li><?php echo html::a($this->createLink('zoutput', 'batchCreate'), $lang->zoutput->batchCreate);?></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=zoutput');?>" data-module="zoutput"></div>
    <?php if($outputs):?>
    <form class="main-table" data-ride="table" method="post" id="zoutputForm" action='<?php echo $this->createLink('zoutput', 'batchEdit');?>'>
      <table class="table table-fixed has-sort-head" id="zoutputTable">
        <thead>
          <tr>
            <?php $batchEdit = common::hasPriv('zoutput', 'batchEdit');?>
            <?php $updateOrder = common::hasPriv('zoutput', 'updateOrder');?>
            <?php $vars = "model=$model&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
            <th class="c-id">
              <?php if($batchEdit):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll;?>"><label></label></div>
              <?php endif;?>
              <?php echo common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class="w-200px"><?php echo $lang->zoutput->activity;?></th>
            <th style="width:auto"><?php echo $lang->zoutput->name;?></th>
            <th class="w-80px"><?php echo $lang->zoutput->optional;?></th>
            <th class="w-150px"><?php echo $lang->zoutput->tailorNorm;?></th>
            <th class="w-100px"><?php echo $lang->zoutput->createdBy;?></th>
            <th class="w-140px"><?php echo $lang->zoutput->createdDate;?></th>
            <th class="c-actions w-80px"><?php echo $lang->actions;?></th>
            <?php if($updateOrder):?>
            <th class="c-actions w-60px"><?php echo $lang->zoutput->order;?></th>
            <?php endif;?>
          </tr>
        </thead>
        <tbody class="sortable" id="outputList">
          <?php foreach($outputs as $id => $zoutput):?>
          <tr data-id="<?php echo $zoutput->id;?>">
            <td class="c-id">
              <?php if($batchEdit):?>
              <?php echo html::checkbox('zoutput', array($zoutput->id => '')) . html::a($this->createLink('zoutput', 'view', "outputID=$zoutput->id"), sprintf('%03d', $zoutput->id));?>
              <?php else:?>
              <?php echo sprintf('%03d', $zoutout->id);?>
              <?php endif;?>
            </td>
            <td class="c-name" title="<?php echo zget($activity, $zoutput->activity, '');?>"><?php echo zget($activity, $zoutput->activity, '');?></td>
            <td class="c-name" title="<?php echo $zoutput->name;?>"><?php common::printLink('zoutput', 'view', "id=$zoutput->id", $zoutput->name);?></td>
            <td title="<?php echo zget($lang->zoutput->optionalList, $zoutput->optional, '');?>"><?php echo zget($lang->zoutput->optionalList, $zoutput->optional, '');?></td>
            <td class="c-name" title="<?php echo $zoutput->tailorNorm;?>"><?php echo $zoutput->tailorNorm;?></td>
            <td title="<?php echo zget($users, $zoutput->createdBy);?>"><?php echo zget($users, $zoutput->createdBy);?></td>
            <td title="<?php echo $zoutput->createdDate;?>"><?php echo $zoutput->createdDate;?></td>
            <td class="c-actions">
            <?php
            $params = "zoutputID=$zoutput->id";
            echo common::printIcon('zoutput', 'edit', $params, $zoutput, 'list', 'edit');
            $deleteClass = common::hasPriv('zoutput', 'delete') ? 'btn' : 'btn disabled';
            echo html::a($this->createLink('zoutput', 'delete', $params), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->zoutput->delete}' class='$deleteClass'");
            ?>
            </td>
            <?php if($updateOrder):?>
            <td class='sort-handler' title='<?php echo $lang->dragAndSort?>'><i class='icon-move'></i></td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($batchEdit):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->edit, '', 'btn');?></div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php else:?>
    <div class="table-empty-tip">
      <?php echo $lang->noData;?>
      <?php echo html::a($this->createLink('zoutput', 'create'), '<i class="icon icon-plus"></i> ' . $lang->zoutput->create, '', 'class="btn btn-info"')?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
