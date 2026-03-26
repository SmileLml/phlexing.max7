<?php $class = $this->cookie->flowViewType == 'list' ? '' : 'hide';?>
<div class='main-table <?php echo $class;?>' id='listMode' data-ride='table'>
  <table class='table has-sort-head table-fixed' id='workflowTable'>
    <thead>
      <tr>
        <?php $vars="mode=browse&status=$status&app=$currentApp&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
        <th class='w-60px'><?php commonModel::printOrderLink('id', $orderBy, $vars, $lang->workflow->id);?></th>
        <th class='w-150px'><?php commonModel::printOrderLink('name', $orderBy, $vars, $lang->workflow->name);?></th>
        <th class='w-150px'><?php commonModel::printOrderLink('module', $orderBy, $vars, $lang->workflow->module);?></th>
        <?php if(empty($currentApp)):?>
        <th class='w-100px'><?php commonModel::printOrderLink('app', $orderBy, $vars, $lang->workflow->app);?></th>
        <?php endif;?>
        <th class='w-100px text-center'><?php commonModel::printOrderLink('buildin', $orderBy, $vars, $lang->workflow->buildin);?></th>
        <th class='w-100px text-center'><?php commonModel::printOrderLink('status', $orderBy, $vars, $lang->workflow->status);?></th>
        <th><?php echo $lang->workflow->desc;?></th>
        <th class='text-center' style='width: <?php echo $lang->workflow->actionFlowWidth;?>px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($flows as $flow):?>
      <?php
      $canCopy        = $this->workflow->isClickable($flow, 'copy');
      $canDelete      = $this->workflow->isClickable($flow, 'delete');
      $canRelease     = $this->workflow->isClickable($flow, 'release');
      $canDeactivate  = $this->workflow->isClickable($flow, 'deactivate');
      $canActivate    = $this->workflow->isClickable($flow, 'activate');
      $canDesignUI    = $this->workflow->isClickable($flow, 'ui');
      $canBrowseField = $flow->buildin && commonModel::hasPriv('workflowfield', 'browse');
      ?>
      <tr data-module='<?php echo $flow->module;?>'>
        <td><?php echo $flow->id;?></td>
        <td title="<?php echo $flow->name;?>">
          <?php echo $canView ? baseHTML::a(inlink('view', "id=$flow->id"), $flow->name, "data-toggle='modal'") : $flow->name;?>
        </td>
        <td><?php echo $flow->module;?></td>
        <?php if(empty($currentApp)):?>
        <td><?php echo zget($apps, $flow->app, '');?></td>
        <?php endif;?>
        <td class='text-center buildin<?php echo $flow->buildin;?>'>
          <?php if($flow->buildin) echo "<i class='icon icon-check'></i>";?>
        </td>
        <?php $textClass = 'text-muted';?>
        <?php if($flow->status == 'normal') $textClass = 'text-success';?>
        <?php if($flow->status == 'wait')   $textClass = 'text-warning';?>
        <td class='text-center <?php echo $textClass;?>'><?php echo zget($lang->workflow->statusList, $flow->status);?></td>
        <td title='<?php echo $flow->desc;?>'><?php echo $flow->desc;?></td>
        <td class='actions'>
          <?php
          if($canEdit)
          {
              echo baseHTML::a(inlink('edit', "id=$flow->id"), $lang->edit, "class='edit' data-toggle='modal'");
          }
          else
          {
              echo baseHTML::a('javascript:;', $lang->edit, "class='edit disabled'");
          }

          if($canDesignUI)
          {
              echo baseHTML::a(inlink('ui', "module=$flow->module"), $lang->workflow->design, "class='design'");
          }
          elseif($canBrowseField)
          {
              echo baseHTML::a($this->createLink('workflowfield', 'browse', "module=$flow->module"), $lang->workflow->design, "class='design'");
          }
          else
          {
              echo baseHTML::a('javascript:;', $lang->workflow->design, "class='design disabled'");
          }

          if($canRelease)
          {
              echo baseHTML::a(inlink('release', "id=$flow->id"), $lang->workflow->release, "class='release' data-toggle='modal'");
          }
          elseif($canDeactivate)
          {
              echo baseHTML::a(inlink('deactivate', "id=$flow->id"), $lang->workflow->deactivate, "class='deactivater'");
          }
          elseif($canActivate)
          {
              echo baseHTML::a(inlink('activate', "id=$flow->id"), $lang->workflow->activate, "class='activater'");
          }
          else
          {
              echo baseHTML::a('javascript:;', $lang->workflow->deactivate, "class='disabled'");
          }

          if($canCopy)
          {
              echo baseHTML::a(inlink('copy', "module=$flow->module"), $lang->workflow->copyFlow, "class='copy' data-toggle='modal'");
          }
          else
          {
              $title = $flow->buildin ? $lang->workflow->title->noCopy : '';
              echo baseHTML::a('javascript:;', $lang->workflow->copyFlow, "class='disabled' title='{$title}'");
          }

          if($canDelete)
          {
              echo baseHTML::a('javascript:;', $lang->delete, "class='delete-btn' data-id='$flow->id' data-toggle='modal' data-target='#deleteModal'");
          }
          else
          {
              echo baseHTML::a('javascript:;', $lang->delete, "class='disabled'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
</div>
