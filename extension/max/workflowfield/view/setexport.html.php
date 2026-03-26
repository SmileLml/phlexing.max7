<?php
/**
 * The set export view file of workflowfield module of ZDOO.
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
<?php $mode = $flow->buildin ? 'extendExport' : 'canExport';?>
<?php js::set('mode', $mode);?>
<?php js::set('emptyExport', $lang->workflowfield->tips->emptyExport);?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflowfield->setExport;?></strong>
      </div>
      <div class='panel-body'>
        <form id='ajaxForm' method='post'>
          <?php if(count($fieldGroups) > 1):?>
          <div class='tabs' id='tabsNav'>
            <ul class='nav nav-tabs'>
              <?php foreach($fieldGroups as $module => $fields):?>
              <li <?php if($module == $flow->module) echo "class='active'";?>>
                <a href='#<?php echo $module;?>' data-toggle='tab'><?php echo zget($flowPairs, $module);?></a>
              </li>
              <?php endforeach;?>
            </ul>
            <div class='tab-content'>
              <?php foreach($fieldGroups as $module => $fields):?>
              <div id='<?php echo $module;?>' class='tab-pane <?php if($module == $flow->module) echo 'active';?>'>
              <?php $checkedFields = zget($exportGroups, $module, '');?>
              <?php include 'fields.html.php';?>
              </div>
              <?php endforeach;?>
            </div>
          </div>
          <?php else:?>
          <?php
          foreach($fieldGroups as $module => $fields)
          {
              $checkedFields = zget($exportGroups, $module, '');
              include 'fields.html.php';
          }
          ?>
          <?php endif;?>
          <div class='form-actions'><?php echo html::submitButton();?></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
