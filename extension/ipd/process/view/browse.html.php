<?php
/**
 * The process view of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     process
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/sortable.html.php';?>
<?php js::set('orderBy', $orderBy);?>
<div id="mainContent" class="main-row">
  <div class='side-col' id='sidebar'>
    <div class='cell'><?php include './menu.html.php';?></div>
  </div>
  <div class="main-col">
    <div class="main-header clearfix">
      <div class="btn-toolbar pull-left">
        <strong><?php echo $lang->process->list?></strong>
        <a class="querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->process->search;?></a>
      </div>
      <div class="btn-toolbar pull-right">
        <?php if(common::hasPriv('process', 'create') and common::hasPriv('process', 'batchCreate')):?>
        <div class='btn-group dropdown'>
          <?php
          $actionLink = $this->createLink('process', 'create', "model=$browseType");
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->process->create}", '', "class='btn btn-primary'");
          ?>
          <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
          <ul class='dropdown-menu pull-right'>
            <li><?php echo html::a($actionLink, $lang->process->create);?></li>
            <li><?php echo html::a($this->createLink('process', 'batchCreate', "model=$browseType"), $lang->process->batchCreate);?></li>
          </ul>
        </div>
        <?php else:?>
        <?php common::printLink('process', 'batchCreate', "model=$browseType", "<i class='icon icon-plus'></i>" . $lang->process->batchCreate, '', "class='btn btn-secondary'");?>
        <?php common::printLink('process', 'create', "model=$browseType", "<i class='icon icon-plus'></i>" . $lang->process->create, '', "class='btn btn-primary'");?>
        <?php endif;?>
      </div>
    </div>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=process');?>" data-module="process"></div>
    <?php if($processList):?>
      <form class="main-table" data-ride="table" method="post" id="processForm">
        <table id="processList" class="table has-sort-head" id="processTable">
          <thead>
            <tr>
              <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
              <th class="c-id w-100px">
                <?php echo common::printOrderLink('id', $orderBy, $vars, $lang->process->id);?>
              </th>
              <th class=""><?php echo $lang->process->name;?></th>
              <th class="w-120px"><?php echo $lang->process->type;?></th>
              <th class="w-100px"><?php echo $lang->process->abbr;?></th>
              <th class="w-100px"><?php echo $lang->process->createdBy;?></th>
              <th class="w-150px"><?php echo $lang->process->createdDate;?></th>
              <th class="text-center w-200px"><?php echo $lang->actions;?></th>
              <th class="w-60px sort-default text-left" title="<?php echo $lang->process->sort;?>"><?php echo $lang->process->sort;?></th>
            </tr>
          </thead>
          <tbody class="sortable" id="orderTableList" style="position: static;">
            <?php foreach($processList as $id => $process):?>
              <tr data-id=<?php echo $process->id;?> data-order=<?php echo $process->order;?>>
              <td class="c-id">
                <?php common::printLink('process', 'view', "id=$process->id", $process->id);?>
              </td>
              <td class="text-ellipsis" title="<?php echo $process->name;?>"><?php common::printLink('process', 'view', "id=$process->id", $process->name);?></td>
              <td title="<?php echo zget($classify, $process->type);?>"><?php echo zget($classify, $process->type);?></td>
              <td title="<?php echo $process->abbr;?>"><?php echo $process->abbr;?></td>
              <td title="<?php echo zget($users, $process->createdBy);?>"><?php echo zget($users, $process->createdBy);?></td>
              <td title="<?php echo $process->createdDate;?>"><?php echo $process->createdDate;?></td>
              <td class="text-center c-actions">
               <?php
                  common::printIcon('activity', 'batchCreate', "processID=$process->id", $process, 'list', 'treemap-alt', '', '', '', '', $lang->process->createActivity);
                  common::printIcon('process', 'activityList', "processID=$process->id", $process, 'list', 'list-alt', '', 'iframe', 'yes', '', $lang->process->activityList);
                  common::printIcon('process', 'edit', "processID=$process->id", $process, 'list', 'edit', '', 'iframe', 'yes', '', $lang->process->edit);
                  $deleteClass = common::hasPriv('process', 'delete') ? 'btn' : 'btn disabled';
                  echo html::a($this->createLink('process', 'delete', "processID=$process->id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->process->delete}' class='$deleteClass'");
                ?>
              </td>
              <td class="sort-handler">
                <i class="icon icon-move"></i>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      <?php if($processList):?>
      <div class='table-footer'>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
      </form>
    <?php else:?>
      <div class="table-empty-tip">
        <?php echo $lang->noData;?>
        <?php echo html::a($this->createLink('process', 'create', "model=$browseType"), '<i class="icon icon-plus"></i> ' . $lang->process->create, '', 'class="btn btn-info"')?>
      </div>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    $('#orderTableList').on('sort.sortable', function(e, data)
    {
        var list = '';
        for(i = 0; i < data.list.length; i++) list += $(data.list[i].item).attr('data-id') + ',';
        $.post(createLink('process', 'updateOrder'), {'process' : list, 'orderBy' : orderBy});
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
