<?php $class = $this->cookie->flowViewType == 'card' ? '' : 'hide';?>
<div class='row <?php echo $class;?>' id='cardMode'>
  <?php foreach($flows as $flow):?>
  <?php
  $canCopy        = $this->workflow->isClickable($flow, 'copy');
  $canDelete      = $this->workflow->isClickable($flow, 'delete');
  $canUpgrade     = $this->workflow->isClickable($flow, 'upgrade');
  $canRelease     = $this->workflow->isClickable($flow, 'release');
  $canDeactivate  = $this->workflow->isClickable($flow, 'deactivate');
  $canActivate    = $this->workflow->isClickable($flow, 'activate');
  $canDesignUI    = $this->workflow->isClickable($flow, 'ui');
  $canBrowseField = $flow->buildin && commonModel::hasPriv('workflowfield', 'browse');
  $canPreview     = !$flow->buildin && $flow->status == 'normal' && commonModel::hasPriv("$flow->app.$flow->module", 'browse');
  ?>
  <div class='col-md-3 col-sm-4'>
    <div class='panel panel-block flow-block' id='flowBlock' data-flowapp='<?php echo $flow->app;?>'>
      <div class='panel-heading'>
        <strong class='title' title="<?php echo $flow->name;?>">
          <?php
          $flowValue = zget($lang->workflow->flowList, $flow->name);
          if(empty($flowValue))
          {
              echo $flow->name;
          }
          else
          {
              echo $flow->name = zget($lang->workflow->flowList, $flow->name);
          }?>
          <span class='text-red'><?php echo $flow->buildin ? "[{$lang->workflow->buildin}]" : '';?></span>
        </strong>
        <?php if($canEdit or $canCopy or $canDelete):?>
        <ul class='panel-actions nav nav-default'>
          <li class='dropdown'>
            <a href='javascript:;' data-toggle='dropdown'><i class='icon icon-ellipsis-v'></i></a>
            <ul class='dropdown-menu pull-right'>
              <?php if($canEdit)   echo '<li>' . baseHTML::a(inlink('edit', "id=$flow->id"), $lang->edit, "data-toggle='modal'") . '</li>';?>
              <?php if($canCopy)   echo '<li>' . baseHTML::a(inlink('copy', "module=$flow->module"), $lang->workflow->copyFlow, "data-toggle='modal'") . '</li>';?>
              <?php if($canDelete) echo '<li>' . baseHTML::a('javascript:;', $lang->delete, "class='delete-btn' data-id='$flow->id' data-toggle='modal' data-target='#deleteModal'") . '</li>';?>
            </ul>
          </li>
        </ul>
        <?php endif;?>
      </div>
      <div class='panel-body'>
        <div class='info'>
          <div>
            <?php echo $lang->workflow->app;?>&nbsp;&nbsp;<span class='text-primary'><?php echo zget($apps, $flow->app, '');?></span>
            <?php if($canUpgrade):?>
            <span class='text-danger pull-right'>
              <strong><?php echo $lang->workflow->upgrade->newVersion;?></strong>
              <?php echo baseHTML::a(inlink('upgrade', "module=$flow->module&step=start"), $lang->workflow->upgrade->clickme, "data-toggle='modal'");?>
            </span>
            <?php endif;?>
          </div>

          <div class='text-muted'><?php echo $flow->desc;?></div>
        </div>
        <div class='footerbar pull-left'>
          <?php
          $labelStatus = 'label-default';
          if($flow->status == 'normal') $labelStatus = 'label-success';
          if($flow->status == 'wait')   $labelStatus = 'label-warning';
          echo "<span class='label $labelStatus'>" . zget($lang->workflow->statusList, $flow->status) . "</span>";
          ?>
        </div>
        <div class='footerbar hide pull-right' data-module='<?php echo $flow->module;?>'>
          <?php
          $flowApp = !empty($this->lang->apps->{$flow->app}) ? $flow->app : 'sys';
          if($canPreview) echo baseHTML::a($this->createLink("{$flow->module}", 'browse', "mode=browse"), $lang->workflow->preview, "class='btn btn-default btn-browse' target='_blank'");
          if($canDesignUI) echo baseHTML::a(inlink('ui', "module=$flow->module"), $lang->workflow->design, "class='btn btn-primary'");
          if($canBrowseField) echo baseHTML::a($this->createLink('workflowfield', 'browse', "module=$flow->module"), $lang->workflow->design, "class='btn btn-primary'");
          if($canRelease) echo baseHTML::a(inlink('release', "id=$flow->id"), $lang->workflow->release, "class='btn btn-secondary' data-toggle='modal'");
          if($canDeactivate) echo baseHTML::a(inlink('deactivate', "id=$flow->id"), $lang->workflow->deactivate, "class='btn btn-secondary deactivater'");
          if($canActivate) echo baseHTML::a(inlink('activate', "id=$flow->id"), $lang->workflow->activate, "class='btn btn-secondary activater'");
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<div class='table-footer <?php echo $class;?>'><?php $pager->show('right', 'pagerjs');?></div>
