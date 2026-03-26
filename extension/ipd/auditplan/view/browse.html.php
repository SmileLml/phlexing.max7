<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include $app->getModuleRoot() . 'common/view/datepicker.html.php';
include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php echo empty($process) ? $lang->auditcl->object : $process->name;?>
      <?php if($processID) echo html::a(inLink('browse', "projectID=$projectID&from=$from&processID=0"), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted' data-app='{$app->tab}'");?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    foreach($lang->auditplan->featureBar['browse'] as $key => $label)
    {
      $active = $key == $browseType ? ' btn-active-text' : '';
      echo html::a(inLink('browse', "projectID=$projectID&from=$from&processID=$processID&browseType=$key"), "<span class='text'>{$label}</span> " . ($browseType == $key ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active' data-app='{$app->tab}'");
    }

    echo "<div class='btn-group' id='more'>";
    $current = $lang->more;
    $active  = '';
    if(isset($lang->auditplan->moreStatusList[$browseType]))
    {
      $current = "<span class='text'>{$lang->auditplan->moreStatusList[$browseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
      $active  = 'btn-active-text';
    }
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active' data-app='{$app->tab}'");
    echo "<ul class='dropdown-menu'>";
    foreach($lang->auditplan->moreStatusList as $key => $value)
    {
      if($key == '') continue;
      echo '<li' . ($key == $browseType ? " class='active'" : '') . '>';
      echo html::a(inLink('browse', "projectID=$projectID&from=$from&processID=$processID&browseType=$key"), $value, '', "data-app='{$app->tab}'");
    }
    echo '</ul></div>';
    $rawModule = $this->app->rawModule;
    $rawMethod = $this->app->rawMethod;
    foreach(customModel::getFeatureMenu($rawModule, $rawMethod) as $menuItem)
    {
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if($menuType == 'QUERY')
        {
            $searchBrowseLink = $this->createLink('auditplan', 'browse', "projectID=$projectID&from=$from&processID=$processID&browseType=bysearch&orderBy=$orderBy&param=%s");
            $isBySearch       = $browseType == 'bysearch';
            include $app->getModuleRoot() . 'common/view/querymenu.html.php';
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
  <?php $canChangeObject = common::canModify($from, $object);?>
  <?php if($canChangeObject):?>
  <?php if(common::hasPriv('auditplan', 'create') and common::hasPriv('auditplan', 'batchCreate')):?>
    <div class='btn-group dropdown'>
      <?php
      $actionLink = $this->createLink('auditplan', 'create', "projectID=$projectID&from=$from");
      echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->auditplan->create}", '', "class='btn btn-primary' data-app='$app->tab'");
      ?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($actionLink, $lang->auditplan->create, '', "data-app='$app->tab'");?></li>
        <li><?php echo html::a($this->createLink('auditplan', 'batchCreate', "projectID=$projectID&from=$from"), $lang->auditplan->batchCreate, '', "data-app='$app->tab'");?></li>
      </ul>
    </div>
  <?php else:?>
    <?php common::printLink('auditplan', 'batchCreate', "projectID=$projectID&from=$from", "<i class='icon icon-plus'></i>" . $lang->auditplan->batchCreate, '', "class='btn btn-secondary' data-app='{$app->tab}'", '');?>
    <?php common::printLink('auditplan', 'create', "projectID=$projectID&from=$from", "<i class='icon icon-plus'></i>" . $lang->auditplan->create, '', "class='btn btn-primary' data-app='{$app->tab}'", '');?>
  <?php endif;?>
  <?php endif;?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div id="sidebar" class="side-col">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <ul class="tree" data-ride="tree" id="projectTree" data-name="tree-project" data-idx="0">
      <?php
      foreach($processList as $id => $processName)
      {
          $activate = '';
          if($processID == $id)  $activate = ' active';
          echo "<li class='{$activate}'>" . html::a(inLink('browse', "projectID=$projectID&from=$from&processID=" . $id), $processName, '', "data-app='{$app->tab}'") . '</li>';
      }
      ?>
      </ul>
    </div>
  </div>
  <?php if(empty($auditplans)):?>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=auditplan');?>" data-module='auditplan'></div>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
  </div>
  <?php else:?>
  <div class='main-col'>
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=auditplan');?>" data-module='auditplan'></div>
    <form class='main-table table-bug' method='post' id='auditplanForm' data-ride='table'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "projectID=$projectID&from=$from&processID=$processID&browseType=$browseType&orderBy=%s&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      $customFields = $this->datatable->getOldSetting('auditplan');
      $widths       = $this->datatable->setFixedFieldWidth($customFields);
      $columns      = 0;
      if(!$useDatatable) echo '<div class="table-responsive">';
      if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';
      ?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='auditplanList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
            <?php
            foreach($customFields as $field)
            {
                if($field->show)
                {
                    $this->datatable->printHead($field, $orderBy, $vars, $canChangeObject);
                    $columns++;
                }
            }
            ?>
          </tr>
        </thead>
        <tbody>
        <?php foreach($auditplans as $auditplan):?>
        <?php $auditplan->ncs = $this->loadModel('auditplan')->getNcCount($auditplan->id);?>
        <?php
        $process     = zget($processes, $auditplan->process);
        $processType = zget($processTypeList, $auditplan->processType);
        $objectType  = $auditplan->objectType == 'activity' ? zget($activities, $auditplan->objectID, '') : zget($outputs, $auditplan->objectID, '');
        if(empty($objectType)) continue;
        $execution   = $auditplan->execution ? zget($executions, $auditplan->execution) : '';
        ?>
          <tr data-id='<?php echo $auditplan->id?>'>
            <?php foreach($customFields as $field) $this->auditplan->printCell($field, $auditplan, $users, $process, $processType, $objectType, $execution, $projectID, $from, $useDatatable ? 'datatable' : 'table', $canChangeObject);?>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class='table-footer'>
        <?php if($canChangeObject):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php if(common::hasPriv('auditplan', 'batchEdit')):?>
          <div class='btn-group'>
            <?php
            $actionLink = $this->createLink('auditplan', 'batchEdit', "projectID=$projectID&from=$from");
            $misc       = "onclick=\"setFormAction('$actionLink')\"";
            echo html::commonButton($lang->edit, $misc);
            ?>
          </div>
          <?php endif;?>
          <?php if(common::hasPriv('auditplan', 'batchCheck')):?>
          <div class='btn-group'>
            <?php
            $actionLink = $this->createLink('auditplan', 'batchCheck', "projectID=$projectID&from=$from");
            $misc       = "onclick=\"setFormAction('$actionLink')\"";
            echo html::commonButton($lang->auditplan->batchCheck, $misc);
            ?>
          </div>
          <?php endif;?>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
  </div>
  <?php endif;?>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
