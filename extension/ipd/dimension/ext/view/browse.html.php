<?php
/**
 * The browse view file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: browse.html.php 4129 2022-11-1 10:28:02Z $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('dimension', 'create', "", "<i class='icon icon-plus'></i> " . $lang->dimension->create, '', "class='iframe btn btn-primary'", true, true);?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <table class="table" id='deminsionList'>
    <thead>
      <tr>
        <th class="w-id"><?php echo $lang->idAB;?></th>
        <th class="w-250px"><?php echo $lang->dimension->name;?></th>
        <th class="w-250px"><?php echo $lang->dimension->code;?></th>
        <th class="c-desc"><?php echo $lang->dimension->desc;?></th>
        <th class="c-actions-2"><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($dimensions as $dimension):?>
      <tr>
        <td><?php echo $dimension->id;?></td>
        <td class="text-ellipsis" title="<?php echo $dimension->name;?>"><?php echo $dimension->name;?></td>
        <td class="text-ellipsis" title="<?php echo $dimension->code;?>"><?php echo $dimension->code;?></td>
        <td class="text-ellipsis" title='<?php echo $dimension->desc;?>'><?php echo $dimension->desc;?></td>
        <td class='c-actions'>
          <?php common::printIcon('dimension', 'edit', "id=$dimension->id", $dimension, 'list', '', '', 'btn iframe showinonlybody', true);?>
          <?php common::printIcon('dimension', 'delete', "id=$dimension->id", $dimension, 'list', 'trash', 'hiddenwin');?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
