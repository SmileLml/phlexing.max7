<?php
/**
 * The browse view file of dataset module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dataset
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->dataset->featureBar['browse'] as $key => $label):?>
    <?php echo html::a(inlink('browse', "type=$key"), "<span class='text'>$label</span>", '', "class='btn btn-link " . ($type == $key ? 'btn-active-text' : '') . "'");?>
    <?php endforeach;?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('dataset', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->dataset->create, '', 'class="btn btn-primary"');?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <table class="table" id='datasetList'>
    <thead>
      <tr>
        <th class="w-250px"><?php echo $lang->dataset->name;?></th>
        <th class="c-desc"><?php echo $lang->dataset->desc;?></th>
        <th class="c-actions"><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($tables as $table):?>
      <tr>
        <td><?php echo html::a(inlink('view', "code=" . $table['code']), $table['name']);?></td>
        <td title='<?php echo $table['desc'];?>'><?php echo $table['desc'];?></td>
        <td class='c-actions'>
          <?php if($table['id']) common::printIcon('dataset', 'edit', "id=" . $table['id'], $table, 'list');?>
          <?php if($table['id']) common::printIcon('dataset', 'delete', "id=" . $table['id'], $table, 'list', 'trash', 'hiddenwin');?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
