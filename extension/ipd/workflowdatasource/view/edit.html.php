<?php
/**
 * The edit view file of workflowdatasource module of ZDOO.
 *
 * @copyright   Copyright 2009-2016 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowdatasource
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form id='ajaxForm' method='post' action='<?php echo inlink('edit', "id=$datasource->id");?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->workflowdatasource->name;?></th>
      <td><?php echo html::input('name', $datasource->name, "class='form-control'");?></td>
      <td class='w-40px'></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowdatasource->code;?></th>
      <td><?php echo html::input('code', $datasource->code, "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->workflowdatasource->type;?></th>
      <td><?php echo html::select('type', $lang->workflowdatasource->typeList, $datasource->type, "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr class='optionTR'>
      <th><?php echo $lang->workflowdatasource->typeList['option'];?></th>
      <td id='datasource'>
        <?php foreach($datasource->options as $key => $value):?>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->key;?></span>
          <?php echo html::input('options[value][]', $key, "class='form-control' placeholder='{$lang->workflowdatasource->placeholder->optionCode}'");?>
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->value;?></span>
          <?php echo html::input('options[text][]', $value, "class='form-control'");?>
          <span class='input-group-btn fix-border'>
            <a href='javascript:;' class='btn addItem'><i class='icon-plus'></i></a>
          </span>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn delItem'><i class='icon-minus'></i></a>
          </span>
        </div> 
        <?php endforeach;?>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->key;?></span>
          <?php echo html::input('options[value][]', '', "class='form-control' placeholder='{$lang->workflowdatasource->placeholder->optionCode}'");?>
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->value;?></span>
          <?php echo html::input('options[text][]', '', "class='form-control'");?>
          <span class='input-group-btn fix-border'>
            <a href='javascript:;' class='btn addItem'><i class='icon-plus'></i></a>
          </span>
          <span class='input-group-btn'>
            <a href='javascript:;' class='btn delItem'><i class='icon-minus'></i></a>
          </span>
        </div> 
        <div id='options'></div>
      </td>
      <td></td>
    </tr>
    <tr class='systemTR'>
      <th><?php echo $lang->workflowdatasource->typeList['system'];?></th>
      <td id='datasource'>
        <div class="input-group">
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->app;?></span>
          <?php echo html::select('app', $apps, $datasource->app, "class='form-control''");?>
          <span class='input-group-addon fix-border'><?php echo $lang->workflowdatasource->module;?></span>
          <?php echo html::select('module', $modules, $datasource->module, "class='form-control'");?>
        </div> 
        <div class="input-group">
          <span class='input-group-addon'><?php echo $lang->workflowdatasource->method;?></span>
          <?php echo html::select('method', $methods, $datasource->method, "class='form-control'");?>
          <span class='input-group-addon fix-border methodDesc'><?php echo $lang->workflowdatasource->desc;?></span>
          <?php echo html::input('methodDesc', $datasource->methodDesc, "class='form-control methodDesc' readonly='readonly' title='{$datasource->methodDesc}'");?>
        </div> 
        <div class='input-group' id='paramsDIV'>
        <?php foreach($datasource->params as $param):?>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $this->lang->workflowdatasource->param;?></span>
            <?php echo html::input("paramName[]", $param->name, "class='form-control' readonly='readonly' title='{$param->name}'");?>
            <span class='input-group-addon fix-border'><?php echo $this->lang->workflowdatasource->paramType;?></span>
            <?php echo html::input("paramType[]", $param->type, "class='form-control' readonly='readonly' title='{$param->type}'");?>
            <span class='input-group-addon fix-border'><?php echo $this->lang->workflowdatasource->desc;?></span>
            <?php echo html::input("paramDesc[]", $param->desc, "class='form-control' readonly='readonly' title='{$param->desc}'");?>
            <span class='input-group-addon fix-border'><?php echo $this->lang->workflowdatasource->paramValue;?></span>
            <?php echo html::input("paramValue[]", $param->value, "class='form-control'");?>
          </div>
        <?php endforeach;?>
        </div>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->sql;?></th>
      <td id='datasource'>
        <div class='required required-wrapper'></div>
        <?php echo html::textarea('sql', $datasource->sql, "rows='5' class='form-control' placeholder='{$lang->workflowdatasource->placeholder->sql}'");?>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->key;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::select('keyField', $fields, htmlspecialchars($datasource->keyField, ENT_QUOTES), "class='form-control chosen'");?>
      </td>
      <td></td>
    </tr>
    <tr class='sqlTR'>
      <th><?php echo $lang->workflowdatasource->value;?></th>
      <td>
        <div class='required required-wrapper'></div>
        <?php echo html::select('valueField', $fields, htmlspecialchars($datasource->valueField, ENT_QUOTES), "class='form-control chosen'");?>
      </td>
      <td></td>
    </tr>
    <tr class='langTR'>
      <th><?php echo $lang->workflowdatasource->typeList['lang'];?></th>
      <td id='datasource'><?php echo html::select('lang', $lang->workflowdatasource->langList, $datasource->lang, "class='form-control'");?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td class='form-actions'><?php echo html::submitButton();?></td>
      <td></td>
    </tr>
  </table>
</form>
<div id='optionGroup' class='hide'>
  <div class='input-group'>
    <span class='input-group-addon'><?php echo $lang->workflowdatasource->key;?></span>
    <?php echo html::input('options[value][]', '', "class='form-control' placeholder='{$lang->workflowdatasource->placeholder->optionCode}'");?>
    <span class='input-group-addon'><?php echo $lang->workflowdatasource->value;?></span>
    <?php echo html::input('options[text][]', '', "class='form-control'");?>
    <div class='input-group-btn fix-border'>
      <a href='javascript:;' class='btn addItem'><i class='icon-plus'></i></a>
    </div>
    <div class='input-group-btn'>
      <a href='javascript:;' class='btn delItem'><i class='icon-minus'></i></a>
    </div>
  </div> 
</div>
<?php include '../../common/view/footer.modal.html.php';?>
