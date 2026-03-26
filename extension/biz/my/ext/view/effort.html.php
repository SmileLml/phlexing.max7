<?php
/**
 * The control file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $menus = customModel::getFeatureMenu($app->rawModule, $app->rawMethod);
    foreach($menus as $menuItem)
    {
        $active = '';
        $label  = "<span class='text'>{$menuItem->text}</span>";
        if($menuItem->name == $type)
        {
            $active = 'btn-active-text';
            $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        }
        echo html::a(inlink('effort', "date={$menuItem->name}"), $label, '', "class='btn btn-link $active' id='{$menuItem->name}'");
    }
    ?>
    <div class="input-control has-icon-right space">
      <?php echo html::input('date', $date, 'class="form-date form-control" onchange=changeDate(this.value)')?>
      <label for="inputPasswordExample1" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('effort', 'calendar')):?>
    <div class="btn-group panel-actions">
      <?php echo html::a(helper::createLink('effort', 'calendar'), "<i class='icon-cards-view'></i> &nbsp;", '', "class='btn btn-icon' title='{$lang->effort->calendar}' id='switchButton'");?>
      <?php echo html::a(helper::createLink('my', 'effort', "type=all"), "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary' title='{$lang->effort->list}' id='switchButton'");?>
    </div>
    <?php endif;?>
    <?php if(common::hasPriv('effort', 'export')) echo html::a(helper::createLink('effort', 'export', "userID={$app->user->id}&orderBy=date_asc", 'html', true), "<i class='icon-export muted'> </i> " . $lang->effort->export, '', "class='btn btn-link export'") ?>
    <?php common::printLink('effort', 'batchCreate', '', "<i class='icon icon-plus'></i> " . $lang->effort->create, '', "class='btn btn-primary iframe' id='batchCreate' data-width='95%'", '', true);?>
  </div>
</div>
<div id="mainContent">
  <form class="main-table table-effort" data-ride="table" method="post" action='<?php echo $this->createLink('effort', 'batchEdit', "from=browse")?>'>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
    $vars         = "type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage";

    if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';
    $customFields = $this->datatable->getOldSetting('my');
    $customFields = json_encode($customFields);
    $customFields = json_decode($customFields);
    $columns      = 0;
    ?>
    <table class='table has-sort-head' data-custom-menu='true' data-checkbox-name='effortIDList[]'>
      <thead>
        <tr>
          <?php
          $hasProject   = false;
          $hasExecution = false;
          foreach($customFields as $field)
          {
              if($field->id == 'project')   $hasProject   = true;
              if($field->id == 'execution') $hasExecution = true;
              $field->show = true;
              $this->datatable->printHead($field, $orderBy, $vars);
              $columns ++;
          }
          ?>
        </tr>
      </thead>
      <?php $times = 0?>
      <?php if($efforts):?>
      <?php
      $executions = array();
      if($hasExecution) $executions = $this->loadModel('execution')->getPairs(0, 'all', $hasProject ? 'multiple' : '');
      ?>
      <tbody>
        <?php foreach($efforts as $effort):?>
        <tr data-id='<?php echo $effort->id;?>'>
          <?php
          $mode = $useDatatable ? 'datatable' : 'table';
          foreach($customFields as $field) $this->effort->printCell($field, $effort, $mode, $executions);
          ?>
        </tr>
        <?php $times += $effort->consumed;?>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
    <?php if($efforts):?>
    <div class="table-footer">
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php if(common::hasPriv('effort', 'batchEdit')):?>
        <?php echo html::submitButton($lang->effort->batchEdit, '', 'btn btn-primary');?>
        <?php endif;?>
      </div>
      <?php if($times) printf('<div class="text pull-left" style="margin-left: 20px;line-height:28px;">' . $lang->company->effort->timeStat . '</div>', $times);?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php
if($type == 'bydate')
{
    if($date == date('Y-m-d'))
    {
        $type = 'today';
    }
    else if($date == date('Y-m-d', strtotime('-1 day')))
    {
        $type = 'yesterday';
    }
}
?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
