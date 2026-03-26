<?php
/**
 * The browse view file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <?php include $app->getModuleRoot() . 'custom/view/sidebar.html.php';?>
  <div id='tableList' class='main-col main-content main-table'>
    <div id="listHead">
      <div class='pull-left'>
        <strong><?php echo $lang->approvalflow->typeList[$type];?></strong>
      </div>
      <div class="btn-toolbar pull-right createBtn">
        <?php common::printLink('approvalflow', 'create', "type=$type", "<i class='icon icon-plus'></i> " . $lang->approvalflow->create, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <?php if(empty($flows)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->approvalflow->noFlow;?></span>
        <?php echo html::a($this->createLink('approvalflow', 'create', "type=$type"), "<i class='icon icon-plus'></i> " . $lang->approvalflow->create, '', "class='btn btn-info'");?>
      </p>
    </div>
    <?php else:?>
    <table class="table" id='flowList'>
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->approvalflow->id;?></th>
          <th class="c-name"><?php echo $lang->approvalflow->name;?></th>
          <th><?php echo $lang->approvalflow->desc;?></th>
          <th class="w-160px"><?php echo $lang->approvalflow->createdDate;?></th>
          <th class="c-user"><?php echo $lang->approvalflow->createdBy;?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($flows as $flow):?>
        <tr>
          <td><?php echo html::a(inlink('view', "flowID=$flow->id"), sprintf('%03d', $flow->id));?></td>
          <td class='text-ellipsis'><?php echo html::a(inlink('view', "flowID=$flow->id"), $flow->name, '', "title='$flow->name'");?></td>
          <td class='text-ellipsis' title='<?php echo $flow->desc?>'><?php echo $flow->desc;?></td>
          <td title='<?php echo $flow->createdDate?>'><?php echo $flow->createdDate;?></td>
          <td class='text-ellipsis' title='<?php echo zget($users, $flow->createdBy, '');?>'><?php echo zget($users, $flow->createdBy, '');?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('approvalflow', 'edit', "flowID=$flow->id", $flow, 'list');
            common::printIcon('approvalflow', 'design', "flowID=$flow->id", $flow, 'list', 'treemap');
            common::printIcon('approvalflow', 'delete', "flowID=$flow->id", $flow, 'list', 'remove', 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="table-statistic"></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
