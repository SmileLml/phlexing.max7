<?php
/**
 * The control file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php js::set('objectType', $effort->objectType);?>
<?php js::set('noticeFinish', $lang->effort->noticeFinish);?>
<?php js::set('today', helper::today());?>
<?php if(isonlybody()):?>
<style> #main {min-width: unset;}</style>
<?php endif;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $effort->objectID;?></span>
        <?php echo $objectName;?>
        <small><?php echo $lang->arrow . $lang->effort->edit;?></small>
      </h2>
    </div>
    <form method='post' target='hiddenwin' style='padding-bottom:80px;'>
      <table class='table table-form'>
        <?php if($effort->objectType == 'task'):?>
        <tr id='productBox'<?php if(empty($project->hasProduct) || $this->config->vision == 'or') echo "class='hide'";?>>
          <th class='w-80px'><?php echo $lang->effort->product;?></th>
          <td class='w-p45'><?php echo html::select('product[]', $products, $effort->product, 'class="form-control chosen" multiple');?></td><td></td>
        </tr>
        <?php endif;?>
        <tr id='executionBox'>
          <th class='w-80px'><?php echo $this->config->vision == 'or' ? $this->lang->stage->common : $lang->effort->execution;?></th>
          <td class='w-p45'><?php echo html::select('execution', array(0 => '') + $executions, $effort->execution, 'class="form-control chosen" data-drop_direction="down"');?></td><td></td>
        </tr>
        <tr>
          <th class='w-80px'><?php echo $lang->effort->date;?></th>
          <td class='w-p45 required'><?php echo html::input('date', $effort->date, "class='form-date form-control'");?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->consumed;?></th>
          <td class='required'><?php echo html::input('consumed', $effort->consumed, 'class="form-control" autocomplete="off"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->left;?></th>
          <?php $readonly = $recentDateID === $effort->id ? '' : 'readonly';?>
          <?php if($effort->objectType == 'task' and !empty($task->team) and $effort->left == 0) $readonly = 'readonly';?>
          <td><?php echo html::input('left', $effort->left, "class='form-control' autocomplete='off' $readonly");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->work;?></th>
          <td colspan='2'><?php echo html::input('work', $effort->work, "class='form-control'");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'>
            <?php echo html::hidden('objectType', $effort->objectType);?>
            <?php echo html::hidden('objectID', $effort->objectID);?>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include './footer.html.php';?>
