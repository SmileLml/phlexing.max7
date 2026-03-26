<?php
/**
 * The set search view file of workflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../workflow/view/header.html.php';?>
<?php js::set('emptyValue', $lang->workflowfield->tips->emptyValue);?>
<?php $mode = 'canSetValue';?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflowfield->setValue;?></strong>
      </div>
      <div class='panel-body'>
        <div class='alert alert-warning'><?php echo $lang->workflowfield->tips->keyValue;?></div>
        <form id='ajaxForm' method='post'>
          <?php $checkedFields = $valueFields;?>
          <?php include 'fields.html.php';?>
          <div class='form-actions'><?php echo html::submitButton();?></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
