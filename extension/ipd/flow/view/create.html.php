<?php
/**
 * The create view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include 'header.html.php';?>
<?php include '../../common/view/picker.html.php';?>
<?php if(!empty($this->config->flow->editor->create)) include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('action', $action->action);?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo str_replace('-', '', $title);?></strong>
  </div>
  <div class='panel-body'>
    <form id='ajaxForm' method='post' enctype='multipart/form-data' action='<?php echo $actionURL;?>'>
      <table class='table table-form'>
        <?php $hasChildFields = false;?>
        <?php $hasPrevField   = false;?>

        <?php foreach($fields as $field):?>
        <?php
        if(!$field->show) continue;

        $width = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
        $value = $field->defaultValue;
        if($field->field == $prevField)
        {
            $hasPrevField = true;
            $value        = $prevDataID;
        }
        ?>

        <?php /* Print files. */ ?>
        <?php if($field->control == 'file'):?>
        <tr class='field-row'>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <?php $fileField  = "files{$field->field}";?>
            <?php $labelsName = "labels{$field->field}";?>
            <?php echo $this->fetch('file', 'buildOldForm', "fileCount=1&percent=0.9&filesName={$fileField}&labelsName={$labelsName}");?>
          </td>
          <td></td>
        </tr>

        <?php /* Print mailto. */ ?>
        <?php elseif($field->field == 'mailto'):?>
        <tr class='field-row'>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('mailto[]', $users, $value, "class='form-control chosen' data-placeholder='{$lang->chooseUserToMail}' multiple");?>
              <?php echo $this->fetch('my', 'buildContactLists');?>
            </div>
          </td>
          <td class='text-important'><?php echo $lang->flow->tips->notice;?></td>
        </tr>

        <?php /* Print sub tables. */ ?>
        <?php elseif(isset($childFields[$field->field])):?>
        <?php $hasChildFields = true;?>
        <tr class='field-row'>
          <th><?php echo $field->name;?></th>
          <td colspan='2' class='child'>
            <table class='table table-form table-child' data-child='<?php echo $field->field;?>' id='<?php echo $field->field;?>' style='width: <?php echo $width;?>'>
              <?php /* Add a empty row of sub table. */ ?>
              <tr>
                <?php foreach($childFields[$field->field] as $childField):?>
                <?php if(!$childField->show) continue;?>
                <?php $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');?>

                <?php $childValue = $childField->defaultValue;?>
                <td style='width: <?php echo $childWidth;?>'>
                  <?php
                  if($field->readonly or $childField->readonly)
                  {
                      if($childField->control == 'multi-select' or $childField->control == 'checkbox')
                      {
                          $childValues = explode(',', $childValue);
                          foreach($childValues as $childV)
                          {
                              if(in_array($childV, $this->config->flow->variables)) $childV = $this->loadModel('workflowhook')->getParamRealValue($childV);

                              echo zget($childField->options, $childV, '') . ' ';
                          }
                      }
                      else
                      {
                          echo zget($childField->options, $childValue);
                      }

                      echo html::hidden("children[$field->field][$childField->field][1]", $childValue);
                  }
                  else
                  {
                      $element = "children[$field->field][$childField->field][1]";

                      echo $this->flow->buildControl($childField, $childValue, $element, $field->field);
                  }
                  ?>
                </td>
                <?php endforeach;?>
                <td class='w-100px'>
                  <?php echo html::hidden("children[{$field->field}][id][1]");?>
                  <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
                  <a href='javascript:;' class='btn btn-default delItem'><i class='icon-close'></i></a>
                </td>
              </tr>

            </table>
          </td>
        </tr>
        <?php /* Print other fields. */ ?>
        <?php else:?>
        <?php
        $attr     = "class='field-row'";
        $relation = zget($relations, $field->field, '');
        if($relation && strpos(",$relation->actions,", ',many2one,') === false)
        {
            $attr = "class='field-row prevTR' data-prev='{$relation->prev}' data-next='{$relation->next}' data-action='$action->action' data-field='{$relation->field}' data-dataID='$prevDataID'";
        }
        ?>
        <tr <?php echo $attr;?>>
          <th class='w-100px'><?php echo $field->name;?></th>
          <td>
            <div style='width: <?php echo $width;?>'>
              <?php
              if($field->readonly)
              {
                  if($field->control == 'multi-select' or $field->control == 'checkbox')
                  {
                      $values = explode(',', $value);
                      foreach($values as $v)
                      {
                          if(in_array($v, $this->config->flow->variables)) $v = $this->loadModel('workflowhook')->getParamRealValue($v);

                          echo zget($field->options, $v, '') . ' ';
                      }
                  }
                  else
                  {
                      if(in_array($value, $this->config->flow->variables)) $value = $this->loadModel('workflowhook', 'flow')->getParamRealValue($value, 'control');
                      echo zget($field->options, $value);
                  }

                  echo html::hidden($field->field, $value);
              }
              else
              {
                  echo $this->flow->buildControl($field, $value);
              }
              ?>
            </div>
          </td>
          <td></td>
        </tr>
        <?php endif;?>
        <?php endforeach;?>

        <tr>
          <th></th>
          <td class='form-actions'>
            <?php if($prevField && !$hasPrevField) echo html::hidden($prevField, is_array($prevDataID) ? implode(',', $prevDataID) : $prevDataID);?>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('childKey', 2);?>
<?php if($formulaScript) echo $formulaScript;?>
<?php if($linkageScript) echo $linkageScript;?>

<?php /* The table below is used to generate dom when click plus button. */?>
<?php if($hasChildFields)
{
    $itemRows = array();
    foreach($childFields as $childModule => $moduleFields)
    {
        $itemRow = '<tr class="field-row">';
        foreach($moduleFields as $childField)
        {
            if(!$childField->show) continue;
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
    }
})
</script>
<script>
<?php helper::import('../js/search.js');?>
</script>
<?php include 'footer.html.php';?>
