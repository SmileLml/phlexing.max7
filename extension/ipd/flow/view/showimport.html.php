<?php
/**
 * The show import view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php js::import($jsRoot . 'md5.js');?>
<?php js::set('moduleName', $flow->module);?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->flow->showImport;?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post'>
      <div class="table-responsive">
        <?php if($mode == 'template') include 'templateimport.html.php';?>
        <?php if($mode == 'auto') include 'autoimport.html.php';?>
      </div>
      <div class='form-actions text-center'>
        <?php echo html::submitButton($lang->import);?>
        <?php echo html::backButton();?>
      </div>
    </form>
  </div>
</div>
<?php include 'footer.html.php';?>
