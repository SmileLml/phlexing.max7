<?php
/**
 * The show import view file of workflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('showImport', "module=$module");?>'>
  <table class='table table-form' id='fieldTable'>
    <thead class='text-center'>
      <tr>
        <th class='w-120px required'><?php echo $lang->workflowfield->name;?></th>
        <th class='w-120px required'><?php echo $lang->workflowfield->field;?></th>
        <th class='w-120px'><?php echo $lang->workflowfield->control;?></th>
        <th><?php echo $lang->workflowfield->type;?></th>
        <th class='w-140px'><?php echo $lang->workflowfield->datasource;?></th>
        <th class='w-180px'><?php echo $lang->workflowfield->options . '/' . $lang->workflowfield->sql;?></th>
        <th class='w-120px'><?php echo $lang->workflowfield->defaultValue;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($fieldList as $key => $field):?>
      <tr class='text-top' data-key='<?php echo $key;?>'>
        <td><?php echo html::input("name[$key]", htmlspecialchars($field->name, ENT_QUOTES), "id='name{$key}' class='form-control'");?></td>
        <td><?php echo html::input("field[$key]", htmlspecialchars($field->field, ENT_QUOTES), "id='field{$key}' class='form-control'");?></td>
        <td><?php echo html::select("control[$key]", $lang->workflowfield->controlTypeList, $field->control, "id='control{$key}' class='form-control chosen'");?></td>
        <td>
          <div class='input-group'>
            <select id='type<?php echo $key;?>' name='type[<?php echo $key;?>]' class='form-control'>
              <?php
              foreach($config->workflowfield->typeList as $group => $typeList)
              {
                  foreach($typeList as $type => $value)
                  {
                      $selected = $type == 'varchar' ? "selected='selected'" : '';
                      echo "<option class='{$group}' value='{$type}' {$selected}>{$value}</option>";
                  }
              }
              ?>
            </select>
            <span class='input-group-addon length'><?php echo $lang->workflowfield->length;?></span>
            <?php echo html::number("length[$key]", 255, "id='length{$key}' max='1000' min='1' step='1' class='form-control length' placeholder='{$lang->workflowfield->placeholder->varcharLength}' title='{$lang->workflowfield->placeholder->varcharLength}'");?>
            <span class='input-group-addon digits'><?php echo $lang->workflowfield->integerDigits;?></span>
            <?php echo html::number("integerDigits[$key]", 10, "id='integerDigits{$key}' max='12' min='1' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->integerDigits}' title='{$lang->workflowfield->placeholder->integerDigits}'");?>
            <span class='input-group-addon digits'><?php echo $lang->workflowfield->decimalDigits;?></span>
            <?php echo html::number("decimalDigits[$key]", 2, "id='decimalDigits{$key}' max='8' min='0' step='1' class='form-control digits' placeholder='{$lang->workflowfield->placeholder->decimalDigits}' title='{$lang->workflowfield->placeholder->decimalDigits}'");?>
          </div>
        </td>
        <td><?php echo html::select("optionType[$key]", $datasources, $field->datasource, "id='optionType{$key}' class='form-control chosen'");?></td>
        <td>
          <?php echo html::textarea("options[$key]", $field->options, "id='options{$key}' class='form-control' rows='1'");?>
          <?php echo html::textarea("sql[$key]", $field->sql, "id='sql{$key}' class='form-control' rows='1'");?>
          <div id='optionsDIV<?php echo $key;?>'></div>
        </td>
        <td><?php echo html::input("default[$key]", $field->defaultValue, "id='default{$key}' class='form-control'");?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td colspan='7' class='text-warning'><?php echo $lang->workflowfield->excel->tips;?></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan='7' class='text-center form-actions'><?php echo html::submitButton();?></td>
      </tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
