<?php
/**
 * The detail view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php
include 'header.html.php';
include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
$activeTab = $flow->module;
if($currentType)
{
    if(isset($linkedDatas[$currentType]))
    {
        $activeTab = $currentType;
    }
    else
    {
        if($currentMode == 'bysearch') $activeTab = 'common';
    }
}
$canLink     = $this->flow->isClickable($flow->module, 'link', $data);
$sessionName = $flow->module . 'List';
$loadLink    = $this->createLink($flow->module, 'link', "dataID=$data->id&linkType=LINKTYPE&mode=MODE");

js::set('moduleName', $flow->module);
js::set('dataID', $data->id);
js::set('linkType', $currentType);
js::set('viewMode', $currentMode);
js::set('loadLink', $loadLink);
?>
<div id='mainTitle' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if(!isonlybody()):?>
    <?php echo $this->session->flowList ? baseHTML::a($this->session->flowList, $this->lang->goback, "class='btn btn-back'") : html::backButton();?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $data->id;?></span>
      <span class="text" title='<?php echo zget($dataPairs, $data->id);?>'><?php echo zget($dataPairs, $data->id);?></span>
    </div>
  </div>
</div>
<div class='tabs' id='tabsNav'>
  <ul class='nav nav-tabs'>
    <?php
    $attr = $activeTab == $flow->module ? "class='active'" : '';
    echo "<li $attr><a href='#{$flow->module}' data-toggle='tab'>{$flow->name}{$lang->flow->detail}</a></li>";

    foreach($linkedDatas as $linkType => $datas)
    {
        $attr = $activeTab == $linkType ? "class='active'" : '';
        echo "<li $attr><a href='#{$linkType}' data-toggle='tab'>" . zget($linkPairs, $linkType, '') . '</a></li>';
    }

    if($canLink)
    {
        $attr     = $activeTab == 'common' ? "class='active'" : "class='hidden'";
        $tabTitle = $activeTab == 'common' ? zget($linkPairs, $currentType, '') : $lang->workflowaction->default->actions['link'];
        echo "<li $attr><a href='#common' data-toggle='tab'>{$tabTitle}</a></li>";
    }
    ?>
  </ul>
  <div class='tab-content'>
    <?php $active = $activeTab == $flow->module ? 'active' : '';?>
    <div id='<?php echo $flow->module;?>' class='tab-pane <?php echo $active;?> <?php if($canLink) echo 'with-link-button';?>'>
      <div class='actions'>
        <?php if($canLink) echo baseHTML::a('#linkTypeBox', $lang->workflowaction->default->actions['link'], "class='btn btn-primary' data-toggle='modal'");?>
      </div>
      <div class='main-row'>
        <div class='main-col col-9'>
          <div class='panel'>
            <div class='panel-body'>
              <?php
              $children = array();
              foreach($fields as $field)
              {
                  if(!$field->show) continue;
                  if($field->position != 'info') continue;

                  if(isset($childFields[$field->field]))
                  {
                      $children[$field->field] = $field->name;
                      continue;
                  }

                  if($field->control == 'file')
                  {
                      $filesName = "{$field->field}files";
                      if(!empty($data->{$filesName}))
                      {
                          echo '<p><strong>' . $field->name . $lang->hyphen . '</strong>' . $this->fetch('file', 'printFiles', array('files' => $data->{$filesName}, 'fieldset' => 'false')) . '</p>';
                      }
                  }
                  else
                  {
                      $attr     = '';
                      $relation = zget($relations, $field->field, '');
                      if($relation && strpos(",$relation->actions,", ',many2one,') === false && strpos(",$relation->actions,", ',many2many,') === false)
                      {
                          $prevDataID = isset($data->{$field->field}) ? $data->{$field->field} : 0;
                          if($prevDataID) $attr = "class='prevP' data-prev='{$relation->prev}' data-next='{$relation->next}' data-action='$flowAction->action' data-field='{$relation->field}' data-dataID='$prevDataID'";
                      }

                      echo "<p $attr>";
                      echo "<strong>" . $field->name . "</strong>";

                      $fieldValue = '';
                      if(!empty($data->{$field->field}))
                      {
                          if(is_array($data->{$field->field}))
                          {
                              foreach($data->{$field->field} as $value) $fieldValue .= $this->flow->processFieldValue($field, $relation, $value) . ' ';
                          }
                          else
                          {
                              $fieldValue = $this->flow->processFieldValue($field, $relation, $data->{$field->field});
                          }
                      }
                      else
                      {
                          if(isset($data->{$field->field}) && $data->{$field->field} === '0') $fieldValue = '0';
                      }

                      if($fieldValue !== '') echo ' ' . $lang->hyphen . ' ' . $fieldValue;

                      echo '</p>';
                  }
              }
              ?>
            </div>
          </div>

          <?php foreach($children as $child => $childName):?>
          <?php if(empty($childDatas[$child])) continue;?>
          <div class='panel panel-block'>
            <div class='panel-heading'><strong><?php echo $childName;?></strong></div>
            <div class='panel-body scroll'>
              <table class='table table-hover table-fixed'>
                <thead>
                  <tr>
                    <?php foreach($childFields[$child] as $childField):?>
                    <?php if(!$childField->show) continue;?>
                    <?php $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');?>
                    <th style='width: <?php echo $childWidth;?>'><?php echo $childField->name;?></th>
                    <?php endforeach;?>
                  </tr>
                </thead>
                <?php foreach($childDatas[$child] as $childData):?>
                <tr>
                  <?php foreach($childFields[$child] as $childField):?>
                  <?php if(!$childField->show) continue;?>
                  <?php
                  if(strpos(',date,datetime,', ",$childField->control,") !== false)
                  {
                      $childValue = formatTime($childData->{$childField->field});
                  }
                  else
                  {
                      if(is_array($childData->{$childField->field}))
                      {
                          $childValues = array();
                          foreach($childData->{$childField->field} as $value)
                          {
                              if(!empty($value)) $childValues[] = zget($childField->options, $value);
                          }
                          $childValue = implode(',', $childValues);
                      }
                      else
                      {
                          $childValue = zget($childField->options, $childData->{$childField->field});
                      }
                  }
                  ?>
                  <td title='<?php echo $childValue;?>'><?php echo $childValue;?></td>
                  <?php endforeach;?>
                </tr>
                <?php endforeach;?>
              </table>
            </div>
          </div>
          <?php endforeach;?>

    <?php $actions = $this->loadModel('action')->getList($flow->module, $data->id);?>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
          <?php echo $this->flow->buildOperateMenu($flow, $data, $type = 'view');?>
        </div>
        <div class='side-col col-3'>
          <?php foreach($processBlocks as $blockKey => $block):?>
          <div class='panel panel-block'>
            <?php if(empty($block->tabs)):?>
            <?php if($block->name):?> <div class='panel-heading'><strong><?php echo $block->name;?></strong></div> <?php endif;?>
            <?php else:?>
            <div class='panel-heading with-nav-tabs'>
              <ul class='nav nav-tabs'>
                <?php $index = 1;?>
                <?php foreach($block->tabs as $tabKey => $tab):?>
                <?php $class = $index == 1 ? "class='active'" : '';?>
                <li <?php echo $class;?>><a data-toggle="tab" href="#<?php echo $blockKey . '_' . $tabKey;?>Tab"><?php echo $tab->name;?></a></li>
                <?php $index ++;?>
                <?php endforeach;?>
              </ul>
            </div>
            <?php endif;?>

            <?php if(!empty($block->tabs)):?>
            <div class='tab-content'>
              <?php $index = 1;?>
              <?php foreach($block->tabs as $tabKey => $tab):?>
              <?php $blockFields = $tab->fields;?>
              <?php $class = $index == 1 ? 'active' : '';?>
              <div id="<?php echo $blockKey . '_' . $tabKey;?>Tab" class='<?php echo $class;?> tab-pane panel-body no-padding-top'>
                <?php include 'block.html.php';?>
              </div>
              <?php $index ++;?>
              <?php endforeach;?>
            </div>
            <?php else:?>
            <div class='panel-body'>
              <?php if(!empty($block->fields)):?>
              <?php $blockFields = $block->fields;?>
              <?php include 'block.html.php';?>
              <?php endif;?>
            </div>
            <?php endif;?>

          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
    <?php include 'linked.html.php';?>
    <?php if($canLink):?>
    <?php include 'linktype.html.php';?>
    <?php $active = $activeTab == 'common' ? 'active' : '';?>
    <div id='common' class='tab-pane <?php echo $active;?>'>
      <div class='actions'>
        <?php echo baseHTML::a('#linkTypeBox', $lang->workflowaction->default->actions['link'], "class='btn btn-primary' data-toggle='modal'");?>
      </div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include 'footer.html.php';?>
