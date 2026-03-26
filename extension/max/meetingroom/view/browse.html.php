<?php
/**
 * The browse of meetingroom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Liyuchun <liyuchun@easycorp.ltd>
 * @package     meetingroom
 * @version     $Id: browse.html.php 4903 2021-06-09 13:48:59Z $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . "common/view/header.html.php"?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toobar pull-left">
    <?php
    $active = $browseType == 'all' ? 'btn-active-text' : '';
    $label  = "<span class='text'>{$lang->meetingroom->all}</span>";
    echo html::a(inlink('browse', "browseType=all"), $label, '', "class='btn btn-link $active'");
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->meetingroom->byQuery;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if((common::hasPriv('meetingroom', 'create')) or (common::hasPriv('meetingroom', 'batchCreate'))):?>
    <div class='btn-group dropdown'>
      <?php
      if(common::hasPriv('meetingroom', 'create'))
      {
          $actionLink = $this->createLink('meetingroom', 'create');
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->meetingroom->create}", '', "class='btn btn-primary'");
      }
      elseif(common::hasPriv('meetingroom', 'batchCreate'))
      {
          $actionLink = $this->createLink('meetingroom', 'batchCreate');
          echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->meetingroom->batchCreate}", '', "class='btn btn-primary'");
      }
      ?>
      <?php if((common::hasPriv('meetingroom', 'create')) and (common::hasPriv('meetingroom', 'batchCreate'))):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->meetingroom->create);?></li>
        <li><?php echo html::a($this->createLink('meetingroom', 'batchCreate'), $lang->meetingroom->batchCreate);?></li>
      </ul>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
</div>
<div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=meetingroom');?>" data-module='meetingroom'></div>
<div id="mainContent" class="main-table">
  <?php if(empty($rooms)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->noData;?></span>
      <?php if(common::hasPriv('meetingroom', 'create')):?>
      <?php echo html::a($this->createLink('meetingroom', 'create'), "<i class='icon icon-plus'></i> " . $lang->meetingroom->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='meetingroomForm' method='post' data-ride="table" action='<?php echo inLink('batchEdit');?>'>
    <table class="table has-sort-head" id='meetingroomList'>
      <?php
      $vars         = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $canBatchEdit = common::hasPriv('meetingroom', 'batchEdit', !empty($meetingroom) ? $meetingroom : null);
      ?>
      <thead>
        <tr>
          <th class='text-left w-80px'>
            <?php
             if($canBatchEdit) echo "<div class='checkbox-primary check-all' title='{$this->lang->selectAll}'><label></label></div>";
             common::printOrderLink('id', $orderBy, $vars, $lang->meetingroom->id);
            ?>
          </th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->meetingroom->name);?></th>
          <th class='w-120px'><?php common::printOrderLink('position', $orderBy, $vars, $lang->meetingroom->position);?></th>
          <th class='w-80px'><?php common::printOrderLink('seats', $orderBy, $vars, $lang->meetingroom->seats);?></th>
          <th class='w-280px'><?php echo $lang->meetingroom->equipment;?></th>
          <th class='w-280px'><?php echo $lang->meetingroom->openTime;?></th>
          <th class='w-50px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rooms as $room):?>
        <tr>
          <td>
            <?php echo $canBatchEdit ? html::checkbox('roomIDList', array($room->id => '')) . sprintf('%03d',$room->id) : sprintf('%03d',$room->id);?>
          </td>
          <td><?php echo html::a($this->createLink('meetingroom', 'view', "meetingroomID=$room->id"), $room->name);?></td>
          <td class='position' title="<?php echo $room->position;?>"><?php echo $room->position;?></td>
          <td><?php echo $room->seats;?></td>
          <td class='equipment' title="<?php echo $room->equipmentName;?>"><?php echo $room->equipmentName;?></td>
          <td class='openTime' title="<?php echo $room->openTimeName;?>"><?php echo $room->openTimeName;?></td>
          <td class='c-actions'>
            <?php
            $params = "meetingroomID=$room->id";
            common::printIcon('meetingroom', 'edit', $params, $room, "list");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->meetingroom->edit, '', 'btn');?></div>
      <?php endif;?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . "common/view/footer.html.php"?>
