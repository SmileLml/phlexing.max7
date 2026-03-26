<?php
/**
 * The operate view file of flow module of ZDOO.
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
if($editor)
{
    $webRoot      = $config->webRoot;
    $jsRoot       = $webRoot . "js/";
    $themeRoot    = $webRoot . "theme/";
    include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
}
?>
<style>
.preview-content td.child > .table tr > td {padding-left: 0px;}
</style>
<div class='preview-content'>
  <table class='table table-form'>
    <?php $hasChildFields = false;?>
    <?php foreach($fields as $field):?>
    <?php if(!$field->show) continue;?>
    <?php $readonly = $field->readonly;?>
    <?php $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');?>
    <?php $value    = ($field->readonly or $action->action == 'browse' or $action->action == 'view') ? $lang->workflowaction->previewData : $field->defaultValue;?>

    <?php /* Print files. */ ?>
    <?php if($field->control == 'file'):?>
    <tr>
      <th class='w-100px'><?php echo $field->name;?></th>
      <td>
        <?php if($readonly) echo $this->fetch('file', 'printFiles', array('files' => $data->files, 'fieldset' => 'false'));?>
        <?php if(!$readonly) echo $this->fetch('file', 'buildForm');?>
      </td>
      <td class='w-160px'></td>
    </tr>

    <?php /* Print mailto. */ ?>
<?php elseif($field->field == 'mailto'):?>
    <tr>
      <th class='w-100px'><?php echo $lang->workflowaction->toList;?></th>
      <td>
        <div class='input-group'>
          <?php echo html::select('mailto[]', $users, $value, "class='form-control chosen' data-placeholder='{$lang->chooseUserToMail}' multiple");?>
          <?php echo $this->fetch('my', 'buildContactLists');?>
        </div>
      </td>
      <td class='text-important w-160px'><?php echo $lang->flow->tips->notice;?></td>
    </tr>

    <?php /* Print sub tables. */ ?>
<?php elseif(isset($childFields[$field->field])):?>
    <?php $hasChildFields = true;?>
    <tr>
      <th><?php echo $field->name;?></th>
      <td colspan='2' class='child'>
        <table class='table table-form table-child' data-child='<?php echo $field->field;?>'>
          <?php for($i = 0; $i < 3; $i++):?>
          <tr>
            <?php foreach($childFields[$field->field] as $childField):?>
            <?php $childValue = ($childField->readonly or $action->action == 'browse' or $action->action == 'view') ? $lang->workflowaction->previewData : $childField->defaultValue;?>
            <?php if(!$childField->show) continue;?>
            <?php if($childField->control == 'file') continue;?>
            <?php $childWidth = ($childField->width && $childField->width != 'auto' ? $childField->width . 'px' : 'auto');?>
            <td style='width: <?php echo $childWidth;?>'>
              <?php
              if(!$readonly && !$childField->readonly)
              {
                  echo $this->flow->buildControl($childField, $childValue, "preview_{$field->field}", $field->field, false, true);
              }
              ?>
            </td>
            <?php endforeach;?>
            <?php if(!$readonly):?>
            <td class='w-100px'>
              <a href='javascript:;' class='btn btn-default addItem'><i class='icon-plus'></i></a>
              <a href='javascript:;' class='btn btn-default delItem'><i class='icon-close'></i></a>
            </td>
            <?php endif;?>
          </tr>
          <?php endfor;?>
        </table>
      </td>
    </tr>

    <?php /* Print other fields. */ ?>
<?php else:?>
    <tr>
      <th class='w-100px'><?php echo $field->name;?></th>
      <td>
        <div style='width: <?php echo $width;?>'>
          <?php if(!$readonly) echo $this->flow->buildControl($field, $value, "preview_{$field->field}", '', false, true);?>
        </div>
      </td>
      <td class='w-160px'></td>
    </tr>
    <?php endif;?>
<?php endforeach;?>
    <tr>
      <th></th>
      <td colspan='2' class='form-actions'>
        <?php echo html::submitButton();?>
        <?php echo baseHTML::commonButton($lang->goback);?>
      </td>
    </tr>
  </table>
</div>
<script>
$('.preview-content a').attr('href', 'javascript:;').removeAttr('onclick');
</script>
