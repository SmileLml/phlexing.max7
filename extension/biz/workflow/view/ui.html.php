<?php
/**
 * The ui view file of workflow module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     workflow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php
switch($currentAction->action)
{
    case 'browse':
        $uiMode = 'browse';
        break;
    case 'batchassign':
    case 'batchcreate':
    case 'batchedit':
        $uiMode = 'batchForm';
        break;
    case 'view':
        $uiMode = 'view';
        break;
    default:
        $uiMode = 'form';
        break;
}
?>
<div id='editorPages' class='toolbox cell'>
  <?php
  $btnHtmlList = array();
  $createBtnHtml = '';
  $editBtnHtml = '';
  $browseBtnHtml = '';
  foreach ($actions as $action)
  {
      $btnClass = $action->action == $currentAction->action ? 'btn-secondary' : 'btn-gray';
      $btnHtml  = baseHTML::a(inlink('ui', "module={$flow->module}&action={$action->action}&groupID={$groupID}"), $action->name, "class='btn $btnClass'");
      if($action->action === 'create')     $createBtnHtml = $btnHtml;
      elseif($action->action === 'edit')   $editBtnHtml   = $btnHtml;
      elseif($action->action === 'browse') $browseBtnHtml = $btnHtml;
      else $btnHtmlList[] = $btnHtml;
  }
  array_unshift($btnHtmlList, $createBtnHtml, $editBtnHtml, $browseBtnHtml);
  echo implode('', $btnHtmlList);
  ?>
</div>
<div class='main-row auto-height columns auto-fade-in fade' id='editor' data-offset='60' data-mode='<?php echo $uiMode; ?>'>
  <div class='side-col column w-200px'>
    <div class='cell'>
      <div class='cell-header'>
        <span><?php echo $lang->workfloweditor->selectField; ?></span>
      </div>
      <div class='cell-body' id='editorControls'>
        <ul id='editorControlsNav'>
          <li class='active'><a class='btn' data-tab href='#editorControlsList'><?php echo $lang->workfloweditor->uiControls; ?></a></li>
          <li><a class='btn' data-tab href='#editorShowedFields'><?php echo $lang->workfloweditor->showedFields; ?></a></li>
        </ul>
        <div class='tab-content'>
            <div class='tab-pane active' id='editorControlsList'>
              <?php unset($lang->workflowfield->controlTypeList['label']);?>
              <?php foreach ($lang->workflowfield->controlTypeList as $key => $name): ?>
              <button type='button' class='btn btn-field-control' data-type='<?php echo $key; ?>'><?php echo $name; ?></button>
              <?php endforeach; ?>
            </div>
            <div class='tab-pane' id='editorShowedFields'>
              <?php foreach ($fields as $field): ?>
              <?php if($field->show != '0') continue; ?>
              <button type='button' class='btn btn-field-control' data-field='<?php echo $field->field; ?>'><?php echo  $field->name; ?></button>
              <?php endforeach; ?>
            </div>
        </div>
      </div>
    </div>
  </div>
  <div class='main-col column'>
    <div class='cell'>
      <div class='cell-header'>
        <span><?php echo $lang->workfloweditor->uiPreview; ?></span>
      </div>
      <div class='cell-body' id='editorPreview'>
        <?php if($uiMode == 'form'): ?>
        <div id='uiPreviewWrapper'>
          <div id='uiPreview' data-drop-tip='<?php echo $lang->workfloweditor->dragDropTip; ?>'></div>
          <div id='uiPreviewBottom'>
          </div>
        </div>
        <?php elseif($uiMode == 'view'): ?>
        <div id='uiViewWrapper'>
          <div class="row">
            <div class="col col-xs-7">
              <div class='panel panel-block view-panel view-panel-info'>
                <div class='panel-heading'><strong><?php echo $lang->workflowlayout->positionList['view']['info'];?></strong></div>
                <div class='panel-body view-dropbox' data-position='info' id='uiInfoViewPreview' data-drop-tip='<?php echo $lang->workfloweditor->dragDropTip; ?>'>
                </div>
              </div>
              <div class='panel panel-block histories'>
                <div class='panel-heading'>
                  <strong class='title'><?php echo $lang->history?></strong>
                  <button type='button' class='btn btn-mini only-icon btn-reverse' title='<?php echo $lang->reverse;?>'><i class='icon icon-arrow-up'></i></button>
                  <button type='button' class='btn btn-mini only-icon btn-expand-all' title='<?php echo $lang->switchDisplay;?>'><i class='icon icon-plus'></i></button>
                </div>
                <div class='panel-body'>
                  <ol class='histories-list'>
                    <li><?php echo str_replace(array('$date', '$actor'), array(date(DT_DATETIME1), $this->app->user->realname), $lang->action->desc->created);?></li>
                  </ol>
                </div>
              </div>
            </div>
            <div class="col col-xs-5">
              <div class='panel panel-block view-panel view-panel-basic'>
                <div class='panel-heading'><strong><?php echo $lang->workflowlayout->positionList['view']['basic'];?></strong></div>
                <div class='panel-body view-dropbox' data-position='basic' id='uiBasicViewPreview' data-drop-tip='<?php echo $lang->workfloweditor->dragDropTip; ?>'>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div id='uiListPreview'>
          <?php if($uiMode != 'batchForm'): ?>
          <header>
            <ul class='nav nav-default'>
              <li class='active'><a><?php echo $lang->workfloweditor->labelAll; ?></a></li>
            </ul>
            <div class='actions'>
              <button type='button' class='btn btn-primary'><i class='icon icon-plus'></i> <?php echo $lang->create; ?></button>
            </div>
          </header>
          <?php endif; ?>
          <div id='uiListTable' data-drop-tip='<?php echo $lang->workfloweditor->dragDropTip; ?>'>
            <div id='uiListHeader'>
            </div>
            <div id='uiListBody'>
              <div id='uiListPreviewItems' class='clearfix'></div>
            </div>
            <?php if($uiMode != 'batchForm'): ?>
            <div id='uiListFooter'>
              <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll; ?></label></div>
              <div class='actions'>
                <button type='button' class='btn'><?php echo $lang->edit; ?></button>
              </div>
              <ul class='pager' data-ride='pager' data-page='1' data-rec-total='99' data-elements='total_text,page_size_text,page_of_total_text,first,prev,next,last'></ul>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class='side-col column  w-260px'>
    <div class='cell'>
      <div class='cell-header'>
        <span><?php echo $lang->workfloweditor->fieldProperties; ?></span>
      </div>
      <div class='cell-body'>
        <div id='filedEditForm'>
          <div class='form-group required show-for-file' id='fieldEditName'>
            <label for='fieldName'><?php echo $lang->workflowfield->name; ?></label>
            <input type='text' id='fieldName' name='name' class='form-control'>
            <div class='help-block' id='fieldNameMessage'></div>
          </div>
          <div class='form-group required hide-in-buildin  show-for-file' id='fieldEditField'>
            <label for='fieldField'><?php echo $lang->workflowfield->field; ?></label>
            <input type='text' id='fieldField' name='field' class='form-control' placeholder='<?php echo $lang->workflowfield->placeholder->code; ?>'>
            <div class='help-block' id='fieldFieldMessage'></div>
          </div>
          <div class='form-group hide-in-buildin  show-for-file' id='fieldEditControl'>
            <label for='fieldControl'><?php echo $lang->workflowfield->control; ?></label>
            <select id='fieldControl' name='control' class='form-control'>
              <?php foreach($lang->workflowfield->controlTypeList as $control => $name):?>
              <option value='<?php echo $control; ?>'><?php echo $name; ?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class='form-group required hide-in-buildin' id='fieldEditType'>
            <label for='fieldType'><?php echo $lang->workflowfield->type; ?></label>
            <div class='input-group'>
              <select id='fieldType' name='type' class='form-control'>
              <?php
              foreach($config->workflowfield->typeList as $type => $typeList)
              {
                  foreach($typeList as $key => $value)
                  {
                      $selected = $key == 'varchar' ? "selected='selected'" : '';
                      echo "<option class='{$type}' value='{$key}' {$selected}>{$value}</option>";
                  }
              }
              ?>
              </select>
              <span class='input-group-addon'><?php echo $lang->workflowfield->length;?></span>
              <input type='number' id='fieldLength' min='1' name='length' class='form-control w-70px'>
            </div>
            <div id='decimalDigitsGroup'>
              <div class='input-group'>
                <span class='input-group-addon digits'><?php echo $lang->workflowfield->integerDigits;?></span>
                <?php echo html::number('integerDigits', 10, "max='{$config->workflowfield->max->integerDigits}' min='{$config->workflowfield->min->integerDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->integerDigits}' title='{$lang->workflowfield->placeholder->integerDigits}'");?>
                <span class='input-group-addon digits'><?php echo $lang->workflowfield->decimalDigits;?></span>
                <?php echo html::number('decimalDigits', 2, "max='{$config->workflowfield->max->decimalDigits}' min='{$config->workflowfield->min->decimalDigits}' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->decimalDigits}' title='{$lang->workflowfield->placeholder->decimalDigits}'");?>
              </div>
            </div>
            <div id='fieldTypeTip' class='help-block text-warning hidden'></div>
            <div class='help-block text-danger' id='fieldLengthMessage'></div>
          </div>
          <?php if($uiMode == 'browse' || $uiMode == 'batchForm'): ?>
          <div class='form-group' id='widthEditControl'>
            <label for='fieldWidth'><?php echo $lang->workflowlayout->width; ?></label>
            <input type='text' id='fieldWidth' name='width' class='form-control' placeholder=''>
            <div class='help-block text-danger' id='fieldWidthMessage'></div>
          </div>
          <?php endif; ?>
          <?php if($uiMode == 'browse' || $uiMode == 'batchForm' || $uiMode == 'view' || $uiMode == 'edit'): ?>
          <div class='form-group' id='positionEditControl'>
            <label for='fieldPosition'><?php echo $lang->workflowlayout->position; ?></label>
            <select id='fieldPosition' name='position' class='form-control'>
              <?php foreach($lang->workflowlayout->positionList[$uiMode == 'batchForm' ? 'browse' : $uiMode] as $position => $positionName):?>
              <option value='<?php echo $position; ?>'><?php echo $positionName; ?></option>
              <?php endforeach;?>
            </select>
          </div>
          <?php endif; ?>
          <div class='form-group hide-in-buildin' id='fieldEditOptionType'>
            <label for='fieldOptionType'><?php echo $lang->workflowfield->datasource; ?></label>
            <select id='fieldOptionType' name='optionType' class='form-control'>
              <?php foreach($datasources as $sourceType => $name):?>
              <option value='<?php echo $sourceType; ?>'><?php echo $name; ?></option>
              <?php endforeach;?>
            </select>
            <div id='fieldEditOptions'>
              <div id='fieldEditOptionsList'></div>
              <button id='addFieldOptionBtn' type='button' class='btn btn-link btn-sm hidden'><i class='icon icon-plus'></i> <?php echo $lang->workfloweditor->addFieldOption; ?></button>
              <div class='help-block text-danger' id='fieldOptionsMessage'></div>
            </div>
          </div>
          <div class='form-group' id='fieldEditSql'>
            <label for='fieldSql'><?php echo $lang->workflowfield->sql;?></label>
            <textarea name='sql' id='fieldSql' cols='30' rows='3' class='form-control' placeholder='<?php echo $lang->workflowfield->placeholder->sql; ?>'></textarea>
          </div>
          <div class='form-group hide-in-buildin' id='fieldEditDefaultValue'>
            <label for='fieldDefaultValue'><?php echo $lang->workflowfield->defaultValue; ?></label>
            <input type='text' id='fieldDefaultValue' name='defaultValue' class='form-control' placeholder='<?php echo $lang->workflowfield->placeholder->defaultValue; ?>'>
            <div class='help-block text-danger' id='fieldDefaultValueMessage'></div>
          </div>
          <div class='form-group hide-in-buildin show-for-file' id='fieldEditRules'>
            <label for='fieldRules'><?php echo $lang->workflowfield->rules; ?></label>
            <select id='fieldRules' name='layoutRules' class='form-control chosen' multiple data-drop_direction='top'>
              <?php foreach($rules as $rule => $name):?>
              <option value='<?php echo $rule; ?>'><?php echo $name; ?></option>
              <?php endforeach;?>
            </select>
          </div>
        </div>
        <div id='filedEditTip' class='text-muted'><?php echo $lang->workfloweditor->selectFieldToEditTip; ?></div>
      </div>
    </div>
  </div>
</div>
<?php js::set('currentDate', date('Y-m-d'));?>
<?php js::set('currentTime', date('Y-m-d H:i:s'));?>
<?php js::set('currentTimeLang', $lang->workflow->currentTime);?>
<?php js::set('fieldDefault', $config->workflowfield->default);?>
<?php js::set('fieldMax', $config->workflowfield->max);?>
<?php js::set('fieldMin', $config->workflowfield->min);?>
<?php js::set('tips', $lang->workflowfield->tips);?>
<?php js::set('fields', array_values($fields)); ?>
<?php js::set('defaultFields', $lang->workflowfield->default->fields); ?>
<?php js::set('keyValueList', $lang->workflowfield->keyValueList); ?>
<?php js::set('custom', $lang->workflow->custom); ?>
<?php js::set('controlTypeList', $lang->workflowfield->controlTypeList); ?>
<?php js::set('flowModule', $flow->module); ?>
<?php js::set('flowBelong', $flow->belong); ?>
<?php js::set('uiMode', $uiMode); ?>
<?php js::set('action', $currentAction->action); ?>
<?php js::set('langOptionCode', $lang->workflowfield->placeholder->optionCode); ?>
<?php js::set('validateMessages', $lang->workfloweditor->validateMessages); ?>
<?php js::set('leavePageTip', $lang->workfloweditor->leavePageTip); ?>
<?php js::set('notEmptyRule', $notEmptyRule->id);?>
<?php js::set('langAddFile', $lang->workfloweditor->addFile);?>
<?php js::set('multiDefaultPlaceholder', $lang->workflowfield->placeholder->defaultValue);?>
<?php js::set('groupID', $groupID);?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
