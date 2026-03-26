<?php $canUnlink = $this->flow->isClickable($flow->module, 'unlink', $data);?>
<?php foreach($linkedDatas as $linkType => $dataList):?>
<?php
if(in_array($linkType, $config->flow->linkPairs))
{
    list($linkedApp, $linkedModule) = $this->flow->extractAppAndModule($linkType);

    echo $this->fetch($linkedModule, 'linked', "module=$flow->module&dataID=$data->id&linkType=$currentType", $linkedApp);

    continue;
}

$linkedFields   = $this->workflowaction->getFields($linkType, 'browse', true, $dataList, $uiID);
$active         = $activeTab == $linkType ? 'active' : '';
$batchActions   = '';
$canBatchAction = $this->config->flow->showBatchActionsInLinkedPage;
if($canBatchAction)
{
    $batchActions   = $this->flow->buildBatchActions($linkType);
    $canBatchAction = !empty($batchActions);
}
?>
<div id='<?php echo $linkType;?>' class='tab-pane <?php echo $active;?> without-search'>
  <div class='actions'>
    <?php if($canLink) echo baseHTML::a('#linkTypeBox', $lang->workflowaction->default->actions['link'], "class='btn btn-primary' data-toggle='modal'");?>
  </div>
  <?php if($currentMode == 'browse'):?>
  <form id='unlink<?php echo $linkType;?>Form' method='post' class='main-table' data-ride='table'>
    <table class='table table-form linkedTable'>
      <thead>
        <?php $index = 1;?>
        <?php foreach($linkedFields as $field):?>
        <?php if(!$field->show) continue;?>
        <?php if(!$canUnlink && $field->field == 'actions') continue;?>
        <?php $width = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');?>
        <th class="text-<?php echo $field->position;?>" style="width:<?php echo $width;?>">
          <?php if($index == 1 && ($canUnlink or $canBatchAction)):?>
          <div class='checkbox-primary check-all' title='<?php echo $this->lang->selectAll;?>'>
            <label><?php echo $field->name;?></label>
          </div>
          <?php else:?>
          <?php echo $field->name;?>
          <?php endif;?>
        </th>
        <?php $index++;?>
        <?php endforeach;?>
      </thead>
      <tbody>
        <?php foreach($dataList as $linkedData):?>
        <tr>
          <?php $index = 1;?>
          <?php foreach($linkedFields as $field):?>
          <?php if(!$field->show or $field->field == 'actions') continue;?>
          <?php
          $output = '';
          if(is_array($linkedData->{$field->field}))
          {
              foreach($linkedData->{$field->field} as $value) $output .= zget($field->options, $value) . ' ';
          }
          else
          {
              if($field->field == 'id')
              {
                  if(commonModel::hasPriv($linkType, 'view'))
                  {
                      $action = $this->workflowaction->getByModuleAndAction($linkType, 'view');
                      $attr   = $action->open == 'modal' ? "data-toggle='modal'" : '';
                      $output = baseHTML::a(helper::createLink($linkType, 'view', "dataID={$linkedData->id}"), $linkedData->id, $attr);
                  }
                  else
                  {
                      $output = $linkedData->id;
                  }
              }
              else
              {
                  $output = zget($field->options, $linkedData->{$field->field});
              }
          }
          ?>
          <td class="text-<?php echo $field->position;?>" title='<?php echo strip_tags(str_replace("</p>", "\n", str_replace(array("\n", "\r"), "", $output)));?>'>
            <?php if($index == 1 && ($canUnlink or $canBatchAction)):?>
            <div class='checkbox-primary'><input type='checkbox' name='dataIDList[]' value='<?php echo $linkedData->id;?>' id='dataIDList<?php echo $linkedData->id;?>'>
              <label for='dataIDList<?php echo $linkedData->id;?>'>
                <?php if($field->field != 'id') echo $output;?>
              </label>
            </div>
            <?php if($field->field == 'id') echo $output;?>
            <?php else:?>
            <?php echo $output;?>
            <?php endif;?>
          </td>
          <?php $index++;?>
          <?php endforeach;?>
          <?php if($canUnlink):?>
          <td class="nowrap">
            <?php $confirm = sprintf($lang->flow->unlinkConfirm, zget($linkPairs, $linkType));?>
            <?php echo baseHTML::a($this->createLink($flow->module, 'unlink', "dataID=$data->id&linkType=$linkType&linkedID=$linkedData->id"), $lang->flow->unlink, "class='unlink' data-confirm='$confirm'");?>
          </td>
          <?php endif;?>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($canUnlink or $canBatchAction):?>
      <div class='checkbox-primary check-all'><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar'>
        <?php
        if($canUnlink)
        {
            $actionLink = $this->createLink($flow->module, 'unlink', "dataID=$data->id&linkType=$linkType");
            echo baseHTML::a('javascript:;', $lang->flow->unlink, "class='btn' onclick=\"setFormAction('$actionLink', '', this)\"");
        }
        if($canBatchAction) echo $batchActions;
        ?>
      </div>
      <?php endif;?>
      <div class='table-statistic'></div>
    </div>
  </form>
  <?php endif;?>
</div>
<?php endforeach;?>
