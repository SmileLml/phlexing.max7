<?php
/**
 * The upgrade view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     upgrade 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php js::set('moduleName', $module);?>
<?php js::set('step', $step);?>
<?php js::set('mode', $mode);?>
<?php if($step == 'start'):?>
<table class='table table-form'>
  <tr>
    <th class='w-80px'><?php echo $lang->workflow->upgrade->currentVersion;?></th>
    <td><?php echo $flow->name . ' v' . $flow->version;?></td>
    <td class='w-40px'></td>
  </tr>
  <tr>
    <th><?php echo $lang->workflow->upgrade->selectVersion;?></th>
    <td><?php echo html::select('version', $versions, '', "class='form-control'");?></td>
    <td></td>
  </tr>
  <tr>
    <th></th>
    <td><?php echo baseHTML::a(inlink('upgrade', "module=$module&step=$step"), $lang->workflow->upgrade->start, "class='btn btn-primary upgradeBtn'");?></td>
    <td></td>
  </tr>
</table>
<?php elseif($step == 'confirm'):?>
<div><?php echo html::textarea('', $sqls, "rows='10' class='form-control'");?></div>
<div class='footer text-center'>
  <?php echo baseHTML::a(inlink('upgrade', "module=$module&step=$step&toVersion=$toVersion&mode=upgrade"), $lang->workflow->upgrade->upgrade, "class='btn btn-primary upgradeBtn' data-mode='upgrade'");?>
  <?php echo baseHTML::a(inlink('upgrade', "module=$module&step=$step&toVersion=$toVersion&mode=install"), $lang->workflow->upgrade->install, "class='btn btn-warning upgradeBtn' data-mode='install'");?>
</div>
<?php elseif($step == 'result'):?>
<?php if(is_array($result) && zget($result, 'result') == 'success'):?>
<div class="alert alert-success with-icon">
  <i class="icon-checked-sign"></i>
  <div class="content">
    <h1><?php echo $lang->workflow->upgrade->{$mode . 'Success'};?></h1>
  </div>
</div>
<?php else:?>
<div class="alert alert-danger with-icon">
  <i class="icon-close-sign"></i>
  <div class="content">
    <h1><?php echo $lang->workflow->upgrade->{$mode . 'Fail'};?></h1>
    <p style='word-break: break-all'><?php echo zget($result, 'message', '');?></p> 
  </div>
</div>
<?php endif;?>
<div class='footer text-center'>
  <?php echo baseHTML::a(inlink('browseFlow'), $lang->close, "class='btn btn-primary'");?>
</div>
<?php endif;?>
<?php include '../../common/view/footer.modal.html.php';?>
