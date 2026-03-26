<?php
/**
 * The ajaxViewDB view file of workflowtable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Sun Guangming<sunguangming@chandao.com>
 * @package     workflowtable
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
#tableDetail {background-color: #F1F1F1; border: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 0;}
#tableDetail table.table tr > th{font-weight: normal; color: #64758b;}
#tableDetail table.table tr > th, #tableDetail table.table tr > td{padding: 2px 5px;}
</style>
<div id='tableDetail' class='panel'>
  <div class='panel-heading'><strong><?php printf($lang->workflowfield->detail, $table->name);?></strong></div>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowtable->name;?></th>
      <td><?php echo $table->name?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflow->table;?></th>
      <td><?php echo $table->table?></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowtable->module;?></th>
      <td><?php echo $table->module;?></td>
    </tr>
  </table>
</div>
