<?php
/**
 * The data view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     feedback
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<?php if($feedbacks):?>
<?php
$this->loadModel('datatable');
$adminMethod  = $this->app->getMethodName() == 'admin';
$datatableId  = $this->moduleName . ucfirst($this->methodName);
$useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
?>
<form class='main-table table-feedback' method='post' id='feedbackForm' <?php if(!$useDatatable) echo "data-ride='table'";?>>
  <div class="table-header fixed-right">
    <nav class="btn-toolbar pull-right"></nav>
  </div>
<?php
$vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';

$canBatchEdit     = common::hasPriv('feedback', 'batchEdit');
$canBatchClose    = common::hasPriv('feedback', 'batchClose');
$canBatchAssignTo = common::hasPriv('feedback', 'batchAssignTo');
$canBatchAction   = $canBatchEdit or $canBatchClose or $canBatchAssignTo;
if($browseType == 'review')
{
    $canBatchReview = common::hasPriv('feedback', 'batchReview');
    $canBatchAction = $canBatchAction or $canBatchReview;
    $config->feedback->datatable->fieldList['actions']['width'] = '120';
}

$setting = $this->loadModel('datatable')->getOldSetting($this->moduleName);
$widths  = $this->datatable->setFixedFieldWidth($setting);
if(!$useDatatable) echo '<div class="table-responsive">';
?>
<table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='feedbackList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
  <thead>
    <tr>
    <?php
    foreach($setting as $key => $value)
    {
        if($value->show) $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
    }
    ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach($feedbacks as $feedback):?>
    <tr data-id='<?php echo $feedback->id?>'>
      <?php
      foreach($setting as $key => $value)
      {
          if($value->show) $this->feedback->printCell($value, $feedback, $users, $allProducts, $depts, $modulePairs, $viewMethod, $browseType, $stories, $bugs, $todos, $tasks, $tickets, $demands);
      }
      ?>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php if(!$useDatatable) echo '</div>';?>
<div class='table-footer'>
  <?php if($this->app->methodName == 'admin' or $this->app->methodName == 'feedback'):?>
  <?php if($canBatchAction):?>
  <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
  <?php endif;?>
  <div class="table-actions btn-toolbar">
    <?php if($canBatchEdit):?>
    <?php
    $actionLink = $this->createLink('feedback', 'batchEdit', "browseType=$browseType");
    $misc       = "onclick=\"setFormAction('$actionLink')\"";
    echo html::commonButton($lang->edit, $misc);
    ?>
    <?php endif;?>
    <?php if($browseType == 'review' and $canBatchReview):?>
    <div class="btn-group dropup">
      <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->feedback->review;?> <span class="caret"></span></button>
      <div class="dropdown-menu search-list">
        <div class="list-group">
        <?php
        foreach($lang->feedback->reviewResultList as $key => $value)
        {
            if(empty($key)) continue;
            $actionLink = $this->createLink('feedback', 'batchReview', "result=$key");
            echo html::a("javascript:setFormAction(\"$actionLink\",\"hiddenwin\");", $value, '');
        }
        ?>
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($canBatchClose):?>
    <?php
    $actionLink = $this->createLink('feedback', 'batchClose');
    $misc       = "onclick=\"setFormAction('$actionLink', '', '#feedbackForm')\"";
    echo html::commonButton($lang->close, $misc);
    ?>
    <?php endif;?>
    <?php if($canBatchAssignTo and $param != 'all'):?>
    <div class="btn-group dropup">
      <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->feedback->assignedTo;?> <span class="caret"></span></button>
      <?php $withSearch = count($users) > 10;?>
      <?php if($withSearch):?>
      <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
        <div class="input-control search-box has-icon-left has-icon-right search-example">
          <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input" />
          <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
          <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
        </div>
        <?php $usersPinYin = common::convert2Pinyin($users);?>
      <?php else:?>
      <div class="dropdown-menu search-list">
      <?php endif;?>
        <div class="list-group">
        <?php
        $actionLink = $this->createLink('feedback', 'batchAssignTo');
        echo html::select('assignedTo', $users, '', 'class="hidden"');
        foreach($users as $key => $value)
        {
            if(empty($key)) continue;
            $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
            echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\",\"hiddenwin\")", $value, '', $searchKey);
        }
        ?>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
  <?php $pager->show('right', 'pagerjs');?>
</div>
</form>
<script>
<?php if($useDatatable):?>
$(function(){$('#feedbackForm').table();})
<?php endif;?>
</script>
<?php else:?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->feedback->noFeedback;?></span></p>
</div>
<?php endif;?>
<?php include './selectproject.html.php';?>
