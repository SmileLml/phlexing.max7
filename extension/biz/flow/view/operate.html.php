<?php
/**
 * The operate view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php
include 'header.html.php';
include '../../common/view/picker.html.php';
$isModal      = $action->open == 'modal';
$colspan      = $isModal ? '' : "colspan='2'";
$editorModule = $action->action == 'edit' ? 'edit' : 'operate';
if(!empty($this->config->flow->editor->$editorModule)) include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
js::set('moduleName', $flow->module);
js::set('action', $action->action);
?>
<?php if(!$isModal):?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo str_replace('-', '', $title);?></strong>
  </div>
  <div class='panel-body'>
<?php else:?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $title;?></h2>
  </div>
<?php endif;?>
    <form id='operateForm' method='post' enctype='multipart/form-data' action='<?php echo $actionURL;?>'>
      <table class='table table-form'>
        <?php if(!empty($config->openedApproval) && $flow->approval == 'enabled' && $action->action == 'approvalsubmit'):?>
        <tr>
          <th class='w-100px'><?php echo $lang->{$flow->module}->reviewers;?></th>
          <td id='reviewerBox'></td>
        </tr>
        <?php endif;?>

        <?php $hasChildFields = false;?>
        <?php $childKey       = 1;?>
        <?php foreach($fields as $field):?>
        <?php if(!$field->show) continue;?>
        <?php $readonly = $field->readonly;?>
        <?php $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');?>

        <?php $value = ($field->defaultValue or $field->defaultValue === 0) ? $field->defaultValue : zget($data, $field->field, '');?>

        <?php /* Print files. */ ?>
        <?php if($field->control == 'file'):?>
        <tr class='field-row'>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <?php $fileField  = "files{$field->field}";?>
            <?php $labelsName = "labels{$field->field}";?>
            <?php if($readonly) echo $this->fetch('file', 'printFiles', array('files' => $data->$fileField, 'fieldset' => 'false'));?>
            <?php if(!$readonly) echo $this->fetch('file', 'buildOldForm', "fileCount=1&percent=0.9&filesName={$fileField}&labelsName={$labelsName}");?>
          </td>
          <?php if(!$isModal):?>
          <td></td>
          <?php endif;?>
        </tr>

        <?php /* Print mailto. */ ?>
        <?php elseif($field->field == 'mailto'):?>
<?php if($field->buildin) $users = $this->loadModel('user')->getDeptPairs('pofirst|nodeleted|noclosed');?>
        <?php if($field->buildin) $users = $this->loadModel('user')->getDeptPairs('pofirst|nodeleted|noclosed');?>
        <tr class='field-row'>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, $value, "class='form-control chosen' data-placeholder='{$lang->chooseUserToMail}' multiple");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
          <?php if(!$isModal):?>
          <td class='text-important'><?php echo $lang->flow->tips->notice;?></td>
          <?php endif;?>
        </tr>

        <?php /* Print sub tables. */ ?>
        <?php elseif(isset($childFields[$field->field])):?>
        <?php $hasChildFields = true;?>
        <?php $childModule    = $field->field;?>
        <tr class='field-row'>
          <th><?php echo $field->name;?></th>
          <td <?php echo $colspan;?> class='child'>
            <table class='table table-form table-child' data-child='<?php echo $field->field;?>' id='<?php echo $field->field;?>' style='width: <?php echo $width;?>'>
              <?php $datas = isset($childDatas[$field->field]) ? $childDatas[$field->field] : array();?>
              <?php foreach($datas as $childData):?>
              <tr>
                <?php foreach($childFields[$field->field] as $childField):?>
                <?php if(!$childField->show) continue;?>
                <?php if($childField->control == 'file') continue;?>
                <?php $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');?>

                <?php $childValue = ($childField->defaultValue or $childField->defaultValue === 0) ? $childField->defaultValue : zget($childData, $childField->field, '');?>
                <td style='width: <?php echo $childWidth;?>'>
                  <?php
                  if($readonly or $childField->readonly)
                  {
                      if($childField->control == 'multi-select' or $childField->control == 'checkbox')
                      {
                          $childValues = explode(',', $childValue);
                          foreach($childValues as $childV)
                          {
                              if(in_array($childV, $this->config->flow->variables)) $childV = $this->loadModel('workflowhook')->getParamRealValue($childV);

                              echo zget($field->options, $childV, '') . ' ';
                          }
                      }
                      else
                      {
                          echo zget($childField->options, $childValue);
                      }

                      echo html::hidden("children[$childModule][$childField->field][$childKey]", $childValue);
                  }
                  else
                  {
                      $element = "children[$childModule][$childField->field][$childKey]";

                      echo $this->flow->buildControl($childField, $childValue, $element, $field->field);
                  }
                  ?>
                </td>
                <?php endforeach;?>
                <?php if(!$readonly):?>
                <td class='w-100px'>
                  <?php echo html::hidden("children[$childModule][id][$childKey]", $childData->id);?>
                  <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
                  <a href='javascript:;' class='btn btn-default delItem'><i class='icon-close'></i></a>
                </td>
                <?php endif;?>
              </tr>
              <?php $childKey++;?>
              <?php endforeach;?>

              <?php /* Add a empty row of sub table. */ ?>
              <?php if(!$readonly && empty($datas)):?>
              <tr class='field-row'>
                <?php foreach($childFields[$field->field] as $childField):?>
                <?php if(!$childField->show) continue;?>
                <?php if($childField->control == 'file') continue;?>
                <?php $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');?>
                <td style='width: <?php echo $childWidth;?>'>
                  <?php $element = "children[$childModule][$childField->field][$childKey]";?>
                  <?php echo $this->flow->buildControl($childField, $childField->defaultValue, $element, $childModule);?>
                </td>
                <?php endforeach;?>
                <td class='w-100px'>
                  <?php echo html::hidden("children[$childModule][id][$childKey]");?>
                  <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
                  <a href='javascript:;' class='btn btn-default delItem'><i class='icon-close'></i></a>
                </td>
              </tr>
              <?php $childKey++;?>
              <?php endif;?>
            </table>
          </td>
        </tr>

        <?php /* Print other fields. */ ?>
        <?php else:?>
        <?php
        $attr     = 'class="field-row"';
        $relation = zget($relations, $field->field, '');
        if($relation && strpos(",$relation->actions,", ',many2one,') === false)
        {
            $prevDataID = isset($data->{$field->field}) ? $data->{$field->field} : 0;
            $attr       = "class='prevTR field-row' data-prev='{$relation->prev}' data-next='{$relation->next}' data-action='$action->action' data-field='{$relation->field}' data-dataID='$prevDataID'";
        }
        ?>
        <tr <?php echo $attr;?>>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <div style='width: <?php echo $width;?>'>
              <?php
              if($readonly)
              {
                  if($field->control == 'multi-select' or $field->control == 'checkbox')
                  {
                      if(!is_array($value)) $value = explode(',', $value);
                      foreach($value as $v)
                      {
                          if(in_array($v, $this->config->flow->variables)) $v = $this->loadModel('workflowhook')->getParamRealValue($v);

                          echo zget($field->options, $v, '') . ' ';
                      }
                      $value = join(',', $value);
                  }
                  else
                  {
                      echo zget($field->options, $value);
                  }

                  echo html::hidden($field->field, $field->control == 'richtext' ? str_replace("'", '&#039;', htmlspecialchars($value)) : $value);
              }
              else
              {
                  echo $this->flow->buildControl($field, $value);
              }
              ?>
            </div>
          </td>
          <?php if(!$isModal):?>
          <td></td>
          <?php endif;?>
        </tr>
        <?php endif;?>
        <?php endforeach;?>

        <tr>
          <th></th>
          <td <?php echo $colspan;?> class='form-actions'>
            <?php echo html::submitButton();?>
            <?php if(!$isModal) echo html::backButton();?>
            <?php echo html::hidden('referer', $referer);?>
          </td>
        </tr>
      </table>
    </form>
