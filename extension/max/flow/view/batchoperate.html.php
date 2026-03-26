<?php
/**
 * The batch operate view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('action', $action->action);?>
<?php js::set('batchMode', $action->batchMode);?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo str_replace('-', '', $title);?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post' action='<?php echo $actionURL;?>'>
      <div class="table-responsive">
        <?php if($action->batchMode == 'same')      include 'samebatch.html.php';?>
        <?php if($action->batchMode == 'different') include 'differentbatch.html.php';?>
      </div>
    </form>
  </div>
</div>
<?php if($formulaScript) echo $formulaScript;?>
<script>
<?php helper::import('../js/search.js');?>
</script>
<?php include 'footer.html.php';?>