<?php if(!$isModal):?>
  </div>
<?php endif;?>
</div>

<?php
/* The table below is used to generate dom when click plus button. */
if($hasChildFields)
{
    $itemRows = array();
    foreach($childFields as $childModule => $moduleFields)
    {
        $itemRow = '<tr>';
        foreach($moduleFields as $childField)
        {
            if(!$childField->show) continue;
            if($childField->control == 'file') continue;
            $element = "children[$childModule][$childField->field][KEY]";
            $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');
            $itemRow .= "<td style='width: {$childWidth}'>";
            $itemRow .= $this->flow->buildControl($childField, $childField->defaultValue, $element, $childModule);
            $itemRow .= '</td>';
        }
        $itemRow .= "<td class='w-100px'>";
        $itemRow .= html::hidden("children[$childModule][id][KEY]");
        $itemRow .= "<a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a> ";
        $itemRow .= "<a href='javascript:;' class='btn btn-default delItem'><i class='icon-close'></i></a>";
        $itemRow .= '</td>';
        $itemRow .= '</tr>';

        $itemRows[$childModule] = $itemRow;
    }

    js::set('itemRows', $itemRows);
}
?>

<?php js::set('childKey', $childKey);?>
<?php if($formulaScript) echo $formulaScript;?>
<?php if($linkageScript) echo $linkageScript;?>
<script>
$(document).on('click', 'td.child .addItem', function()
{
    var child = $(this).parents('table').data('child');
    $(this).closest('tr').after(itemRows[child].replace(/KEY/g, childKey));
    initSelect($(this).closest('tr').next().find('.picker-select'));
    $(this).closest('tr').next().find('.form-date').datetimepicker(
    {
        language:  config.clientLang,
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        format: 'yyyy-mm-dd'
    });
    $(this).closest('tr').next().find('.form-datetime').datetimepicker(
    {
        language:  config.clientLang,
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'yyyy-mm-dd hh:ii'
    });

    childKey++;
});

$(document).on('click', 'td.child .delItem', function()
{
    if($(this).parents('.table-child').find('tr').size() > 1)
    {
        $(this).closest('tr').remove();
    }
    else
    {
        $(this).closest('tr').find('input,select,textarea').val('');
        $(this).closest('tr').find('.chosen-controled').trigger('chosen:updated');
        $(this).closest('tr').find('.picker-selection-remove').click();
    }
})

var link = createLink('flow', 'ajaxGetNodes', 'object=' + moduleName);
$('#reviewerBox').load(link, function(){$(this).find('select').chosen()});
</script>
<script>
<?php helper::import('../js/search.js');?>
</script>
<?php include 'footer.html.php';?>
